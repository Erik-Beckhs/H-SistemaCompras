<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Reynaldo Kantuta <rkatuta@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_OrdenCompra
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

  $id = $_GET['id'];
  $estado = $_GET['estado'];
  $respuesta = '';
  $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $id);
  $observacion = $beanoc->orc_obs;
  if ($estado == 5) {
    if ($observacion == '' || $observacion == null ) {
      $respuesta = 'Es necesario que coloque una observaciÃ³n';
    }
    else {
      $respuesta = true;
    }
  }
  else {
    $respuesta = true;
  }
  $data = array('r' => $respuesta );
  echo json_encode($data);
?>
