<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/
class Cldespacho_delte_r
{
    function Fndespacho_delte_r($bean, $event, $arguments)
    {
        //Id de Embarque
        $id_embarque = $bean->id;
        //Id de Despacho
        $id_desp = $arguments['related_id'];
        //Actualizando el estado a 1 y la fecha a vacio del modulo de DESPACHOS
        $bean->retrieve($row['sco_embarque_sco_despachossco_despachos_idb']);
        $bean->db->query("UPDATE sco_despachos SET des_est='1', des_fechaprev = '' WHERE id='".$id_desp."'");
        //se obtiene el despacho
        #$bean_des = BeanFactory::getBean('SCO_Despachos',$id_desp);
        #require_once("custom/modules/SCO_Embarque/NotificaEmbarqueDelet.php");
        #$notificacion = new Notificaciones();
        #$notificacion->FnnotificaDespacho($bean_des);
    }
}

?>
