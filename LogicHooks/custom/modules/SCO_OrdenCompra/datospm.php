<?php
/**
*esta clase extrae todo los datos para la integracion con el proceso de Solicitud de adquisiciones MHES
*@author Yilmar Choque <jose.choque.m@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/custom/modules/SCO_Aprobadores
*/

class Cldatospm
{   static $already_ran = false;
    function datosapr($bean, $event, $arguments) {
        if(self::$already_ran == true) return;
        self::$already_ran = true;
    if ($bean->orc_estado == "3")  {  
        $query = "SELECT apr.* FROM suitecrm.sco_ordencompra oc
        inner join sco_ordencompra_sco_aprobadores_c oa on oa.sco_ordencompra_sco_aprobadoressco_ordencompra_ida = oc.id
        inner join sco_aprobadores apr on apr.id = oa.sco_ordencompra_sco_aprobadoressco_aprobadores_idb
        where oc.id = '$bean->id'";
        $results = $GLOBALS['db']->query($query, true);
        $nombre = " ";
        while($row = $GLOBALS['db']->fetchByAssoc($results))
        {
            $nombre .= "hola mundo2".$bean->id;
        }
        $bean->name = $nombre;
        $bean->save();
    }
    }
}

?>