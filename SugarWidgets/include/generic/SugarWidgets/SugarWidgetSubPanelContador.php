<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/include/generic/SugarWidgets/
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SugarWidgetSubPanelContador extends SugarWidgetField
{		
	function displayHeaderCell($layout_def){
      return '<b>#</b>';
    }
    function displayList($layout_def)
    {	
    	$id = $layout_def['fields']['ID'];
    	$name = $layout_def['fields']['NAME'];
    	#$productodespachos = BeanFactory::getBean('SCO_ProductosDespachos', $id);
    	#name = $productodespachos->id;

    }
}