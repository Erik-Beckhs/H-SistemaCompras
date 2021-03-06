<?php
/**
 *
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@copyright 2020
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@Update 2020
 *Esta clase realiza la eliminacion de la relacion
 *de consolidacion con Productos cotizados de vente.
 *@license ruta: /var/www/html/custom/modules/SCO_Embarque
 */
class Celiminarelacion {
	function Felimina($bean, $event, $arguments) {
		#Id de Consolidacion
		$idConsolidacion = $bean->id;
		#Id de ProductoCotizadoDeVenta
		$idPcv = $arguments['related_id'];
		#Actualizando datos de Productos cotizados de venta

		#$beanPcv = new SCO_ProductosCotizadosVenta();
		#$beanPcv->retrieve($idPcv);
		$beanPcv           = BeanFactory::getBean('SCO_ProductosCotizadosVenta', $idPcv);
		$pcv_cantidadsaldo = $bean->pcv_cantidadsaldo+$beanPcv->pcv_cantidadconsolidado;
		#$beanPcv->pcv_cantidadconsolidado = 0;
		#$beanPcv->save();

		$bean->retrieve($row['sco_consol4725osventa_idb']);
		$bean->db->query("UPDATE sco_productoscotizadosventa SET pcv_cantidadsaldo ='".$pcv_cantidadsaldo."', pcv_cantidadconsolidado = 0, pcv_consolidado = 0 WHERE id='".$idPcv."'");


	}
}