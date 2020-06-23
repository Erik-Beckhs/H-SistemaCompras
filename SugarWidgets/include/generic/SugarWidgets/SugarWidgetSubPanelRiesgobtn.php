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

class SugarWidgetSubPanelRiesgobtn extends SugarWidgetField
{
    function displayHeaderCell($layout_def){
      return '';
    }
    function displayList($layout_def)
    {
      $html = '';
      $html = '<style>
          #ries_a_prob{
          background-color: #eee !important;
          color:#333 !important;
          border: 1px solid #cccccc;
          padding: 0;
          margin: 0 !important;
          display: inline-block;
          font-size: 1.1em !important;
          padding: 5px 10px;
          float:center;
        }
        #ries_a_prob:hover{
          opacity: 0.8 !important;
        }
      </style>';
      $id = $layout_def['fields']['ID'];

      $beand = BeanFactory::getBean('SCO_Eventos', $id);

      $id_pr_co = $beand->prdes_idproductos_co;
      $html .= "<button class='btn btn-sm' id='ries_a_prob' style='' value='".$beand->prdes_unidad."'  onclick='com_problema(\"".$id."\")'>Convertir Problema <b style='color:red !important;'>✘</b></button>";
      $html .= "<script>
      function com_problema(id){
         if(confirm('Esta seguro de convertir a problema')){
            $.ajax({
            type: 'get',
            url: 'index.php?to_pdf=true&module=SCO_Riesgo&action=riegoproblema&id='+id,
            success: function(data) {
              var datos = $.parseJSON(data);
              if(datos != 'Error'){
                //alert('ok');
                location.reload(true);
              }else{
                alert('DIE');
              }
            }
          });
         }else{}
      }
      </script>";
      return $html;
    }
}
 ?>
