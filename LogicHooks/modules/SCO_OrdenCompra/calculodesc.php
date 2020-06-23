<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_OrdenCompra
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

 	$id = $_GET['id']; 
 	$bean = BeanFactory::getBean('SCO_OrdenCompra', $id);
	$desc_por = $_GET['desc_por'];
	$desc_val = $_GET['desc_val'];
	$tipo = $_GET['tipo'];

	if($tipo == 1){
		$bean->orc_descpor = $desc_por;		
		$desc_por = $desc_por * $bean->orc_importet /100;	
		$bean->orc_descvalor = $desc_por;				
		$bean->orc_tototal = $bean->orc_importet - $desc_por;
	}
	if($tipo == 2){
		$bean->orc_descvalor = $desc_val;
		$desc_val_p = $desc_val * 100 / $bean->orc_importet;
		$bean->orc_descpor = $desc_val_p;		
		$bean->orc_tototal = $bean->orc_importet - $desc_val;
	}

	$bean->load_relationship('sco_ordencompra_sco_plandepagos');
	$relatedBeans = $bean->sco_ordencompra_sco_plandepagos->getBeans(); 
	$beanrec = "";
	foreach ($relatedBeans as $ppbean) {
		
		$ppbean->ppg_monto = $bean->orc_tototal * $ppbean->ppg_porc / 100;
		$beanrec .= $ppbean->ppg_monto ."|";
		$ppbean->save();
	}
	$bean->orc_productos = $beanrec;
	echo json_encode($bean->orc_importet.",".$bean->orc_descpor.",".$bean->orc_descvalor.",".$bean->orc_tototal);
	$bean->save();	
 ?>