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

		if ($bean_eventos->eve_fechare != null && $bean_eventos->transportistaotros) {
				echo json_encode($bean_eventos->eve_fechare);
				$bean_eventos->eve_estado = $est;
				$bean_eventos->save();
		}
		else {
			echo json_encode("error");
		}


?>
