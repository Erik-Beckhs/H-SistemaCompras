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
 
$division = $_GET['division']?$_GET['division']:"";
$aMercado = $_GET['aMercado']?$_GET['aMercado']:"";
$familia = $_GET['familia']?$_GET['familia']:"";
$grupo = $_GET['grupo']?$_GET['grupo']:"";
$filtro = $_GET['filtro'];


switch ($filtro) {
	case 1:
		try {
		    $query = "call suitecrm.sp_ordencompra_rep01('".$division."','".$aMercado."','".$familia."','".$grupo."');";
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
		#Exportamos a EXCEL con php
		try {
			date_default_timezone_set('America/Lima');
			$fecha = date("d-m-Y H:i:s");
		    
		    $filename = "ReporteGerencial.xls";
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=".$filename);

			$query = "call suitecrm.sp_ordencompra_rep01('".$division."','".$aMercado."','".$familia."','".$grupo."');";
		    $results = $GLOBALS['db']->query($query, true);
		    $object= array();
		    $arreglo = array();
		    while($row = $GLOBALS['db']->fetchByAssoc($results))
		        {
		            $object['IdProducto'] = $row['IdProducto'];
		            $object['CodigoProveedor'] = $row['CodigoProveedor'];
		            $object['Producto'] = $row['Producto'];
		            $object['PrecioVta'] = $row['PrecioVta'];
		            $object['SaldoStock'] = $row['SaldoStock'];
		            $object['StockRango>180'] = $row['StockRango180'];
		            $object['SalidaAutorizada'] = $row['SalidaAutorizada'];
		            $object['AreaMercado'] = $row['AreaMercado'];
		            $object['SubGrupo'] = $row['SubGrupo'];
		            $object['Familia'] = $row['Familia'];
		            $object['Grupo'] = $row['Grupo'];
		            $object['Venta Total 2017'] = $row['VentaCantidad3AnioAtras'];
		            $object['Venta Total 2018'] = $row['VentaCantidad2AnioAtras'];
		            $object['Venta Total 2019'] = $row['VentaCantidad1AnioAtras'];
		            $object['Venta Total 2020'] = $row['VentaCantidad0AnioAtras'];
		            $object['Pedido Sugerido'] = '0';
		            $arreglo[] = $object;
		        }
			$mostrar_columnas = false;
			foreach($arreglo as $arr) {
				if(!$mostrar_columnas) {
					echo implode("\t", array_keys($arr)) . "\n";
					$mostrar_columnas = true;
				}
					echo implode("\t", array_values($arr)) . "\n";
			}
		    //echo json_encode($object);
		} catch (Exception $e) {
			echo "Error, no se pudo realizar la peticion";
		}
		break;
	case 'aMercado':
		$object= array();
		$query2 = "SELECT DISTINCT(idamercado_c) as idamercado_c, idamercado_c_name
		         FROM suitecrm.sco_viewdar
		         WHERE iddivision_c = '".$division."'
		         ORDER BY idfmilia_c asc;";
		$results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $object[] = $row;
        }
        echo json_encode($object);			
		break;			
	case 'familia':
		$object= array();
		$query2 = "SELECT DISTINCT(idfmilia_c) as idfmilia_c, idfamilia_c_name
		         FROM suitecrm.sco_viewdar
		         WHERE iddivision_c = '".$division."'
		         AND idamercado_c = '".$aMercado."'
		         ORDER BY idfmilia_c asc;";
		$results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $object[] = $row;
        }
        echo json_encode($object);			
		break;	
	case 'grupo':
		$object= array();
		$query2 = "SELECT DISTINCT(idgrupo_c) as idgrupo_c, idgrupo_c_name
		         FROM suitecrm.sco_viewdar
		         WHERE iddivision_c = '".$division."'
		         AND idamercado_c = '".$aMercado."'
		         AND idfmilia_c = '".$familia."'
		         ORDER BY idgrupo_c asc;";
		$results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $object[] = $row;
        }
        echo json_encode($object);			
		break;	
	case 'subgrupo':
		$object= array();
		$query2 = "SELECT DISTINCT(idsubgrupo_c) as idsubgrupo_c, idsubgrupo_c_name
		         FROM suitecrm.sco_viewdar
		         WHERE iddivision_c = '".$division."'
		         AND idamercado_c = '".$aMercado."'
		         AND idfmilia_c = '".$familia."'
		         AND idgrupo_c = '".$grupo."'      
		         ORDER BY idsubgrupo_c asc;";
		$results2 = $GLOBALS['db']->query($query2, true);
        while($row = $GLOBALS['db']->fetchByAssoc($results2))
        {
            $object[] = $row;
        }
        echo json_encode($object);			
		break;
	default:
		echo "Error, no se pudo realizar la peticion";
		break;
}

