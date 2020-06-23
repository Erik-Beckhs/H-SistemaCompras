<?php
/**
*Esta clase realiza el clonado de una ORDEN DE COMPRA con relacion a su Cabecera y sus
*subpaneles de PRODUCTOS, CONTACTOS Y APROBADORES
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Clclonar
{
	static $ult_rid = "";
	static $already_ran = false;

  function Fnclonar($bean, $event, $arguments)
  {
    if(self::$already_ran == true) return;
    self::$already_ran = true;
    //Campos habilitados para la clonacion de una orden de compra
    $idoc_cl = $bean->sco_ordencompra_id_c;
    $nomoc_cl= $bean->orc_occ;
    //pobla usuario actual logeado
    global $current_user;
    $bean->assigned_user_id = $current_user->id;
    $bean->iddivision_c = $current_user->iddivision_c;
    $bean->idregional_c = $current_user->idregional_c;
    $bean->idamercado_c = $current_user->idamercado_c;

    if(!empty($idoc_cl)){
    	//Consulta para obtener los campos de la OC
	    $query = "SELECT *
			FROM sco_ordencompra as oc
			INNER JOIN sco_proveedor_sco_ordencompra_c as ocpv
			ON oc.id = ocpv.sco_proveedor_sco_ordencomprasco_ordencompra_idb
			INNER JOIN sco_ordencompra_contacts_c as occo
			ON occo.sco_ordencompra_contactssco_ordencompra_idb = oc.id
			WHERE oc.deleted = 0 AND ocpv.deleted = 0 AND occo.deleted = 0
			AND oc.id = '$idoc_cl'";
		    $obj = $bean->db->query($query, true);
		    $row = $bean->db->fetchByAssoc($obj);
		    //Poblando los campos del Modulo SCO_ORDENCOMPRA
		    $bean->name = "COPIA_".$row["name"];
		    $bean->orc_tipo = $row["orc_tipo"];
		    $bean->orc_tipoo = $row["orc_tipoo"];
		    #$bean->orc_fechaord = $row["orc_fechaord"];
		    $bean->orc_solicitado = $row["orc_solicitado"];
		    $bean->user_id1_c = $row["user_id1_c"];
		    $bean->sco_proveedor_sco_ordencompra_name = $row["sco_proveedor_sco_ordencompra_name"];
		    $bean->sco_proveedor_sco_ordencomprasco_proveedor_ida = $row["sco_proveedor_sco_ordencomprasco_proveedor_ida"];
		    $bean->sco_ordencompra_contacts_name = $row["sco_ordencompra_contacts_name"];
		    $bean->sco_ordencompra_contactscontacts_ida = $row["sco_ordencompra_contactscontacts_ida"];
	        $bean->orc_decop = $row["orc_decop"];
		    $bean->orc_tcinco = $row["orc_tcinco"];
		    $bean->orc_tclugent = $row["orc_tclugent"];
		    $bean->orc_tcforpag = $row["orc_tcforpag"];
		    $bean->orc_tcgarantia = $row["orc_tcgarantia"];
		    $bean->orc_tcmoneda = $row["orc_tcmoneda"];
		    $bean->orc_tcmulta = $row["orc_tcmulta"];
		    $bean->orc_tccertor = $row["orc_tccertor"];
		    $bean->orc_importet = $row["orc_importet"];
		    // adicion del campo tiempo en el proceso de clonacion
		    $bean->orc_tiempo = $row["orc_tiempo"];
				$bean->orc_tototal = $row["orc_tototal"];
				$bean->orc_descvalor = $row["orc_descvalor"];
				$bean->orc_descpor = $row["orc_descpor"];
				$bean->orc_division = $row["orc_division"];
				$bean->orc_observaciones = $row["orc_observaciones"];
				$dateFC = date_create(date("Y-m-d H:i:s"));
				$bean->date_entered = date_format($dateFC, 'Y-m-d H:i:s');
		    $bean->sco_ordencompra_id_c = '';
	    	$bean->orc_occ = '';
	    	$bean->orc_verco = 0;
		 	$bean->save();
		 	$idoc = $bean->id;
	  	//Relacion OC con SCO_Contactos
	    $query2 = "SELECT *
			FROM sco_contactos as co
			INNER JOIN sco_ordencompra_sco_contactos_c as occo
			ON co.id = occo.sco_ordencompra_sco_contactossco_contactos_idb
			WHERE co.deleted = 0
			AND occo.deleted = 0
			AND occo.sco_ordencompra_sco_contactossco_ordencompra_ida = '".$idoc_cl."' ";
	    $obj2 = $bean->db->query($query2, true);
	    while($row2 = $bean->db->fetchByAssoc($obj2)){
	    	$new_idocco =  create_guid();
	    	//Query, Insertando datos a la tabla relacion SCO_ORDENCOMPRAS con SCO_CONTACTOS
	        $query3 = "INSERT INTO sco_ordencompra_sco_contactos_c
				(id,deleted,date_modified,sco_ordencompra_sco_contactossco_ordencompra_ida,sco_ordencompra_sco_contactossco_contactos_idb)
				VALUES
				('".$new_idocco."',".$row2['deleted'].",'".$row2['date_modified']."','".$idoc."','".$row2['sco_ordencompra_sco_contactossco_contactos_idb']."');
	        ";
	        $obj3 = $bean->db->query($query3, true);
	    }
	    //Relacion de OC con SCO_Aprobadores y Modulo SCO_Aprobadores
			//Generador de correlativos
			$queryApro = "SELECT count(sco_ordencompra_sco_aprobadoressco_aprobadores_idb) as total
	                  FROM suitecrm.sco_ordencompra_sco_aprobadores_c
	                  where deleted = 0 and  sco_ordencompra_sco_aprobadoressco_ordencompra_ida = '$idoc_cl'";
	    $resultsApro = $GLOBALS['db']->query($queryApro, true);
	    $rowApro = $GLOBALS['db']->fetchByAssoc($resultsApro);
	    #Obteniendo la cantidad de decimales sobrantes
	    $decimales = ($rowApro["total"]+1) / 7;
	    #Declaramos la cantidad de
	    $valSalto = 0;
	    $incremento = 0;
	    // La función intval nos extrae solo los numeros enteros de un numero decimal
	    $saldos = $decimales - (intval($decimales));
	    $saldoFirmas = $saldos * 10;
	    if (intval($saldoFirmas) > 1){
	      //echo $saldoFirmas." Existe 1 firma abajo => Necesario validar 7 y 5";
	      $valSalto = 7;
	      $incremento = 5;
	    }
	    else{
	      //echo $saldoFirmas." No hay firmas de 1 => Necesario validar 8 y 4";
	      $valSalto = 8;
	      $incremento = 4;
	    }
	    #Generando la numeración correlativa
	    $Numeracion = array();
	    if ($rowApro["total"] == 0) {
	      $Numeracion[1] = 11;
	    }
	    else {
	      for ($i=0; $i < ($rowApro["total"]+1) ; $i++) {
	        if($i == 0)
	        {
	          $Numeracion[$i] = 10;
	        }else
	        {
	          $Numeracion[$i] = $Numeracion[$i - 1] + 1;
	          $salto = $Numeracion[$i] % 10;
	          if($salto == $valSalto)
	          {
	            $Numeracion[$i] = $Numeracion[$i - 1] + $incremento;
	          }
	        }
	      }
	    }
			//Fin generador de correlativos
			#asignamos los correlativos a la lista de aprobadores
			$index = 1;

				#Modificando los correlativos ella lista de Aprobadores
				$query6 = "SELECT * from sco_aprobadores ap
									inner join sco_ordencompra_sco_aprobadores_c ocap on ocap.sco_ordencompra_sco_aprobadoressco_aprobadores_idb = ap.id
									where ocap.sco_ordencompra_sco_aprobadoressco_ordencompra_ida = '$idoc_cl' and ocap.deleted = 0
									order by ap.apr_correlativo";
				$obj6 = $bean->db->query($query6, true);

		while($row6 = $bean->db->fetchByAssoc($obj6)){
			$new_idocap =  create_guid();
			$new_idap = create_guid();
			//Query, Insertando datos a la relacion de SCO_ORDENCOMPRA con SCO_APROBADORES
			$query7 = "INSERT INTO sco_ordencompra_sco_aprobadores_c
									(id, date_modified,deleted,sco_ordencompra_sco_aprobadoressco_ordencompra_ida,sco_ordencompra_sco_aprobadoressco_aprobadores_idb)
									VALUES
									('".$new_idocap."','".$row6['date_modified']."','".$row6['deleted']."','".$idoc."','".$new_idap."');";
      $obj7 = $bean->db->query($query7, true);
      //Query, Insertando datos al modulo de SCO_APROBADORES
      $query62 ="INSERT INTO sco_aprobadores
						      (id,date_modified , name, deleted, apr_titulo, apr_aprueba, user_id_c, apr_correlativo,apr_tipo)
									VALUES
									('".$new_idap."','".$row6['date_modified']."','".$row6['name']."','".$row6['deleted']."','".$row6['apr_titulo']."','No','".$row6['user_id_c']."','".$Numeracion[$index]."','".$row6['apr_tipo']."');";
			$obj62 = $bean->db->query($query62, true);
			$index++;
		}
		//Relacion de OC con SCO_Productos y Modulo de Productos
		$query8 = "SELECT *
								FROM sco_productos as p
								INNER JOIN sco_ordencompra_sco_productos_c as ocp
								ON p.id = ocp.sco_ordencompra_sco_productossco_productos_idb
								WHERE p.deleted = 0
								AND ocp.deleted = 0
								AND sco_ordencompra_sco_productossco_ordencompra_ida = '".$idoc_cl."';";
		$obj8 = $bean->db->query($query8, true);
		while($row8 = $bean->db->fetchByAssoc($obj8)){
			//Crando ID
			$new_idocp = create_guid();
			$new_idp = create_guid();
			//Query, Insertando la relacion de SCO_ORDERCOMPRAR con SCO_PRODUCTOS
			$query9 = "INSERT INTO sco_ordencompra_sco_productos_c
									(id, date_modified, deleted, sco_ordencompra_sco_productossco_ordencompra_ida, sco_ordencompra_sco_productossco_productos_idb)
									VALUES
									('".$new_idocp."','".$row8['date_modified']."',".$row8['deleted'].",'".$idoc."','".$new_idp."')";
									$obj9 = $bean->db->query($query9, true);
			//Query, Insertando datos al modulo SCO_PRODUCTOS (insertando el array del frontend)
			$query10 = "INSERT INTO sco_productos
			(id,name,date_entered,date_modified,modified_user_id,created_by,description,deleted,pro_subtotal,pro_valor,pro_procentaje,pro_aux1)
			VALUES
			(
				'".$new_idp."',
				'".$row8['name']."',
				'".$row8['date_entered']."',
				'".$row8['date_modified']."',
				'".$row8['modified_user_id']."',
				'".$row8['created_by']."',
				'".$row8['description']."',
				'".$row8['deleted']."',
				'".$row8['pro_subtotal']."',
				'".$row8['pro_valor']."',
				'".$row8['pro_procentaje']."',
				'".$row8['pro_aux1']."'
			);";
			$obj10 = $bean->db->query($query10, true);
		}
		//Query, Tabla sco_productos_co
		$query12 = "SELECT * FROM sco_productos_co WHERE pro_idco = '".$idoc_cl."' ";
		$obj12 = $bean->db->query($query12, true);
			while($row12 = $bean->db->fetchByAssoc($obj12)){
			//Creando ID
			$new_idprod = create_guid();
			//Query, !!IMPORTANTE Insertando datos en la tabla de sco_productos_co
			$query11 = "INSERT INTO sco_productos_co
			(
					id,
					deleted,
					pro_nombre,
					pro_descripcion,
					pro_unidad,
					pro_cantidad,
					pro_preciounid,
					pro_descval,
					pro_descpor,
					pro_fecha,
					pro_nomproyco,
					pro_idco,
					pro_idproy,
					pro_idpro,
					pro_tipocotiza,
					pro_subtotal,
					pro_canttrans,
					pro_cantresivida,
					pro_saldos
				)
			VALUES
			(
				'".$new_idprod."',
				'".$row12['deleted']."',
				'".$row12['pro_nombre']."',
				'".$row12['pro_descripcion']."',
				'".$row12['pro_unidad']."',
				'".$row12['pro_cantidad']."',
				'".$row12['pro_preciounid']."',
				'".$row12['pro_descval']."',
				'".$row12['pro_descpor']."',
				'".$row12['pro_fecha']."',
				'".$row12['pro_nomproyco']."',
				'".$idoc."',
				'".$row12['pro_idproy']."',
				'".$row12['pro_idpro']."',
				'".$row12['pro_tipocotiza']."',
				'".$row12['pro_subtotal']."',
				'0',
				'0',
				'".$row12['pro_cantidad']."'
			);
			";
			$obj11 = $bean->db->query($query11, true);
			//Insertando los datos de productos cotizados
			//Insertado al modulo PRODUCTOS COTIZADOS los datos del array del modulo
			$productoscotizados ="INSERT INTO sco_productoscotizados
																	 (id, name, deleted, pro_descripcion, pro_unidad, pro_cantidad, pro_preciounid, pro_descval, pro_descpor, pro_fecha, pro_nomproyco, pro_idco, pro_idproy, pro_idpro, pro_tipocotiza, pro_subtotal, pro_canttrans, pro_cantresivida, pro_saldos)
														VALUES (
																			'".$new_idprod."',
																			'".$row12['pro_nombre']."',
																			'0',
																			'".$row12['pro_descripcion']."',
																			'".$row12['pro_unidad']."',
																			'".$row12['pro_cantidad']."',
																			'".$row12['pro_preciounid']."',
																			'".$row12['pro_descval']."',
																			'".$row12['pro_descpor']."',
																			'".$row12['pro_fecha']."',
																			'".$row12['pro_nomproyco']."',
																			'".$idoc."',
																			'".$row12['pro_idproy']."',
																			'".$row12['pro_idpro']."',
																			'".$row12['pro_tipocotiza']."',
																			'".$row12['pro_subtotal']."',
																			'0',
																			'0',
																			'".$row12['pro_cantidad']."'
																		);";
			$obj_productoscotizados = $bean->db->query($productoscotizados, true);

			//Agregando la relacion con PRODUCTOS COTIZADOS Y PROYECTOS
			if($row12['pro_idpro'] != ''){
				$proyectos ="INSERT INTO sco_productoscotizados_sco_proyectosco_c
														(id,date_modified,deleted,sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida,sco_productoscotizados_sco_proyectoscosco_proyectosco_idb)
										 VALUES (UUid(),'".$row12['pro_fecha']."','0','$new_idprod','".$row12['pro_idproy']."')";
				$obj_proyectos = $bean->db->query($proyectos, true);
			}

			//Agregando la relacion de PRODUCTOS COTIZADOS con ORDEN DE COMPRA
			if($row12['pro_idpro'] != ''){
					$ordencompra ="INSERT INTO sco_productoscotizados_sco_ordencompra_c
																(id,date_modified,deleted,sco_productoscotizados_sco_ordencomprasco_productoscotizados_ida,sco_productoscotizados_sco_ordencomprasco_ordencompra_idb)
												 VALUES (UUid(),'".$row12['pro_fecha']."','0','$new_idprod','$idoc')";
					$obj_ordencompra = $bean->db->query($ordencompra, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			if($row12['pro_idpro'] != ''){
				$pd ="INSERT INTO sco_productoscompras_sco_proyectosco_c
											(id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra)
							VALUES (UUid(),'".$row12['pro_fecha']."','0','".$row12['pro_idpro']."','".$row12['pro_idproy']."','$idoc')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de ORDEN DE COMPRA con PRODUCTOS COMPRAS
			if($row12['pro_idpro'] != ''){
				$pd ="INSERT INTO sco_ordencompra_sco_productoscompras_c
											(id,date_modified,deleted,sco_ordencompra_sco_productoscomprassco_ordencompra_ida,sco_ordencompra_sco_productoscomprassco_productoscompras_idb)
							VALUES (UUid(),'".$row12['pro_fecha']."','0','$idoc','".$row12['pro_idpro']."')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			if($row12['pro_idpro'] != ''){
				$pd ="INSERT INTO sco_productoscompras_sco_proyectosco_c
											(id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra)
							VALUES (UUid(),'".$row12['pro_fecha']."','0','".$row12['pro_idpro']."','".$row12['pro_idproy']."','$idoc')";
				$dsad = $bean->db->query($pd, true);
			}

			//Agregando la relacion de PRODUCTOS COMPRAS con PRODUCTOS COTIZADOS
			if($row12['pro_idpro'] != ''){
				$pd ="INSERT INTO sco_productoscompras_sco_productoscotizados_c
											(id,date_modified,deleted,sco_producb25ccompras_ida,sco_produc4599tizados_idb)
							VALUES (UUid(),'".$row12['pro_fecha']."','0','".$row12['pro_idpro']."','$new_idprod')";
				$dsad = $bean->db->query($pd, true);
			}
		}
 	}
  }

}
?>
