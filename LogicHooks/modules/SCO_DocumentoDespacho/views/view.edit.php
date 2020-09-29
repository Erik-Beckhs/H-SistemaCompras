<?php
/**
*Esta clase realiza realiza la modificacion del la vista del boton de "Formulraio completo"
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_DocumentoDespacho/views/
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
  die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_DocumentoDespachoViewEdit extends ViewEdit {

  function SCO_DocumentoDespachoViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display($bean){

    $idoc = $this->bean->id;

    $idoc = $_REQUEST['sco_embarque_id'];
    echo "ID PADRE".$idoc;
    
    echo '<link href="/modules/SCO_documentos/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    echo '<link href="/modules/SCO_documentos/all.css" media="all" rel="stylesheet" type="text/css"/>';
    echo '<link href="/modules/SCO_documentos/theme.css" media="all" rel="stylesheet" type="text/css"/>';
    echo '<script src="/modules/SCO_documentos/sortable.js?'.time().'" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/fileinput.js?'.time().'" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/es.js?'.time().'" type="text/javascript"></script>';
    echo '<script src="/modules/SCO_documentos/theme.js?'.time().'" type="text/javascript"></script>';
    $html = '<button type="button" name="button" class="button" onclick="mostrarModal()">Carga masiva</button>';
    echo '<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="">Carga masiva de Archivos</h4>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data" id="formArchivos">
                <div class="form-group">
                <input id="file-5" class="file" name="filename_file" type="file" multiple data-preview-file-type="any" data-upload-url="index.php?to_pdf=true&module=SCO_DocumentoDespacho&action=cargaArchivos&idoc='.$idoc.'" data-theme="fas">
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-xs-6">
                <button type="button" style="width: 100%;background: #dc3545;color:#fff;border:solid 1px#dc3545;" class="btn btn-sm " data-dismiss="modal">Cancelar</button>
              </div>
              <div class="col-xs-6">
                <button type="button" title="Guardar" style="width: 100%; background:#0e2741;" class="btn btn-sm btn-info" onclick="location.reload();" value="Guardar">Aceptar</button>
              </div>
            </div>            
          </div>
        </div>
      </div>
    </div>';
    echo "
    <script>
    $(\"#SCO_DocumentoDespacho_subpanel_save_button\").after('$html');
    function mostrarModal()
    {
      $('#modalFile').modal('show');  
    }
    </script>";
    echo "<style>#SCO_DocumentoDespacho_subpanel_full_form_button{display:none;}</style>";
    parent::display();
  }
}
?>
