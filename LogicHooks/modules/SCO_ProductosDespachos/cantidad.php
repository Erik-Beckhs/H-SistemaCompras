<?php
/**
*Esta clase realiza operaciones matemÃƒÂ¡ticas.
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
	  		$query = "SELECT id, pro_cantidad, pro_canttrans,pro_cantresivida, pro_saldos
				FROM sco_productos_co
				WHERE deleted = 0 AND id = '$id_pr_co'";
				$results = $GLOBALS['db']->query($query, true);
				$row = $GLOBALS['db']->fetchByAssoc($results);
        $pro_cantidad = $row['pro_cantidad'];
		  	$cant = $row['pro_canttrans'] + $row['pro_saldos'];
        $diferencia =  $row['pro_cantidad'] - $value;  
  			if($diferencia <= $pro_cantidad && $value > 0){
			  	$beanpd1 = BeanFactory::getBean('SCO_ProductosDespachos', $id);
					$beanpd1->prdes_cantidad = $value;
					$beanpd1->save();

					$query_pd = "SELECT pro_cantidad, pro_canttrans,pro_cantresivida, pro_saldos
					FROM sco_productos_co			
					WHERE id = '".$id_pr_co."'
					AND deleted = 0; ";
					$results_pd = $GLOBALS['db']->query($query_pd, true);
					$row_pd = $GLOBALS['db']->fetchByAssoc($results_pd);
	  		  $new_cant = $row['pro_cantidad'] - $row_pd['cantidad_pd'];
			  		//**Acualizando la tabla sco_productos_co campos de cantidad y saldos
					$query_p = "UPDATE sco_productos_co
					SET pro_saldos = '$diferencia', pro_canttrans = '".$value."'
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
