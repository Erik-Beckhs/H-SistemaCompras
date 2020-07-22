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
$division = $_POST['division'];
$idco = $_POST['idco'];
$filtro = $_POST['filtro'];

$idDiv = $current_user->iddivision_c;

switch ($filtro) {
	case 1:
		try {
		    $query = "call suitecrm.sp_consolidacion_backorder('$fecha_desde','$fecha_hasta','$division','');";
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
	case 2:
		try {
		    $query = "SELECT 
						pro_nombre,
						pro_descripcion,
						pro_unidad,
						pro_cantidad,
						pro_preciounid,
						pro_descval,
						pro_descpor,
						pro_saldos
						FROM suitecrm.sco_productos_co
						WHERE pro_idco = '".$idco."'
						AND pro_saldos > 0;";
		    $results = $GLOBALS['db']->query($query, true);
		    $objectPro= array();
		    while($row = $GLOBALS['db']->fetchByAssoc($results))
		        {
		            $objectPro[] = $row;
		        }
		    echo json_encode($objectPro);
		} catch (Exception $e) {
			echo "Error, no se pudo realizar la peticion";
		}
		break;
	default:
		echo "Error, no se pudo realizar la peticion";
		break;
}

