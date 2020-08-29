<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Eventos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

	$id = $_GET['id'];
	$est = $_GET['est'];
	#utilizar update de sql y no el bean del sugarcrm para no crear conflictos de bean
    #$despachos = "UPDATE sco_despachos SET des_est = '".$est."' WHERE id = '".$id."';";
    #$obj_des = $GLOBALS['db']->query($despachos, true);
	#echo json_encode($est);

    $bean_eventos = BeanFactory::getBean('SCO_Eventos', $id);
    #Obteniendo id del Embarque
    $bean_eventos->load_relationship('sco_embarque_sco_eventos');
	$relatedBeans = $bean_eventos->sco_embarque_sco_eventos->getBeans();
	reset($relatedBeans);
	$parentBean   = current($relatedBeans);
	#id del Embarque relacionado con el evento
	$idEmbarque   = $parentBean->id;

	#Conexion con servicio Rest para el evnio de datos
	include ('enviaDatosCrmVentas.php');
	$envioDatosCrm= new EnviaDatosCRM();
	$respuesta = $envioDatosCrm->enviarInformacion($idEmbarque,$id,$bean_eventos->name);
	#Verificando si la conexion fue 200
    if($respuesta == '200'){
		if ($bean_eventos->eve_fechare != null && $bean_eventos->transportistaotros) {
				echo json_encode($bean_eventos->eve_fechare);
				$bean_eventos->eve_estado = $est;
				$bean_eventos->save();
		}
		else {
			echo json_encode("error");
		}
    }else{
    	echo json_encode($respuesta);
    }
	
?>
