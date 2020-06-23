<?php
/**
*Esta clase realiza el poblado de datos desde el modulo de CONTACTOS de Suitecrm
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Clfecha
{
	static $ult_rid = "";
	static $already_ran = false;

  	function Fnfecha($bean, $event, $arguments)
  	{
  		if(self::$already_ran == true) return;
    	self::$already_ran = true;
  		$idOC = $bean->id;
	    //Query, datos del modulo de contactos de SUITECRM
	    $con = "UPDATE sco_ordencompra SET date_entered=now() where id = '".$idOC."';";
	    $results = $GLOBALS['db']->query($con, true);
  	}
}
?>
