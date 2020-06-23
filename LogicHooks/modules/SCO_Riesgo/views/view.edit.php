<?php
/**
*Esta clase realiza realiza la modificacion del la vista del boton de "Formulraio completo"
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_Problema/views/
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
  die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_RiesgoViewEdit extends ViewEdit {

  function SCO_RiesgoViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display($bean){ 
  //CSS, oculta el boton de formulario completo   
    echo "<style>#SCO_Riesgo_subpanel_full_form_button{display:none;}</style>";
    parent::display();
  }
}
?>