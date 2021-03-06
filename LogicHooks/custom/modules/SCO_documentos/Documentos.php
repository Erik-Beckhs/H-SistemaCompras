<?php 
/**
*Esta clase realiza operaciones matemÃƒÂ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_documentos
*/
class Documentos 
{
  function Fndocs($bean, $event, $arguments) 
  {       
    if (!isset($bean->ignore_update_c) || $bean->ignore_update_c === false)
        {            
    $bean->name = $bean->filename;        
    //documento cotizacion
    $bean->load_relationship('sco_ordencompra_sco_documentos');
      $relatedBeans = $bean->sco_ordencompra_sco_documentos->getBeans();
      reset($relatedBeans);
      $parentBean = current($relatedBeans);
      $idoc = $parentBean->id;

      $beanoc = BeanFactory::getBean('SCO_OrdenCompra', $idoc);
      $query = "SELECT ap.id as id
      FROM sco_aprobadores as ap
      INNER JOIN sco_ordencompra_sco_aprobadores_c as ocap
      ON ap.id = ocap.sco_ordencompra_sco_aprobadoressco_aprobadores_idb
      WHERE ocap.deleted = 0 AND ocap.sco_ordencompra_sco_aprobadoressco_ordencompra_ida = '".$idoc."'";
      $obj = $bean->db->query($query, true);
      
    if ($bean->doc_tipo == 1){
        $cotiza = explode(".", $bean->filename);
        /*$beanoc->orc_cotizacion = $cotiza[0];*/
        $beanoc->save();
      }
      
    
    global $current_user;
    if($current_user->iddivision_c == '06'){
    
    }else{
    #Descomentar en caso de Aprobacion Manual.
        if($bean->doc_tipo == 2){
          if($beanoc->orc_estado == 6 || $beanoc->orc_estado == 1){
  
          }else{
            $cotiza = explode(".", $bean->filename);
            #$beanoc->orc_cotizacion = $cotiza[0];
              if($beanoc->orc_tipoo == "1"){
                $beanoc->orc_estado = 6;
              }else{
                $beanoc->orc_estado = 1;
              }    
              $fecha_act = date("y-d-m");
              $beanap->apr_fecha = $fecha_act;
            while($row = $bean->db->fetchByAssoc($obj))
            {
               $beanap = BeanFactory::getBean('SCO_Aprobadores', $row['id']);
               $beanap->apr_aprueba = 1;
               $beanap->save();
            }
            $beanoc->save();
          }        
        }
    }

      $bean->ignore_update_c = true;  
      $bean->save();    
        }
  }   
}
 ?>