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
    
    if($filtro == 'cotizacionList'){
        $query = "SELECT pcv_numerocotizacion FROM suitecrm.sco_productoscotizadosventa WHERE deleted = 0
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
    if($filtro == 'fabricante'){
        $pcv_numerocotizacion = $_POST['pcv_numerocotizacion'];
        $query = "SELECT sco_proveedor_id_c,pcv_nombreproveedor,pcv_proveedoraio
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$pcv_numerocotizacion' AND
        deleted = 0
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
    if($filtro == 'cotizacion'){
        $nroCotizacion = $_POST['nroCotizacion'];
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT pcv_codigoproveedor,name 
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$nroCotizacion' AND
        deleted = 0
        AND sco_proveedor_id_c = '$pcv_proveedoraio';
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
        AND sco_proveedor_id_c = '$pcv_proveedoraio' AND
        deleted = 0
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
    if($filtro == 'plazo'){
        $nroCotizacion = $_POST['nroCotizacion'];
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT pcv_plzentrega 
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$nroCotizacion'
        AND sco_proveedor_id_c = '$pcv_proveedoraio' AND
        deleted = 0
        group by pcv_plzentrega;
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    if($filtro == 'familia'){
        $nroCotizacion = $_POST['nroCotizacion'];
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'];
        $query = "SELECT pcv_familia 
        FROM suitecrm.sco_productoscotizadosventa
        WHERE pcv_numerocotizacion = '$nroCotizacion'
        AND sco_proveedor_id_c = '$pcv_proveedoraio' AND
        deleted = 0
        group by pcv_familia;
        ";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    if($filtro == 'tabla1'){
        $pcv_proveedoraio = $_POST['pcv_proveedoraio'] ? $_POST['pcv_proveedoraio'] : "";
        $pcv_numerocotizacion = $_POST['nroCotizacion'] ? $_POST['nroCotizacion'] :"";
        $name = $_POST['name'] ? $_POST['name'] : "";
        $pcv_clienteaio = $_POST['pcv_clienteaio'] ? $_POST['pcv_clienteaio'] : "";
        $plazoEntrega = $_POST['plazoEntrega'] ? $_POST['plazoEntrega'] :"";
        $query = "call suitecrm.sp_consolidacion_filtro('$pcv_proveedoraio', '$pcv_numerocotizacion', '$name', '$pcv_clienteaio', '','$pcv_familia');";
        $results = $GLOBALS['db']->query($query, true);
        $object= array();
        while($row = $GLOBALS['db']->fetchByAssoc($results))
            {
                $object[] = $row;
            }
        echo json_encode($object);
    }
    
 
