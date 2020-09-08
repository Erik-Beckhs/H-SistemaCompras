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

class SugarWidgetSubPanelDespachosCantidad extends SugarWidgetField
{
	function displayHeaderCell($layout_def){
        if($id_pro = $_REQUEST['module'] == 'SCO_ProductosCompras'){
            return '<a style="font-weight: normal;color:#fff;">Cnt. Producto</a>';
        }else{
            return '<a style="font-weight: normal;color:#fff;">Cnt. Productos</a>';
        }
    }
    function displayList($layout_def)
    {
        //Obteniendo el id del Subpanel de Despachos
    	$id_des = $layout_def['fields']['ID'];
    	$nombre = $layout_def['fields']['NAME'];
        //Obteniedno Id de la vista detallada ProdcutosCompras
        $id_pro = $_REQUEST['record'];
        //Query para obtener cantidades de productos en despachos de acuerdo al Id de Despacho ($id_des) y el Id de ProductosCompras ($id_des)
        $pc_des = "SELECT ifnull(SUM(pd.prdes_cantidad), 0) as cantidad
        FROM sco_despachos_sco_productosdespachos_c as d_pd
        INNER JOIN sco_productosdespachos as pd
        ON d_pd.sco_despachos_sco_productosdespachossco_productosdespachos_idb = pd.id
        INNER JOIN sco_productos_co as p
        ON pd.prdes_idproductos_co = p.id
        WHERE d_pd.deleted = 0
        AND pd.deleted = 0
        AND d_pd.sco_despachos_sco_productosdespachossco_despachos_ida = '$id_des'
        AND p.pro_idpro = '$id_pro'; ";
        $obj_pc_des = $GLOBALS['db']->query($pc_des, true);
        $row_pc_des = $GLOBALS['db']->fetchByAssoc($obj_pc_des);

        $pc_items = "SELECT COUNT(*) as cantidadItems
                    FROM suitecrm.sco_despachos_sco_productosdespachos_c dp
                    INNER JOIN suitecrm.sco_productosdespachos pd
                    ON dp.sco_despachos_sco_productosdespachossco_productosdespachos_idb = pd.id
                    WHERE sco_despachos_sco_productosdespachossco_despachos_ida = '$id_des'
                    AND dp.deleted = 0
                    AND pd.deleted = 0; ";
        $obj_pc_items = $GLOBALS['db']->query($pc_items, true);
        $row_pc_items = $GLOBALS['db']->fetchByAssoc($obj_pc_items);

        if($row_pc_des['cantidad'] != 0){
            return "<p style='font-size: 12px;'>items ".$row_pc_items['cantidadItems']."</p> <p style='font-size: 12px; background: #fff;color:#000;' class='' class='text-info'>Cantidad".$row_pc_des['cantidad']."</p>";
        }else{
            //Query para obtener cantidades de productos en despachos de acuerdo al Id de Despacho ($id_des)
            $despacho = "SELECT SUM(pd.prdes_cantidad) as cantidad
            FROM sco_despachos_sco_productosdespachos_c as d_pd
            INNER JOIN sco_productosdespachos as pd
            ON d_pd.sco_despachos_sco_productosdespachossco_productosdespachos_idb = pd.id
            INNER JOIN sco_productos_co as p
            ON pd.prdes_idproductos_co = p.id
            WHERE d_pd.deleted = 0
            AND pd.deleted = 0
            AND d_pd.sco_despachos_sco_productosdespachossco_despachos_ida = '$id_des';";
            $obj_despacho = $GLOBALS['db']->query($despacho, true);
            $row_despacho = $GLOBALS['db']->fetchByAssoc($obj_despacho);
            return "<p class='text-info'style='font-size: 12px;'>items ".$row_pc_items['cantidadItems']." </p><p style='font-size: 12px;background: #fff;color:#000;' class=''>Cantidad ".$row_despacho['cantidad']."</p>";
        }
    }
}
