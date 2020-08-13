<?php
/**
*Esta clase Elimina la Orden de compra de acuerdo a condiciones
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/

class ClelimanOrdenCompra
{
  	function Fnelimina($bean, $event, $arguments)
  	{
  		$idOC = $bean->id;
	    $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $idOC);
      if($beanoc->orc_estado == '2'){
        echo "<script>alert('Eliminacion Exitosa".$bean->name.");</script>";
        #$beanoc->deleted = 1;
        #$beanoc->save();
      }else{
        echo "<script>alert('No se pudo eliminar la Orden de Compra".$bean->name.");</script>";
        #die(SugarApplication::redirect('index.php?module=SCO_Despachos&action=DetailView&record='.$bean->id));
        exit();
      }
  	}


}
?>
