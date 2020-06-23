<?php
/**
*Esta clase realiza El poblado de datos del Proveedor en el modulo de SCO_ORDENCOMPRA
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Cldatoso
{
  static $already_ran = false;

  function Fndatoso($bean, $event, $arguments)
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;
    //Copia datos facturacion en datos envio
    $op_comp = $bean->orc_decop;
    if ($op_comp == '1')
    {
      #Codigo Suprimir de acuerdo al campo estalecido po el backend
      $bean->orc_denomemp = $bean->orc_dfnomemp;
      $bean->orc_dedireccion = $bean->orc_dfdireccion;
      $bean->orc_detelefono = $bean->orc_dftelefono;
      $bean->orc_defax = $bean->orc_dffax;
      $bean->orc_depobox = $bean->orc_dfpobox;
      $bean->orc_depais = $bean->orc_dfpais;
    }
    //pobla datos proveedor
    $sel_prov = $bean->sco_proveedor_sco_ordencomprasco_proveedor_ida;
    $bean_prov = BeanFactory::getBean('SCO_Proveedor', $sel_prov);
    $bean->orc_pronomemp = $bean_prov->name;
    $bean->orc_procodaio = $bean_prov->prv_codaio;
    //$bean->orc_promovil = $bean_prov->prv_movil;
    //$bean->orc_proemail = $bean_prov->prv_email;
    //$bean->orc_protelefono = $bean_prov->prv_tel;
    //$bean->orc_prodireccion = $bean_prov->prv_direc;
    #MOdificaion
    $bean->orc_nomcorto = $bean_prov->prv_monr;
    //pobla datos usuario
    $sel_ususol = $bean->user_id1_c;
    $bean_ususol = BeanFactory::getBean('Users', $sel_ususol);
    $bean->orc_division = $bean_ususol->iddivision_c;
    $bean->orc_regional = $bean_ususol->idregional_c;
    $bean->idamercado_c = $bean_ususol->idamercado_c;
    //Calculando el tiempo de lleaga
    #$bean->orc_fechaent = $bean->orc_fechaord + $bean->orc_tiempo;
    $feha_actual = date_create($bean->orc_fechaord);
    date_add($feha_actual, date_interval_create_from_date_string(''.$bean->orc_tiempo.' days'));
    $bean->orc_fechaent = date_format($feha_actual, 'Y-m-d');
    //pobla usuario actual logeado
    global $current_user;
    $bean->user_id_c = $current_user->id;
    $bean->assigned_user_id = $current_user->id;
    $bean->iddivision_c = $current_user->iddivision_c;
    $bean->idregional_c = $current_user->idregional_c;   
    //Grupos de seguridad divisional
    /*$query = "SELECT * from securitygroups where name = 'div-".$current_user->iddivision_c."' ";
    $results = $GLOBALS['db']->query($query, true);
    $rowGRP = $GLOBALS['db']->fetchByAssoc($results);
    //Insert relation securitygroups Se asigna el grupo de seguridad al que perteneces, segun se creen las OC
    $idRecord = create_guid();
    $queryInsert ="INSERT INTO securitygroups_records (id, deleted, securitygroup_id, record_id, module)
    VALUES ('$idRecord',0,'".$rowGRP["id"]."','$bean->id','SCO_OrdenCompra_Div');";
    $obj = $bean->db->query($queryInsert, true);

    //Grupos de seguridad regional
    $query = "SELECT * from securitygroups where name = 'reg-".$current_user->idregional_c."' ";
    $results = $GLOBALS['db']->query($query, true);
    $rowGRP = $GLOBALS['db']->fetchByAssoc($results);
    //Insert relation securitygroups Se asigna el grupo de seguridad al que perteneces, segun se creen las OC
    $idRecord = create_guid();
    $queryInsert ="INSERT INTO securitygroups_records (id, deleted, securitygroup_id, record_id, module)
    VALUES ('$idRecord',0,'".$rowGRP["id"]."','$bean->id','SCO_OrdenCompra_Reg');";
    $obj = $bean->db->query($queryInsert, true);*/

    /*$GroupsBean = BeanFactory::newBean('SecurityGroups_Records');
    $GroupsBean->securitygroup_id = $rowGRP["id"];
    $GroupsBean->record_id = $bean->id;
    $GroupsBean->module = 'SCO_OrdenCompra';
    $GroupsBean->save();*/
    $bean->save();
  }
}
?>
