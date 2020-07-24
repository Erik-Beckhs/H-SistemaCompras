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

class SugarWidgetSubPanelDespachosDias extends SugarWidgetField
{
	function displayHeaderCell($layout_def){
        return '<a style="font-weight: normal;">Días transito</a>';
    }
    function displayList($layout_def)
    {
        //Obteniendo el id del Subpanel de Despachos
    	$id_des = $layout_def['fields']['ID'];
			$pc_des = "SELECT em.emb_fechacrea,des.des_diastrans,
																		des.des_est,
																		des.des_fechacrea,
																		des.des_fechaprev,em.date_modified,em.emb_estado
									FROM suitecrm.sco_despachos des
									inner join sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_despachos_idb = des.id
									inner join sco_embarque em on em.id = emde.sco_embarque_sco_despachossco_embarque_ida
									where des.id =  '$id_des'; ";
			$obj_pc_des = $GLOBALS['db']->query($pc_des, true);
			$row_pc_des = $GLOBALS['db']->fetchByAssoc($obj_pc_des);
			$date2 ="";
			if ($row_pc_des["emb_estado"] == 3) {
				$date2 = new DateTime($row_pc_des["date_modified"]);
			}
			else {
				$date2 = new DateTime(date("Y-m-d"));
			}
			$date1 = new DateTime($row_pc_des["emb_fechacrea"]);
			$diff = $date1->diff($date2);
			$dias = $diff->days;
			// this will output 4 days
        //Query para obtener cantidades de productos en despachos de acuerdo al Id de Despacho ($id_des) y el Id de ProductosCompras ($id_des)
				if ($dias == $row_pc_des["des_diastrans"]) {
					return "<span style='box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);background: #378cbe;font-size:12px;color:#fff;' class='label label-warning' class='text-info'>".$dias."/".$row_pc_des["des_diastrans"]."</span>";
				}
				elseif ($dias > $row_pc_des["des_diastrans"]) {
					return "<span style='box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);background: #378cbe;font-size:12px;color:#fff;' class='label label-danger' class='text-info'>".$dias."/".$row_pc_des["des_diastrans"]."</span>";
				}
				elseif ($dias < $row_pc_des["des_diastrans"]) {
					return "<span style='box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);background: #378cbe;font-size:12px;color:#fff;' class='label label-success' class='text-info'>".$dias."/".$row_pc_des["des_diastrans"]."</span>";
				}

    }
}
