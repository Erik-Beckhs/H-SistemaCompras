<?php
//Consulta que obtiene la informacion del panel de contactos
	/*$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
								oc.id as idOrdenC,oc.name as ordenCompra,emb.id as idEmbarque,
								emb.name as embarque,
								des.name as despacho,
								ri.name as problema, ev.name as evento,
			          ev.id as idEvento, oc.orc_division as division
        from suitecrm.users u
        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
        inner join suitecrm.sco_contactos con on con.user_id_c = u.id
        inner join suitecrm.sco_ordencompra_sco_contactos_c ocon on ocon.sco_ordencompra_sco_contactossco_contactos_idb = con.id
        inner join suitecrm.sco_ordencompra oc on oc.id = ocon.sco_ordencompra_sco_contactossco_ordencompra_ida
        inner join suitecrm.sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
        inner join suitecrm.sco_despachos des on des.id = deor.sco_despachos_sco_ordencomprasco_despachos_idb
        inner join suitecrm.sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_despachos_idb = des.id
        inner join suitecrm.sco_embarque emb on emb.id = emde.sco_embarque_sco_despachossco_embarque_ida
        inner join suitecrm.sco_embarque_sco_eventos_c emen on emen.sco_embarque_sco_eventossco_embarque_ida = emb.id
        inner join suitecrm.sco_eventos ev on ev.id = emen.sco_embarque_sco_eventossco_eventos_idb
        inner join suitecrm.sco_eventos_sco_riesgo_c evri on evri.sco_eventos_sco_riesgosco_eventos_ida = ev.id
        inner join suitecrm.sco_riesgo ri on ri.id = evri.sco_eventos_sco_riesgosco_riesgo_idb
		where evri.sco_eventos_sco_riesgosco_riesgo_idb = '$id' and emde.deleted = 0 and  deor.deleted = 0";*/

		//$resultAprobadores = $GLOBALS['db']->query($query, true);
		//$embarque = ""; $ordenCompra = ""; $evento = ""; $problema = "";$idEvento = "";

		// consulta que obtiene los templates del sistema
	 $queryTemp = "SELECT id, subject, body_html FROM email_templates
	               WHERE name like 'plantilla_problema_registrado' and deleted = 0";
	 $result = $GLOBALS['db']->query($queryTemp, true);
	 while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
	        $subject = $row2 ['subject'];
	        $template = $row2 ['body_html'];
	 }
	 $accion = "Se registro un problema en el embarque: ";
	 $c = 0;
	//Consulta que obtiene los datos del PM
	require_once('include/SugarPHPMailer.php');
	$consultaPM = "SELECT u.*,em.*,ri.name as problema, ev.name as evento, ev.id as idEvento,emb.name as embarque,oc.name as ordenCompra,oc.id as idOrdenC,oc.orc_division
						from users u
						inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
						inner join suitecrm.email_addresses em on em.id = rel.email_address_id
						inner join sco_proyectosco_users_c prus on prus.sco_proyectosco_usersusers_idb = u.id
						inner join sco_proyectosco proy on proy.id = prus.sco_proyectosco_userssco_proyectosco_ida
						inner join sco_productoscompras_sco_proyectosco_c pcproy on pcproy.sco_productoscompras_sco_proyectoscosco_proyectosco_idb = proy.id
						inner join sco_productoscompras pcom on pcom.id = pcproy.sco_productoscompras_sco_proyectoscosco_productoscompras_ida
						inner join sco_productoscompras_sco_despachos_c prodes on prodes.sco_productoscompras_sco_despachossco_productoscompras_ida = pcom.id
						inner join sco_despachos des on des.id = prodes.sco_productoscompras_sco_despachossco_despachos_idb
						inner join sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_despachos_idb = des.id
						inner join sco_ordencompra oc on oc.id = deor.sco_despachos_sco_ordencomprasco_ordencompra_ida
						inner join sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_despachos_idb = des.id
						inner join sco_embarque emb on emb.id = emde.sco_embarque_sco_despachossco_embarque_ida
						inner join sco_embarque_sco_eventos_c emen on emen.sco_embarque_sco_eventossco_embarque_ida = emb.id
						inner join sco_eventos ev on ev.id = emen.sco_embarque_sco_eventossco_eventos_idb
						inner join sco_eventos_sco_riesgo_c evri on evri.sco_eventos_sco_riesgosco_eventos_ida = ev.id
						inner join sco_riesgo ri on ri.id = evri.sco_eventos_sco_riesgosco_riesgo_idb
						where evri.sco_eventos_sco_riesgosco_riesgo_idb = '$id' and emde.deleted = 0 and des.deleted = 0
						group by u.first_name";

  // Envio de correos al PM


		$resultPM = $GLOBALS['db']->query($consultaPM, true);
		while ( $row = $GLOBALS['db']->fetchByAssoc($resultPM) )
		{
			//Capturamos el id de orden de compra
			$idoc = $row["idOrdenC"];
	    $idEvento = $row["idEvento"];
	    $contenidoBody = correogeneral ( $template, $subject, $row, $accion, $idEvento);
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			$mail = new SugarPHPMailer ();
			$mail->setMailerForSystem ();
			$mail->From = $defaults['email'];
			$mail->FromName = 'Hansa CRM ';
			$mail->Subject = $contenidoBody ['subject'];//"Ocurrio un problema";// Objetivo del mensaje
			$mail->isHTML ( true );
			$mail->Body = $contenidoBody ['template'];//Enviamos el contenido de la plantilla modificado
			$mail->prepForOutbound ();
			//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
			$mail->AddAddress ( $row['email_address'] );
			$mail->Send ();
			$c = $c + 1;
		}
		if($c == 0){
			$consultaGerentePY = "SELECT ri.name as problema, ev.name as evento, ev.id as idEvento,emb.name as embarque,oc.name as ordenCompra,oc.id as idOrdenC, oc.orc_division, us.*
							from (select u.user_name,em.email_address, u.first_name,u.last_name,u.title
																				from users u
																				inner join email_addr_bean_rel rel on u.id = rel.bean_id
																				inner join email_addresses em on em.id = rel.email_address_id
																				where u.title = 'Gerente de proyectos' and u.address_city = 'La paz') us
							inner join sco_despachos des
							inner join sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_despachos_idb = des.id
							inner join sco_ordencompra oc on oc.id = deor.sco_despachos_sco_ordencomprasco_ordencompra_ida
							inner join sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_despachos_idb = des.id
							inner join sco_embarque emb on emb.id = emde.sco_embarque_sco_despachossco_embarque_ida
							inner join sco_embarque_sco_eventos_c emen on emen.sco_embarque_sco_eventossco_embarque_ida = emb.id
							inner join sco_eventos ev on ev.id = emen.sco_embarque_sco_eventossco_eventos_idb
							inner join sco_eventos_sco_riesgo_c evri on evri.sco_eventos_sco_riesgosco_eventos_ida = ev.id
							inner join sco_riesgo ri on ri.id = evri.sco_eventos_sco_riesgosco_riesgo_idb
							where evri.sco_eventos_sco_riesgosco_riesgo_idb = '$id' and emde.deleted = 0 and des.deleted = 0";
			$resultPM = $GLOBALS['db']->query($consultaGerentePY, true);
			while ( $row = $GLOBALS['db']->fetchByAssoc($resultPM) )
			{
				//Capturamos el id de orden de compra
				$idoc = $row["idOrdenC"];
		    $idEvento = $row["idEvento"];
		    $contenidoBody = correogeneral ( $template, $subject, $row, $accion, $idEvento);
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer ();
				$mail->setMailerForSystem ();
				$mail->From = $defaults['email'];
				$mail->FromName = 'Hansa CRM ';
				$mail->Subject = $contenidoBody ['subject'];//"Ocurrio un problema";// Objetivo del mensaje
				$mail->isHTML ( true );
				$mail->Body = $contenidoBody ['template'];//Enviamos el contenido de la plantilla modificado
				$mail->prepForOutbound ();
				//$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
				$mail->AddAddress ( $row['email_address'] );
				$mail->Send ();
			}
		}

  //Asignando el template en las plantillas
  /*while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
        $subject = $row2 ['subject'];
        $template = $row2 ['body_html'];
	}
  //$embarque = ""; $ordenCompra = ""; $evento = ""; $problema = "";$idEvento = "";
    // Envio de correos a lista de contactos
	while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
	{
		//Capturamos el id de orden de compra
		$idoc = $row["idOrdenC"];
		//Capturamos el id de la orden de compra
		$embarque = $row["embarque"]; $ordenCompra = $row["ordenCompra"]; $evento = $row["evento"]; $problema = $row["problema"];
    $idEvento = $row["idEvento"];
    $contenidoBody = correogeneral ( $template, $subject, $row, $accion, $idEvento);
		$emailObj = new Email();
		$defaults = $emailObj->getSystemDefaultEmail();
		$mail = new SugarPHPMailer ();
		$mail->setMailerForSystem ();
		$mail->From = $defaults['email'];
		$mail->FromName = 'Hansa CRM ';
		$mail->Subject = $contenidoBody ['subject'];//"Ocurrio un problema";// Objetivo del mensaje
		$mail->isHTML ( true );
		$mail->Body = $contenidoBody ['template'];//Enviamos el contenido de la plantilla modificado
		$mail->prepForOutbound ();
		$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
		//$mail->AddAddress ( $row['email_address'] );
		$mail->Send ();
	}
	$datosEmail = array($embarque,$ordenCompra,$evento,$problema);
	// Obtenemos los datos del solicitante y le enviamos un email
	$query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title, oc.orc_division as division
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                        where oc.id = '$idoc'";
    $resultSolicitante = $GLOBALS['db']->query($query, true);
    while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
    {
        $accion = "Se registro un problema en el embarque: ";
        $contenidoBody = correoAprobador ( $template, $subject, $row,$datosEmail, $accion, $idEvento );
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer ();
        $mail->setMailerForSystem ();
        $mail->From = $defaults['email'];
        $mail->FromName = 'Hansa CRM ';
        $mail->Subject = $contenidoBody ['subject'];//"Se registro un problema de embarque";//
        $mail->isHTML ( true );
        $mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
        $mail->prepForOutbound ();
        //$mail->AddAddress ( $row['email_address'] );
        $mail->AddAddress ( 'rkantuta@hansa.com.bo' );
        $mail->Send ();
    }
		*/
	function correogeneral($template, $subject, $aprobador, $accion, $id)
	{
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
        if (strpos ( $template, '$embarque' ) !== true) {
            $template = str_ireplace ( '$embarque', $aprobador["embarque"], $template );
        }
        if (strpos ( $template, '$ordenCompra' ) !== true) {
            $template = str_ireplace ( '$ordenCompra', $aprobador["ordenCompra"], $template );
        }
        if (strpos ( $template, '$evento' ) !== true) {
            $template = str_ireplace ( '$evento', $aprobador["evento"], $template );
        }
        if (strpos ( $template, '$problema' ) !== true) {
            $template = str_ireplace ( '$problema', $aprobador["problema"], $template );
        }
				if (strpos ( $template, '$divSolicictante' ) !== true) {
            $template = str_ireplace ( '$divSolicictante', $aprobador['orc_division'], $template );
        }
        if (strpos ( $template, '$linkEv' ) !== true) {
						$host= 'http://'.$_SERVER['HTTP_HOST'].'/';
            $link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Eventos%26action%3DDetailView%26record%3D'.$id;
            $template = str_ireplace ( '$linkEv', $link , $template );
        }
        $arraytemplate = array (
                'subject' => $subject." ".$aprobador["embarque"],
                'template' => $template
        );
        return $arraytemplate;
    }
  function correoAprobador($template, $subject, $aprobador, $data, $accion, $id)
	{
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
        if (strpos ( $template, '$embarque' ) !== true) {
            $template = str_ireplace ( '$embarque', $data[0], $template );
        }
        if (strpos ( $template, '$ordenCompra' ) !== true) {
            $template = str_ireplace ( '$ordenCompra', $data[1], $template );
        }
        if (strpos ( $template, '$evento' ) !== true) {
            $template = str_ireplace ( '$evento', $data[2], $template );
        }
        if (strpos ( $template, '$problema' ) !== true) {
            $template = str_ireplace ( '$problema', $data[3], $template );
        }
				if (strpos ( $template, '$divSolicictante' ) !== true) {
            $template = str_ireplace ( '$divSolicictante', $aprobador['orc_division'], $template );
        }
        if (strpos ( $template, '$link' ) !== true) {
            $host= 'http://'.$_SERVER['HTTP_HOST'].'/';
            $link = 'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Eventos%26action%3DDetailView%26record%3D'.$id;
            $template = str_ireplace ( '$link', $link , $template );
        }
        $arraytemplate = array (
                'subject' => $subject." ".$data[0],
                'template' => $template
        );
        return $arraytemplate;
    }
 ?>
