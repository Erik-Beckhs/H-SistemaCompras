<?php
/**
*Esta clase de enviodatoscrmventas.php realiza el envio de id Contizacion de producto para actulizar el codigo AIO del CRM
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class EnviaDatosCRM{
    function enviarInformacion($idCotizacion,$idProductoCotizado,$codigoAIO){
        try {            
            $payload = '{"idCotizacion": "'.$idCotizacion.'","idProducto": "'.$idProductoCotizado.'","codigoAIO": "'.$codigoAIO.'",}';
            $ch = curl_init();
            #curl_setopt($ch, CURLOPT_URL, "http://localhost:7507/api/embarqueestado"); # DEV
            curl_setopt($ch, CURLOPT_URL, "http://docker-qas.hansa.com.bo:7507/api/updateaiocrm"); #QAS
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
            $content = curl_exec($ch);
            $intReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close ($ch);
            if ($intReturnCode != 200) {
                $var = '404';
            }else{
                $var = '200';
            }
        } catch (Exception $e) {
            $var = '404';   
        }
         return $var;       
    }
}
?>