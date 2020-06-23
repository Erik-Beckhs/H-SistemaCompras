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

class SugarWidgetSubPanelFechasEvento2 extends SugarWidgetField
{
  function displayHeaderCell($layout_def){
        return '<a style="font-weight: normal;color: #fff;">Fecha Nueva</a><span style="margin-left:2px; margin-top: 0px; font-size:14px; position:absolute;"> &#128198;</span>';
    }
  function displayList($layout_def)
  {
    $id = $layout_def['fields']['ID'];

    $bean_fechas = BeanFactory::getBean('SCO_Eventos', $id);
    $t_estimado = $bean_fechas->eve_tiempoest * (-1);
    $fp = $bean_fechas->eve_fechaplan;
    $fr = $bean_fechas->eve_fechare;
    $fn = $bean_fechas->eve_fechanuevo;
    $estado = $bean_fechas->eve_estado;

    $eventos = "SELECT * FROM sco_eventos WHERE id = '".$id."'";
    $res_ev = $GLOBALS['db']->query($eventos, true);
    $row_ev = $row_fila = $GLOBALS['db']->fetchByAssoc($res_ev);
    $fp_1 = $row_ev['eve_fechaplan'];
    $fr_1 = $row_ev['eve_fechare'];
    $fn_1 = $row_ev['eve_fechanuevo'];    
    
    $f_r_n = date_create($fn_1);
    date_add($f_r_n, date_interval_create_from_date_string(''.$t_estimado.' days'));
    $fecha_r_antes = date_format($f_r_n, 'Y-m-d');   

    $fecha_plan = strtotime($fp_1); 
    $fecha_nueva = strtotime($fn_1);
    
    $diff = $fecha_nueva - $fecha_plan; 
    $tiempo = round($diff / 86400);
    $f_r_n2 = date_create($fn_1);
    date_add($f_r_n2, date_interval_create_from_date_string(''.$tiempo.' days'));
    $fecha_r_antes2 = date_format($f_r_n2, 'Y-m-d');

    $fecha_actual = date('Y-m-d');

    if($fn_1 < $fecha_actual && $fr == ''){      
      return '<span style="color:red;">'.$fn.'</span>';
    }else{
      return $fn;    
    }
  }
}
 ?>
