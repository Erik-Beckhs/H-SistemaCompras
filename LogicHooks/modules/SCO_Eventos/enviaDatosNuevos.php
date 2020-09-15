<?php
/**
*Esta clase realiza el amacenamiento y recalculo de fechas para el trackOrder del Embarque.
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class Fechas{
    function functionFechas($idEvento,$fechaReal,$agenciaId,$agenciaNombre){
        global $current_user;
        try { 
            #obteniendo bean del modulo SCO_Eventos
            $bean_evento = BeanFactory::getBean('SCO_Eventos', $idEvento);   
            $bean_evento->assigned_user_id = $current_user->id;
            $bean_evento->iddivision_c = $current_user->iddivision_c;
            $bean_evento->idregional_c = $current_user->idregional_c;
            $bean_evento->idamercado_c = $current_user->idamercado_c; 
            #obteniendo id Embarque
            $bean_evento->load_relationship('sco_embarque_sco_eventos');
            $relatedBeans = $bean_evento->sco_embarque_sco_eventos->getBeans();
            reset($relatedBeans);
            $parentBean = current($relatedBeans);
            $id_embarque = $parentBean->id;
            $fechaR = explode('/', $fechaReal);
            $fechaRe = $fechaR[2]."-".$fechaR[0]."-".$fechaR[1];

            $bean_evento->eve_fechare = $fechaRe;
            $bean_evento->sco_cnf_eventos_list_id_c = $agenciaId;
            $bean_evento->save();

            //Operacion, resta entre las fechas real y fecha plan

            $fecha_real = explode('/', $fechaReal);
            $fecha_real2 = $fecha_real[2]."-".$fecha_real[0]."-".$fecha_real[1];
            $fecha_real3 = strtotime($fecha_real2);
            $eve_fechaplan = strtotime($bean_evento->eve_fechaplan);
            $diferenciaTiempo = $fecha_real3 - $eve_fechaplan;
            $tiempo = round($diferenciaTiempo / 86400);
            #Obteniendo las Fecha Plan, Fecha Real y Fecha nueva
            $emb_des = "SELECT ev.eve_tiempoest, ev.eve_fechaplan as fechaplan, ev.eve_fechanuevo as fechanuevo, ev.eve_fechare as fechareal, ev.id as id_ev
                FROM sco_embarque_sco_eventos_c as em_ev
                INNER JOIN sco_eventos as ev
                ON em_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                WHERE em_ev.sco_embarque_sco_eventossco_embarque_ida = '".$id_embarque."'
                AND em_ev.deleted = 0
                ORDER BY eve_fechaplan asc;";
            $res_emb_ev = $GLOBALS['db']->query($emb_des, true);
            $fecha = date("Y-m-d");
            $fecha = explode('-', $fecha);;;
            $fecha_ac = $fecha[0]."-".$fecha[1]."-".$fecha[2];
            #Actualziacion de las Fecha Nueva y Fecha Real
            $fechasArr = '';
            while($row_fila = $GLOBALS['db']->fetchByAssoc($res_emb_ev)){
                #if($row_fila['fechaplan'] >= $fecha_ac){
                    $feha_actual = date_create($row_fila['fechaplan']);
                    date_add($feha_actual, date_interval_create_from_date_string(''.$tiempo.' days'));
                    $fecha_nuevo = date_format($feha_actual, 'Y-m-d');
                        if(empty($row_fila['fechareal']) || $row_fila['fechareal'] == '0000-00-00' || $row_fila['fechareal'] == null || $row_fila['fechareal'] == 'null'){
                            //Query, Actualiza el campo fecha nuevo del modulo EVENTOS
                            $update_eventos = "UPDATE sco_eventos
                                                SET eve_fechanuevo = '".$fecha_nuevo."'
                                                WHERE id = '".$row_fila['id_ev']."';";
                            $obj_eventos = $GLOBALS['db']->query($update_eventos, true);
                            $fechasArr .= $fecha_nuevo;
                        }else{
                            //Query, Actualiza el campo fecha nuevo del modulo EVENTOS
                            $update_eventos = "UPDATE sco_eventos
                                                SET eve_fechanuevo = ''
                                                WHERE id = '".$row_fila['id_ev']."';";
                            $obj_eventos = $GLOBALS['db']->query($update_eventos, true);
                        }                        
                #}
            }

            #Cambio de estado del EMBARQUE, comparando los eventos concluidos vs cantida de eventos
            $ev_concluidoFecha ="
                        SELECT COUNT(*) as concluidoFecha
                        FROM sco_embarque_sco_eventos_c as e_ev
                        INNER JOIN sco_eventos as ev
                        ON e_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                        WHERE e_ev.sco_embarque_sco_eventossco_embarque_ida= '$id_embarque'
                        AND ev.eve_fechare <> ''
                        AND e_ev.deleted = 0
                        AND ev.deleted = 0;";
            $obj_ev_concluidoFecha = $GLOBALS['db']->query($ev_concluidoFecha, true);
            $row_ev_concluidoFecha = $GLOBALS['db']->fetchByAssoc($obj_ev_concluidoFecha);

            #Cambio de estado del EMBARQUE, comparando los eventos concluidos vs cantida de eventos
            $ev_concluido ="
                        SELECT COUNT(*) as concluido
                        FROM sco_embarque_sco_eventos_c as e_ev
                        INNER JOIN sco_eventos as ev
                        ON e_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                        WHERE e_ev.sco_embarque_sco_eventossco_embarque_ida= '$id_embarque'
                        AND ev.eve_estado = 'Concluido'
                        AND e_ev.deleted = 0
                        AND ev.deleted = 0;";
            $obj_ev_concluido = $GLOBALS['db']->query($ev_concluido, true);
            $row_ev_concluido = $GLOBALS['db']->fetchByAssoc($obj_ev_concluido);

            #Consulta a la relacion de EMBARQUE con EVENTOS, obtencion de id
            $evento = "SELECT *
                        FROM sco_embarque_sco_eventos_c as emb_ev
                        INNER JOIN sco_eventos as ev
                        ON emb_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                        WHERE sco_embarque_sco_eventossco_embarque_ida = '".$id_embarque."'
                        ORDER BY eve_fechaplan desc; ";
            $r_evento = $GLOBALS['db']->query($evento, true);
            $row_ev = $GLOBALS['db']->fetchByAssoc($r_evento);
            #Guardando en variables la fecha plan y fecha nueva
            $fp = $row_ev['eve_fechaplan'];
            $fn = $row_ev['eve_fechanuevo'];
            $fr = $row_ev['eve_fechare'];
            if($fn != '' && $fn != null){
                if($fn == '0000-00-00'){
                  $fecha = $fr; 
                }else{
                  $fecha = $fn;
                }                 
            }else{
                $fecha = $fp;
            }
            
            #Consulta para obtener la cantiad de EVENTOS relacionado al EMBARQUE
            $ev_count = "
                        SELECT COUNT(*) as cantidad
                        FROM sco_embarque_sco_eventos_c as e_ev
                        INNER JOIN sco_eventos as ev
                        ON e_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                        WHERE e_ev.sco_embarque_sco_eventossco_embarque_ida= '$id_embarque'
                        AND e_ev.deleted = 0
                        AND ev.deleted = 0;";
            $obj_ev_count = $GLOBALS['db']->query($ev_count, true);
            $row_ev_count = $GLOBALS['db']->fetchByAssoc($obj_ev_count);
            #Consulta a la relacion de EMBARQUE con DESPACHOS, obtencion de id de despachos
            $embarque_des = "SELECT des.id as id_desp
                        FROM sco_embarque_sco_despachos_c as emb_des
                        INNER JOIN sco_despachos as des
                        ON emb_des.sco_embarque_sco_despachossco_despachos_idb = des.id
                        WHERE emb_des.sco_embarque_sco_despachossco_embarque_ida = '".$id_embarque."'
                        AND emb_des.deleted = 0; ";
            $r_emabarque_des = $GLOBALS['db']->query($embarque_des, true);
            #Consulta Datos Embarque
            $bean_embarque = BeanFactory::getBean('SCO_Embarque', $id_embarque);

            if($row_ev_concluido['concluido'] == $row_ev_count['cantidad'] && $bean_embarque->emb_estado != 3){
                
            }else{
              while($fila = $GLOBALS['db']->fetchByAssoc($r_emabarque_des)){
                  $id_desp = $fila['id_desp'];
                  if($fecha == null || $fecha == ''){
                    $var = "La fecha es null o vacio";
                  }else{
                      if(!empty($id_desp)){
                      //Query, actualiza la fecha del modulo de DESPACHOS
                      $despacho_update = "UPDATE sco_despachos
                          SET des_fechaprev = '".$fecha."'
                          WHERE id = '".$id_desp."'; ";
                      $obj_update = $GLOBALS['db']->query($despacho_update, true);
                      $var .= "idDesoacho: ".$id_desp." feha:".$fecha;
                      }
                  }
              }                
            }
            
        } catch (Exception $e) {
            $var = '404';   
        }
         return $var;       
    }
}
?>