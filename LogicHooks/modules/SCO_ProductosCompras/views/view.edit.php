<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/view/SCO_ProductosCompras
*/

if (! defined ( 'sugarEntry' ) || ! sugarEntry)
  die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');

class SCO_ProductosComprasViewEdit extends ViewEdit {

  function SCO_ProductosComprasViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display(){ 
    global $current_user;    
    $user_div = $current_user->id;
    $div_u = "SELECT iddivision_c FROM users_cstm WHERE id_c ='".$user_div."'; ";
    $obj_div_u= $GLOBALS['db']->query($div_u, true);  
    $row_div_u= $GLOBALS['db']->fetchByAssoc($obj_div_u);
    echo "<script>$('#proge_division').val('".$row_div_u['iddivision_c']."'); </script>";   
    parent::display();
  }
}
?>
