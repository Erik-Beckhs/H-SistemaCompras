<?php
/**
*Esta clase realiza el envio del estado del EMbarque con los datos de la orden de compra y el producto cotizado a CRM de ventas
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class EnviaDatosCRM{
    function enviarInformacion($idEmbarque,$idEvento,$nombreEvento){
        try {            
            $payload = '{"idEmbarque": "'.$idEmbarque.'","idEvento": "'.$idEvento.'","nombreEvento":"'.$nombreEvento.'"}';
            $ch = curl_init();
            #curl_setopt($ch, CURLOPT_URL, "http://localhost:7507/api/embarqueestado"); # DEV
            curl_setopt($ch, CURLOPT_URL, "http://docker-qas.hansa.com.bo:7508"); #QAS
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