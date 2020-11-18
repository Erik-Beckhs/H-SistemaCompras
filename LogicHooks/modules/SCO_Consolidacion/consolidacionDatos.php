<?php
/*
 * @author Limberg Alcon <lalcon@hansa.com.bo>
 * @copyright 2020
 * @license /var/www/html/modules/SCO_Consolidacion
 * Este archivo realiza el almacenamiento de datos de los siguientes modulos.
 * SCO_Consolidacion
 * SCO_OrdenCompra
 * SCO_ProductosCotizadosVenta
 * SCO_Productos.
 */

if (!defined('sugarEntry')) {
	define('sugarEntry', true);
}

include ('../../../../../config.php');
include ('../../../../../custom/include/language/es_ES.lang.php');
require_once ('data/BeanFactory.php');
require_once ('include/entryPoint.php');

global $sugar_config, $current_user;

$nombre               = $_POST['nombre']?$_POST['nombre']:"";
$solicitante          = $_POST['solicitante']?$_POST['solicitante']:"";
$solicitante_id       = $_POST['solicitante_id']?$_POST['solicitante_id']:"";
$desc                 = $_POST['desc']?$_POST['desc']:"";
$contactoProveedor    = $_POST['contactoProveedor']?$_POST['contactoProveedor']:"";
$contactoProveedor_id = $_POST['contactoProveedor_id']?$_POST['contactoProveedor_id']:"";
$modTransporte        = $_POST['modTransporte']?$_POST['modTransporte']:"";
$incoterm             = $_POST['incoterm']?$_POST['incoterm']:"";
$lugarEntrega         = $_POST['lugarEntrega']?$_POST['lugarEntrega']:"";
$moneda               = $_POST['moneda']?$_POST['moneda']:"";
$multas               = $_POST['multas']?$_POST['multas']:"";
$formaPago            = $_POST['formaPago']?$_POST['formaPago']:"";
$tiempoEntrega        = $_POST['tiempoEntrega']?$_POST['tiempoEntrega']:"";
$garantia             = $_POST['garantia']?$_POST['garantia']:"";
$cantidadTotal        = $_POST['cantidadTotal']?$_POST['cantidadTotal']:"";
$precioTotalFob       = $_POST['precioTotalFob']?$_POST['precioTotalFob']:"";
$proveedor            = $_POST['proveedor']?$_POST['proveedor']:"";
$proveedor_id         = $_POST['proveedor_id']?$_POST['proveedor_id']:"";
$nombreEmpresa        = $_POST['nombreEmpresa']?$_POST['nombreEmpresa']:"";
$fax                  = $_POST['fax']?$_POST['fax']:"";
$box                  = $_POST['box']?$_POST['box']:"";
$pais                 = $_POST['pais']?$_POST['pais']:"";
$telefono             = $_POST['telefono']?$_POST['telefono']:"";
$direccion            = $_POST['direccion']?$_POST['direccion']:"";
$nombreConsolidacion  = $_POST['nombreConsolidacion']?$_POST['nombreConsolidacion']:"";
$nombreOc             = $_POST['nombreOc']?$_POST['nombreOc']:"";
$proyecto             = $_POST['proyecto']?$_POST['proyecto']:"";
$proyecto_id          = $_POST['proyecto_id']?$_POST['proyecto_id']:"";
$proyecto_tipo        = $_POST['proyecto_tipo']?$_POST['proyecto_tipo']:"";
$items                = $_POST['items']?$_POST['items']:"";

if($proyecto_tipo == ''){
	$proyecto_tipo = 0;
}
$dateFC = date_create(date("Y-m-d H:i:s"));
#Extrayendo el Proveedor por su codigo AIO
/*
$queryProveedor = "SELECT id FROM suitecrm.sco_proveedor where prv_codaio = '".$proveedor_id."'  and deleted = 0;";
$proveedorObj  = $GLOBALS['db']->query($queryProveedor, true);
$proveedorDato = $GLOBALS['db']->fetchByAssoc($proveedorObj);
$proveedorDato["id"];
*/
#Creación de la Orden de compra
$beanOc                                                 = BeanFactory::newBean('SCO_OrdenCompra');
$beanOc->name                                           = "OC_".$nombre;
$beanOc->orc_tipo                                       = 1;
$beanOc->orc_tipoo                                      = 2;
$beanOc->orc_fechaent                                   = date_format($dateFC, 'Y-m-d H:m:s');
$beanOc->orc_solicitado                                 = $solicitante;
$beanOc->user_id1_c                                     = $solicitante_id;
$beanOc->sco_proveedor_sco_ordencompra_name             = $proveedor;
$beanOc->sco_proveedor_sco_ordencomprasco_proveedor_ida = $proveedor_id;
$beanOc->sco_ordencompra_contacts_name                  = $contactoProveedor;
$beanOc->sco_ordencompra_contactscontacts_ida           = $contactoProveedor_id;
$beanOc->orc_decop                                      = '1';
$beanOc->orc_tcinco                                     = $incoterm;
$beanOc->orc_tclugent                                   = $lugarEntrega;
$beanOc->orc_tcforpag                                   = $formaPago;
$beanOc->orc_tcgarantia                                 = $garantia;
$beanOc->orc_tcmoneda                                   = $moneda;
$beanOc->orc_tcmulta                                    = $multas;
$beanOc->orc_tccertor                                   = 1;
$beanOc->orc_importet                                   = $precioTotalFob;
$beanOc->orc_tiempo                                     = $tiempoEntrega;
$beanOc->orc_tototal                                    = $precioTotalFob;
$beanOc->orc_descvalor                                  = 0;
$beanOc->orc_descpor                                    = 0;
$bean_ususol                                            = BeanFactory::getBean('Users', $solicitante_id);
$beanOc->orc_division                                   = $bean_ususol->iddivision_c;
$beanOc->orc_regional                                   = $bean_ususol->idregional_c;
$beanOc->idamercado_c                                   = $bean_ususol->idamercado_c;
$beanOc->orc_observaciones                              = $desc;
$beanOc->date_entered                                   = date_format($dateFC, 'Y-m-d H:i:s');
$beanOc->sco_ordencompra_id_c                           = '';
$beanOc->orc_occ                                        = '';
$beanOc->orc_verco                                      = 0;
$beanOc->orc_modalidadtransporte                        = $modTransporte;
$beanOc->save();
#Devuelve el id de la OC creada
$idOc = $beanOc->id;

