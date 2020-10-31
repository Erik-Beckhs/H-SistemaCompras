<?php
/**
*Este metodo realiza la extracion de datos de la base de datos para mostrarlos en en FrontEnd
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license ruta: /var/www/html/modules/SCO_Productos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
include ('../../../../../config.php');
//include ('../../../../../custom/application/Ext/Utils/custom_utils.ext.php');
include ('../../../../../custom/include/language/es_ES.lang.php');

global $sugar_config;

    
$aMercado = $_GET['aMercado'];
$filtro = $_GET['filtro'];

if ($aMercado == '00') {
	$aMercado = '';
}

switch ($filtro) {
	case 1:
		try {
		    $query = "call suitecrm.sp_ordencompra_rep01('01 HERRAMIENTAS');";
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
		break;
	default:
		echo "Error, no se pudo realizar la peticion";
		break;
}

