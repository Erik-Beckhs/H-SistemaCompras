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

class SugarWidgetSubPanelEditProdDespPrec extends SugarWidgetField
{
    function displayList($layout_def)
    {
      $html = '';      
      $html = '<style>
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
      }
      input[type=number] { 
        -moz-appearance:textfield; 
      }
      </style>'; 
      $id = $layout_def['fields']['ID'];
      $beand = BeanFactory::getBean('SCO_ProductosDespachos', $id);   
      $id_pr_co = $beand->prdes_idproductos_co;   
      if ($_REQUEST['module'] == 'SCO_Despachos') {   
        $html .= "<input type='number' min='0'  max='".$beand->prdes_unidad."' id='precio' class='precio' style='background:#f2f2f2;border:none;text-align: center; width:50px;' value='".$beand->prdes_unidad."' onblur='precio_unidad(event, this.value, \"".$id."\", \"".$id_pr_co."\")' onkeypress='precio_unidad(event, this.value, \"".$id."\", \"".$id_pr_co."\")' onclick='precio_unidad(event, this.value, \"".$id."\", \"".$id_pr_co."\")'>";      
      }else{
        $html .= "<span>".$beand->prdes_unidad."</span>";
      }  
      $query = "SELECT *
      FROM sco_productos_co
      WHERE id = '".$id."'; ";
      $results = $GLOBALS['db']->query($query, true);
      while($row = $GLOBALS['db']->fetchByAssoc($results)){
        $cant_p = $row['pro_cantidad'];
        $html .= "<script>alert('$cant_p');</script>";
      }

      $html .= "<script>   
      $('.precio').click(function(){
        $(this).css({'background':'#fff', 'border':'solid 1px #cccccc'});

      });
      $('.precio').on('blur', function(){
        $(this).css({'background':'#f2f2f2', 'border':'none'});
      });
      
      function precio_unidad(e, value, id, id_pr_co) {
        var tipo = 'precio';
        if (e.keyCode === 13 && !e.shiftKey) {
          $.ajax({
            type: 'get',
            url: 'index.php?to_pdf=true&module=SCO_ProductosDespachos&action=cantidad&id='+id,
            data: {value, id_pr_co, tipo},
            success: function(data) {
              debugger;
              var datos = $.parseJSON(data);
              if(datos != 'Error'){
                alert('precio ok');
              }else{
                alert('Error ');
                $('#precio').val('');
              }
            }
          });  
        } 
      }
      </script>";
      return $html;
    }
}
 ?>