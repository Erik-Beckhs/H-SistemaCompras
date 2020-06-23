<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Riesgo
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

$id = $_GET['id'];
$bean_riesgo = BeanFactory::getBean('SCO_Riesgo', $id);
$bean_riesgo->rie_estado = 2;

$bean_problema = BeanFactory::newBean('SCO_Problema');
$bean_problema->name = $bean_riesgo->name;
$bean_problema->prl_estado = $bean_riesgo->rie_estado;
$bean_problema->prl_pri = $bean_riesgo->rie_prioridad;
$bean_problema->prl_fechav = $bean_riesgo->rie_fechaven;
$bean_problema->sco_eventos_sco_problemasco_eventos_ida = $bean_riesgo->sco_eventos_sco_riesgosco_eventos_ida;
require_once('modules/SCO_Riesgo/NotificaProblema.php');
echo json_encode($id);
$bean_problema->save();
$bean_riesgo->save();

?>
