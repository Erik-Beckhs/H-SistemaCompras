<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Despachos
*/
class Clprodelimina
{

  	function Fnprodelimina($bean, $event, $arguments)
  	{      
      //Obteniendo el Id del modulo de PRODUCTOS DESPACHOS
      $id_proddes = $arguments['related_id'];
      //Obteniendo los datos (bean) del modulo de PRODUCTOS DESPACHOS
      $bean_proddes = BeanFactory::getBean('SCO_ProductosDespachos', $id_proddes);
      $id_cant_pd = $bean_proddes->prdes_idproductos_co;
      $cantidad = $bean_proddes->prdes_cantidad;
      //Validando registros no eliminados
      if($bean_proddes->deleted == 0){
        //Query para obtener los datos de los salods y cantidad transito
        $query2 = "SELECT pro_saldos, pro_canttrans
        FROM sco_productos_co 
        WHERE id = '".$id_cant_pd."'; ";
        $obj2 = $bean->db->query($query2, true);
        $row2 = $GLOBALS['db']->fetchByAssoc($obj2);
        
        //Opreacion, suma de cantidad de PRODUCTOS DESPACHOS con saldos de la tabla sco_productos_co, para obetner un nuevo saldo
        $saldo_new = $cantidad + $row2['pro_saldos'];
        //Opreacion, resta de cantidad en transito de la tabla sco_productos_co con cantidad de PRODUCTOSDESPACHOS
        $canttrans = $row2['pro_canttrans'] - $cantidad;

        //**Actualizando los campos de salos y cantidad transito de la tabla SCO_PRODUCTOS_CO
        $query_p = "UPDATE sco_productos_co
        SET pro_saldos = '".$saldo_new."', pro_canttrans = '".$canttrans."'
        WHERE id = '".$id_cant_pd."' " ;
        $obj_p = $GLOBALS['db']->query($query_p, true);

        //**Acualizando el modulo PRODUCTOS COTIZADOS campos de cantidad y saldos
        $bean_prodcotiza = BeanFactory::getBean('SCO_ProductosCotizados', $id_cant_pd);
        $bean_prodcotiza->pro_canttrans = $canttrans;
        $bean_prodcotiza->pro_saldos = $saldo_new;
        $bean_prodcotiza->save();

        //Eliminando el registro de PRODUCTOS DESPACHOS de acuerdo a si id
        $query_pd = "UPDATE sco_productosdespachos
        SET deleted = 1
        WHERE id = '".$id_proddes."' " ;
        $obj_pd = $GLOBALS['db']->query($query_pd, true);

        //Eliminando la relacion de PRODUCTOS COMPRAS con DESPACHOS de acuerdo al id del ProductoDespacho
        $d_pd = "UPDATE sco_productoscompras_sco_despachos_c
        SET deleted = 1
        WHERE id_productodespacho = '".$id_proddes."' " ;
        $obj_d_pd = $GLOBALS['db']->query($d_pd, true);
      }      
  	}
}
?>