#Armado de array de productos para orden de compra
$cantidadItems = 0;
foreach ($items as $key => $value) {
	$cantidadItems++;
	$idItem     = $value['id'];
	$subtotal   = $value['pcv_cantidadconsolidado'] * $value['pcv_preciofob'];
	if($cantidadItems < count($items)){
		$arrayItems .= '["'.$value['pcv_codigoproveedor'].'","'.$value['pcv_descripcion'].'","PZA","'.$value['pcv_cantidadconsolidado'].'","'.$value['pcv_preciofob'].'","0.00","0.00","'.$subtotal.'","'.$proyecto.'","'.$value['sco_productoscompras_id_c'].'","'.$proyecto_id.'","'.$proyecto_tipo.'","'.$value['name'].'","'.$idItem.'"],';	
	}else{
		$arrayItems .= '["'.$value['pcv_codigoproveedor'].'","'.$value['pcv_descripcion'].'","PZA","'.$value['pcv_cantidadconsolidado'].'","'.$value['pcv_preciofob'].'","0.00","0.00","'.$subtotal.'","'.$proyecto.'","'.$value['sco_productoscompras_id_c'].'","'.$proyecto_id.'","'.$proyecto_tipo.'","'.$value['name'].'","'.$idItem.'"]';	
	}		
}
$arrayItemsTotal = '['.$arrayItems.']|'.$precioTotalFob.',0,0,'.$precioTotalFob.'|'.$idOc;

#Creación del modulo de productos. realcionados a la Orden de compra
$beanProductos              = BeanFactory::newBean('SCO_Productos');
$beanProductos->description = $arrayItemsTotal;
$beanProductos->save();
$idProducto = $beanProductos->id;

$queryInsert = "INSERT INTO suitecrm.sco_ordencompra_sco_productos_c
	(id, date_modified, deleted, sco_ordencompra_sco_productossco_ordencompra_ida, sco_ordencompra_sco_productossco_productos_idb)
	VALUES
	(UUid(), '$newDate', 0, '$idOc', '$idProducto');
";
$obj  = $GLOBALS['db']->query($queryInsert, true);
$obj1 = $GLOBALS['db']->fetchByAssoc($obj);

#Creacion de nuevo de Consolidacion
$beanConsolidacion                                                       = BeanFactory::newBean('SCO_Consolidacion');
$beanConsolidacion->name                                                 = $nombre;
$beanConsolidacion->con_descripcion                                      = $desc;
$beanConsolidacion->con_estado                                           = 1;
$beanConsolidacion->date_entered                                         = date_format($dateFC, 'Y-m-d H:i:s');
$beanConsolidacion->con_cantitems                                        = $cantidadTotal;
$beanConsolidacion->sco_consolidacion_sco_proveedorsco_proveedor_ida     = $proveedor_id;
$beanConsolidacion->sco_consolidacion_sco_ordencomprasco_ordencompra_idb = $idOc;
$beanConsolidacion->con_preciototal                                      = $precioTotalFob;
$beanConsolidacion->save();
#Devuelve el id de la Consolidacion creada
$idConsolidacion = $beanConsolidacion->id;

#Creando las realaciones de los Productos cotizados con el modulo de consolidacion.
foreach ($items as $key => $value) {
	$idItem      = $value['id'];
	$queryInsert = "INSERT INTO suitecrm.sco_consolidacion_sco_productoscotizadosventa_c
	(id, date_modified, deleted, sco_consolf10bidacion_ida, sco_consol4725osventa_idb)
	VALUES
	(UUid(), '$newDate', 0, '$idConsolidacion', '$idItem');
";
	$obj  = $GLOBALS['db']->query($queryInsert, true);
	$obj1 = $GLOBALS['db']->fetchByAssoc($obj);
	#Actualizando registros de productos a relacionar.
	$beanPcv = new SCO_ProductosCotizadosVenta();
	$beanPcv->retrieve($idItem);
	$beanPcv->pcv_cantidadconsolidado = $value['pcv_cantidadconsolidado'];
	$beanPcv->pcv_cantidadsaldo       = $value['pcv_cantidadsaldo'];
	$beanPcv->pcv_consolidado         = 1;
	;

	$beanPcv->sco_consolf10bidacion_ida = $idConsolidacion;
	$beanPcv->save();
}

echo $idConsolidacion;

?>

