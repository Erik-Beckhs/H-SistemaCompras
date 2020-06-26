<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Productos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
//pobla usuario actual logeado
global $current_user;
    
    $filtro = $_POST['filtro'];
    $idDiv = $current_user->iddivision_c;
    
    if($filtro == 'fabricanteList'){
        $query = "SELECT pcv_proveedoraio,pcv_nombreproveedor FROM suitecrm.sco_productoscotizadosventa
        group by pcv_proveedoraio;
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    if($filtro == 'fabricante'){
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT pcv_numerocotizacion
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_proveedoraio = '$pcv_proveedoraio'
        group by pcv_numerocotizacion;
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    if($filtro == 'cotizacion'){
        $nroCotizacion = $_POST['nroCotizacion'];
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT name 
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$nroCotizacion'
        AND pcv_proveedoraio = '$pcv_proveedoraio';
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    if($filtro == 'cliente'){
        $nroCotizacion = $_POST['nroCotizacion'];
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT pcv_clienteaio, pcv_cliente 
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$nroCotizacion'
        AND pcv_proveedoraio = '$pcv_proveedoraio'
        group by pcv_clienteaio;
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }

 
