<?php
/**
*Esta clase realiza el poblado en la orden de compra en la variable auxiliar,  
*suma de los porcentajes guardando en el campo orc_aux1
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_PLanPagos
*/
class PlanPagos2 
{

  function Fndatospp2($bean, $event, $arguments)
  {

    $id = $bean->id;    
    $bean->load_relationship('sco_ordencompra_sco_plandepagos');
    $relatedBeans = $bean->sco_ordencompra_sco_plandepagos->getBeans();
    reset($relatedBeans);
    $parentBean = current($relatedBeans);
    $id_oc = $parentBean->id;

    $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $id_oc);

    $querypp = "SELECT SUM(ppg_porc) as sumapor
    FROM sco_plandepagos as pp
    INNER JOIN sco_ordencompra_sco_plandepagos_c as rpp
    ON pp.id = rpp.sco_ordencompra_sco_plandepagossco_plandepagos_idb
    WHERE pp.deleted = 0 AND rpp.deleted = 0
    AND sco_ordencompra_sco_plandepagossco_ordencompra_ida = '".$id_oc."';";
    $objpp = $bean->db->query($querypp, true);    
    $rowpp = $bean->db->fetchByAssoc($objpp);
    
    $beanoc->orc_aux1 = $rowpp['sumapor'];                                                
    $beanoc->save(); 
  }
}
?>