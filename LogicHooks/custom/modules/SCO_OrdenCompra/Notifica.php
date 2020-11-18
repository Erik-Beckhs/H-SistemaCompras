<?php
/**
*Esta clase realiza el llamado de datos del modulo Users y los guarda en los campos de
*SCO_Contactos.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
require_once('include/SugarPHPMailer.php');
class Clnotifica
{
	static $ult_rid = "";
	static $already_ran = false;
	function Fnnotifica($bean, $event, $arguments){
		if(self::$already_ran == true) return;
    	self::$already_ran = true;
    	//Se obtiene el id de la orden de compra
		$id = $bean->id;
		//Se obtinen el estado de la orden de compra
		$estado = $bean->orc_estado;
		//Se verifica el estado de la orden de compra
		if ($estado == '3') {
			//En caso de que el estado sea 3 esta solicitando la aprobacion a los aprobadores
			// y se envia una notificacion a los aprobadores
			$this->envianoti ( $bean );
		}
		// Para validar que no exista despachos y solo enviar en la solicitud
		$bean->load_relationship('sco_despachos_sco_ordencompra');
	    $relatedBeans = $bean->sco_despachos_sco_ordencompra->getBeans();
	    $parentBean = current($relatedBeans);
    	$idoc = $parentBean->id;
		if ($estado == '1' && !(isset($idoc))) {
			//En caso de que el estado sea 3 esta solicitando la aprobacion a los aprobadores
			// y se envia una notificacion a los aprobadores
			//$this->envianotiEncurso ( $bean );
		}
		if ($estado == '6') {
			//En caso de que el estado sea 6 la orden de compra esta cerrada/completada
			// y se envia una notificacion a los aprobadores
			$this->envianotiCerrado ( $bean );
		}
		if ($estado == '5') {
			//En caso de que el estado sea 5 la orden de compra esta cancelado
			// y se envia una notificacion a los aprobadores
			$this->envianotiCancelado ( $bean );
		}
	}
	function envianoti($bean) {
		$idOrdenCompra = $bean->id;

		//llamamos a la funcion query_template
		$result = $this->query_template('plantilla_solicitud_orden_compra');
		while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
			$subject = $row2 ['subject'];
			$template = $row2 ['body_html'];
		}
		$accion = "La orden de compra <strong>OC</strong> creada en fecha FH se encuentra en <strong>SOLICITUD DE APROBACIÃ“N</strong>";
		//Enviamos Notificacion al PM
		$this->notificacionesPM( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
		//llamamos a la funcion de query_aprovadores
		/*$resultAprobadores = $this->query_aprovadores($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
		{
			$accion = "Se le solicita la <strong>aprobaciÃ³n</strong> de la orden de compra<strong>OC</strong>:";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			//$mail->AddAddress ( $row["email_address"] );// Habilitar para el envio de notificaciones reales
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
		//llamamos a la funcion query_solicitante
		/*$resultSolicitante = $this->query_solicitante($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
		{
			$accion = "La orden de compra <strong>OC</strong> creada en fecha FH se encuentra en <strong>SOLICITUD DE APROBACIÃ“N</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			//$mail->AddAddress ( $row["email_address"] );// Habilitar para el envio de notificaciones reales
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
	}
	function envianotiEncurso($bean) {
		$idOrdenCompra = $bean->id;

		// consulta que obtiene los templates del sistema
		$result = $this->query_template('plantilla_orden_compra_en_curso');
		while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
				$subject = $row2 ['subject'];
				$template = $row2 ['body_html'];
		}
		$accion = "La orden de compra <strong>OC</strong> creada en FH se encuentra <strong>en curso</strong>";
		//Enviamos Notificacion al PM
		$this->notificacionesPM( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
		// Consulta que obtiene la lista de contactos
		$resultAprobadores = $this->query_contactos($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
		{
			$accion = "La orden de compra <strong>OC</strong> creada en FH se encuentra <strong>en curso</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			$mail->AddAddress ( $row["email_address"] );
			//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}
		//llamamos a la funcion query_solicitante
		/*$resultSolicitante = $this->query_solicitante($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
		{
			$accion = "La orden de compra <strong>OC</strong> creada en FH se encuentra <strong>en curso</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			//$mail->AddAddress ( $row["email_address"] );
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
	}
	function envianotiCerrado($bean) {
		$idOrdenCompra = $bean->id;

		// consulta que obtiene los templates del sistema
		//llamamos a la funcion query_template
		$result = $this->query_template('plantilla_orden_compra_cerrada');
		while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
			$subject = $row2 ['subject'];
			$template = $row2 ['body_html'];
		}
		//Enviamos Notificacion al PM
		$this->notificacionesPM( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
		// Consulta que obtiene la lista de contactos
		/*$resultContactos = $this->query_contactos($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultContactos) )
		{
			$accion = "La orden de compra <strong>OC</strong> se encuentra <strong>cerrada</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			//$mail->AddAddress ( $row["email_address"] );
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
		//llamamos a la funcion query_solicitante
		$resultSolicitante = $this->query_solicitante($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
		{
			$accion = "La orden de compra <strong>OC</strong> se encuentra <strong>cerrada</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			$mail->AddAddress ( $row["email_address"] );
			//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}

		//llamamos a la funcion de query_aprovadores
		/*$resultAprobadores = $this->query_aprovadores($idOrdenCompra);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
		{
			$accion = "La orden de compra <strong>OC</strong> se encuentra <strong>cerrada</strong>";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			//$mail->AddAddress ( $row['email_address'] );
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
	}
	function envianotiCancelado($bean) {
		$idOrdenCompra = $bean->id;

		// consulta que obtiene los templates del sistema
		$accion = "se ha cancelado ";
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
									WHERE name like 'plantilla_orden_compra_cancelada' and deleted = 0";
		$result = $GLOBALS['db']->query($queryTemp, true);
		while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
			$subject = $row2 ['subject'];
			$template = $row2 ['body_html'];
		}
		//Enviamos Notificacion al PM
		$this->notificacionesPM( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
		// Consulta que obtiene la lista de lo aprobadores
		/*$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                     oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
              (	select concat(usr.first_name,' ',usr.last_name)
								from suitecrm.users usr
								where usr.id = oc.user_id_c) as encargado,oc.date_entered as fecha
              from suitecrm.users u
              inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
              inner join suitecrm.email_addresses em on em.id = rel.email_address_id
              inner join suitecrm.sco_aprobadores apro on apro.user_id_c = u.id
              inner join suitecrm.sco_ordencompra_sco_aprobadores_c orap on orap.sco_ordencompra_sco_aprobadoressco_aprobadores_idb = apro.id
              inner join suitecrm.sco_ordencompra oc on oc.id = orap.sco_ordencompra_sco_aprobadoressco_ordencompra_ida
              where oc.id = '$idOrdenCompra' ";
		$resultAprobadores = $GLOBALS['db']->query($query, true);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
		{
			$accion = "se ha cancelado ";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			$mail = new SugarPHPMailer ();
			$mail->setMailerForSystem ();
			$mail->From = $defaults['email'];
			$mail->FromName = 'Hansa CRM ';
			$mail->Subject = $contenidoBody ['subject'];//"Orden de compra en curso";//
			$mail->isHTML ( true );
			$mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
			$mail->prepForOutbound ();
			//$mail->AddAddress ( $row["email_address"] );
			$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}*/
		$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
					oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                      (select concat(usr.first_name,' ',usr.last_name)  from suitecrm.users usr where usr.id = oc.user_id_c) as encargado
                      from suitecrm.users u
                      inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                      inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                      inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                      where oc.id = '$idOrdenCompra'";
		$resultSolicitante = $GLOBALS['db']->query($query, true);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
		{
			$accion = "se ha cancelado ";
			$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
			$mail->AddAddress ( $row["email_address"] );
			//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->Send ();
		}
	}
	function notificacionesPM( $template, $subject, $bean, $row, $accion ,$idOrdenCompra)
	{
		//Consulta PM
		$c = 0;
		$consultaPM = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                     oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
			              (	select concat(usr.first_name,' ',usr.last_name)
											from suitecrm.users usr
											where usr.id = oc.user_id_c) as encargado,oc.date_entered as fecha
								from sco_productos_co pro
								inner join sco_ordencompra oc on oc.id = pro.pro_idco
								inner join sco_proyectosco proy on proy.id = pro.pro_idproy
								inner join sco_proyectosco_users_c prus on prus.sco_proyectosco_userssco_proyectosco_ida = proy.id
								inner join users u on u.id = prus.sco_proyectosco_usersusers_idb
								inner join email_addr_bean_rel rel on u.id = rel.bean_id
								inner join email_addresses em on em.id = rel.email_address_id
								where oc.id = '$idOrdenCompra' and oc.iddivision_c = proy.proyc_division
								group by u.user_name";
			$resultPM = $GLOBALS['db']->query($consultaPM, true);
				while ( $row = $GLOBALS['db']->fetchByAssoc($resultPM) )
				{
					$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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
					$c = $c+1;
				}
				if($c == 0)
				{
					$queryGerenteProyectos = "SELECT oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,us.*,
																			(	select concat(usr.first_name,' ',usr.last_name)
																			from suitecrm.users usr
																			where usr.id = oc.user_id_c) as encargado
																		from sco_ordencompra oc ,(select u.user_name,em.email_address, u.first_name,u.last_name,u.title
																			from users u
																			inner join email_addr_bean_rel rel on u.id = rel.bean_id
																			inner join email_addresses em on em.id = rel.email_address_id
																			where u.title = 'Gerente de proyectos' and u.address_city = 'La paz') us
																			where oc.id= '$idOrdenCompra'";
					$resultGP = $GLOBALS['db']->query($queryGerenteProyectos, true);
					while ( $row = $GLOBALS['db']->fetchByAssoc($resultGP) )
								{
									$contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion ,$idOrdenCompra);
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

	}
	function query_aprovadores($idOC)
	{
		$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                      oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                      (	select concat(usr.first_name,' ',usr.last_name)
												from suitecrm.users usr
												where usr.id = oc.user_id_c) as encargado,
											oc.date_entered as fecha
              from suitecrm.users u
              inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
              inner join suitecrm.email_addresses em on em.id = rel.email_address_id
              inner join suitecrm.sco_aprobadores apro on apro.user_id_c = u.id
              inner join suitecrm.sco_ordencompra_sco_aprobadores_c orap on orap.sco_ordencompra_sco_aprobadoressco_aprobadores_idb = apro.id
              inner join suitecrm.sco_ordencompra oc on oc.id = orap.sco_ordencompra_sco_aprobadoressco_ordencompra_ida
              where oc.id = '$idOC' ";
		$resultAprobadores = $GLOBALS['db']->query($query, true);
		return $resultAprobadores;
	}
	// funcion que devuelve la lista de contactos de la OC
	function query_contactos($idOC)
	{
		$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
										 oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
										(	SELECT concat(usr.first_name,' ',usr.last_name)
											from suitecrm.users usr
											where usr.id = oc.user_id_c) as encargado,
											oc.date_entered as fecha
							from suitecrm.users u
							inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
							inner join suitecrm.email_addresses em on em.id = rel.email_address_id
							inner join suitecrm.sco_contactos con on con.user_id_c = u.id
							inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
							inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
							where oc.id = '$idOC'";

		$resultAprobadores = $GLOBALS['db']->query($query, true);
		return $resultAprobadores;
	}
	// funcione que devuelve la lista de aprobadores
	function query_solicitante($idOC)
	{
		$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
										 oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
              			(	select concat(usr.first_name,' ',usr.last_name)
											from suitecrm.users usr
											where usr.id = oc.user_id_c) as encargado,
										 oc.date_entered as fecha
              from suitecrm.users u
              inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
              inner join suitecrm.email_addresses em on em.id = rel.email_address_id
              inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
              where oc.id = '$idOC'";
		$resultSolicitante = $GLOBALS['db']->query($query, true);
		return $resultSolicitante;
	}
	//funcion que obtiene los templates del sistema
	function query_template($name)
	{
		$queryTemp = "SELECT id, subject, body_html FROM email_templates
									WHERE name like '$name' and deleted = 0";
		$result = $GLOBALS['db']->query($queryTemp, true);
		return $result;
	}
	//funcione que genera los correos en general segun las caracteristicas
	function correogeneral($template, $subject, $ordenCompra, $aprobador, $accion,$id) {
		// Modifica las variables del template
		if (strpos ( $template, '$accion' ) !== true) {
			if (strpos ( $accion, 'OC' ) !== true) {
				$accion = str_ireplace ( 'OC',  $ordenCompra->name, $accion );
			}
			if (strpos ( $accion, 'FH' ) !== true) {
				$accion = str_ireplace ( 'FH',  $aprobador['fecha'], $accion );
			}
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
		if (strpos ( $template, '$monto' ) !== true) {
			$template = str_ireplace ( '$monto', $ordenCompra->orc_tototal." ".$ordenCompra->orc_tcmoneda, $template );
		}
		if (strpos ( $template, '$solicitante' ) !== true) {
			$template = str_ireplace ( '$solicitante', $ordenCompra->modified_user_id, $template );
		}
		if (strpos ( $template, '$ordenConpra' ) !== true) {
			$template = str_ireplace ( '$ordenConpra', $ordenCompra->name, $template );
		}
		if (strpos ( $template, '$link' ) !== true) {
			$host= 'http://'.$_SERVER['HTTP_HOST'].'/';
			$link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_OrdenCompra%26action%3DDetailView%26record%3D'.$id;
			$template = str_ireplace ( '$link', $link , $template );
		}
		if (strpos ( $template, '$divSolicictante' ) !== true) {
			$template = str_ireplace ( '$divSolicictante',  $aprobador['division'], $template );
		}
		if (strpos ( $template, '$proveedor' ) !== true) {
			$template = str_ireplace ( '$proveedor',  $aprobador['proveedor'], $template );
		}
		if (strpos ( $template, '$responsable' ) !== true) {
			$template = str_ireplace ( '$responsable',  $aprobador['encargado'], $template );
		}
		// Modifica el subject del correo
		$subject = $subject." ".$ordenCompra->name;
		$arraytemplate = array (
				'subject' => $subject." ".$aprobador['proveedor'],
				'template' => $template
		);
		return $arraytemplate;
	}
}
?>
