<?php
/**
*Esta clase realiza el llamado de datos del modulo Users y los guarda en los campos de
*SCO_Contactos.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Embarques
*/
class Notificaciones
{
	function FnnotificaDespacho($bean)
    {
        require_once('include/SugarPHPMailer.php');
        //Se obtinen el estado de la orden de compra
        //$estado = $bean->des_est;
        // estado de despacho en trancito
            //En caso de que el estado sea 3 esta solicitando la aprobacion a los aprobadores
            // y se envia una notificacion a los aprobadores
            $this->envianotiEnTrancito ( $bean );
    }

    function envianotiEnTrancito($bean) {
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
        // Consulta que obtiene la lista de lo contactos
        $query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,oc.id as idOC
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_contactos con on con.user_id_c = u.id
                        inner join suitecrm.sco_ordencompra_sco_contactos_c occo on occo.sco_ordencompra_sco_contactossco_contactos_idb = con.id
                        inner join suitecrm.sco_ordencompra oc on oc.id = occo.sco_ordencompra_sco_contactossco_ordencompra_ida
                        inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                        inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                        where des.id = '$idDespacho' and con.con_rol = 'Logistico' and des.deleted = 0 and oc.deleted = 0
												group by u.user_name ";
            $resultAprobadores = $GLOBALS['db']->query($query, true);
            while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
            {
                $contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion, $idDespacho );
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer ();
                $mail->setMailerForSystem ();
                $mail->From = $defaults['email'];
                $mail->FromName = $defaults['name'];
                $mail->Subject = $contenidoBody ['subject'];
                $mail->isHTML ( true );
                $mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
                $mail->prepForOutbound ();
                $mail->AddAddress ( $assigned_user->email1 );
                //$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
                $mail->Send ();
            }
            $bean->load_relationship('sco_despachos_sco_ordencompra');
            $relatedBeans = $bean->sco_despachos_sco_ordencompra->getBeans();
            $parentBean = current($relatedBeans);
            $idoc = $parentBean->id;
            $query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,oc.id as idOC
											from users u
											inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
											inner join suitecrm.email_addresses em on em.id = rel.email_address_id
											inner join sco_proyectosco_users_c pyu on pyu.sco_proyectosco_usersusers_idb = u.id
											inner join sco_productos_co pro on pro.pro_idproy = pyu.sco_proyectosco_userssco_proyectosco_ida
											inner join sco_ordencompra oc on oc.id = pro.pro_idco
											inner join sco_despachos_sco_ordencompra_c deoc on deoc.sco_despachos_sco_ordencomprasco_ordencompra_ida = pro.pro_idco
											where deoc.sco_despachos_sco_ordencomprasco_despachos_idb = '$idDespacho'
											group by u.user_name";
            $resultSolicitante = $GLOBALS['db']->query($query, true);
            while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
            {
                $contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion, $idDespacho );
                $emailObj = new Email();
                $defaults = $emailObj->getSystemDefaultEmail();
                $mail = new SugarPHPMailer ();
                $mail->setMailerForSystem ();
                $mail->From = $defaults['email'];
                $mail->FromName = $defaults['name'];
                $mail->Subject = $contenidoBody ['subject'];
                $mail->isHTML ( true );
                $mail->Body = $contenidoBody ['template'];//"<h1>hola reynaldo</h1> tu id es $id";//$contenidoBody ['template'];
                $mail->prepForOutbound ();
                $mail->AddAddress ( $assigned_user->email1 );
                //$mail->AddAddress ( 'rkantuta@hansa.com.bo' );
                $mail->Send ();
            }
    }
    function correogeneral($template, $subject, $bean, $aprobador, $accion, $id) {
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
        if (strpos ( $template, '$monto' ) !== true) {
            $template = str_ireplace ( '$monto', $ordenCompra->orc_tototal." ".$ordenCompra->orc_tcmoneda, $template );
        }
        if (strpos ( $template, '$solicitante' ) !== true) {
            $template = str_ireplace ( '$solicitante', $ordenCompra->modified_user_id, $template );
        }
        if (strpos ( $template, '$ordenConpra' ) !== true) {
            $template = str_ireplace ( '$ordenConpra', $ordenCompra->name, $template );
        }
				if (strpos ( $template, '$observaciones' ) !== true) {
            $template = str_ireplace ( '$observaciones', '', $template );
        }
        if (strpos ( $template, '$link' ) !== true) {
            $host= 'http://'.$_SERVER['HTTP_HOST'].'/';
            $link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Despachos%26action%3DDetailView%26record%3D'.$id;
            $template = str_ireplace ( '$link', $link , $template );
        }
				if (strpos ( $template, '$lnkOC' ) !== true) {
            $host= 'http://'.$_SERVER['HTTP_HOST'].'/';
            $link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_OrdenCompra%26action%3DDetailView%26record%3D'.$aprobador['idOC'];
            $template = str_ireplace ( '$lnkOC', $link , $template );
        }
        if (strpos ( $template, '$divSolicictante' ) !== true) {
            $template = str_ireplace ( '$divSolicictante',  $aprobador['division'], $template );
        }
        if (strpos ( $template, '$proveedor' ) !== true) {
            $template = str_ireplace ( '$proveedor',  $aprobador['proveedor'], $template );
        }
        $arraytemplate = array (
                'subject' => $subject,
                'template' => $template
        );
        return $arraytemplate;
    }
}

 ?>
