<?php
/**
*Esta clase realiza el llamado de datos del modulo Users y los guarda en los campos de 
*SCO_Contactos.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Contactos
*/
class CldatosC 
{
  function Fndatosc($bean, $event, $arguments) 
  {
    //carga datos de usuario
    $user_id = $bean->user_id_c;
    //Bean USERS
    $bean_user = BeanFactory::getBean('Users', $user_id);
    $bean->name = $bean_user->user_name;
    $bean->con_cargo = $bean_user->title;
    $bean->con_telefono = $bean_user->phone_work;
    $bean->con_movil = $bean_user->phone_mobile;

    //extraccion correo princial
    $oSugarEmailAdd = new SugarEmailAddress;
    $bean->con_email = $oSugarEmailAdd->getPrimaryAddress($bean_user); 

    $bean->save(); 
  } 
}
?>