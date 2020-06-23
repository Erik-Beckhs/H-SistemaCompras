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

class SCO_AprobadoresViewEdit extends ViewEdit {

  function SCO_AprobadoresViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display($bean){
  //CSS, oculta el boton de formulario completo
    $html = '<input type=\"radio\" name=\"apr_tipo\" id=\"apr_tipo\" size=\"30\" maxlength=\"255\" value=\"Aprobado por:\" title=\"\"> Aprobado por <br><input type=\"radio\" name=\"apr_tipo\" id=\"apr_tipo\" size=\"30\" maxlength=\"255\" value=\"Solicitado por:\" title=\"\"> Solicitado por <br><input type=\"radio\" name=\"apr_tipo\" id=\"apr_tipo\" size=\"30\" maxlength=\"255\" value=\"Autorizado por:\" title=\"\"> Autorizado por';
    echo "<script>
    $('#apr_tipo').val('Elaborado por:');
    $('#apr_tipo').attr('type','radio');
    $('#apr_tipo').is(':checked');
    $('#apr_tipo').after(' Elaborado por <br>".$html."');
    </script>";
    parent::display();
  }
}
?>
