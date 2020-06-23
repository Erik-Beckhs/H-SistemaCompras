<?php
/**
*Esta clase realiza el llamado de datos del modulo Users y los guarda en los campos de
*SCO_Contactos.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_Despachos
*/
 class Notifica
 {

	function FnnotificaDespacho($bean,$estado,$cantidadItem)
	{
    	require_once('include/SugarPHPMailer.php');
		//Se obtinen el estado de la orden de compra
		//$estado = $bean->des_est;
		//Se verifica el estado de despacho solicitud
		if ($estado == '1') {
			//Solicitud de embarque
			// y se envia una notificacion a los contactos y al solicitante
			$this->envianoti ( $bean ,$cantidadItem);
		}
		// en transito
		if ($estado == '2') {
			$this->envianotiEnTrancito ( $bean ,$cantidadItem);
		}
		//concluido
		if ($estado == '3') {
			//En caso de que el estado sea 3 esta solicitando la aprobacion a los aprobadores
			// y se envia una notificacion a los aprobadores
			//$this->envianotiConcluido ( $bean ,$cantidadItem);
		}
		// Estado de Despacho Anulado
		if ($estado == '4') {
			$this->envianotiAnulado ( $bean ,$cantidadItem);
		}
	}
	function envianoti($bean,$cantidad) {
		$idDespacho = $bean->id;
		$accion = "se solicita el embarque para el despacho";
		// consulta que obtiene los templates del sistema
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
						  WHERE name like 'plantilla_despacho_solicitado' and deleted = 0";
			$result = $GLOBALS['db']->query($queryTemp, true);
			while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
					$subject = $row2 ['subject'];
					$template = $row2 ['body_html'];
				}
		// Consulta que obtiene la lista de lo contactos rol logistico
		$query = "
                        SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                        oc.orc_tcinco
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_contactos con on con.user_id_c = u.id
                        inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
                        inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho' and occo.deleted = 0 and con.con_rol = 'Logistico'  ";

			$resultAprobadores = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
	}
	function envianotiEnTrancito($bean,$cantidad) {
		$idDespacho = $bean->id;
		// consulta que obtiene los templates del sistema
		$accion = "se aprobo un embarque para el despacho ";
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
						  WHERE name like 'plantilla_despacho_en_trancito' and deleted = 0";
			$result = $GLOBALS['db']->query($queryTemp, true);
			while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
					$subject = $row2 ['subject'];
					$template = $row2 ['body_html'];
				}
		// Consulta que obtiene la lista de lo contactos rol logistico
		$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
              oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
              oc.orc_tcinco
              from suitecrm.users u
              inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
              inner join suitecrm.email_addresses em on em.id = rel.email_address_id
              inner join suitecrm.sco_contactos con on con.user_id_c = u.id
              inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
              inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
              inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
              inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
              where des.id = '$idDespacho' and occo.deleted = 0 and con.con_rol = 'Logistico'";

			$resultAprobadores = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
			$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho'";
			$resultSolicitante = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion,$idDespacho ,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
	}
	function envianotiConcluido($bean,$cantidad) {
		$idDespacho = $bean->id;
		// consulta que obtiene los templates del sistema
		$accion = "se concluyo el despacho ";
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
						  WHERE name like 'plantilla_despacho_concluido' and deleted = 0";
			$result = $GLOBALS['db']->query($queryTemp, true);
			while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
					$subject = $row2 ['subject'];
					$template = $row2 ['body_html'];
				}
		// Consulta que obtiene la lista de lo contactos rol logistico
		$query = "
                        SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                        oc.orc_tcinco
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_contactos con on con.user_id_c = u.id
                        inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
                        inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho' and occo.deleted = 0 and con.con_rol = 'Logistico'";

			$resultAprobadores = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
			$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho'";
			$resultSolicitante = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
	}
	function envianotiAnulado($bean,$cantidad) {
		$idDespacho = $bean->id;
		$accion = "se anulo el despacho";
		// consulta que obtiene los templates del sistema
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
						  WHERE name like 'plantilla_despacho_anulado' and deleted = 0";
			$result = $GLOBALS['db']->query($queryTemp, true);
			while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
					$subject = $row2 ['subject'];
					$template = $row2 ['body_html'];
				}
		// Consulta que obtiene la lista de lo contactos rol logistico
		$query = "
                        SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,des.des_observaciones,oc.orc_tcinco,oc.orc_nomcorto as proveedor
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_contactos con on con.user_id_c = u.id
                        inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
                        inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho' and occo.deleted = 0 and con.con_rol = 'Logistico' ";

			$resultAprobadores = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );

				$mail->Send ();
			}
			$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,des.des_observaciones,oc.orc_tcinco,
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho'";
			$resultSolicitante = $GLOBALS['db']->query($query, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
			{
				$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idDespacho,$cantidad);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
				$mail->prepForOutbound ();
				$mail->AddAddress ( $row['email_address'] );
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->Send ();
			}
	}
	function correogeneral($template, $subject, $bean, $aprobador, $accion,$id,$cantidad) {
		if (strpos ( $template, '$accion' ) !== true) {
			$template = str_ireplace ( '$accion', $accion, $template );
		}
		if (strpos ( $template, '$despacho' ) !== true) {
			$template = str_ireplace ( '$despacho', $bean->name, $template );
		}
		if (strpos ( $template, '$nombre_apellido' ) !== true) {
			$template = str_ireplace ( '$nombre_apellido', $aprobador['first_name']." ".$aprobador['last_name'], $template );
		}
		if (strpos ( $template, '$titulo' ) !== true) {
			$template = str_ireplace ( '$titulo', $aprobador['title'], $template );
		}
		if (strpos ( $template, '$fecha' ) !== true) {
			$template = str_ireplace ( '$fecha', date("Y-m-d"), $template );
		}
		if (strpos ( $template, '$origen' ) !== true) {
			$template = str_ireplace ( '$origen', $bean->des_origen, $template );
		}
		if (strpos ( $template, '$modalidad' ) !== true) {
			$template = str_ireplace ( '$modalidad', $bean->des_modtrans, $template );
		}
		if (strpos ( $template, '$diasT' ) !== true) {
			$template = str_ireplace ( '$diasT', $bean->des_diastrans, $template );
		}
		if (strpos ( $template, '$accion' ) !== true) {
			$template = str_ireplace ( '$accion', $accion, $template );
		}
		if (strpos ( $template, '$nombre_apellido' ) !== true) {
			$template = str_ireplace ( '$nombre_apellido', $aprobador['first_name']." ".$aprobador['last_name'], $template );
		}
		if (strpos ( $template, '$titulo' ) !== true) {
			$template = str_ireplace ( '$titulo', $aprobador['title'], $template );
		}
		if (strpos ( $template, '$fecha' ) !== true) {
			$template = str_ireplace ( '$fecha', date("Y-m-d"), $template );
		}
    if (strpos ( $template, '$proveedor' ) !== true) {
			$template = str_ireplace ( '$fecha', date("Y-m-d"), $template );
		}
		/*if (strpos ( $template, '$monto' ) !== true) {
			$template = str_ireplace ( '$monto', $ordenCompra->orc_tototal." ".$ordenCompra->orc_tcmoneda, $template );
		}
		if (strpos ( $template, '$solicitante' ) !== true) {
			$template = str_ireplace ( '$solicitante', $ordenCompra->modified_user_id, $template );
		}
		if (strpos ( $template, '$ordenConpra' ) !== true) {
			$template = str_ireplace ( '$ordenConpra', $ordenCompra->name, $template );
		}*/
		if (strpos ( $template, '$link' ) !== true) {
			$host= 'http://'.$_SERVER['HTTP_HOST'].'/';
			$link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Despachos%26action%3DDetailView%26record%3D'.$id;
			$template = str_ireplace ( '$link', $link , $template );
		}
		if (strpos ( $template, '$lnkOC' ) !== true) {
			$host= 'http://'.$_SERVER['HTTP_HOST'].'/';
			$idOC =  $aprobador['idOrdenCompra'];
			$linkOC = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_OrdenCompra%26action%3DDetailView%26record%3D'.$idOC;
			$template = str_ireplace ( '$lnkOC', $linkOC , $template );
		}
		if (strpos ( $template, '$divSolicictante' ) !== true) {
			$template = str_ireplace ( '$divSolicictante',  $aprobador['division'], $template );
		}
		if (strpos ( $template, '$proveedor' ) !== true) {
			$template = str_ireplace ( '$proveedor',  $aprobador['proveedor'], $template );
		}
		if (strpos ( $template, '$observaciones' ) !== true) {
			$template = str_ireplace ( '$observaciones',  $aprobador['des_observaciones'], $template );
		}
		if (strpos ( $template, '$item' ) !== true) {
			$template = str_ireplace ( '$item',  $cantidad, $template );
		}
		if (strpos ( $template, '$incoterm' ) !== true) {
			$template = str_ireplace ( '$incoterm',   $aprobador['orc_tcinco'], $template );
		}
    if (strpos ( $template, '$incoterm' ) !== true) {
			$template = str_ireplace ( '$incoterm',   $aprobador['orc_tcinco'], $template );
		}
    $Prov = '';
    if (strpos ( $template, '$proveedor' ) !== true) {
			$template = str_ireplace ( '$proveedor',   $aprobador['proveedor'], $template );
      $Prov = $aprobador['proveedor'];
		}
		$arraytemplate = array (
				'subject' => $subject." ".$bean->name." del Proveedor: ".$Prov,
				'template' => $template
		);
		return $arraytemplate;
	}
 }

?>
