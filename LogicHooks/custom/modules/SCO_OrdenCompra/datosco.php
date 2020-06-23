<?php
/**
*Esta clase realiza el poblado de datos de SCO_CONTANTOS (hansa), se autopobla los datos del Subpanel
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Cldatosco
{
  static $already_ran = false;

  function Fndatosco($bean, $event, $arguments)
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;
    if(!empty($bean->orc_solicitado)){
    $div = $bean->orc_division;
    $reg = $bean->orc_regional;
    $ido = $bean->id;

    $query = "SELECT count(*) as cantidad
      FROM sco_ordencompra_sco_contactos_c
      where sco_ordencompra_sco_contactossco_ordencompra_ida = '$ido'
      and deleted = 0";
    $results = $GLOBALS['db']->query($query, true);
    $row = $GLOBALS['db']->fetchByAssoc($results);

    if ($row["cantidad"] == 0)
    {
      $query = "SELECT u.id, cc.cnfco_te
      FROM sco_cnf_contactos cc
      inner join users u on (u.user_name=cc.name)
      where cc.cnfco_div = '$div'
      and cc.cnfco_reg = '$reg'
      and cc.deleted = 0";

        $results = $GLOBALS['db']->query($query, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
        //crea contacto
        $contactoBean = BeanFactory::newBean('SCO_Contactos');
        $contactoBean->sco_ordencompra_sco_contactossco_ordencompra_ida = $bean->id;
        $contactoBean->user_id_c = $row["id"];
        $contactoBean->con_rol = $row["cnfco_te"];
        $contactoBean->save();
        }
      }
    }
  }
}
?>
