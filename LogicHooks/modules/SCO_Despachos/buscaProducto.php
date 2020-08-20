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
require_once('include/nusoap/nusoap.php');

	$nomp = $_GET['nomp'];
	$nomp = trim($nomp);
	$query = "SELECT id, 
					name, 
					proge_nompro, 
					proge_descripcion, 
					proge_unidad, 
					proge_preciounid,
					proge_codaio,
					proge_subgrupo
			FROM sco_productoscompras
			WHERE deleted = 0 
			AND TRIM(name) = '$nomp'";
  	$results = $GLOBALS['db']->query($query, true);
  	//$row = $GLOBALS['db']->fetchByAssoc($results);
		$data = '';
		while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
			$data[] = $row;
		}
		echo $data;
		$ap = array('codio' => $nomp );
		if($data == 0){
			$client = new nusoap_client("http://hannacwebp01.hansa.com.bo/QAsWebServicesIndustria/producto.asmx?WSDL", 'wsdl');
			$parametros = array('ITCODITEMS' => $nomp );
			$respuesta = $client->call('mostrar_Producto',$parametros);
			if ($respuesta["mostrar_ProductoResult"]["result"]) {
				$result = $respuesta["mostrar_ProductoResult"]["result"]["producto"];
				$qry = "SELECT id FROM sco_productoscompras WHERE TRIM(proge_codaio) = '$nomp'";
			  	$res = $GLOBALS['db']->query($qry, true);
				$row2 = $GLOBALS['db']->fetchByAssoc($res);
				if ($row2 == 0) {
					$new_idprod = create_guid();
					$query3 = "INSERT INTO sco_productoscompras
					(
						id,
						deleted,
						name,
						proge_unidad,
						proge_codaio,
						proge_nompro,
						proge_division,
						proge_familia,
						proge_grupo,
						proge_subgrupo
					)
				VALUES
					(
						'".$new_idprod."',
						0,
						'".$result["IDPRODFABRICA"]."',
						'".$result["UMBASE"]."',
						'".$result["IDPRODUCTO"]."',
						'".$result["PRODUCTO"]."',
						'".$result["IDDIVISION"]."',
						'".$result["IDFAMILIA"]."',
						'".$result["IDGRUPO"]."',
						'".$result["IDSUBGRUPO"]."'
					);";
	        		$GLOBALS['db']->query($query3, true);
					$producto = array(	"id" => $new_idprod,
										"name"=>$result["IDPRODFABRICA"],
										"proge_nompro"=>$result["PRODUCTO"],
										"proge_descripcion"=>"",
										"proge_unidad"=>$result["UMBASE"],
										"proge_preciounid"=>"",
										"proge_codaio" => $result["IDPRODUCTO"],
										"proge_subgrupo"=>$result["IDSUBGRUPO"]);
				}
				else {
					//echo "actualizar";
					$query_Ap = "UPDATE sco_productoscompras
			                     SET	name = '".$result["IDPRODFABRICA"]."',
									proge_unidad = '".$result["UMBASE"]."',
									proge_codaio = '".$result["IDPRODUCTO"]."',
									proge_nompro = '".$result["PRODUCTO"]."',
									proge_division = '".$result["IDDIVISION"]."',
									proge_familia = '".$result["IDFAMILIA"]."',
									proge_grupo = '".$result["IDGRUPO"]."',
									proge_subgrupo = '".$result["IDSUBGRUPO"]."'
			                     WHERE id = '".$row2["id"]."' " ;
	        $GLOBALS['db']->query($query_Ap, true);
					$producto = array(	"id" => $row2,
										"name"=>$result["IDPRODFABRICA"],
										"proge_nompro"=>$result["PRODUCTO"],
										"proge_descripcion"=>"",
										"proge_unidad"=>$result["UMBASE"],
										"proge_preciounid"=>"",
										"proge_codaio" => $result["IDPRODUCTO"],
										"proge_subgrupo"=>$result["IDSUBGRUPO"]);
				}
			}
			$data[] = $producto;
		}
	echo json_encode($data);
 ?>
