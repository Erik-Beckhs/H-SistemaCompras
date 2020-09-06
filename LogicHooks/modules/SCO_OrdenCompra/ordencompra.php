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
          #Guardamos los cambios de la orden de compra.
          $beanoc->save(); 
          break;
        case "2":
          $beanoc->orc_estado = 2;
          #Guardamos los cambios de la orden de compra.
          $beanoc->save(); 
          break;
        case "3":
          /*
          //Llamando a la clase Aprobadores y enverificando el envio de datos al serivicio
          include ('aprobacionpm.php');

          $aprobacionpm = new Aprobadores();
          $DatosItem = $aprobacionpm->getAprobador($id);
          $respuesta = $DatosItem;
          if($respuesta == '200'){
            $beanoc->orc_estado = 3;
            #Cambia el nombre de la orden de compra de acuerdo a los proyectos registrados en el modulo de Productos.
            $GLOBALS['db'];
            $db = DBManagerFactory::getInstance();
            $query = "
              SELECT 
                  DISTINCT(pro_nomproyco) as nombre, 
                  pro_idproy, 
                  pro_tipocotiza
              FROM sco_productos_co pco
              WHERE pro_idco = '$id' ";
            $result = $GLOBALS['db']->query($query, true);
            while($row = $GLOBALS['db']->fetchByAssoc($result))
              {
                #Actualizar los campos del modulo de Proyectos SCO_ProyectosCO sumando el correlativo.
                $idproy = $row['pro_idproy'];
                $beanproy = BeanFactory::getBean('SCO_ProyectosCO', $idproy);
                $correl = $beanproy->proyc_correlativo + 1;
                $beanproy->proyc_correlativo = $correl;
                $beanproy->save();

                $nombreoc .= $row['pro_tipocotiza'].$row['nombre'] . "_" . $correl . " - ";
              }

            $queryCnf = "SELECT 
                          name,
                          cnf_val_proyecto 
                        FROM suitecrm.sco_cnfvalproyectos 
                        where cnf_division = '".$iddv."' 
                        and deleted =0;";
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
            #Guardamos los cambios de la orden de compra.
            $beanoc->save();  
          }
          */
          ######################################################################
          ######################################################################
          #USAR SOLO EN PROCESO MANUAL DE DESARROLLO
        
          $beanoc->orc_estado = 3;
            #Cambia el nombre de la orden de compra de acuerdo a los proyectos registrados en el modulo de Productos.
            $GLOBALS['db'];
            $db = DBManagerFactory::getInstance();
            $query = "
              SELECT 
                  DISTINCT(pro_nomproyco) as nombre, 
                  pro_idproy, 
                  pro_tipocotiza
              FROM sco_productos_co pco
              WHERE pro_idco = '$id' ";
            $result = $GLOBALS['db']->query($query, true);
            while($row = $GLOBALS['db']->fetchByAssoc($result))
              {
                #Actualizar los campos del modulo de Proyectos SCO_ProyectosCO sumando el correlativo.
                $idproy = $row['pro_idproy'];
                $beanproy = BeanFactory::getBean('SCO_ProyectosCO', $idproy);
                $correl = $beanproy->proyc_correlativo + 1;
                $beanproy->proyc_correlativo = $correl;
                $beanproy->save();

                $nombreoc .= $row['pro_tipocotiza'].$row['nombre'] . "_" . $correl . " - ";
              }

            $queryCnf = "SELECT 
                          name,
                          cnf_val_proyecto 
                        FROM suitecrm.sco_cnfvalproyectos 
                        where cnf_division = '".$iddv."' 
                        and deleted =0;";
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
            #Guardamos los cambios de la orden de compra.
            $beanoc->save();  
            
            ######################################################################
            ######################################################################
          break;
        case "4":
          $beanoc->orc_estado = 4;
          #Guardamos los cambios de la orden de compra.
          $beanoc->save(); 
          break;
        case "5":
          $beanoc->orc_estado = 5;
          #Guardamos los cambios de la orden de compra.
          $beanoc->save(); 
          break;
        case "6":
          $beanoc->orc_estado = 1;
          #Guardamos los cambios de la orden de compra.
          $beanoc->save(); 
          break;
        default:
          break;
        }
        $desctotal =  trim($desctotal);
        $proyecto = trim($proyecto);
        $total_pp = trim($total_pp);
        $importe_total = trim($importe_total);
        echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~".$respuesta);
        #echo json_encode($DatosItem);
      }
      else{
        echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~");
      }
    }else{
      echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~");
    }
  }else{
    echo json_encode($desctotal."~".$proyecto."~".$total_pp."~".$importe_total."~");
  }

   
?>
