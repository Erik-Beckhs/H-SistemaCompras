<?php
/**
 *Esta clase, !!!!!!!!!IMPORTANTE
 *realiza la descomposicion del array [] que se guarda en el modulo de SCO_PRODUCTOS,
 *en el array se encuentran todos los datos de los productos relacionados con SCO_ORDEN DE COMPRA
 *y tambien con SCO_PROYECTOS y tambien con SCO_PRODUCTOS COMPRAS.
 *
 *IMPORTANTE, en esta clase se realiza las inserciones de datos en las tablas relacionales de
 *SCO_ORDENCOMPRA con SCO_PRODUCTOSCOMPRAS, SCO_PRODUCTOSCOMPRAS con SCO_PRODUCTOSCOTIZADOS, y
 *SCO_PRODUCTOSCOMPRAS con SCO_PROYECTOS
 *
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@copyright 2018
 *@license /var/www/html/custom/modules/SCO_Productos
 */
class ClDeproductos {
	static $already_ran = false;
	function FnDeproductos($bean, $event, $arguments) {
		if (self::$already_ran == true) {return;
		}

		self::$already_ran = true;

		global $current_user;
		$id_usuario             = $current_user->id;
		$bean->assigned_user_id = $current_user->id;
		$bean->iddivision_c     = $current_user->iddivision_c;
		$bean->idregional_c     = $current_user->idregional_c;
		$bean->idamercado_c     = $current_user->idamercado_c;

		//Obteniendo el ID de la ORDEN DE COMPRA
		$bean->load_relationship('sco_ordencompra_sco_productos');
		$relatedBeans = $bean->sco_ordencompra_sco_productos->getBeans();
		reset($relatedBeans);
		$parentBean = current($relatedBeans);
		$idoc       = $parentBean->id;
		//Eliminando la realcion de PRODUCTOS COTIZADOS con PROYECTOS C/O de acuerdo al id de la ORDEN DE COMPRA
		$productos_co = "SELECT * FROM sco_productos_co WHERE pro_idco = '$idoc';";

		$objo_productos_co = $bean->db->query($productos_co, true);
		while ($row = $GLOBALS['db']->fetchByAssoc($objo_productos_co)) {
			$relacion = "DELETE FROM sco_productoscotizados_sco_proyectosco_c 
			WHERE sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida ='".$row['id']."';";

			#"UPDATE sco_productoscotizados_sco_proyectosco_c SET deleted = 1 WHERE sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida = '".$row['id']."';";

			$obj_relacion = $bean->db->query($relacion, true);

			//Eliminando la relacion de PRODUCTOS COMPRAS con PRODUCTOS COTIZADOS
			$pc_pcot = "DELETE FROM sco_productoscompras_sco_productoscotizados_c WHERE sco_produc4599tizados_idb = '".$row['id']."';";

			#"UPDATE sco_productoscompras_sco_productoscotizados_c SET deleted = 1 WHERE sco_produc4599tizados_idb = '".$row['id']."';";

			$obj_pc_pcot = $bean->db->query($pc_pcot, true);
		}

		//Eliminando la relacion de PRODUCTOS COMPRAS con PROYECTOS
		$pcom_py = "DELETE FROM sco_productoscompras_sco_proyectosco_c WHERE id_ordencompra ='$idoc';";

		#"UPDATE sco_productoscompras_sco_proyectosco_c SET deleted = 1 WHERE id_ordencompra = '".$idoc."';";

		$obj_pcom_py = $bean->db->query($pcom_py, true);

		//Eliminando la relacion de  ORDEN DE COMPRA con PRODUCTOS COMPRAS
		$pcom_oc = "DELETE FROM sco_ordencompra_sco_productoscompras_c 
		WHERE sco_ordencompra_sco_productoscomprassco_ordencompra_ida = '$idoc';";

		#"UPDATE sco_ordencompra_sco_productoscompras_c SET deleted = 1 WHERE sco_ordencompra_sco_productoscomprassco_ordencompra_ida = '".$idoc."' ;";

		$obj_pcom_oc = $bean->db->query($pcom_oc, true);

		//Eliminando la realcion de PRODUCTOS COTIZADOS con ORDEN DE COMPRA de acuerdo al id de la ORDEN DE COMPRA
		$pcot_oc = "
		DELETE FROM sco_productoscotizados_sco_ordencompra_c WHERE sco_productoscotizados_sco_ordencomprasco_ordencompra_idb='$idoc';";

		#UPDATE sco_productoscotizados_sco_ordencompra_c SET deleted = 1 WHERE sco_productoscotizados_sco_ordencomprasco_ordencompra_idb = '$idoc';";

		$obj_relacion2 = $bean->db->query($pcot_oc, true);

		//Eliminando los datos de PRODUCTOS COTIZADOS de acuerdo al id de la ORDEN DE COMPRA
		$productoscotizados = "DELETE FROM sco_productoscotizados WHERE pro_idco='$idoc';";

		$objo_productoscotizados = $bean->db->query($productoscotizados, true);

		//Eliminando datos de la TABLA productos_co de acuerdo al id de la ORDEN DE COMPRA
		$queryoc = "DELETE FROM sco_productos_co WHERE pro_idco='$idoc';";

		$objoc = $bean->db->query($queryoc, true);

		$newDate = date("d-m-y H:m:s");
		//Para ordenar la lista de productos inicializamos unsvalor minutos = 0
		$minutos = 0;
		$arr1    = $bean->description;
		//DESCOMPONIENDO EL ARRAY QUE SE ENCUENTRA EN EL CAMPO DE description del modulo SCO_PRODUCTOS
		$arr1 = str_replace("[[", "", $arr1);
		$arr1 = str_replace("]]", "", $arr1);

		$arr1    = str_replace("[", "", $arr1);
		$arr1    = str_replace("],", "|", $arr1);
		$arr1    = str_replace("&quot;", "'", $arr1);
		$arr1    = str_replace("','", "~", $arr1);
		$arr1    = str_replace("'", "", $arr1);
		$arr2    = explode("|", $arr1);
		#id de la orden de compra del array de productos
		$arridoc = array_pop($arr2);
		$arrprec = array_pop($arr2);
		$arrprec = explode(",", $arrprec);
		//almacenando en variables los campos del array
		for ($i = 0; $i < count($arr2); $i++) {

			$newDate = date("Y-m-d H:m:s");
			$newDate = strtotime('+'.$minutos.' minute', strtotime($newDate));
			$newDate = strtotime('+'.$i.' second', $newDate);
			$date    = date('Y-m-j H:i:s', $newDate);
			if ($date == date("Y-m-d H:m")."59") {
				$minutos = $minutos+1;
			}
			$textfila = $arr2[$i];
			$fila     = explode("~", $textfila);
			$idpc     = str_replace("&gt;", ">", str_replace("&lt;", "<", str_replace("**", "\"", $fila[0])));
			$descr    = str_replace("&aacute;", "á",
				str_replace("&eacute;", "é",
					str_replace("&iacute;", "í",
						str_replace("&oacute;", "ó",
							str_replace("&uacute;", "ú",
								str_replace("&Aacute;", "Á",
									str_replace("&Eacute;", "É",
										str_replace("&Oacute;", "Ó",
											str_replace("&Iacute;", "Í",
												str_replace("&Uacute;", "Ú",
													str_replace("&ntilde;", "ñ",
														str_replace("&Ntilde;", "Ñ",
															str_replace("&acute;", "´",
																str_replace("&gt;", ">",
																	str_replace("&lt;", "<",
																		str_replace("**", "\"", $fila[1])))))))))))))
					)
				)
			);
			$unid     = $fila[2];
			$cant     = $fila[3];
			$prec     = $fila[4];
			$dscp     = $fila[5];
			$dscv     = $fila[6];
			$stot     = $fila[7];
			$idpo     = $fila[8];
			$idpro    = $fila[9];
			$idproy   = $fila[10];
			$tipoProy = $fila[11];
			$aio      = $fila[12];
			$idProdCot= $fila[13];

			//creando id para productos_co
			$id_sco_productos_co       = create_guid();
			$id_sco_productoscotizados = create_guid();

			//Insertando a la TABLA sco_productos_co los datos del array
			$query = "INSERT INTO sco_productos_co (id, deleted, pro_nombre, pro_descripcion, pro_unidad, pro_cantidad, pro_preciounid, pro_descval, pro_descpor, pro_fecha, pro_nomproyco, pro_idco, pro_idproy, pro_idpro, pro_tipocotiza, pro_subtotal, pro_canttrans, pro_cantresivida, pro_saldos,pro_codaio, iddivision_c, idregional_c, pro_idproductocotizado) VALUES ('$id_sco_productos_co','0','$idpc','$descr','$unid','$cant','$prec','$dscv','$dscp','$date','$idpo','$arridoc','$idproy','$idpro','$tipoProy','$stot','0','0','$cant','$aio','$bean->iddivision_c','$bean->idregional_c','$idProdCot');";

			$obj = $bean->db->query($query, true);

			//Insertado al modulo PRODUCTOS COTIZADOS los datos del array del modulo
			$productoscotizados = "INSERT INTO sco_productoscotizados (id, name, deleted, pro_descripcion, pro_unidad, pro_cantidad, pro_preciounid, pro_descval, pro_descpor, pro_fecha, pro_nomproyco, pro_idco, pro_idproy, pro_idpro, pro_tipocotiza, pro_subtotal, pro_canttrans, pro_cantresivida, pro_saldos, iddivision_c, idregional_c, pro_codaio, pro_idproductocotizado) VALUES ('$id_sco_productos_co','$idpc','0','$descr','$unid','$cant','$prec','$dscv','$dscp','$date','$idpo','$arridoc','$idproy','$idpro','$tipoProy','$stot','0','0','$cant','$bean->iddivision_c','$bean->idregional_c','$aio','$idProdCot');";

			$obj_productoscotizados = $bean->db->query($productoscotizados, true);

			//Agregando la relacion con PRODUCTOS COTIZADOS Y PROYECTOS
			if ($idpro != '') {
				$proyectos     = "INSERT INTO sco_productoscotizados_sco_proyectosco_c (id,date_modified,deleted,sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida,sco_productoscotizados_sco_proyectoscosco_proyectosco_idb) VALUES (UUid(),'$date','0','$id_sco_productos_co','$idproy')";
				$obj_proyectos = $bean->db->query($proyectos, true);
			}

			//Agregando la relacion de PRODUCTOS COTIZADOS con ORDEN DE COMPRA
			if ($idpro != '') {
				$ordencompra     = "INSERT INTO sco_productoscotizados_sco_ordencompra_c (id,date_modified,deleted,sco_productoscotizados_sco_ordencomprasco_productoscotizados_ida,sco_productoscotizados_sco_ordencomprasco_ordencompra_idb) VALUES (UUid(),'$date','0','$id_sco_productos_co','$arridoc')";
				$obj_ordencompra = $bean->db->query($ordencompra, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			if ($idpro != '') {
				$pd   = "INSERT INTO sco_productoscompras_sco_proyectosco_c (id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra) VALUES (UUid(),'$date','0','$idpro','$idproy','$arridoc')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de ORDEN DE COMPRA con PRODUCTOS COMPRAS
			if ($idpro != '') {
				$pd   = "INSERT INTO sco_ordencompra_sco_productoscompras_c (id,date_modified,deleted,sco_ordencompra_sco_productoscomprassco_ordencompra_ida,sco_ordencompra_sco_productoscomprassco_productoscompras_idb) VALUES (UUid(),'$date','0','$arridoc','$idpro')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			if ($idpro != '') {
				$pd   = "INSERT INTO sco_productoscompras_sco_proyectosco_c (id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra) VALUES (UUid(),'$date','0','$idpro','$idproy','$arridoc')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PRODUCTOS COTIZADOS
			if ($idpro != '') {
				$pd   = "INSERT INTO sco_productoscompras_sco_productoscotizados_c (id,date_modified,deleted,sco_producb25ccompras_ida,sco_produc4599tizados_idb) VALUES (UUid(),'$date','0','$idpro','$id_sco_productos_co')";
				$dsad = $bean->db->query($pd, true);
			}
		}
		//Query, obteniendo el nombre del proyecto de la tabla SCO_PRODUCTOS_CO
		$queryproy = "SELECT DISTINCT(pro_nomproyco) as name, pro_tipocotiza FROM sco_productos_co WHERE pro_idco ='$idoc';";

		$objproy = $bean->db->query($queryproy, true);
		//Concatenando el correlativo para el nombre de la ORDEN DE COMPRA

		while ($row = $bean->db->fetchByAssoc($objproy)) {
			if ($nom == $nom) {
				$nom .= $row["pro_tipocotiza"].$row["name"]."_";
			} else {
				$correlativo++;
			}
		}
		//Bean ORDENCOMPRA, guardando la ronde de compra desde el modulo SCO_PRODUCTOS
		//Adicional a ver si el proyecto existe tambien verificamos si existe la excepcion para no agregar un proyecto
		//Verificamos la configuración de los Cnf_Valida_proyecto para la orden de compra
		$beanoc   = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
		$queryCnf = "SELECT name,cnf_val_proyecto FROM suitecrm.sco_cnfvalproyectos where cnf_division = '".$beanoc->iddivision_c."' and deleted =0;";

		$cnf_valProy = $GLOBALS['db']->query($queryCnf, true);
		$row_cnfVP   = $GLOBALS['db']->fetchByAssoc($cnf_valProy);
		$valProyecto = true;
		if ($row_cnfVP != false) {
			//En caso de existir una configuracion de no validar proyectos ponemos la cantidad de PY en 0
			if ($row_cnfVP["cnf_val_proyecto"] == 0) {
				//Dejamos el nombre de la orden de compra intacto
				$valProyecto = false;
			}
		}

		if ($nom != "_") {
			if ($valProyecto == true) {
				$beanoc->name = $nom;
			}
		} else {}
		$beanoc->orc_tototal   = $arrprec[0];
		$beanoc->orc_descvalor = $arrprec[1];
		$beanoc->orc_descpor   = $arrprec[2];
		$beanoc->orc_importet  = $arrprec[3];
		$beanoc->save();
	}
}
?>
