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
        $var = 'soy el id'.$id;
        $payload ='{"id":"'.$id.'"}';
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, "http://192.168.101.64:8081");
        curl_setopt($ch, CURLOPT_URL, "http://Localhost:8081");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_exec($ch);
        curl_close($ch);
        return $var;
       
    }
}
?>