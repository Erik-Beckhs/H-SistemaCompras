<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/include/generic/SugarWidgets/
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SugarWidgetSubPanelEditProdDesp extends SugarWidgetField
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
      }</style>';
      $id = $layout_def['fields']['ID'];
      $beand = BeanFactory::getBean('SCO_ProductosDespachos', $id);
      $id_pr_co = $beand->prdes_idproductos_co;
      if ($_REQUEST['module'] == 'SCO_Despachos') {
        $html .= "<input type='number' min='0'  max='".$beand->prdes_cantidad."' id='cantidad' class='cantidad' style='background:#f2f2f2;border:none;text-align: center; width:50px;' value='".$beand->prdes_cantidad."' onkeypress='habilita(event, this.value, \"".$id."\", \"".$id_pr_co."\")'>";
      }else{
        $html .= "<span class='cantidad' >".$beand->prdes_cantidad."</span>";
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
      $('.cantidad').click(function(){
        $(this).css({'background':'#fff', 'border':'solid 1px #cccccc'});
      });
      $('.cantidad').on('blur', function(){
        $(this).css({'background':'#f2f2f2', 'border':'none'});
        $('.cantidadColor').css({'background':'#a7d1a9', 'border':'none'});
      });
      $('.cantidad').keypress( function(){
        $(this).css({'background':'#a7d1a9', 'border':'none'}).addClass('cantidadColor');
      });
      function habilita(e, value, id, id_pr_co) {
        var tipo = 'cantidad';
        //debugger;
        if (e.keyCode === 13 && !e.shiftKey) {
          $.ajax({
            type: 'get',
            url: 'index.php?to_pdf=true&module=SCO_ProductosDespachos&action=cantidad&id='+id,
            data: {value, id_pr_co, tipo},
            success: function(data) {
              //debugger;
              var datos = $.parseJSON(data);
              if(datos != 'Error'){
                alert('Modificación realizada con éxito');
              }else{
                alert('La canitdad debe ser mayor a 0');                
              }
            }
          });
        }
      }
      //la funcion que se asctiva en el desenfoque
      function habilitav2(e, value, id, id_pr_co) {
        var tipo = 'cantidad';
        //debugger;
        $.ajax({
          type: 'get',
          url: 'index.php?to_pdf=true&module=SCO_ProductosDespachos&action=cantidad&id='+id,
          data: {value, id_pr_co, tipo},
          success: function(data) {
            //debugger;
            var datos = $.parseJSON(data);
            if(datos != 'Error'){
              //alert('Cantidad restante ' + datos);
            }else{
              //alert('La canitdad no puede ser mayor a ');
              //$('#cantidad').val('');
            }
          }
        });
      }
      </script>";
      return $html;
    }
}
?>
