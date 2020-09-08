<?php
/**
*Esta clase cumple la funcion de actualizar los items del despacho.
*
*@author Limberg Alcon Espejo <rkantuta@hansa.com.bo>
*@copyright 2020
*@license ruta: /var/www/html/modules/SCO_Despachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
// recibimos los datos del nuevo despacho

$arrItem = $_POST["arrItem"];

try {
	for($i=0; $i < sizeof($arrItem); $i++){
		$queryUpdate = "UPDATE suitecrm.sco_productosdespachos
					SET
					prdes_observaciones = '".$arrItem[$i][observacion]."',
					prdes_numeracion = ".$arrItem[$i][numeracion]."
					WHERE id = '".$arrItem[$i][idProductoDespacho]."';";
		$obj = $GLOBALS['db']->query($queryUpdate, true);	
	}
	echo json_encode("200");
} catch (Exception $e) {
	echo json_encode("500");
}


?>