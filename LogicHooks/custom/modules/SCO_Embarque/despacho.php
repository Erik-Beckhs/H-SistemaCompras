<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@Update 2020
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Cldespacho
{
    function Fndespacho($bean, $event, $arguments)
    {
        //Id de Embarque
        $id_embarque = $bean->id;
        //Id de Despacho
        $id_desp = $arguments['related_id'];       
        //Query, obteniendo fechas.
        $evento ="SELECT * 
        FROM sco_embarque_sco_eventos_c as emb_ev
        INNER JOIN sco_eventos as ev
        ON emb_ev.sco_embarque_sco_eventossco_eventos_idb = ev.id
        WHERE sco_embarque_sco_eventossco_embarque_ida = '".$id_embarque."'
        ORDER BY eve_fechaplan desc";
        $r_evento = $GLOBALS['db']->query($evento, true);
        $row = $GLOBALS['db']->fetchByAssoc($r_evento);

        $fp = $row['eve_fechaplan'];
        $fn = $row['eve_fechanuevo'];
        //Validando la fecha nueva si no esta vacia
        if($fn != ''){
            $fecha = $fn;
        }else{
            $fecha = $fp;
        }
        //Quey, actualizando el estado des_est y la fecha des_fechaprev del modulo de DESPACHOS
        $despacho_update = "UPDATE sco_despachos 
            SET des_est = '2', des_fechaprev = '".$fecha."'
            WHERE id = '".$id_desp."'; ";
        $obj_update = $GLOBALS['db']->query($despacho_update, true);
        //se obtiene el despacho 
        //Obtenemos la cantidad de productos del despacho
        $queryProDespachos = "select sum(pro.prdes_cantidad) as cantidad
        from sco_productosdespachos pro
        inner join sco_despachos_sco_productosdespachos_c depro on depro.sco_despachos_sco_productosdespachossco_productosdespachos_idb = pro.id
        inner join sco_despachos des on des.id = depro.sco_despachos_sco_productosdespachossco_despachos_ida
        where des.id = '$id_desp' and pro.deleted = 0";
        $productos_despachos = $GLOBALS['db']->query($queryProDespachos, true);
        $row_productos_despachos = $GLOBALS['db']->fetchByAssoc($productos_despachos);
        $cantidadProductos = $row_productos_despachos['cantidad'];        
        //Instanciamos la clase para enviar los despachos
        $bean_des = BeanFactory::getBean('SCO_Despachos',$id_desp);
        if($bean_des->id != ""){
          require_once("custom/modules/SCO_Embarque/NotificaEmbarque.php");
          $notificacion = new Notificaciones();
          $notificacion->FnnotificaDespacho($bean_des,$cantidadProductos);
        }
    }
}
?>