<?php
/**
*Esta clase realiza el llamado de datos del modulo Users y los guarda en los campos de
*SCO_Contactos.
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Eventos
*/
class ClnotificaEv
{

    public function Fnnotifica($bean)
    {
        require_once('include/SugarPHPMailer.php');
        //En caso de que el evento haya concluido se envia un anotificacion a los encargados de almacen
        // y se envia una notificacion a los aprobadores
        $estado = $bean->eve_estado;
        $noti = $bean->notificar;
        if ($estado == "Concluido" && $noti == '1') {
            $this->envianotiEnTrancito ( $bean );
        }
    }
    function envianotiEnTrancito($bean) {
        $idEvento = $bean->id;
        // consulta que obtiene los templates del sistema
        $queryTemp = "SELECT id, subject, body_html FROM email_templates
                          WHERE name like 'plantilla_plantilla_Evento' and deleted = 0";
            $result = $GLOBALS['db']->query($queryTemp, true);
            while ( $row2 = $GLOBALS['db']->fetchByAssoc($result) ) {
                    $subject = $row2 ['subject'];
                    $template = $row2 ['body_html'];
                }
        // Consulta que obtiene la lista de lo aprobadores
        $query = "SELECT oc.id as ordenCompra,oc.name as ordenCompra,emb.name as embarque,us.*,oc.orc_division,emb.emb_origen, emb.emb_modtra,emb.emb_diastran,emb.id as idEmbarque
                    from (select u.user_name,em.email_address, u.first_name,u.last_name,u.title
                                                                            from users u
                                                                            inner join email_addr_bean_rel rel on u.id = rel.bean_id
                                                                            inner join email_addresses em on em.id = rel.email_address_id
                                                                            where u.title = 'Jefe de AlmacÃ©n Nacional') us
                                        inner join sco_ordencompra oc
                    inner join suitecrm.sco_despachos_sco_ordencompra_c desor on desor.sco_despachos_sco_ordencomprasco_ordencompra_ida = oc.id
                    inner join suitecrm.sco_despachos des on des.id = desor.sco_despachos_sco_ordencomprasco_despachos_idb
                    inner join suitecrm.sco_embarque_sco_despachos_c emde on emde.sco_embarque_sco_despachossco_despachos_idb = des.id
                    inner join suitecrm.sco_embarque emb on emb.id = emde.sco_embarque_sco_despachossco_embarque_ida
                    inner join suitecrm.sco_embarque_sco_eventos_c emev on emev.sco_embarque_sco_eventossco_embarque_ida = emb.id
                    inner join suitecrm.sco_eventos ev on ev.id = emev.sco_embarque_sco_eventossco_eventos_idb
                    where ev.id = '$idEvento' and emde.deleted = 0 and  desor.deleted = 0";

        $resultAprobadores = $GLOBALS['db']->query($query, true);
        while ( $row = $GLOBALS['db']->fetchByAssoc($resultAprobadores) )
        {
            $accion = "se concluyo el evento ";
            $contenidoBody = $this->correogeneral ( $template, $subject, $bean, $row, $accion );
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
    function correogeneral($template, $subject, $bean, $aprobador, $accion) {
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
        if (strpos ( $template, '$ordenCompra' ) !== true) {
            $template = str_ireplace ( '$ordenCompra',$aprobador['ordenCompra'], $template );
        }
                if (strpos ( $template, '$origen' ) !== true) {
            $template = str_ireplace ( '$origen', $aprobador['emb_origen'], $template );
        }
        if (strpos ( $template, '$modalidad' ) !== true) {
            $template = str_ireplace ( '$modalidad', $aprobador['emb_modtra'], $template );
        }
        if (strpos ( $template, '$embarque' ) !== true) {
            $template = str_ireplace ( '$embarque', $aprobador['embarque'], $template );
        }
                if (strpos ( $template, 'divSolicictante' ) !== true) {
            $template = str_ireplace ( 'divSolicictante', $aprobador['orc_division'], $template );
        }
                if (strpos ( $template, '$link' ) !== true) {
                    $idEm = $aprobador['idEmbarque'];
                    $host= 'http://'.$_SERVER['HTTP_HOST'].'/';
                    $link = $host.'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSCO_Embarque%26offset%3D1%26stamp%3D1534982374034107000%26return_module%3DSCO_Embarque%26action%3DDetailView%26record%3D'.$idEm;
                    $template = str_ireplace ( '$link', $link , $template );
                }
        $arraytemplate = array (
                'subject' => "El evento ".$bean->name." del embarque".$aprobador['embarque']." ha concluido",
                'template' => $template
        );
        return $arraytemplate;
    }
}

 ?>
