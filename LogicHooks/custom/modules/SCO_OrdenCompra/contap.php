<?php 
/**
*Esta clase realiza el poblado de datos desde el modulo de CONTACTOS de Suitecrm
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class Clcontap 
{
	static $ult_rid = "";
	static $already_ran = false;

  	function Fncontap($bean, $event, $arguments) 
  	{
  		if(self::$already_ran == true) return;
    	self::$already_ran = true;
  		$pro_id = $bean->sco_ordencompra_contactscontacts_ida;
    	#$pro_re = $bean->orc_region;

	    /*$con = "
		SELECT 
		di.contact_id_c,
		co.title, 
		co.phone_mobile, 
		co.phone_work, 
		co.primary_address_street,
		em.email_address 
		from sco_proveedor_sco_distribuidor_c pd 
		inner join sco_distribuidor di on (pd.sco_proveedor_sco_distribuidorsco_distribuidor_idb = di.id) 
		inner join contacts co on (di.contact_id_c = co.id)
		inner join email_addr_bean_rel er on (co.id = er.bean_id) 
		inner join email_addresses em on (em.id = er.email_address_id)
		where 
		pd.deleted = 0
		and pd.sco_proveedor_sco_distribuidorsco_proveedor_ida = '$pro_id' 
		and di.dis_region = '$pro_re'
		and di.dis_principal = 1
		and er.primary_address = 1
	    ";*/
	    //Query, datos del modulo de contactos de SUITECRM
	    $con = "
	    SELECT 
  		co.id,
  		co.title, 
  		co.phone_mobile, 
  		co.phone_work, 
  		co.primary_address_street
  		FROM contacts as co 
  		where co.id = '".$pro_id."' ";
	    $results = $GLOBALS['db']->query($con, true);
	    $row = $GLOBALS['db']->fetchByAssoc($results);
	    $con_id = $row["id"];
	    //Query, Datos de EMAIL de la tabla email_addr_bean_rel con email_addresses (tablas de SUITECRM)
	    $email = "SELECT em.email_address
  		FROM email_addr_bean_rel as er  
  		inner join email_addresses as em 
  		on (em.id = er.email_address_id)
  		where er.primary_address = 1
  		AND er.bean_id = '".$con_id."';";
  		$obj = $GLOBALS['db']->query($email, true);
	    $row_e = $GLOBALS['db']->fetchByAssoc($obj);
	    //Evitando el loop
	    if(self::$ult_rid == $bean->id) return;
    	self::$ult_rid = $bean->id;
    	//Poblando datos del modulo de SCO_ORDENCOMPRA
	    $bean->orc_propercon = $bean->sco_ordencompra_contacts_name;
      	$bean->orc_protelefono = $row["phone_work"];
	    $bean->orc_promovil = $row["phone_mobile"];
	    $bean->orc_procargo = $row["title"];
	    if($row_e["email_address"] != ''){
	      $bean->orc_proemail = $row_e["email_address"];
      }else{
        $bean->orc_proemail = ''; 
      }
      if($row["primary_address_street"] != ''){
	      $bean->orc_prodireccion = $row["primary_address_street"];
      }else{
        $bean->orc_prodireccion = '';
      }
      	//$bean->orc_observaciones = 'carlos';
	    $bean->save();
  	}
}
?>