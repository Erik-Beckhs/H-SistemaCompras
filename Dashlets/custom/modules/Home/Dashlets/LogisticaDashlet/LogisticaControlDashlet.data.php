<?php
#ini_set ( 'display_errors' , 1 );
#ini_set ( 'display_startup_errors' , 1 );
#error_reporting ( E_ALL );
if (! defined ( 'sugarEntry' ))
define ( 'sugarEntry', true );
include ('../../../../../config.php');
//include ('../../../../../custom/application/Ext/Utils/custom_utils.ext.php');
include ('../../../../../custom/include/language/es_ES.lang.php');

global $sugar_config;
$division = $_GET['division']? $_GET['division'] :"";
$estadoEmbarque = $_GET['estadoEmbarque']? $_GET['estadoEmbarque'] :"";
$divCompra = $_GET['divCompra']? $_GET['divCompra'] :"";
$rol = $_GET['rol']? $_GET['rol'] :"";
$aMercado = $_GET['aMercado']? $_GET['aMercado'] :"";

//$curl = curl_init('http://192.168.1.24:800'); //QAS
$curl = curl_init('http://192.168.1.62:800'); //PRD
//$curl = curl_init('http://192.168.0.14:800'); //DEV
//$curl = curl_init($ServicioWeb);

$data = '{"divCompra":"'.$divCompra.'", "division":"'.$division.'","estadoEmbarque":"'.$estadoEmbarque.'","rol":"'.$rol.'","aMercado":"'.$aMercado.'"}';
try{
  curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-type:application/json"));
  curl_setopt($curl,CURLOPT_POST,true);
  curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
  curl_exec($curl);
  curl_close($curl);
}catch(exception $e){
  echo $op;
}
?>
