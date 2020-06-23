<?php
class RelDeproductos
{
  static $ult_rid = "";
	static $already_ran = false;
	function FnRelDeproductos($bean, $event, $arguments)
 	{
      if(self::$already_ran == true) return;
    	self::$already_ran = true;
      $idoc = $bean->id;
	    //Eliminando la realcion de PRODUCTOS COTIZADOS con PROYECTOS C/O de acuerdo al id de la ORDEN DE COMPRA
	    $productos_co = "SELECT * FROM sco_productos_co WHERE pro_idco = '$idoc';";
	    $objo_productos_co = $bean->db->query($productos_co, true);
	    while($row = $GLOBALS['db']->fetchByAssoc($objo_productos_co)){
	    	$relacion = "UPDATE sco_productoscotizados_sco_proyectosco_c SET deleted = 1 WHERE sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida = '".$row['id']."';";
  			$obj_relacion = $bean->db->query($relacion, true);

  			//Eliminando la relacion de PRODUCTOS COMPRAS con PRODUCTOS COTIZADOS
  			$pc_pcot = "UPDATE sco_productoscompras_sco_productoscotizados_c SET deleted = 1 WHERE sco_produc4599tizados_idb = '".$row['id']."';";
  			$obj_pc_pcot = $bean->db->query($pc_pcot, true);
	    }

		//Eliminando la relacion de PRODUCTOS COMPRAS con PROYECTOS
		$pcom_py = "UPDATE sco_productoscompras_sco_proyectosco_c SET deleted = 1 WHERE id_ordencompra = '".$idoc."';";
		$obj_pcom_py = $bean->db->query($pcom_py, true);

		//Eliminando la relacion de  ORDEN DE COMPRA con PRODUCTOS COMPRAS
		$pcom_oc = "UPDATE sco_ordencompra_sco_productoscompras_c SET deleted = 1 WHERE sco_ordencompra_sco_productoscomprassco_ordencompra_ida = '".$idoc."' ;";
		$obj_pcom_oc= $bean->db->query($pcom_oc, true);

	    //Eliminando la realcion de PRODUCTOS COTIZADOS con ORDEN DE COMPRA de acuerdo al id de la ORDEN DE COMPRA
		$pcot_oc = "UPDATE sco_productoscotizados_sco_ordencompra_c SET deleted = 1 WHERE sco_productoscotizados_sco_ordencomprasco_ordencompra_idb = '$idoc';";
		$obj_relacion2 = $bean->db->query($pcot_oc, true);

		//Eliminando los datos de PRODUCTOS COTIZADOS de acuerdo al id de la ORDEN DE COMPRA
	    $productoscotizados = "DELETE FROM sco_productoscotizados WHERE pro_idco='$idoc';";
	    $objo_productoscotizados = $bean->db->query($productoscotizados, true);

		//Eliminando datos de la TABLA productos_co de acuerdo al id de la ORDEN DE COMPRA
	    $queryoc = "DELETE FROM sco_productos_co WHERE pro_idco='$idoc';";
	    $objoc = $bean->db->query($queryoc, true);

	    $date = date("d-m-y");
	    $arr1 = $bean->description;
	    //DESCOMPONIENDO EL ARRAY QUE SE ENCUENTRA EN EL CAMPO DE description del modulo SCO_PRODUCTOS
  	 	$arr1 = str_replace("[[", "", $arr1);
  	 	$arr1 = str_replace("]]", "", $arr1);

	    $arr1 = str_replace("[", "", $arr1);
	    $arr1 = str_replace("],", "|", $arr1);
	    $arr1 = str_replace("&quot;","'",$arr1);
	    $arr1 = str_replace("','", "~", $arr1);
     	$arr1 = str_replace("'", "", $arr1);
	    $arr2 = explode("|", $arr1);
	    $arridoc = array_pop($arr2);
	    $arrprec = array_pop($arr2);
	    $arrprec = explode(",", $arrprec);
	    //almacenando en variables los campos del array
	    for ($i=0; $i<count($arr2); $i++)
			{
		    $textfila = $arr2[$i];
		    $fila = explode("~", $textfila);
		    $idpc =  str_replace("&gt;",">",str_replace("&lt;","<",str_replace("**","\"",$fila[0])));
        $descr = mberegi_replace("[\n|\r|\n\r|\t|\0|\x0B]", "", str_replace("&gt;",">",str_replace("&lt;","<",str_replace("**","\"",$fila[1]))));
		    $unid = $fila[2];
		    $cant = $fila[3];
		    $prec = $fila[4];
		    $dscp = $fila[5];
		    $dscv = $fila[6];
		    $stot = $fila[7];
		    $idpo = $fila[8];
		    $idpro = $fila[9];
		    $idproy = $fila[10];
		    $tipoProy = $fila[11];

		    //creando id para productos_co
		    $id_sco_productos_co = create_guid();
		    $id_sco_productoscotizados = create_guid();

				//Insertando a la TABLA sco_productos_co los datos del array
		    $query ="INSERT INTO sco_productos_co (id, deleted, pro_nombre, pro_descripcion, pro_unidad, pro_cantidad, pro_preciounid, pro_descval, pro_descpor, pro_fecha, pro_nomproyco, pro_idco, pro_idproy, pro_idpro, pro_tipocotiza, pro_subtotal, pro_canttrans, pro_cantresivida, pro_saldos) VALUES ('$id_sco_productos_co','0','$idpc','$descr','$unid','$cant','$prec','$dscv','$dscp','$date','$idpo','$idoc','$idproy','$idpro','$tipoProy','$stot','0','0','$cant');";
		    $obj = $bean->db->query($query, true);

		    //Insertado al modulo PRODUCTOS COTIZADOS los datos del array del modulo
		    $productoscotizados ="INSERT INTO sco_productoscotizados (id, name, deleted, pro_descripcion, pro_unidad, pro_cantidad, pro_preciounid, pro_descval, pro_descpor, pro_fecha, pro_nomproyco, pro_idco, pro_idproy, pro_idpro, pro_tipocotiza, pro_subtotal, pro_canttrans, pro_cantresivida, pro_saldos) VALUES ('$id_sco_productos_co','$idpc','0','$descr','$unid','$cant','$prec','$dscv','$dscp','$date','$idpo','$idoc','$idproy','$idpro','$tipoProy','$stot','0','0','$cant');";
		    $obj_productoscotizados = $bean->db->query($productoscotizados, true);

		    	//Agregando la relacion con PRODUCTOS COTIZADOS Y PROYECTOS
			    if($idpro != ''){
			    	$proyectos ="INSERT INTO sco_productoscotizados_sco_proyectosco_c (id,date_modified,deleted,sco_productoscotizados_sco_proyectoscosco_productoscotizados_ida,sco_productoscotizados_sco_proyectoscosco_proyectosco_idb) VALUES (UUid(),'$date','0','$id_sco_productos_co','$idproy')";
			    	$obj_proyectos = $bean->db->query($proyectos, true);
				}

				//Agregando la relacion de PRODUCTOS COTIZADOS con ORDEN DE COMPRA
				if($idpro != ''){
			    	$ordencompra ="INSERT INTO sco_productoscotizados_sco_ordencompra_c (id,date_modified,deleted,sco_productoscotizados_sco_ordencomprasco_productoscotizados_ida,sco_productoscotizados_sco_ordencomprasco_ordencompra_idb) VALUES (UUid(),'$date','0','$id_sco_productos_co','$idoc')";
			    	$obj_ordencompra = $bean->db->query($ordencompra, true);
				}

				//Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			    if($idpro != ''){
			    	$pd ="INSERT INTO sco_productoscompras_sco_proyectosco_c (id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra) VALUES (UUid(),'$date','0','$idpro','$idproy','$idoc')";
			    	$dsad = $bean->db->query($pd, true);
			    }

				//Agregando la relacion de ORDEN DE COMPRA con PRODUCTOS COMPRAS
				if($idpro != ''){
			    	$pd ="INSERT INTO sco_ordencompra_sco_productoscompras_c (id,date_modified,deleted,sco_ordencompra_sco_productoscomprassco_ordencompra_ida,sco_ordencompra_sco_productoscomprassco_productoscompras_idb) VALUES (UUid(),'$date','0','$idoc','$idpro')";
			    	$dsad = $bean->db->query($pd, true);
			    }

			    //Agregando la relacion de PRODUCTOS COMPRAS con PROYECTOS
			    if($idpro != ''){
			    	$pd ="INSERT INTO sco_productoscompras_sco_proyectosco_c (id,date_modified,deleted,sco_productoscompras_sco_proyectoscosco_productoscompras_ida,sco_productoscompras_sco_proyectoscosco_proyectosco_idb,id_ordencompra) VALUES (UUid(),'$date','0','$idpro','$idproy','$idoc')";
			    	$dsad = $bean->db->query($pd, true);
			    }

			    //Agregando la relacion de PRODUCTOS COMPRAS con PRODUCTOS COTIZADOS
			    if($idpro != ''){
			    	$pd ="INSERT INTO sco_productoscompras_sco_productoscotizados_c (id,date_modified,deleted,sco_producb25ccompras_ida,sco_produc4599tizados_idb) VALUES (UUid(),'$date','0','$idpro','$id_sco_productos_co')";
			    	$dsad = $bean->db->query($pd, true);
			    }
	    }
	    //Query, obteniendo el nombre del proyecto de la tabla SCO_PRODUCTOS_CO
	    $queryproy = "SELECT DISTINCT(pro_nomproyco) as name, pro_tipocotiza FROM sco_productos_co WHERE pro_idco ='$idoc';";
	    $objproy = $bean->db->query($queryproy, true);
	    //Concatenando el correlativo para el nombre de la ORDEN DE COMPRA
	    while($row = $bean->db->fetchByAssoc($objproy))
	    {
	    	if($nom == $nom){
	        	$nom .= $row["pro_tipocotiza"] . $row["name"] . "_";
	    	}else{
	    		$correlativo++;
	    	}
	    }
	    //Bean ORDENCOMPRA, guardando la ronde de compra desde el modulo SCO_PRODUCTOS
	    $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
      if($nom != "_"){
	      $beanoc->name = $nom;
      }else{}
	    $beanoc->orc_tototal = $arrprec[0];
	    $beanoc->orc_descvalor = $arrprec[1];
      	$beanoc->orc_descpor = $arrprec[2];
	    $beanoc->orc_importet = $arrprec[3];
	    $beanoc->save();
 	}
}
 ?>
