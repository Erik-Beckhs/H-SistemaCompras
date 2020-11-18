<?php
/**
*Esta clase realiza el llenado de datos del modulo Users en los campos de SCO_Aprobadores
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/
class Clautoriza
{
  function Fnautoriza($bean, $event, $arguments)
  {
    //move_uploaded_file ( string $upload , string $Autorizaciones ) : bool
    $resp = rename ('upload/'.$bean->id,'upload/Autorizaciones/'.$bean->id);
  

  }
}
?>
