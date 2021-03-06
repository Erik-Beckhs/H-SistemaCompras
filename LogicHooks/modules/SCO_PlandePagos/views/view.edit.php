<?php
/**
*Esta clase realiza operaciones matemÃƒÂ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_PlanPagos
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
  die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_PlandePagosViewEdit extends ViewEdit {

  function SCO_PlandePagosViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display($bean){
    $js = "<script>
      //Cargando el precio total del modulo de productos
      var b = [];
      var cont = 0;
      var datosProductos = $('#idOrdeCompra').text();
      /*
      $('#list_subpanel_sco_ordencompra_sco_productos .list tbody .oddListRowS1').each(function(){
      if(cont == 0){
        var a = $(this).text();
        b = a.split('|');
        cont++;
      }else{cont++;}
      });
      */
      b = datosProductos.split('|');      
      var tot = b[1].split(',');
      var total = tot[3];
      $('#ppg_monto').on('keyup', function (){
        var b = $(this).val() * 100 / total;
        $('#ppg_porc').val(b.toFixed(2));
      });
      $('#ppg_porc').on('keyup', function (){
        var a = $(this).val() * total / 100;
        $('#ppg_monto').val(a.toFixed(2));
      });
      $('#ppg_porc').on('keyup', function(){
        if($(this).val() > 100){
          $(this).val((100).toFixed(2));
        }else if($(this).val() <= 0){
          $(this).val((0).toFixed(2));
        }
      });
      $('#ppg_monto').on('keyup', function(){
        if($(this).val() <= 0){
            $(this).val((0).toFixed(2));
          }
      });

      //Codigo modificado por Rkantuta
      $('#ppg_cantdias').on('keyup',function()
      {
        alert(12);
      })
    </script>";
    echo "<style>#SCO_PlandePagos_subpanel_full_form_button{display:none;}</style>";
    echo $js;
    parent::display();
  }
}
?>
