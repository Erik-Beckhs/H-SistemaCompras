<?php 
	
if (! defined ( 'sugarEntry' ))
define ( 'sugarEntry', true );
include ('../../../../../config.php');
include ('../../../../../custom/include/language/es_ES.lang.php');

global $sugar_config;
$datos = $_POST['datos']? $_POST['datos'] :"";

$jsonDatos = json_decode($datos)
$jsonDatos->nombre;
echo $jsonDatos->nombre;

?>	