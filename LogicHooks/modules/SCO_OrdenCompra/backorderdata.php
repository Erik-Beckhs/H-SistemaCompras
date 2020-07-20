<?php
/**
*Este metodo realiza la extracion de datos de la base de datos para mostrarlos en en FrontEnd
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license ruta: /var/www/html/modules/SCO_Productos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
//pobla usuario actual logeado
global $current_user;
    
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$idDiv = $current_user->iddivision_c;
try {
	$pcv_proveedoraio = $_POST['pcv_proveedoraio'] ? $_POST['pcv_proveedoraio'] : "";
    $pcv_numerocotizacion = $_POST['nroCotizacion'] ? $_POST['nroCotizacion'] :"";
    
    $query = "call suitecrm.sp_consolidacion_backorder('2019-01-01','2020-01-01');";
    $results = $GLOBALS['db']->query($query, true);
    $object= array();
    while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
            $object[] = $row;
        }
    echo json_encode($object);
} catch (Exception $e) {
	echo "Error, no se pudo realizar la peticion";
}
