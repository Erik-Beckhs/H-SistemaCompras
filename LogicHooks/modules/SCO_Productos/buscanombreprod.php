<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Productos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

  $descrp = $_GET['descrp'];
  $pronum = $_GET['pronum'];
  $descrp = trim($descrp);
  if($descrp != ''){
    $query = "SELECT id, name, proge_nompro, proge_unidad, proge_preciounid
          FROM sco_productoscompras 
          WHERE deleted = 0 
          AND proge_nompro like '%$descrp%'
          AND proge_division = '06'
          ORDER BY date_modified asc LIMIT 20";
    $results = $GLOBALS['db']->query($query, true);
    $count = 0;
    while($row = $GLOBALS['db']->fetchByAssoc($results)){
          $datos .= "<div class='row'><div class='col-xs-5'><a style='color:#000;cursor: pointer' onclick='productos(".$pronum.",\"".$row['id']."\",\"".$row['name']."\",\"".$row['proge_nompro']."\",\"".$row['proge_unidad']."\",\"".$row['proge_preciounid']."\")'>".$row['name']."</a></div><div class='col-xs-7'><a style='cursor: pointer'onclick='productos(".$pronum.",\"".$row['id']."\",\"".$row['name']."\",\"".$row['proge_nompro']."\",\"".$row['proge_unidad']."\",\"".$row['proge_preciounid']."\")'>".$row['proge_nompro']."</a></div></div>";
          $count++;                   
     }   
     if($count != 0){ 
       $html = "<div>".$datos."</div>";
     }else{
       $html = ''; 
     }
     echo $html; 
  }else{
    echo json_encode();
  }
  
 ?>