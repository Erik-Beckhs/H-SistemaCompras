<?php
/**
*Esta clase realiza realiza la modificacion del la vista del boton de "Formulraio completo"
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_documentos/views/
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
  die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_documentosViewEdit extends ViewEdit {

  function SCO_documentosViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display($bean){

    $idoc = $this->bean->id;

    $idoc = $_REQUEST['sco_ordencompra_id'];
    $beanOc = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
    $estadoOc = $beanOc->orc_estado;
  //CSS, oculta el boton de formulario completo
    //echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">';
    echo '<link href="/modules/SCO_documentos/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    echo '<link href="/modules/SCO_documentos/all.css" media="all" rel="stylesheet" type="text/css"/>';
    //echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">';
    echo '<link href="/modules/SCO_documentos/theme.css" media="all" rel="stylesheet" type="text/css"/>';
    //echo '<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>';
    //echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';
    echo '<script src="/modules/SCO_documentos/sortable.js" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/fileinput.js" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/es.js" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/theme.js" type="text/javascript"></script>';
    $html = '<button type="button" name="button" class="button" onclick="mostrarModal()">Carga masiva</button>';
    echo '<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id=""></h4>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data" id="formArchivos">
                <hr>
                <div class="form-group">
                <input id="file-5" class="file" name="filename_file" type="file" multiple data-preview-file-type="any" data-upload-url="index.php?to_pdf=true&module=SCO_documentos&action=cargaArchivos&idoc='.$idoc.'" data-theme="fas">
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" title="Guardar" class="button" onclick="location.reload();" value="Guardar">Aceptar</button>
          </div>
        </div>
      </div>
    </div>';
    echo "
    <style>
      // #SCO_documentos_subpanel_full_form_button{display:none;}
      // .kv-file-upload{display:none;}
    </style>
    <script>
    $(\"#SCO_documentos_subpanel_save_button\").after('$html');
    //$(\"#SCO_documentos_subpanel_save_button\").remove();
    // $(\"#doc_descipcion\").hide();
    // $(\"#doc_tipo\").hide();
    // $(\"#Default_SCO_documentos_Subpanel\").hide();
    // $(\"#filename_file\").remove();
    // $(\".kv-file-upload\").remove();
    //$(\".fileinput-upload-button\").remove();
    //mostrarModal();
    function mostrarModal()
    {
      $('#modalFile').modal('show');
      // if ($(\"#SCO_OrdenCompra_detailview_tabs\").length) {
      //
      // }
    }
    
    var estadoOc = '".$estadoOc."';
    $('#doc_tipo').on('change', function(){
      if($('#doc_tipo').val() == 2 && estadoOc == 2){
        alert('Debe \"Solicitar Aprobacion\" antes de subir una orden de compra firmanda');
        $('input[name*=\"filename_file\"]').css('display','none')
      }else{
        $('input[name*=\"filename_file\"]').css('display','block')
      }  
    });

    </script>";
    echo "<style>#SCO_documentos_subpanel_full_form_button{display:none;}</style>";
    parent::display();
  }
}
?>
