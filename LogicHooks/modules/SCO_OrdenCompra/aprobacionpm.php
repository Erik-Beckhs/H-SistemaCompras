<?php
/**
*esta clase extrae todo los datos para la integracion con el proceso de Solicitud de adquisiciones MHES
*@author Yilmar Choque <jose.choque.m@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class Aprobadores{
    function getAprobador($id){
        try {            
            $payload ='{"id":"'.$id.'"}';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://docker-qas.hansa.com.bo:2202"); # QAS
            #curl_setopt($ch, CURLOPT_URL, "docker-qas.hansa.com.bo:7506/api/consolidaciones"); #Desarrollo
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
            curl_exec($ch);
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