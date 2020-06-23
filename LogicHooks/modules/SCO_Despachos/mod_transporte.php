<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/SCO_Despachos
*/
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
	//ID, Origen, Modalidad de transporte, de DESPACHOS
	$id = $_GET['id'];
	$modtrans = '';
	if (isset($_GET['origen'])) {
		$origen = $_GET['origen'];
	}
	if (isset($_GET['modtrans'])) {
		$mt = $_GET['modtrans'];
	}

	switch ($id) {
	 	case '1':
	 		//Query, Obteniendo datos de origen
		 	$query = "SELECT DISTINCT(cnfev_modtrans),name
			FROM sco_cnf_eventos
			WHERE name = (SELECT DISTINCT(name)
			FROM sco_cnf_eventos
			WHERE name = '".$origen."') AND deleted = 0
			ORDER BY name asc;";
			$obj = $GLOBALS['db']->query($query, true);
			//todos los registros relacionados a el origen
			while($row = $GLOBALS['db']->fetchByAssoc($obj))
			{
				$modtrans .= $row['cnfev_modtrans']."|";
			}

			echo json_encode($modtrans);
	 		break;
	 	case '2':
	 	//Query, total de dias transito del modulo de CNF_EVENTOS
	 		// $query2 = "SELECT SUM(cnfev_diastrans) as diast
			// FROM sco_cnf_eventos
			// WHERE name = '".$origen."' AND cnfev_modtrans =  '".$mt."' AND deleted = 0 ORDER BY name asc;";
			// $results = $GLOBALS['db']->query($query2, true);
  		// 	while($row2 = $GLOBALS['db']->fetchByAssoc($results)){
  		// 		$diast = $row2['diast'];
  		// 	}
			//Query para obtener la fecha de la base de datos
			$fecha = "";
			$query2 = "SELECT now() as fecha;";
			$results = $GLOBALS['db']->query($query2, true);
  			while($row2 = $GLOBALS['db']->fetchByAssoc($results)){
  				$fecha = $row2['fecha'];
  			}
			//Query, obteniendo los prieros 2 numeros de los registros del modulo de CNFEV_EVENTOS, de acuerdo a los beans origen y modalidad de transporte
      $query = "SELECT SUBSTRING(cnfev_evento, 1,2) as num, name, cnfev_evento, cnfev_diastrans, cnfev_modtrans, deleted, notificar
      FROM sco_cnf_eventos
      WHERE name = '".$origen."' AND cnfev_modtrans = '".$mt."' AND deleted = 0 ORDER BY CAST(cnfev_evento AS UNSIGNED) asc;";
      $results = $GLOBALS['db']->query($query, true);

      $dias = 0;
			$data = array();
			$fin = "";
			$nuevoplan = 0;
      while($row = $GLOBALS['db']->fetchByAssoc($results)){
        //Query, Insertando datos a la relacion de EMBARQUES y EVENTOS
        //incrementamos la cantodad de días para asignar una nueva fecha plan
        $dias += $row['cnfev_diastrans'];
				$nuevoplan = $row['cnfev_diastrans'];
        #$fecha_plan = date('Y-m-d', strtotime('+'.$dias.' day'));
        //Operacion, las fechas del modulo de EVENTOS## Incrementamos la cantidad de dias a la fecha de creación para tener la fecha plan del evento
        $fecha_plan = date('Y-m-d', strtotime($fecha."+ ".$dias." day"));
        //Verificamos si la fecha plan del evento es dias Sabado o Domingo
        $fplan=date("w", strtotime($fecha_plan));
        // if($fplan=="0" or $fplan == "6")
        // {
        //   // Si es 0 o 6 el evento cae en fin de semana
        //   if($fplan=="6"){
        //     // es Sabado e incrementamos 2 días a la fecha plan
        //     $dias += 2;
        //   }
        //   if($fplan=="0"){
        //     // es Domingo e incrementamos 1 día a la fecha plan
        //     $dias += 1;
        //   }
        // }

			}
			echo json_encode($dias);
	 		break;
	 	case '3':
	 	//Query, obtiene la lista de los origenes y devuelve un resultado JSON
	 		$query = "SELECT DISTINCT(name) as origen
			FROM sco_cnf_eventos
            WHERE name != '".$origen."' and deleted = 0
            ORDER BY origen asc";
            $origen = array();
  			$obj = $GLOBALS['db']->query($query, true);
  			while($row2 = $GLOBALS['db']->fetchByAssoc($obj)){
  				$origen[] = $row2['origen'];
  			}
			echo json_encode($origen);
	 		break;
			case '4':
		 	//Query, obtiene la lista de productos del despacho
				$idDespacho = $_POST["despacho"];

		 		$query = "SELECT prds.name,prds.prdes_descripcion,prds.prdes_cantidad,prds.id as idPro, oc.id as idOc,prds.prdes_idproductos_co,ds.id as idDespacho,pro.pro_nomproyco as proyecto,prds.prdes_unidad as punitario
									from sco_productos_co pro, sco_productosdespachos prds
									inner join sco_despachos_sco_productosdespachos_c dsprds on prds.id = dsprds.sco_despachos_sco_productosdespachossco_productosdespachos_idb
									inner join sco_despachos ds on ds.id = dsprds.sco_despachos_sco_productosdespachossco_despachos_ida
									inner join sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_despachos_idb = ds.id
									inner join sco_ordencompra oc on oc.id = deor.sco_despachos_sco_ordencomprasco_ordencompra_ida
									where pro.pro_nombre = prds.name and
												ds.id = '$idDespacho' and
												oc.id = pro.pro_idco and
												pro.pro_descripcion = prds.prdes_descripcion";
	      $productos = array();
	  		$obj = $GLOBALS['db']->query($query, true);
  			while($row2 = $GLOBALS['db']->fetchByAssoc($obj)){
  				$productos[] = $row2;
  			}
				echo json_encode($productos);
		 	break;
	 	default:
	 		echo json_encode();
	 		break;
	 }

?>
