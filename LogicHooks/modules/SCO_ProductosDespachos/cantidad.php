<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_ProductosDespachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

	$id = $_GET['id'];
	$id_pr_co = $_GET['id_pr_co'];

	$value = $_GET['value'];
	$tipo = $_GET['tipo'];

  	switch ($tipo) {
  		case 'cantidad':
	  		#$query = "SELECT id, pro_cantidad, pro_saldos
				#FROM sco_productos_co
				#WHERE deleted = 0 AND id = '$id_pr_co'";
				#$results = $GLOBALS['db']->query($query, true);
				#$row = $GLOBALS['db']->fetchByAssoc($results);
		  	#$cant = $row['pro_cantidad'] - $row['pro_saldos'];
  			if($value >= 0){
			  	$beanpd1 = BeanFactory::getBean('SCO_ProductosDespachos', $id);
					$beanpd1->prdes_cantidad = $value;
					$beanpd1->save();

					$query_pd = "SELECT sum(prdes_cantidad) as cantidad_pd
					FROM sco_productosdespachos as pd
					INNER JOIN sco_despachos_sco_productosdespachos_c as dpd
					ON pd.id = dpd.sco_despachos_sco_productosdespachossco_productosdespachos_idb
					WHERE pd.prdes_idproductos_co = '".$id_pr_co."'
					AND pd.deleted = 0
					AND dpd.deleted = 0; ";
					$results_pd = $GLOBALS['db']->query($query_pd, true);
					$row_pd = $GLOBALS['db']->fetchByAssoc($results_pd);

			  		$new_cant = $row['pro_cantidad'] - $row_pd['cantidad_pd'];
			  		//**Acualizando la tabla sco_productos_co campos de cantidad y saldos
					$query_p = "UPDATE sco_productos_co
					SET pro_saldos = '$new_cant', pro_canttrans = '".$row_pd['cantidad_pd']."'
					WHERE id = '$id_pr_co';";
					$obj = $GLOBALS['db']->query($query_p, true);

				//**Acualizando el modulo PRODUCTOS COTIZADOS campos de cantidad y saldos
		        $bean_prodcotiza = BeanFactory::getBean('SCO_ProductosCotizados', $row["id"]);
		        $bean_prodcotiza->pro_canttrans = $row_pd['cantidad_pd'];
		        $bean_prodcotiza->pro_saldos = $new_cant;
		        $bean_prodcotiza->save();

		  		echo json_encode($new_cant);
		  	}else{
		  		echo json_encode('Error');
		  	}
  		break;
  		case 'precio':
  			$beanpd = BeanFactory::getBean('SCO_ProductosDespachos', $id);
				$beanpd->prdes_unidad = $value;
				$beanpd->save();
  			echo json_encode($beanpd->prdes_unidad);
  		break;
			case 'observacion':
  			$beanpd = BeanFactory::getBean('SCO_ProductosDespachos', $id);
				$beanpd->prdes_observaciones = $value;
				$beanpd->save();
  			echo json_encode($beanpd->id);
  		break;
  	}
?>
