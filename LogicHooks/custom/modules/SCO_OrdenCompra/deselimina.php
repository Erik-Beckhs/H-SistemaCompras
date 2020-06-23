<?php
/**
*Esta clase realiza la eliminacion de la relacion con un DESPACHOS y todos sus PRODUCTOS 
*DESPACHOS, y recalcula las cantidades de la tabla sco_produtos_co
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Cldeselimina
{
	function Fndeselimina($bean, $event, $arguments)
	{
    //Id de la Orden de Compra
  	$id_oc = $bean->id;  
    //Id del subpanel despacho
    $id_des = $id_desp = $arguments['related_id'];
    if(!empty($id_des)){
    //query en el la realcion de Despachos con ProductosDespachos
    $desp_proddes = "SELECT sco_despachos_sco_productosdespachossco_productosdespachos_idb as idpd, sco_despachos_sco_productosdespachossco_despachos_ida 
    FROM sco_despachos_sco_productosdespachos_c 
    WHERE sco_despachos_sco_productosdespachossco_despachos_ida = '".$id_des."'
    AND deleted = 0 ; ";
    $obj = $bean->db->query($desp_proddes, true);

    while($row = $GLOBALS['db']->fetchByAssoc($obj)){
      //verificando la existencia del id de despacho
      //if(isset($row['sco_despachos_sco_productosdespachossco_despachos_ida'])){
        $bean_proddes = BeanFactory::getBean('SCO_ProductosDespachos', $row['idpd']);
        $bean_proddes->deleted = 1;
        $id_productos_co = $bean_proddes->prdes_idproductos_co;

        $query_pr_co = "SELECT *
        FROM sco_productos_co
        WHERE id = '".$id_productos_co."'; ";
        $obj_pr_co = $GLOBALS['db']->query($query_pr_co, true);
        #$row_pr_co = $GLOBALS['db']->fetchByAssoc($obj_pr_co);
        while($row_pr_co = $GLOBALS['db']->fetchByAssoc($obj_pr_co)){
          $canttrans = $row_pr_co['pro_canttrans'];
          $saldo = $row_pr_co['pro_saldos'];
        }
        $new_saldo = $saldo + $bean_proddes->prdes_cantidad;#$row['prdes_cantidad'];
        $new_canttrans = $canttrans - $bean_proddes->prdes_cantidad;#$row['prdes_cantidad'];
        //**Query, actualizar la tabla sco_productos_co campos saldos y cantidad transito
        $query_p = "UPDATE sco_productos_co
        SET pro_saldos = '".$new_saldo."', pro_canttrans = '".$new_canttrans."'
        WHERE id = '".$id_productos_co."' " ;
        $obj_p = $GLOBALS['db']->query($query_p, true);

        //**Acualizando el modulo PRODUCTOS COTIZADOS campos de cantidad y saldos
        $bean_prodcotiza = BeanFactory::getBean('SCO_ProductosCotizados', $id_productos_co);
        $bean_prodcotiza->pro_canttrans = $new_canttrans;
        $bean_prodcotiza->pro_saldos = $new_saldo;
        $bean_prodcotiza->save();
        //Query, actualizando el modulo de PRODUCTOS DESPACHOS
        $productos_despacho = "UPDATE sco_productosdespachos
        SET deleted  = '1'
        WHERE id = '".$bean_proddes->id."' " ;
        $onj_productos_despacho = $GLOBALS['db']->query($productos_despacho, true);
      }
        $despacho = "UPDATE sco_despachos
        SET deleted  = '1'
        WHERE id = '".$id_des."' " ;
        $onj_desp = $GLOBALS['db']->query($despacho, true);      
        
        //Eliminando la relacion de PRODUCTOS COMPRAS con DESPACHOS
        $r_pc_des = "UPDATE sco_productoscompras_sco_despachos_c
        SET deleted = 1 
        WHERE sco_productoscompras_sco_despachossco_despachos_idb = '".$id_des."';";
        $obj_pc_des = $GLOBALS['db']->query($r_pc_des, true); 

        //QUERY, eliminando la realcion de DESPACHOS con PRODUCTOS DESPACHOS
        $productos_despacho = "UPDATE sco_despachos_sco_productosdespachos_c
        SET deleted  = '1'
        WHERE sco_despachos_sco_productosdespachossco_despachos_ida = '".$id_des."' " ;
        $onj_productos_despacho = $GLOBALS['db']->query($productos_despacho, true);
        }
	}
}
?>