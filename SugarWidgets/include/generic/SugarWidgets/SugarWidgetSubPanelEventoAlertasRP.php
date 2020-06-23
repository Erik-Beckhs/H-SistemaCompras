<?php 
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/include/generic/SugarWidgets/
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SugarWidgetSubPanelEventoAlertasRP extends SugarWidgetField
{
    function displayHeaderCell($layout_def){
        return '<a style="font-weight: normal;color: #fff;">Alertas</a><span style="margin-left:2px; margin-top: -3px; font-size:16px; position:absolute;">&#128681;</span>';
    }
    function displayList($layout_def)
    {
      //Obteniendo el id del campo subpanel
      $name = $layout_def['fields']['ID'];
      //css, diseño de las alertas riesgo y problema
      $html = '';      
      $html .= '<style>
        #ries_a_prob:hover{
            opacity: 0.8 !important;
        }        
        #riesgo_e{
          background:#FFCC66; 
          padding:3px 4px;           
          color:#FFF; 
          font-size:12px;                    
          height:20px;
          width:40px;
          border-radius:10%;
          text-align: center;          
          box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);}
       #peligro_e{
          background:#DD4D2C;     
          padding:3px 4px;           
          color:#FFF; 
          font-size:12px;
          height:20px;
          width:40px;
          border-radius:10%;
          text-align: center;                          
          border-bottom: 0.5px solid rgba(255,255,255,0.4);
          box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);}
      </style>'; 
      $id = $layout_def['fields']['ID'];         
      //Query, obtenindo la cantidad de riesgos, relacionados al embarque
      $evento_riesgo = "SELECT COUNT(r.name) as nombre_riesgo                 
         FROM sco_embarque as e
         INNER JOIN sco_embarque_sco_eventos_c as e_ev
         ON e.id = e_ev.sco_embarque_sco_eventossco_embarque_ida
         INNER JOIN sco_eventos_sco_riesgo_c as ev_ri
         on ev_ri.sco_eventos_sco_riesgosco_eventos_ida = e_ev.sco_embarque_sco_eventossco_eventos_idb
         INNER JOIN sco_riesgo as r
         on r.id = ev_ri.sco_eventos_sco_riesgosco_riesgo_idb
         WHERE  ev_ri.sco_eventos_sco_riesgosco_eventos_ida = '".$id."'
         AND ev_ri.deleted = 0
         AND e.deleted = 0
         AND e_ev.deleted = 0
         AND r.deleted = 0";
      $res_evento_riesgo = $GLOBALS['db']->query($evento_riesgo, true);                      
      //Query, obteniendo la cantidad de problemas, relacionados con el embarque
      $evento_problema ="SELECT COUNT(pr.name) as nombre_problema
         FROM sco_embarque as e
         INNER JOIN sco_embarque_sco_eventos_c as e_ev
         ON e.id = e_ev.sco_embarque_sco_eventossco_embarque_ida
         INNER JOIN sco_eventos_sco_problema_c as ev_pr
         ON ev_pr.sco_eventos_sco_problemasco_eventos_ida = e_ev.sco_embarque_sco_eventossco_eventos_idb
         INNER JOIN sco_problema as pr
         ON pr.id = ev_pr.sco_eventos_sco_problemasco_problema_idb
         WHERE ev_pr.sco_eventos_sco_problemasco_eventos_ida = '".$id."'
         AND e.deleted = 0
         AND e_ev.deleted = 0
         AND ev_pr.deleted = 0
         AND pr.deleted = 0";
      $res_evento_problema = $GLOBALS['db']->query($evento_problema, true);

      while($row_evento_riesgo = $GLOBALS['db']->fetchByAssoc($res_evento_riesgo)){
            $nombre_riesgo = $row_evento_riesgo['nombre_riesgo']." ";
       }
        while($row_evento_problema = $GLOBALS['db']->fetchByAssoc($res_evento_problema)){
            $nombre_problema = $row_evento_problema['nombre_problema']." ";
       }
      if($nombre_riesgo != 0 or $nombre_problema != 0){
            if($nombre_riesgo != 0)
                 $html .= '<span id="riesgo_e" >Riesgo</span> ';
            if($nombre_problema != 0)
                 $html .= '<span id="peligro_e">Problema</span>';
       }
      return $html;
    }
}

class SugarWidgetSubPanelEventoEstado extends SugarWidgetField
{
    #function displayHeaderCell($layout_def){
      #return '<b>#</b>';
    #}
    function displayList($layout_def)
    {
      $id = $layout_def['fields']['ID'];
      $nombre = $layout_def['fields']['vname'];
      //Obteniendo el estaod del modulo de EVENTOS
      $bean_evento = BeanFactory::getBean('SCO_Eventos', $id);
      $estado = $bean_evento->eve_estado;
      if($estado == 'Pendiente'){
        return '<span class="label label-warning" style="background:#FFCC66; font-size:12px; color:#fff;">'.$estado.'</span>';
      }else{
        return '<span class="label label-success"style="font-size:12px; color:#fff;">'.$estado.'</span><span style="color:rgba(92,173,92,1);"> &#10004;</span>';
      }
    }
}
?>