<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Cldocumentosdespachos_delte_r
{
  #static $already_ran = false;

  function Fndocumentosdespachos_delte_r($bean, $event, $arguments)
  {
    #if(self::$already_ran == true) return;
    #self::$already_ran = true;

    $id_usuario = $current_user->id;
    //id del modulo de Embarque
    $id_emb = $bean->id;
    //id del modulo relacionado Despacho
    $id_desp = $arguments['related_id'];
    //Realizamos consultas para no tener conflictos con los Beans
    //Consulta a la base de datos tabla sco_embarque_sco_despachos_c, id Depsachos
    //Consulta para obtener los productos del modulo ProductosDespachos relacionalo al Desp
      $despacho = "SELECT sco_despachos_sco_documentodespachosco_documentodespacho_idb
                  FROM sco_despachos_sco_documentodespacho_c
                  where sco_despachos_sco_documentodespachosco_despachos_ida = '".$id_desp."'
                  and deleted = 0";
      $res_despacho = $GLOBALS['db']->query($despacho);
      while ($row_desp = $GLOBALS['db']->fetchByAssoc($res_despacho)) {
        //Modificando la relacion a estado deleted
        $rel_emb_prod_desp = "UPDATE sco_embarque_sco_documentodespacho_c
        SET  deleted = 1
        WHERE sco_embarque_sco_documentodespachosco_documentodespacho_idb = '".$row_desp['sco_despachos_sco_documentodespachosco_documentodespacho_idb']."'; ";
        $res_rel_emb_prod_desp = $GLOBALS['db']->query($rel_emb_prod_desp);
      }
  }
}
?>
