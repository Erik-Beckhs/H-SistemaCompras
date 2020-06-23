<?php
/**
*Esta clase cumple la función de concluir los despachos intangibles.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Despachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

$id = $_GET['id'];
$est = $_GET['est'];

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
	$observaciones = "Despacho intangible";
	if ($observaciones != '') {
			//Query, Actualizando el estado del DESPACHOS
			$despachos = "UPDATE sco_despachos SET des_est = '".$est."', des_observaciones = '".$observaciones."' WHERE id = '".$id."';";
	    $obj_des = $GLOBALS['db']->query($despachos, true);
	    //Query, total de cantidad en transito y total de saldos en la tabla sco_productos_co
          $oc_productos_co = "SELECT SUM(pro_canttrans) as pro_canttrans, SUM(pro_saldos) as pro_saldos
          FROM sco_productos_co
          WHERE pro_idco = '".$idoc."' AND deleted = 0";
          $obj_oc_productos_co = $GLOBALS['db']->query($oc_productos_co, true);
          $row_oc_productos_co = $GLOBALS['db']->fetchByAssoc($obj_oc_productos_co);
					// Query para obtener la cantidad de productos en transito y los productos que se terminan de enviar en el despacho
					$productosTrans = "SELECT pro.id as idproOc, pro.pro_canttrans, prodes.prdes_cantidad
															FROM sco_productos_co pro
															inner join sco_productosdespachos prodes on prodes.prdes_idproductos_co = pro.id
															inner join sco_despachos_sco_productosdespachos_c pdes on pdes.sco_despachos_sco_productosdespachossco_productosdespachos_idb = prodes.id
															where pdes.sco_despachos_sco_productosdespachossco_despachos_ida = '".$id."'";
					$resultProTrans = $GLOBALS['db']->query($productosTrans, true);
					while($row = $GLOBALS['db']->fetchByAssoc($resultProTrans)){
						//Query, Actualizando el estado del los productos de la tabla sco_productos_co
						$nuevoTotal = $row["pro_canttrans"] - $row["prdes_cantidad"];
						$nuevoResivida = $row["pro_cantresivida"] + $row["prdes_cantidad"];
						$QuaeryProducto_co = "UPDATE sco_productos_co SET pro_canttrans = '".$nuevoTotal."', pro_cantresivida = '".$nuevoResivida."' WHERE id = '".$row["idproOc"]."';";
						$GLOBALS['db']->query($QuaeryProducto_co, true);
					}
					// Verificamos que no existan despachos en borrador, transito y en solicitud de embarque
					$QuerydesTrans ="SELECT count(des.id) as desTrans from sco_despachos des
													inner join  sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_despachos_idb = des.id
													inner join sco_ordencompra oc on oc.id = deor.sco_despachos_sco_ordencomprasco_ordencompra_ida
													where oc.id = '".$idoc."' and (des_est = 0 or des_est = 1 or des_est = 2) and des.deleted = 0";
					$resultDesTrans = $GLOBALS['db']->query($QuerydesTrans, true);
					$rowDesTrans = $GLOBALS['db']->fetchByAssoc($resultDesTrans);
          //Validando la cantidad de saldos de la tabla sco_productos_co
          if($row_oc_productos_co['pro_saldos'] == 0 && $rowDesTrans["desTrans"] == 0){
          	//Bean ORDEN COMPRA, Actualizando el estado de la ORDEN DE COMPRA
              $bean_oc = BeanFactory::getBean("SCO_OrdenCompra", $idoc);
              $bean_oc->orc_estado = 6;
              $bean_oc->save();
          }
          //$notificaciones->FnnotificaDespacho($bean_des,$est,$cantidadProductos);
    $estadoArray = array($est);
		echo json_encode($estadoArray);
  }
  else{
    $estadoArray = array('9');
		echo json_encode($estadoArray);
  }

?>
