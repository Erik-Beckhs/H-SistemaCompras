<?php
/**
*Esta clase realiza operaciones matemÃƒÂ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Clevento
{
  static $already_ran = false;
  function Fnevento($bean, $event, $arguments)
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;

    //Poblando datos importantes para usuario actual, logeado
    global $current_user;
    $id_usuario = $current_user->id;
    $bean->assigned_user_id = $current_user->id;
    $bean->iddivision_c = $current_user->iddivision_c;
    $bean->idregional_c = $current_user->idregional_c;
    #$bean->idamercado_c = $current_user->idamercado_c;
    $bean->emb_orig = $bean->emb_origen;
    $bean->emb_transp = $bean->emb_modtra;
    $id_emb = $bean->id;
    $fecha = $bean->emb_fechacrea;
    //Query, obteniendo cantidad de regisrtos relacionados de EVENTOS con el EMBARQUE
    $emb_des = "SELECT count(*) as cantidad
      FROM sco_embarque_sco_eventos_c
      where sco_embarque_sco_eventossco_embarque_ida = '$id_emb'
      and deleted = 0";
    $res_emb_ev = $GLOBALS['db']->query($emb_des, true);
    $fila = $GLOBALS['db']->fetchByAssoc($res_emb_ev);
    //validando la cantida igual a 0 del Modulo de EVENTOS
    if ($fila["cantidad"] == 0)
    {
      //Query, obteniendo los prieros 2 numeros de los registros del modulo de CNFEV_EVENTOS, de acuerdo a los beans origen y modalidad de transporte
      $query = "SELECT SUBSTRING(cnfev_evento, 1,2) as num, name, cnfev_evento, cnfev_diastrans, cnfev_modtrans, deleted, notificar
      FROM sco_cnf_eventos
      WHERE name = '".$bean->emb_origen."' AND cnfev_modtrans = '".$bean->emb_modtra."' AND deleted = 0 ORDER BY CAST(cnfev_evento AS UNSIGNED) asc;";
      $results = $GLOBALS['db']->query($query, true);
      $dias = 0;

      while($row = $GLOBALS['db']->fetchByAssoc($results)){
        //Creando ID para la relacion
        $id_emb_ev = create_guid();
        $id_rel = create_guid();
        //Query, Insertando datos a la relacion de EMBARQUES y EVENTOS
        $query1 ="INSERT INTO sco_embarque_sco_eventos_c
            (id, deleted, sco_embarque_sco_eventossco_embarque_ida, sco_embarque_sco_eventossco_eventos_idb)
          VALUES
            ('".$id_rel."','".$row['deleted']."','".$id_emb."','".$id_emb_ev."');";
        $obj1 = $bean->db->query($query1, true);
        //Generamos la fecha actual de creaciÃƒÂ³n
        $fecha_actual = date('Y-m-d');
        //incrementamos la cantodad de dÃƒÂ­as para asignar una nueva fecha plan
        $diasTrans = $row['cnfev_diastrans'];
        $dias += $diasTrans;
        #$fecha_plan = date('Y-m-d', strtotime('+'.$dias.' day'));
        //Operacion, las fechas del modulo de EVENTOS## Incrementamos la cantidad de dias a la fecha de creaciÃƒÂ³n para tener la fecha plan del evento
        $fecha_plan = date('Y-m-d', strtotime($fecha."+ ".$dias." day"));
        //Verificamos si la fecha plan del evento es dias Sabado o Domingo
        $fplan=date("w", strtotime($fecha_plan));
        // if($fplan=="0" or $fplan == "6")
        // {
        //   // Si es 0 o 6 el evento cae en fin de semana
        //   if($fplan=="6"){
        //     // es Sabado e incrementamos 2 dÃƒÂ­as a la fecha plan
        //     $dias += 2;
        //     $diasTrans += 2;
        //     //Operacion, las fechas del modulo de EVENTOS## Incrementamos la cantidad de dias a la fecha de creaciÃƒÂ³n para tener la fecha plan del evento
        //     $fecha_plan = date('Y-m-d', strtotime($fecha."+ ".$dias." day"));
        //   }
        //   if($fplan=="0"){
        //     // es Domingo e incrementamos 1 dÃƒÂ­a a la fecha plan
        //     $dias += 1;
        //     $diasTrans += 1;
        //     //Operacion, las fechas del modulo de EVENTOS## Incrementamos la cantidad de dias a la fecha de creaciÃƒÂ³n para tener la fecha plan del evento
        //     $fecha_plan = date('Y-m-d', strtotime($fecha."+ ".$dias." day"));
        //   }
        // }
        //Query, Insertando las fechas y campos importantes en el modulo de EVENTOS
        $query2 = "INSERT INTO sco_eventos
          (id, name, date_entered, created_by, deleted, eve_tiempoest, eve_fechaplan, eve_estado, notificar)
          VALUES
          ('".$id_emb_ev."','".$row['cnfev_evento']."','".$fecha_actual."','".$id_usuario."','".$row['deleted']."','".$diasTrans."', '".$fecha_plan."', 'Pendiente','".$row['notificar']."');";
        $obj2 = $bean->db->query($query2, true);
      }
    }
    //si el campo usuario asignado esta vacio, asignarle el usuario logueado
    if($bean->assigned_user_id == '')
    $bean->assigned_user_id = $id_usuario;
    $bean->save();
  }
}
?>
