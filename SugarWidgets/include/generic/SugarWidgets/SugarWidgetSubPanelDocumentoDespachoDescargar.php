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

class SugarWidgetSubPanelDocumentoDespachoDescargar extends SugarWidgetField
{		
	/*function displayHeaderCell($layout_def){
      return '<b>#</b>';
    }*/
    function displayList($layout_def)
    {	
    	$id = $layout_def['fields']['ID'];
    	$name = $layout_def['fields']['document_name'];
    	$beanPD = BeanFactory::getBean('SCO_DocumentoDespacho', $id);
    	$file_mime_type = $beanPD->file_mime_type;
        if($file_mime_type == 'application/pdf' || $file_mime_type == 'image/jpeg' || $file_mime_type == 'image/png'){
            $html = '<p style="font-size:11px;"><a href="index.php?entryPoint=download&id='.$id.'&type=SCO_DocumentoDespacho" >descargar &#x1f4e5;&#xfe0e; </a></p><p style="font-size:11px;"><a href="index.php?preview=yes&entryPoint=download&id='.$id.'&type=SCO_DocumentoDespacho" target="_blank" style="color:#42c5b4;">vista <i class="glyphicon glyphicon-eye-open"></i> </a></p>';
        }else{
            $html = '<p style="font-size:11px;"><a href="index.php?entryPoint=download&id='.$id.'&type=SCO_DocumentoDespacho" >descargar &#x1f4e5;&#xfe0e; </a></p>';
        }
        return $html;
    }
}