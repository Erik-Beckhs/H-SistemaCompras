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

class SugarWidgetSubPanelFechasEvento extends SugarWidgetField
{ 
    function displayHeaderCell($layout_def){
        return '<a style="font-weight: normal">Fecha Real</a>';
    }
    function displayList($layout_def)
    {           
      $id = $layout_def['fields']['ID'];
      $bean_fechas = BeanFactory::getBean('SCO_Eventos', $id);
      if($bean_fechas->eve_fechare <= $bean_fechas->eve_fechaplan){
        return '<span style="color:#909191!important;">'.$bean_fechas->eve_fechare.'</span>';
      }else{
        return '<span style="color:#909191!important;">'.$bean_fechas->eve_fechare.'</span>';
      }
    }
}
 ?>