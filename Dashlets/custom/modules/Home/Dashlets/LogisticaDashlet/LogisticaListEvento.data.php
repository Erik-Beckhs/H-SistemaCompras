<?php
#ini_set ( 'display_errors' , 1 );
#ini_set ( 'display_startup_errors' , 1 );
#error_reporting ( E_ALL );
if (! defined ( 'sugarEntry' ))
define ( 'sugarEntry', true );
include ('../../../../../config.php');
include ('../../../../../custom/include/language/es_ES.lang.php');

global $sugar_config;
$id = $_GET['id']? $_GET['id'] :"";


//$curl = curl_init('http://192.168.1.24:800'); //QAS
$curl = curl_init('http://192.168.1.62:800/eventosComentario'); //PRD
//$curl = curl_init('http://192.168.0.14:800/eventosComentario'); //DEV
//$curl = curl_init($ServicioWeb);

$data = '{"id":"'.$id.'"}';
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
