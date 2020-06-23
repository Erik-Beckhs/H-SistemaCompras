<?php 
/**
*Esta clase realiza el poblado de datos desde el modulo de SCO_APROBADORES
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Cldatosap 
{static $already_ran = false;

  function Fndatosap($bean, $event, $arguments) 
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;
    if(!empty($bean->orc_solicitado)){
    $div = $bean->orc_division;
    $reg = $bean->orc_regional;
    $ido = $bean->id;
    //Query, Obteniedno la cantidad de la relacion entre SCO_ORDENCOMPRA con SCO_APROBADORES
    $query = "SELECT count(*) as cantidad 
      FROM sco_ordencompra_sco_aprobadores_c 
      where sco_ordencompra_sco_aprobadoressco_ordencompra_ida = '$ido'
      and deleted = 0";
    $results = $GLOBALS['db']->query($query, true);
    $row = $GLOBALS['db']->fetchByAssoc($results);
        
    if ($row["cantidad"] == 0)  
    {
      //Query, Obteniendo ID del USUARIO
      $query = "SELECT u.id 
      FROM sco_cnf_aprobadores ca
      inner join users u on (u.user_name=ca.name)
      where ca.cnfapro_div = '".$div."'
      and ca.deleted = 0";
      
        $results = $GLOBALS['db']->query($query, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
        //BEAN Aprobadores, Crea un nuevo registro de Aprobadores con relacion a la ORden de COmpra
        $contactoBean = BeanFactory::newBean('SCO_Aprobadores');
        $contactoBean->sco_ordencompra_sco_aprobadoressco_ordencompra_ida = $bean->id;
        $contactoBean->user_id_c = $row["id"];
        $contactoBean->save();
        }
      }
    }
  }
}
?>