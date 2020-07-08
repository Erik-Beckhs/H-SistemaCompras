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

if (! defined ( 'sugarEntry' )) 
define ( 'sugarEntry', true );
include ('../../../../../config.php');
include ('../../../../../custom/include/language/es_ES.lang.php');

require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

global $sugar_config, $current_user;
$datos = $_POST['datos']? $_POST['datos'] :"";

//Creacion de nuevo bean (registro)
$beanConsolidacion = BeanFactory::newBean('SCO_Consolidacion');
//Poblacion de datos
$beanConsolidacion->name = 'Consolidacion Prueba 2';
$beanConsolidacion->save();

//Creacion de bean relationship
// $beanConsolidacion->load_relationship('SCO_Consolidacion');
// //Añadiendo la relacion con consolidacion y orden de compra
// $beanConsolidacion->SCO_Consolidacion->add('45fa0345-1790-cd2c-b8e2-5d0906255c03');

//Devuelve el id de la Consolidacion creada
$idConsolidacion = $beanConsolidacion->id;

echo $idConsolidacion;

?>	