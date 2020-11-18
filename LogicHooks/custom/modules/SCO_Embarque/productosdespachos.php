<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Clproductosdespachos
{
  #static $already_ran = false;

  function Fnproductosdespachos($bean, $event, $arguments)
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
      $despacho = "SELECT sco_despachos_sco_productosdespachossco_productosdespachos_idb
        FROM sco_despachos_sco_productosdespachos_c 
        WHERE sco_despachos_sco_productosdespachossco_despachos_ida = '".$id_desp."'
        AND deleted = 0; ";
      $res_despacho = $GLOBALS['db']->query($despacho);
      while ($row_desp = $GLOBALS['db']->fetchByAssoc($res_despacho)) {
        //Creando Id de repacion
        $id_emb_prod_desp = create_guid();
        $fecha_actual = date('Y-m-d H:m:s');
        //realizamos un insert a las tabla de la relacion entre el embarque y el despacho
        $rel_emb_prod_desp = "INSERT INTO sco_embarque_sco_productosdespachos_c 
        (id, date_modified, deleted, sco_embarque_sco_productosdespachossco_embarque_ida, sco_embarque_sco_productosdespachossco_productosdespachos_idb)
        VALUES
          ('".$id_emb_prod_desp."','".$fecha_actual."','0','".$id_emb."','".$row_desp['sco_despachos_sco_productosdespachossco_productosdespachos_idb']."');";
        $res_rel_emb_prod_desp = $GLOBALS['db']->query($rel_emb_prod_desp);    
      }    
  }
}
?>
