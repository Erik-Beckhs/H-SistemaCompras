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
    function FnnotificaDespacho($bean,$cantidadItem)
    {
        require_once('include/SugarPHPMailer.php');
        //Se obtinen el estado de la orden de compra
        //$estado = $bean->des_est;
        // estado de despacho en transito
            //En caso de que el estado sea 3 esta solicitando la aprobacion a los aprobadores
            // y se envia una notificacion a los aprobadores
            $this->envianotiEnTransito ( $bean ,$cantidadItem);
    }

    function envianotiEnTransito($bean,$cantidad) {
        $idDespacho = $bean->id;
        $accion = "se puso en transito el despacho";
        // consulta que obtiene los templates del sistema
        $queryTemp = "SELECT id, subject, body_html FROM email_templates
                          WHERE name like 'plantilla_despacho_en_transito' and deleted = 0";
            $result = $GLOBALS['db']->query($queryTemp, true);
            while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
                    $subject = $row2 ['subject'];
                    $template = $row2 ['body_html'];
                }
        // Consulta que obtiene la lista de lo aprobadores
                $query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                        oc.orc_tcinco, oc.id as idOrdenCompra
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
                        //enviamos el correo a la lista de contactos
            while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
            {
                $contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion, $idDespacho , $cantidad );
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
                        // envio de correo al solicitante
            $bean->load_relationship('sco_despachos_sco_ordencompra');
            $relatedBeans = $bean->sco_despachos_sco_ordencompra->getBeans();
            $parentBean = current($relatedBeans);
            $idoc = $parentBean->id;
            $query = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,
                        oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                        oc.orc_tcinco, oc.id as idOrdenCompra
                        from suitecrm.users u
                        inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                        inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                        inner join suitecrm.sco_ordencompra oc on oc.user_id1_c = u.id
                        where oc.id = '$idoc'";
            $resultSolicitante = $GLOBALS['db']->query($query, true);
            while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
            {
                $contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion, $idDespacho , $cantidad);
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
                        // envio de correo al PM
                        //consulta de PM
                        $consultaPM ="SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                                                                    oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                                                                    oc.orc_tcinco
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
                                                    where des.id = '$idDespacho' and des.deleted = 0
                                                    group by u.user_name";
                            $resultSolicitante = $GLOBALS['db']->query($consultaPM, true);
                            if ($GLOBALS['db']->fetchByAssoc($resultSolicitante) != null) {
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
                            else {
                                $queryGerenteProyectos = "SELECT u.user_name,em.email_address, u.first_name,u.last_name,u.title,oc.id as idOrdenCompra,
                                                                    oc.name as ordenCompra, oc.orc_division as division, oc.orc_pronomemp as proveedor,
                                                                    oc.orc_tcinco
                                                    from users u
                                                    inner join suitecrm.email_addr_bean_rel rel on u.id = rel.bean_id
                                                    inner join suitecrm.email_addresses em on em.id = rel.email_address_id
                                                    inner join sco_despachos des
                                                    inner join sco_despachos_sco_ordencompra_c deor on deor.sco_despachos_sco_ordencomprasco_despachos_idb = des.id
                                                    inner join sco_ordencompra oc on oc.id = deor.sco_despachos_sco_ordencomprasco_ordencompra_ida
                                                    where des.id = '$idDespacho' and des.deleted = 0 and
                                                    u.address_city = 'la paz' and u.title = 'Gerente de Proyectos'";
                                $resultSolicitante = $GLOBALS['db']->query($queryGerenteProyectos, true);
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

    }
    function correogeneral($template, $subject, $bean, $aprobador, $accion, $id,$cantidad) {
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
            $template = str_ireplace ( '$observaciones',  $bean->des_observaciones, $template );
        }
        if (strpos ( $template, '$item' ) !== true) {
            $template = str_ireplace ( '$item',  $cantidad, $template );
        }
        if (strpos ( $template, '$incoterm' ) !== true) {
            $template = str_ireplace ( '$incoterm',   $aprobador['orc_tcinco'], $template );
        }

        if (strpos ( $template, '$trackOrder' ) !== true) {
            $query = "select ev.name,ev.eve_tiempoest,ev.eve_fechaplan,ev.eve_fechare,ev.eve_estado
                from sco_eventos ev
                inner join sco_embarque_sco_eventos_c emev on emev.sco_embarque_sco_eventossco_eventos_idb = ev.id
                inner join sco_embarque em on em.id = emev.sco_embarque_sco_eventossco_embarque_ida
                inner join sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_embarque_ida = em.id
                where emde.sco_embarque_sco_despachossco_despachos_idb = '$id'
                group by ev.name
                order by ev.eve_fechaplan";
            $trackOrder = "";
            $resultSolicitante = $GLOBALS['db']->query($query, true);
            $trackOrder .= "<table>
                                <tr style='background-color: #194070; color:#ffff'>
                                    <th>Eventos</th>
                                    <th>Tiempo estimado</th>
                                    <th>Fecha plan</th>
                                    <th>Fecha real</th>
                                    <th>Estado</th>
                                </tr>";
            while ( $row = $GLOBALS['db']->fetchByAssoc($resultSolicitante) )
            {
                $trackOrder.= "<tr style='background-color: #c2ddff'>
                                    <td><strong>".$row["name"]."</strong></td>
                                    <td>".$row["eve_tiempoest"]." dÃ­as</td>
                                    <td>".$row["eve_fechaplan"]."</td>
                                    <td><strong>".$row["eve_fechare"]."</strong></td>
                                    <td>".$row["eve_estado"]."</td>
                                </tr>";
            }
            $trackOrder .= "</table>";
            $template = str_ireplace ( '$trackOrder',   $trackOrder, $template );
        }
        $arraytemplate = array (
                'subject' => $subject." ".$bean->name." de la OC: ".$aprobador['ordenCompra'],
                'template' => $template
        );
        return $arraytemplate;
    }
}

 ?>
