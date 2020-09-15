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
$idPcv = $_GET['idPcv'];
$idProDes = $_GET['idProDes'];

$beanoc = BeanFactory::getBean('SCO_ProductosCotizadosVenta', $idPcv);
$idcotizacion = $beanoc->pcv_idcotizacion;
if($idcotizacion != '' || $idcotizacion !=  null){
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
			AND TRIM(proge_codaio) = '$nomp'";
  	$results = $GLOBALS['db']->query($query, true);
  	//$row = $GLOBALS['db']->fetchByAssoc($results);
	$data = '';
	while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
		$data[] = $row;
	}
	$ap = array('codio' => $nomp );

		if($data == 0){
			$client = new nusoap_client("http://hannacwebp01.hansa.com.bo/QAsWebServicesIndustria/producto.asmx?WSDL", 'wsdl');
			$parametros = array('ITCODITEMS' => $nomp );
			$respuesta = $client->call('mostrar_Producto',$parametros);
			if ($respuesta["mostrar_ProductoResult"]["result"]) {
				$result = $respuesta["mostrar_ProductoResult"]["result"]["producto"];
				$qry = "SELECT id 
						FROM sco_productoscompras 
						WHERE TRIM(proge_codaio) = '$nomp'";
			  	$res = $GLOBALS['db']->query($qry, true);
				$row2 = $GLOBALS['db']->fetchByAssoc($res);
				if ($row2 == 0) {
					//Instanciamos la clase que realiza el envio de datos a CRM Ventas
					include ('enviodatoscrmventas.php');
					$envioDatosCrm= new EnviaDatosCRM();
					$respuesta = $envioDatosCrm->enviarInformacion($idcotizacion,$idPcv,$result["IDPRODUCTO"]);

					if($respuesta == '200'){
						#echo 'Item Nuevo';
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

		        		#Actualiza codigo SAP de Productos cotizados de venta "Consolidaciones"
						$bean_pcv = BeanFactory::getBean('SCO_ProductosCotizadosVenta', $idPcv);
						$bean_pcv->name = $result["IDPRODUCTO"];
						$bean_pcv->save();

						$bean_pcv = BeanFactory::getBean('SCO_ProductosDespachos', $idProDes);
						$bean_pcv->prdes_codaio = $result["IDPRODUCTO"];
						$bean_pcv->save();
						
						$producto = array("respuesra_servicio"=>$respuesta);
					}
				}
				else {
					//Instanciamos la clase que realiza el envio de datos a CRM Ventas
					include ('enviodatoscrmventas.php');
					$envioDatosCrm= new EnviaDatosCRM();
					$respuesta = $envioDatosCrm->enviarInformacion($idcotizacion,$idPcv,$result["IDPRODUCTO"]);
					
					if($respuesta == '200'){
		        		#Actualiza codigo SAP de Productos cotizados de venta "Consolidaciones"
						$bean_pcv = BeanFactory::getBean('SCO_ProductosCotizadosVenta', $idPcv);
						$bean_pcv->name = $result["IDPRODUCTO"];
						$bean_pcv->save();

						$bean_pcv = BeanFactory::getBean('SCO_ProductosDespachos', $idProDes);
						$bean_pcv->prdes_codaio = $result["IDPRODUCTO"];
						$bean_pcv->save();

						$producto = array("respuesra_servicio"=>$respuesta);
					}
				}
			}
			else{
				$producto = array("respuesra_servicio"=>$respuesta);
			}
		}
		else
		{
			include ('enviodatoscrmventas.php');
			$envioDatosCrm= new EnviaDatosCRM();
			$respuesta = $envioDatosCrm->enviarInformacion($idcotizacion,$idPcv,$data[0]['proge_codaio']);
			if($respuesta == '200'){
				#echo 'El item existe:';
				if($data[0]['name'] != ''){
					#Actualiza codigo SAP de Productos cotizados de venta "Consolidaciones"

					$bean_pcv = BeanFactory::getBean('SCO_ProductosCotizadosVenta', $idPcv);
					$bean_pcv->name = $data[0]['proge_codaio'];
					$bean_pcv->save();

					$bean_pcv = BeanFactory::getBean('SCO_ProductosDespachos', $idProDes);
					$bean_pcv->prdes_codaio = $data[0]['proge_codaio'];
					$bean_pcv->save();
				
				}
			}
			$producto = array("respuesra_servicio"=>$respuesta);
		}
	echo json_encode($producto);
}else{
	$producto = array("respuesra_servicio"=>"404");
	echo json_encode($producto);
}
	
 ?>
