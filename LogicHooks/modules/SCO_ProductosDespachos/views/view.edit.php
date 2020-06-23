<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_ProductosDespachos
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry) die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_ProductosDespachosViewEdit extends ViewEdit {

  function SCO_ProductosDespachosViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display(){ 
  echo  "<style> #form_SubpanelQuickCreate_SCO_ProductosDespachos tr {float: left;}
  #form_SubpanelQuickCreate_SCO_ProductosDespachos #prdes_descripcion{} 
   #form_SubpanelQuickCreate_SCO_ProductosDespachos #prdes_cantidad{width:60px;} 
   #form_SubpanelQuickCreate_SCO_ProductosDespachos #prdes_unidad{width:60px;} 
   #form_SubpanelQuickCreate_SCO_ProductosDespachos #prdes_subtotal{width:60px;}
  #SCO_ProductosDespachos_subpanel_full_form_button{display:none;}
  </style>";
  	parent::display();

  }
}