<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_OrdenCompra
*/

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

  //$datosAprobador = '';
  $DatosItem ='';
  $id = $_GET['id'];
  $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $id);
  $desctotal = $beanoc->orc_aux1;
  $importe_total = $beanoc->orc_importet;
  $idDiv = $beanoc->orc_division;
  $iddv = $beanoc->iddivision_c;
  $num = $_GET['num'];
  $nombreoc = '';
  $proyecto = " SELECT count(pro_idproy) as proyecto
        FROM sco_productos_co
        WHERE pro_idco = '$id'
        AND pro_idproy = '' ";
  $res_proyecto = $GLOBALS['db']->query($proyecto, true);
  $row_proyecto = $GLOBALS['db']->fetchByAssoc($res_proyecto);
  //Cantidad de productos qe no tienen asignados proyectos
  $proyecto = $row_proyecto['proyecto'];
  //Consulta que extrae el plan de pagos total de la orden de copra
  $ppagos = "SELECT sum(ppg_monto) as ppg_monto
    FROM sco_plandepagos as pp
    INNER JOIN sco_ordencompra_sco_plandepagos_c as ocpp
    on pp.id = ocpp.sco_ordencompra_sco_plandepagossco_plandepagos_idb
    WHERE ocpp.sco_ordencompra_sco_plandepagossco_ordencompra_ida = '$id'
    AND ocpp.deleted = 0
    AND pp.deleted = 0 ;";
  $obj_ppagos = $GLOBALS['db']->query($ppagos, true);
  $row_ppagos = $GLOBALS['db']->fetchByAssoc($obj_ppagos);
  //Importe de total de la orden de compra
  $total_pp = $row_ppagos['ppg_monto'];
  //Verificamos la configuración de los Cnf_Valida_proyecto para la orden de compra
  $queryCnf = "SELECT name,cnf_val_proyecto FROM suitecrm.sco_cnfvalproyectos where cnf_division = '$idDiv' and deleted =0;";
  $cnf_valProy = $GLOBALS['db']->query($queryCnf, true);
  $row_cnfVP = $GLOBALS['db']->fetchByAssoc($cnf_valProy);
  if ($row_cnfVP != false) {
    //En caso de existir una configuracion de no validar proyectos ponemos la cantidad de PY en 0
    if ($row_cnfVP["cnf_val_proyecto"] == 0) {
      $proyecto = 0;
    }
  }
  //$datosAprobador = '';
  if($desctotal == 100){
    if($proyecto == 0){
     if($importe_total == $total_pp){
        switch ($num) {
        case "1":
          $beanoc->orc_estado = 2;
          break;
        case "2":
          $beanoc->orc_estado = 2;
          break;
        case "3":
        //inicio de codigo ychm

        include ('aprobacionpm.php');

        $aprobacionpm = new Aprobadores();
        $DatosItem = $aprobacionpm->getAprobador($id);
        $beanoc->orc_obs =$DatosItem;
        /*$datosAprobador = $aprobacionpm->getAprobador($id);
        $beanoc->orc_obs = $datosAprobador;
        $DatosPlanPagos = $aprobacionpm->getPlanPagos($id);
        $beanoc->orc_obs =$DatosPlanPagos;
        $DatosOrdenCompra =$aprobacionpm->getOrdenCompra($id);
        $beanoc->orc_obs =$DatosOrdenCompra;*/
        //$resultado_final = [];
        /*$resultado_final['Aprobador'] = $results;
        $resultado2 = $aprobacionpm->getPlanPagos();
        $resultado_final['Plan_pagos'] = $results2;
        $resultado3 = $aprobacionpm->getOrdenCompra();
        $resultado_final['Ordencompra'] = $results3;
        print_r($resultado_final)
        $curl = curl_init('http://localhost:8000');
                $data =  json_encode($resultado_final);
                try
                {
                curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-type:application/json"));
                curl_setopt($curl,CURLOPT_POST,true);
                curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
                curl_exec($curl);
                curl_close($curl);
                }
                catch(exception $e)
                {
                echo "Error";
                }*/
        //$beanoc->orc_obs=$resultado; 
        //fin del codigo ychm
          //$beanoc->orc_estado = 3;
          $GLOBALS['db'];
            $db = DBManagerFactory::getInstance();
          $query = "
            SELECT DISTINCT(pro_nomproyco) as nombre, pro_idproy, pro_tipocotiza
            FROM sco_productos_co pco
            WHERE pro_idco = '$id' ";
          $result = $GLOBALS['db']->query($query, true);
            while($row = $GLOBALS['db']->fetchByAssoc($result))
            {
                $idproy = $row['pro_idproy'];
                $beanproy = BeanFactory::getBean('SCO_ProyectosCO', $idproy);
                $correl = $beanproy->proyc_correlativo + 1;
                $beanproy->proyc_correlativo = $correl;
                $beanproy->save();
                $nombreoc .= $row['pro_tipocotiza'].$row['nombre'] . "_" . $correl . " - ";
            }
            $queryCnf = "SELECT name,cnf_val_proyecto FROM suitecrm.sco_cnfvalproyectos where cnf_division = '".$iddv."' and deleted =0;";
      			$cnf_valProy = $GLOBALS['db']->query($queryCnf, true);
      			$row_cnfVP = $GLOBALS['db']->fetchByAssoc($cnf_valProy);
      			$valProyecto = true;
      			if ($row_cnfVP != false) {
      				//En caso de existir una configuracion de no validar proyectos ponemos la cantidad de PY en 0
      				if ($row_cnfVP["cnf_val_proyecto"] == 0) {
      					//Dejamos el nombre de la orden de compra intacto
      					$valProyecto = false;
      				}
      			}
            if ($valProyecto == true) {
              $beanoc->name = $nombreoc;
              $beanoc->name = trim($beanoc->name, ' - ');
  					}
                //---------------------------
              //------------------------------
          break;
        case "4":
          $beanoc->orc_estado = 4;
          break;
        case "5":
          $beanoc->orc_estado = 5;
          break;
        case "6":
          $beanoc->orc_estado = 1;
          break;
        default:
          break;
        }
        $beanoc->save();
        $desctotal =  trim($desctotal);
        $proyecto = trim($proyecto);
        $total_pp = trim($total_pp);
        $importe_total = trim($importe_total);
         //echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~".$datosAprobador);
        echo json_encode($DatosItem);
      }
      else{
        echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~".$DatosItem."exitoso");
      }
    }else{
      echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~".$DatosItem."exitoso");
    }
  }else{
    echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~".$DatosItem."exitoso");
  }

   
?>
