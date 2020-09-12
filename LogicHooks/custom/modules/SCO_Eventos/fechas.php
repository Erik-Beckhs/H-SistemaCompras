<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Eventos
*/
class Clfechas
{
    static $ult_rid = false;
    function Fnfechas($bean, $event, $arguments)
    {
         //Obteniendo el id del modulo de EMBARQUE
        $bean->load_relationship('sco_embarque_sco_eventos');
        $relatedBeans = $bean->sco_embarque_sco_eventos->getBeans();
        reset($relatedBeans);
        $parentBean = current($relatedBeans);
        $id_emb = $parentBean->id;
        //Beans Embarque
        $bean_embarque = BeanFactory::getBean('SCO_Embarque', $id_emb);
        #$bean->eve_fechare = date('Y-m-d');
        /*
        $fecha_plan = strtotime($bean->eve_fechaplan);
        $fecha_real = strtotime($bean->eve_fechare);
        //verificando la fecha real
        if(!empty($bean->eve_fechare)){
        //Operacion, resta entre las fechas real y fecha plan
        $diff = $fecha_real - $fecha_plan;
        $tiempo = round($diff / 86400);
        //ID del usuario logueado
        $id_usuario = $current_user->id;
        if (!isset($bean->ignore_update_c) || $bean->ignore_update_c === false)
        {
        //Query para obtener las fechas de los eventos de acuerdo al id del EMBARQUE
        $emb_des = "SELECT ev.eve_fechaplan as fechaplan, ev.eve_fechanuevo as fechanuevo, ev.eve_fechare as fechareal, ev.id as id_ev
        FROM sco_embarque_sco_eventos_c as em_ev
        INNER JOIN sco_eventos as ev
        ON em_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
        WHERE em_ev.sco_embarque_sco_eventossco_embarque_ida = '$id_emb'
        AND em_ev.deleted = 0
        ORDER BY eve_fechaplan asc;";
        $res_emb_ev = $GLOBALS['db']->query($emb_des, true);
        $fecha = date("Y-m-d");
        $fecha = explode('-', $fecha);
        //Concatenacion de la fecha actual
        $fecha_ac = $fecha[0]."-".$fecha[1]."-".$fecha[2];
        while($row_fila = $GLOBALS['db']->fetchByAssoc($res_emb_ev)){
        if($row_fila['fechaplan'] >= $fecha_ac){
            $feha_actual = date_create($row_fila['fechaplan']);
            date_add($feha_actual, date_interval_create_from_date_string(''.$tiempo.' days'));
            $fecha_nuevo = date_format($feha_actual, 'Y-m-d');
            #$fecha_plan = date('Y-m-d', strtotime($fecha."+ ".$dias." day"));
            if(empty($row_fila['fechareal']) || $row_fila['fechareal'] == ''){
            //Query, Actualiza el campo fecha nuevo del modulo EVENTOS
                $update_eventos = "UPDATE sco_eventos
                SET eve_fechanuevo = '".$fecha_nuevo."'
                WHERE id = '".$row_fila['id_ev']."';";
                $obj_eventos = $GLOBALS['db']->query($update_eventos, true);
            }else{
            //Query, Actualiza el campo fecha nuevo del modulo EVENTOS
                $update_eventos = "UPDATE sco_eventos
                SET eve_fechanuevo = ''
                WHERE id = '".$row_fila['id_ev']."';";
                $obj_eventos = $GLOBALS['db']->query($update_eventos, true);
                }
            }
        }
        $bean->eve_fechanuevo = '';
        //$bean->eve_estado = 'Concluido';
        //$bean->eve_fechare = date('Y-m-d');
       
        //Consulta a la relacion de EMBARQUE con EVENTOS, obtencion de id
        $evento = "SELECT *
        FROM sco_embarque_sco_eventos_c as emb_ev
        INNER JOIN sco_eventos as ev
        ON emb_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
        WHERE sco_embarque_sco_eventossco_embarque_ida = '".$id_emb."'
        ORDER BY eve_fechaplan desc; ";
        $r_evento = $GLOBALS['db']->query($evento, true);
        $row_ev = $GLOBALS['db']->fetchByAssoc($r_evento);
        //Guardando en variables la fecha plan y fecha nueva
        $fp = $row_ev['eve_fechaplan'];
        $fn = $row_ev['eve_fechanuevo'];
        $fr = $row_ev['eve_fechare'];
        if($fn != ''){
            $fecha = $fn;
        }else{
            $fecha = $fp;
        }
        */
        //Cambio de estado del EMBARQUE, comparando los eventos concluidos vs cantida de eventos
        $ev_concluido ="
            SELECT COUNT(*) as concluido
            FROM sco_embarque_sco_eventos_c as e_ev
            INNER JOIN sco_eventos as ev
            ON e_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
            WHERE e_ev.sco_embarque_sco_eventossco_embarque_ida= '$id_emb'
            AND ev.eve_estado = 'Concluido'
            AND e_ev.deleted = 0
            AND ev.deleted = 0;";
        $obj_ev_concluido = $GLOBALS['db']->query($ev_concluido, true);
        $row_ev_concluido = $GLOBALS['db']->fetchByAssoc($obj_ev_concluido);
        //Consulta para obtener la cantiad de EVENTOS relacionado al EMBARQUE
        $ev_count = "
            SELECT COUNT(*) as cantidad
            FROM sco_embarque_sco_eventos_c as e_ev
            INNER JOIN sco_eventos as ev
            ON e_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
            WHERE e_ev.sco_embarque_sco_eventossco_embarque_ida= '$id_emb'
            AND e_ev.deleted = 0
            AND ev.deleted = 0;";
        $obj_ev_count = $GLOBALS['db']->query($ev_count, true);
        $row_ev_count = $GLOBALS['db']->fetchByAssoc($obj_ev_count);

        //Consulta a la relacion de EMBARQUE con DESPACHOS, obtencion de id de despachos
            $embarque_des = "SELECT des.id as id_desp
            FROM sco_embarque_sco_despachos_c as emb_des
            INNER JOIN sco_despachos as des
            ON emb_des.sco_embarque_sco_despachossco_despachos_idb = des.id
            WHERE emb_des.sco_embarque_sco_despachossco_embarque_ida = '".$id_emb."'
            AND emb_des.deleted = 0; ";
            $r_emabarque_des = $GLOBALS['db']->query($embarque_des, true);

        if($row_ev_concluido['concluido'] == $row_ev_count['cantidad'] && $bean_embarque->emb_estado != 3){
            //Query, actualiza el estado del Modulo de EMBARQUE
            $update_embarque = "UPDATE sco_embarque
            SET emb_estado = '3'
            WHERE id = '".$id_emb."';";
            $obj_embarque = $GLOBALS['db']->query($update_embarque, true);
            //Condicion para evitar el loop de los beans
            if(self::$ult_rid == $bean->id) return;
            self::$ult_rid = $bean->id;

             //Query, EMBARQUE relacionado a PRODUCTOS DESPACHOS, extrayendo productos del modulo
            $desp_pd = "SELECT pd.prdes_cantidad, pd.prdes_idproductos_co, pd.id
            FROM sco_embarque_sco_productosdespachos_c as e_pd
            INNER JOIN sco_productosdespachos as pd
            ON e_pd.sco_embarque_sco_productosdespachossco_productosdespachos_idb = pd.id
            WHERE sco_embarque_sco_productosdespachossco_embarque_ida = '$id_emb'
            AND e_pd.deleted = 0
            AND pd.deleted = 0;";
            $obj_desp_pd = $GLOBALS['db']->query($desp_pd, true);

            while($row_desp_pd = $GLOBALS['db']->fetchByAssoc($obj_desp_pd)){
                //alamacenando en variables, las cantidades y el Id de la tabla sco_productos_co
                $cantidad_prdes = $row_desp_pd['prdes_cantidad'];
                $id_idproductos_co = $row_desp_pd['prdes_idproductos_co'];
                $id_prdes = $row_desp_pd['id'];

                //Query, obteniendo la cantidad de la tabla sco_productos_co
                $prod_co = "SELECT pro_canttrans, pro_cantresivida 
                            FROM sco_productos_co 
                            WHERE id = '$id_idproductos_co';";
                $obj_prod_co = $GLOBALS['db']->query($prod_co, true);
                $row_prod_co = $GLOBALS['db']->fetchByAssoc($obj_prod_co);
                //Operacion, resta de cantidad en transito con cantidad entregada
                $cantidad_newtr = $cantidad_prdes - $row_prod_co['pro_canttrans'];
                $cantidad_newtr = abs($cantidad_newtr);
                //Operacion, suma de cantidad recivida con cantidad entregada
                $cantidad_newres = $cantidad_prdes + $row_prod_co['pro_cantresivida'];
                //**Query, actualizando la tabla de sco_productos_co, campo cantidad transito y cantidad resivida
                $productos_co = "UPDATE sco_productos_co
                                SET pro_canttrans = '".$cantidad_newtr."', pro_cantresivida = '".$cantidad_newres."'
                                WHERE id = '$id_idproductos_co'; ";
                $obj_productos_co = $GLOBALS['db']->query($productos_co, true);
                //**Query, actualizando el modulo PRODUCTOS COTIZADOS, campo cantidad transito y cantidad resivida
                $bean_prodcot = BeanFactory::getBean('SCO_ProductosCotizados',$id_idproductos_co);
                $bean_prodcot->pro_canttrans = $cantidad_newtr;
                $bean_prodcot->pro_cantresivida = $cantidad_newres;
                $bean_prodcot->save();
            }

            while($fila = $GLOBALS['db']->fetchByAssoc($r_emabarque_des)){
                $cnt++;
                $id_desp = $fila['id_desp'];
                if(!empty($id_desp)){
                    //Query, actualiza el estado y fecha del Modulo de DESPACHOS
                    $update_despachos = "UPDATE sco_despachos
                    SET des_fechaprev = '".$fr."', des_est = '3'
                    WHERE id = '".$id_desp."';";
                    $obj_despachos = $GLOBALS['db']->query($update_despachos, true);
                }

                $bean_des = BeanFactory::getBean("SCO_Despachos", $id_desp);
                $bean_des->load_relationship('sco_despachos_sco_ordencompra');
                $relatedBeans = $bean_des->sco_despachos_sco_ordencompra->getBeans();
                reset($relatedBeans);
                $parentBean = current($relatedBeans);
                $idoc = $parentBean->id;
                //Query, obteniendo cantidades de la tabbla sco_productos_co
                $oc_productos_co = "SELECT SUM(pro_canttrans) as pro_canttrans, SUM(pro_saldos) as pro_saldos
                FROM sco_productos_co
                WHERE pro_idco = '".$idoc."' AND deleted = 0";
                $obj_oc_productos_co = $GLOBALS['db']->query($oc_productos_co, true);
                $row_oc_productos_co = $GLOBALS['db']->fetchByAssoc($obj_oc_productos_co);
                //Validacion de cantidades en transito y saldos
                if($row_oc_productos_co['pro_canttrans'] == 0 && $row_oc_productos_co['pro_saldos'] == 0){
                    //Bean ORDEN COMPRA, Actualzando el estado a Cerrado
                    $bean_oc = BeanFactory::getBean("SCO_OrdenCompra", $idoc);
                    $bean_oc->orc_estado = 6;
                    $bean_oc->save();
                }
            }
        }/*else{
        while($fila = $GLOBALS['db']->fetchByAssoc($r_emabarque_des)){
            $id_desp = $fila['id_desp'];
            if(!empty($id_desp)){
                //Query, actualiza la fecha del modulo de DESPACHOS
                $despacho_update = "UPDATE sco_despachos
                    SET des_fechaprev = '".$fecha."'
                    WHERE id = '".$id_desp."'; ";
                $obj_update = $GLOBALS['db']->query($despacho_update, true);
                }
            }
        }
        //pobla usuario actual logeado
        global $current_user;
        $bean->assigned_user_id = $current_user->id;
        $bean->iddivision_c = $current_user->iddivision_c;
        $bean->idregional_c = $current_user->idregional_c;
        $bean->idamercado_c = $current_user->idamercado_c;
        $bean->save();
        }
    }
        // if(self::$ult_rid == $bean->id) return;
        //     self::$ult_rid = $bean->id;
        // require_once("custom/modules/SCO_Eventos/NotificaEvento.php");
        // $notificacion = new ClnotificaEv();
        // $idEve = $bean->id;
        // $bean_evento = BeanFactory::getBean('SCO_Eventos', $idEve);
        // $notificacion->Fnnotifica($bean_evento);
        */
    }
}
?>
