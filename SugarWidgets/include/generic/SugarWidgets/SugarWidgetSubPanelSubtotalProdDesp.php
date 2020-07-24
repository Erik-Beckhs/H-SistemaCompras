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

class SugarWidgetSubPanelSubtotalProdDesp extends SugarWidgetField
{
      function displayHeaderCell($layout_def){        
       return '<a style="font-weight: normal;"><b>Subtotal</b></a>';
    }
    function displayList($layout_def)
    {

        //Obteniendo el id del Subpanel de Despachos
          $id = $layout_def['fields']['ID'];
          $nombre = $layout_def['fields']['NAME'];
        //Obteniedno Id de la vista detallada ProdcutosCompras
        $beand = BeanFactory::getBean('SCO_ProductosDespachos', $id);
        $descValor = $beand->description;
        $id_pr_co = $beand->prdes_idproductos_co;
        $subtotal = $beand->prdes_cantidad * $beand->prdes_unidad;
        $subtotal = $subtotal - $descValor;
        $html = '<span class="label subtotalProducto"style="font-size: 12px;color: #000;background-color: #eee;">'.$subtotal.'</span>';
       return $html;   
    }
}
