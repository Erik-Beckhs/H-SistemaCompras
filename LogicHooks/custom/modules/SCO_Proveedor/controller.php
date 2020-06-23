<?php 
/**
*Esta clase realiza el guardado de datos del usuario logueado en el modulo de SCO_PROVEEDOR
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Proveedor
*/
class SCO_ProveedorController extends SugarController {  

	public function post_save() { 
    //Datos  usuario logueado
    global $current_user;
    $this->bean->assigned_user_id = $current_user->id;
    $this->bean->iddivision_c = $current_user->iddivision_c;
    $this->bean->idregional_c = $current_user->idregional_c;
    $this->bean->idamercado_c = $current_user->idamercado_c;
    $this->bean->save();
    parent::post_save();
	}
}
?>
