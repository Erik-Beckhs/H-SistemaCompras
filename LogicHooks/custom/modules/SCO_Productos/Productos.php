<?php
/**
*Esta clase realiza la eliminacion del registro y su relacion del modulo de SCO_PRODUCTOS
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Productos
*/
class ClProductos 
{	
	#static $already_ran = false;
	function Fnproductos($bean, $event, $arguments) 
    {
 	#if(self::$already_ran == true) return;
    #self::$already_ran = true;    	
	  	/*$bdpro = "
	    CREATE TABLE sco_productos_co (
		  id VARCHAR(100) NULL,
		  deleted INT NULL,
		  pro_nombre VARCHAR(250) NULL,
		  pro_descripcion TEXT NULL,
		  pro_unidad VARCHAR(100) NULL,
		  pro_cantidad INT NULL,
		  pro_preciounid DECIMAL(11,2) NULL,
		  pro_descval DECIMAL(10,2) NULL,
		  pro_descpor DECIMAL(10,2) NULL,
		  pro_fecha DATETIME NULL,
		  pro_nomproyco VARCHAR(45) NULL,
		  pro_idco VARCHAR(45) NULL,
		  pro_idproy VARCHAR(45) NULL,
		  pro_idpro VARCHAR(100) NULL,
		  pro_tipocotiza VARCHAR(100) NULL,
		  pro_subtotal VARCHAR(45) NULL,
		  pro_canttrans DOUBLE NULL,
		  pro_cantresivida DOUBLE NULL,
		  pro_saldos DOUBLE NULL,
		  PRIMARY KEY (id))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8
		COLLATE = utf8_bin;
	    ";*/
	    #$bdp = $bean->db->query($bdpro, true);

	  	$bean->load_relationship('sco_ordencompra_sco_productos');
	    $relatedBeans = $bean->sco_ordencompra_sco_productos->getBeans();
	    reset($relatedBeans);
	    $parentBean = current($relatedBeans);
	    $idoc = $parentBean->id;
		//Query, Eliminando el registro anterior al momento de guardar el modulo
		$query1 = 
		"	DELETE FROM sco_productos  
			WHERE id 
			in(SELECT sco_ordencompra_sco_productossco_productos_idb FROM sco_ordencompra_sco_productos_c
			WHERE deleted = 0 and sco_ordencompra_sco_productossco_ordencompra_ida = '$idoc')
			and deleted = 0;
			";
		#"	UPDATE sco_productos  
		#	SET deleted = 1 
		#	WHERE id 
		#	in(SELECT sco_ordencompra_sco_productossco_productos_idb FROM sco_ordencompra_sco_productos_c
		#	WHERE deleted = 0 and sco_ordencompra_sco_productossco_ordencompra_ida = '$idoc')
		#	and deleted = 0;
		#	";
		$obj1 = $bean->db->query($query1, true);	
		//Query, Eliminando la relacion de SCO_ORDENCOMPRA con SCO_PRODUCTOS
	    $query2 =  "DELETE FROM sco_ordencompra_sco_productos_c WHERE sco_ordencompra_sco_productossco_ordencompra_ida = '$idoc';";

	    #"UPDATE sco_ordencompra_sco_productos_c 			SET deleted = 1 WHERE sco_ordencompra_sco_productossco_ordencompra_ida = '$idoc';";
		$obj2 = $bean->db->query($query2, true);	

		$bean->save();

 	}
}
?>