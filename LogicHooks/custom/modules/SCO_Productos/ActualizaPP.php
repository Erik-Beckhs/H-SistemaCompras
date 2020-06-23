<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Productos
*/
/*
class ClActualizaPP 
{
	function FnActualizaPP($bean, $event, $arguments) 
 	{
 		#Obteniendo Id OC
        $id_p = $bean->id;      
		$bean->load_relationship('sco_ordencompra_sco_productos');
	    $relatedBeans = $bean->sco_ordencompra_sco_productos->getBeans();
	    reset($relatedBeans);
	    $parentBean = current($relatedBeans);
	    $idoc = $parentBean->id;
	    #Obtenieno Monto total en la Orden de compra
	    $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
	    $tot_oc = $beanoc->orc_importet;
	    $mon_oc = $beanoc->orc_tcmoneda;
	    //obteniendo plan de pagos
	    $beanoc->load_relationship('sco_ordencompra_sco_plandepagos');
	    $relatedBeanspp = $beanoc->sco_ordencompra_sco_plandepagos->getBeans();
	    foreach ($relatedBeanspp as $beanrelpp) {
	    	$pp_porc = $beanrelpp->ppg_porc;
	        $pp_impact = $tot_oc * $pp_porc / 100;
	        $beanrelpp->ppg_monto = $pp_impact;
	        $beanrelpp->currency_id = $mon_oc;
	        $beanrelpp->save();
	    }	               
 	}
}
*/
 ?>