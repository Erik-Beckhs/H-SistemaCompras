<?php
/**
 *
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@copyright 2020
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@Update 2020
 *Esta clase realiza la eliminacion de la conoslidacion de acuerdo a la realacionado a una Orden de compra
 *de consolidacion con Productos cotizados de vente.
 *@license ruta: /var/www/html/custom/modules/SCO_Embarque
 */
class Celiminaconsolidacion {
	function Feliminaconsolidacion($bean, $event, $arguments) {
		#Id de Consolidacion
		$idConsolidacion = $bean->id;
		#Obteniendo id de la Orden de compra
	 

		if($bean->con_estado != 1){
			echo "<script>alert('Eliminacion Exitosa".$bean->name.");</script>";
		}else{
			echo "<script>alert('No se pudo eliminar la Orden de Compra".$bean->name.");</script>";
			#die(SugarApplication::redirect('index.php?module=SCO_Despachos&action=DetailView&record='.$bean->id));
			exit();
		}
	}
}