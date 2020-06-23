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

class SugarWidgetSubPanelEditProdDespObs extends SugarWidgetField
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
        $html .= "<input type='text' id='observacion' class='observacion' style='background:#f2f2f2;border:none;text-align: left; width:100px;' value='".$beand->prdes_observaciones."' onkeypress='hab(event, this.value, \"".$id."\", \"".$id_pr_co."\")' >";
      }else{
        $html .= "<span>".$beand->prdes_observaciones."</span>";
      }
      $query = "SELECT *
      FROM sco_productos_co
      WHERE id = '".$id."'; ";
      $results = $GLOBALS['db']->query($query, true);
      while($row = $GLOBALS['db']->fetchByAssoc($results)){
        $cant_p = $row['prdes_observaciones'];
        $html .= "<script>alert('$cant_p');</script>";
      }

      $html .= "<script>
      $('.observacion').click(function(){
        $(this).css({'background':'#fff', 'border':'solid 1px #cccccc'});
      });
      $('.observacion').on('blur', function(){
        $(this).css({'background':'#f2f2f2', 'border':'none'});
        $('.cantidadColor').css({'background':'#a7d1a9', 'border':'none'});
      });
      $('.observacion').keypress( function(){
        $(this).css({'background':'#a7d1a9', 'border':'none'}).addClass('cantidadColor');
      });
      function hab(e, value, id, id_pr_co) {
        var tipo = 'observacion';
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
                alert('Las observaciones se guardaron exitosamente!!');
              }else{
                alert('No se pudo guardar los datos');
                $('#observacion').val('');
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
              alert('La canitdad no puede ser mayor a ');
              $('#cantidad').val('');
            }
          }
        });
      }
      </script>";
      return $html;
    }
}
?>
