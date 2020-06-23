<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Despachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once("modules/SCO_Despachos/NotificaDespacho.php");

$id = $_GET['id'];
$est = $_GET['est'];
//Instanciamos la clase notificaciones
$notificaciones = new Notifica();
//utilizar update de sql y no el bean del sugarcrm para no crear conflictos de bean
//Actualizadndo el estado del despacho de acuerdo a la variable que se envia
// Realizamos una consulta que nos muestra la cantidad de items del despacho
$queryProDespachos = "select sum(pro.prdes_cantidad) as cantidad
		from sco_productosdespachos pro
		inner join sco_despachos_sco_productosdespachos_c depro on depro.sco_despachos_sco_productosdespachossco_productosdespachos_idb = pro.id
		inner join sco_despachos des on des.id = depro.sco_despachos_sco_productosdespachossco_despachos_ida
		where des.id = '$id' and pro.deleted = 0";
		$productos_despachos = $GLOBALS['db']->query($queryProDespachos, true);
        $row_productos_despachos = $GLOBALS['db']->fetchByAssoc($productos_despachos);
        $cantidadProductos = $row_productos_despachos['cantidad'];
switch ($est) {
	case '4':
		//Bean DESPACHOS, obteniendo datos
		$bean_des = BeanFactory::getBean('SCO_Despachos',$id);

		//bean DESPACHOS relacionado a ORNDE COMPRA
		$bean_des->load_relationship('sco_despachos_sco_ordencompra');
        $relatedBeans = $bean_des->sco_despachos_sco_ordencompra->getBeans();
        reset($relatedBeans);
        $parentBean = current($relatedBeans);
        //ID de ORDEN DE COMPRA
        $idoc = $parentBean->id;
        //Valiadando el campo de Observaciones del modulo de DESPACHOS
		if ($bean_des->des_observaciones != '') {
			//Query, Actualizando el estado del DESPACHOS
			$despachos = "UPDATE sco_despachos SET des_est = '".$est."' WHERE id = '".$id."';";
		    $obj_des = $GLOBALS['db']->query($despachos, true);
		    //Query, total de cantidad en transito y total de saldos en la tabla sco_productos_co
            $oc_productos_co = "SELECT SUM(pro_canttrans) as pro_canttrans, SUM(pro_saldos) as pro_saldos
            FROM sco_productos_co
            WHERE pro_idco = '".$idoc."' AND deleted = 0";
            $obj_oc_productos_co = $GLOBALS['db']->query($oc_productos_co, true);
            $row_oc_productos_co = $GLOBALS['db']->fetchByAssoc($obj_oc_productos_co);
            //Validando la cantidad de saldos de la tabla sco_productos_co
            if($row_oc_productos_co['pro_saldos'] == 0){
            	//Bean ORDEN COMPRA, Actualizando el estado de la ORDEN DE COMPRA
                $bean_oc = BeanFactory::getBean("SCO_OrdenCompra", $idoc);
                $bean_oc->orc_estado = 6;
                $bean_oc->save();
            }
            $notificaciones->FnnotificaDespacho($bean_des,$est,$cantidadProductos);
			echo json_encode($est);
		}else{
			echo json_encode('9');
		}
		break;
		default:
			$bean_des = BeanFactory::getBean('SCO_Despachos',$id);
			$notificaciones->FnnotificaDespacho($bean_des,$est,$cantidadProductos);
			$despachos = "UPDATE sco_despachos SET des_est = '".$est."' WHERE id = '".$id."';";
		    $obj_des = $GLOBALS['db']->query($despachos, true);
			echo json_encode($est);
		break;
}

?>
