<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Clproductosdespachos_delte_r
{
    function Fnproductosdespachos_delte_r($bean, $event, $arguments)
    {
        $id_usuario = $current_user->id;
        //id del modulo de Embarque
        $id_emb = $bean->id;
        //id del modulo relacionado Despacho        
        $id_desp = $arguments['related_id'];
        //Consulta para obtener los productos del modulo ProductosDespachos relacionalo al Desp
        $despacho = "SELECT sco_despachos_sco_productosdespachossco_productosdespachos_idb
        FROM sco_despachos_sco_productosdespachos_c 
        WHERE sco_despachos_sco_productosdespachossco_despachos_ida = '".$id_desp."'
        AND deleted = 0; ";
        $res_despacho = $GLOBALS['db']->query($despacho);
        while ($row_desp = $GLOBALS['db']->fetchByAssoc($res_despacho)) {
            //Query, Eliminacion de la relacion
            $rel_emb_prod_desp = "UPDATE sco_embarque_sco_productosdespachos_c 
            SET  deleted = 1
            WHERE sco_embarque_sco_productosdespachossco_productosdespachos_idb = '".$row_desp['sco_despachos_sco_productosdespachossco_productosdespachos_idb']."'; ";
            $res_rel_emb_prod_desp = $GLOBALS['db']->query($rel_emb_prod_desp);    
        }
    }
}

?>