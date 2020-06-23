<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Embarque
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry) die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_OrdenCompraViewEdit extends ViewEdit {

  function SCO_OrdenCompraViewEdit() {
    parent::ViewEdit ();
    //$this->useForSubpanel = true;
  }

  function display(){
    

  	parent::display();
  }

}
