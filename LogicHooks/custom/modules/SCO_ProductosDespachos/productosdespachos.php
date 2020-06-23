<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_ProductosDespachos
*/
class Clproductosdespachos
{
static $already_ran = false;

  	function Fnproductosdespachos($bean, $event, $arguments)
  	{
  	if(self::$already_ran == true) return;
    self::$already_ran = true;
    if($bean->prdes_idproductos_co == ''){
	  	$id = $bean->id;
	    $bean->name = $bean->sco_productoscompras_sco_productosdespachos_name;
	    global $current_user;
	    $bean->assigned_user_id = $current_user->id;
	    $bean->iddivision_c = $current_user->iddivision_c;
	    $bean->idregional_c = $current_user->idregional_c;
	    $bean->idamercado_c = $current_user->idamercado_c;
	    $bean->save();
    }
  }
}
?>
