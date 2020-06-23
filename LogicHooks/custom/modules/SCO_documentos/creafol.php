<?php
/**
*Esta clase realiza el guardado de los archivos en el servicio de OpenKM
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_documentos
*/
class Clcreafol
{
	function Fncreafol($bean, $event, $arguments) 
	{
		$id = $bean->id;
		$query = "
    	SELECT 
		sco_ordencompra_sco_documentossco_ordencompra_ida as oc_id
		FROM sco_ordencompra_sco_documentos_c
		where 
		sco_ordencompra_sco_documentossco_documentos_idb = '".$id."'";
    	$results = $bean->db->query($query, true);
    	$row = $bean->db->fetchByAssoc($results);

    	$beanoc = BeanFactory::getBean('SCO_OrdenCompra', $row['oc_id']);

		$Anio = date('Y');
		$mes = date('m');
		$nombre = $beanoc->name;

		$OKMAuth = new SoapClient('http://192.168.1.85:8080/OpenKM/services/OKMAuth?wsdl');
		$OKMFolder = new SoapClient('http://192.168.1.85:8080/OpenKM/services/OKMFolder?wsdl');
    	$loginResp = $OKMAuth->login(array('user' => 'okmAdmin', 'password' => 'admin'));
    	$token = $loginResp->return;

    	$rutaa = '/okm:root/OrdenesCompras/'.$Anio;
    	$creaCarpeta = $OKMFolder->createSimple(array('token' => $token, 'fldPath' => $rutaa));

    	$rutam = '/okm:root/OrdenesCompras/'.$Anio.'/'.$mes;
    	$creaCarpeta = $OKMFolder->createSimple(array('token' => $token, 'fldPath' => $rutam));
    	
    	$rutaoc = '/okm:root/OrdenesCompras/'.$Anio.'/'.$mes.'/'.$nombre;
    	$creaCarpeta = $OKMFolder->createSimple(array('token' => $token, 'fldPath' => $rutaoc));

    	$OKMAuth->logout($token);
	}

	function Fnscargaarch($bean, $event, $arguments) 
	{
		//$preAdded = $_POST['preAdded'];
		//$GLOBALS['log']->debug("jjsc ".serialize($bean));
	}
}
?>