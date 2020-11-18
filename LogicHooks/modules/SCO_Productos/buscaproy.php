<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Productos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
//pobla usuario actual logeado
global $current_user;
	$nomproy = $_GET['nomproy'];
	$idDiv = $current_user->iddivision_c;
	//FunciÃ³n que verifia si el proyecto existe
	$query = "SELECT id, name, proyc_tipo
						FROM sco_proyectosco
						WHERE deleted = 0
						AND name = '$nomproy'
						AND proyc_division = '$current_user->iddivision_c'";
	$results = $GLOBALS['db']->query($query, true);
	$row = $GLOBALS['db']->fetchByAssoc($results);
	//Adicional a ver si el proyecto existe tambien verificamos si existe la excepcion para no agregar un proyecto
	//Verificamos la configuraciÃ³n de los Cnf_Valida_proyecto para la orden de compra
  $queryCnf = "SELECT name,cnf_val_proyecto FROM suitecrm.sco_cnfvalproyectos where cnf_division = '$idDiv' and deleted =0;";
  $cnf_valProy = $GLOBALS['db']->query($queryCnf, true);
  $row_cnfVP = $GLOBALS['db']->fetchByAssoc($cnf_valProy);
  if ($row_cnfVP != false) {
    //En caso de existir una configuracion de no validar proyectos ponemos la cantidad de PY en 0
    if ($row_cnfVP["cnf_val_proyecto"] == 0 && $row == false) {
			//Generamos un array por defecto con el nombre del proyecto que no esta en la base de datos
      $row = array("id"=>"","name"=>$nomproy,"proyc_tipo"=>"");
    }
  }
	echo json_encode($row);
 ?>
