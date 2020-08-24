<?php
/**
 *Esta clase realiza operaciones matemáticas.
 *
 *@author Limberg Alcon <lalcon@hansa.com.bo>
 *@copyright 2018
 *@license ruta: /var/www/html/include/generic/SugarWidgets/
 */
if (!defined('sugarEntry') || !sugarEntry) {die('Not A Valid Entry Point');
}

require_once ('data/BeanFactory.php');
require_once ('include/entryPoint.php');

class SugarWidgetSubPanelDespachos extends SugarWidgetField {
  function displayHeaderCell($layout_def) {
    return '<a style="font-weight: normal; color:#fff;">Eventos</a>';
  }
  function displayList($layout_def) {
    $html = '<style>
      #Flecha {
          position: absolute;
          width: 0;
          height: 0;
          border-top: 8.5px solid transparent;
          border-right: 8.5px solid #91ff00;
          -webkit-transform: rotate(-85deg);
          -moz-transform: rotate(-85deg);
          -ms-transform: rotate(-85deg);
          transform: rotate(-85deg); }
       #Flecha:after {
          content: "";
          position: absolute;
          border: 0 solid transparent;
          border-top: 5px solid #91ff00;
          border-radius: 20px 0 0 0;
          top: -18px;
          left: -9px;
          width: 12px;
          height: 12px;
          -webkit-transform: rotate(-90deg);
          -moz-transform: rotate(-90deg);
          -ms-transform: rotate(-90deg);
          transform: rotate(-90deg);
        }
       #retraso{
          background:#d9534f;
          width:5px;
          height:5px;
          position:absolute;
          border-radius:50%;
          box-shadow: 0px 10px 10px -10px rgba(0,0,0,0.5);
          margin-top:21px;
          margin-left:21px;}
       #atiempo{
          color:#5cb85c;
          font-size:12px;
          position:absolute;
          border-radius:50%;
          box-shadow: 0px 10px 10px -10px rgba(0,0,0,0.5);
          margin-top: 14px;
          margin-left: 18px;}
      .cuadrado{
          width: 25px;
          height: 25px;
          background:#FFFFFF;
          float: left;
          margin-right: 0px;
          padding: 8px 4px;
          border-radius:30%;
          text-align:center;
          font-size:10px;
          box-shadow: 0 0 1px transparent;
          -webkit-transition-duration: 0.4s;
          transition-duration: 0.4s;
          -webkit-transition-property: transform;
          transition-property: transform;
          z-index:-1;
          margin-left:2px;
          margin-bottom:6px;
          border-top: 0.5px solid rgba(0,0,0,0.12);
          box-shadow: 0 10px 15px -8px rgba(0, 0, 0, 0.4);}
      .cuadrado:hover{
          box-shadow: 0 0 1px transparent;
          -webkit-transform: translateY(-5px);
          transform: translateY(-5px);
          z-index:1000;
          background:rgba(0,0,0,0.5) !important;
          box-shadow: 0 10px 10px -6px rgba(0, 0, 0, 0.7);}
       #riesgo{
          background:#FFCC66;
          padding:1px 2px;
          //border: solid 0.5px;
          color:#FFF;
          font-size:8px;
          position:absolute;
          height:15px;
          width:15px;
          border-radius:50%;
          text-align: center;
          margin-left: -15px;
          margin-left: -4px;
          margin-top: -26px;
          }

       @keyframes alerta {
         from { transform: none; }
         50% { transform: scale(1.4); }
         to { transform: none; } }

       #riesgo:hover{
          background:#807e77;}
       #peligro{
          background:#DD4D2C;
          padding:1px 2px;
          //border: solid 0.5px;
          color:#FFF;
          font-size:8px;
          height:15px;
          width:15px;
          border-radius:50%;
          text-align: center;
          //animation: alerta 1.5s infinite;
          margin-top: -26px;
          margin-left: 11px;
          border-bottom: 0.5px solid rgba(255,255,255,0.4);
          }
       #peligro:hover{
          background:#807e77;}
       #grup_ev{
          margin-top:-22px;
          margin-left: 10px;}
       .navegacion{
          background:#FFF;
          text-align:left;
          position:absolute;
          display:none;
          padding:5px;
          margin-top: 15px;
          z-index:1000;
          width:250px;
          -webkit-transform: perspective(1px) translateZ(0);
          transform: perspective(1px) translateZ(0);
          box-shadow: 0 0 1px transparent;
          -webkit-transition-duration: 0.4s;
          transition-duration: 0.4s;
          -webkit-transition-property: transform;
          transition-property: transform;
          z-index:1000;}
       .cuadrado:hover .navegacion{
          background:rgba(0,0,0,0.6);
          display: block;
          color:#FFF;
          border:solid;
          border-color: rgba(0,0,0, 1)
          transparent;
          border-width: 6px 6px 0 6px;
          box-shadow:0 0 5px #5ddff0;
          -webkit-transform: translateY(5px);
          transform: translateY(5px);}
       .navegacion {
          transition: color 0.8s linear 0.2s;}
       .cuadrado p{
          font-size:12px;}
       .riesg{
          width:250px;
          text-align:left;
          background:#FFF;
          position:absolute;
          display:none;
          padding:5px;
          margin-top: -60px;
          z-index:1000;}
       .cuadrado:hover .riesg{
          background:rgba(0,0,0,0.6);
          display: block;
          color:#FFF;
          border:solid;
          border-color: rgba(0,0,0, 1) transparent;
          border-width: 6px 6px 0 6px;
          box-shadow:0 0 5px #5ddff0;
          -webkit-transform: translateY(0px);
          transform: translateY(0px); }
        .cuadrado:hover #riesgo{
          display:none;
        }
        .cuadrado:hover #peligro{
          display:none;
        }

      </style>';
    $id         = $layout_def['fields']['ID'];
    $beand      = BeanFactory::getBean('SCO_Despachos', $id);
    $origen     = $beand->des_orig;
    $modtrans   = $beand->des_modtra;
    $id_emba    = $beand->sco_embarque_sco_despachossco_embarque_ida;
    $estado_des = $beand->des_est;

    $beanemb    = BeanFactory::getBean('SCO_Embarque', $id_emba);
    $estado_emb = $beanemb->emb_estado;
    if ($id != '') {
      $query2 = "SELECT COUNT(ev.name) as cantidad_eve
          FROM sco_embarque_sco_eventos_c as eev
          INNER JOIN sco_eventos as ev
          ON eev.sco_embarque_sco_eventossco_eventos_idb = ev.id
          WHERE eev.sco_embarque_sco_eventossco_embarque_ida = '".$id_emba."';
";
      $results = $GLOBALS['db']->query($query2, true);
      $count   = 1;
      switch ($estado_des) {
        case '2':
          while ($row2 = $GLOBALS['db']->fetchByAssoc($results)) {
            $diast = $row2['cantidad_eve'];
          }
          $diast;
          $eventos = "SELECT SUBSTRING(ev.name, 1,2) as num,
          ev.name as name_evento,
          eev.sco_embarque_sco_eventossco_eventos_idb,
          ev.eve_fechaplan as fp,
          ev.eve_fechare as fr,
          ev.eve_fechanuevo as fn
          FROM sco_embarque_sco_eventos_c as eev
          INNER JOIN sco_eventos as ev
          ON eev.sco_embarque_sco_eventossco_eventos_idb = ev.id
          WHERE eev.sco_embarque_sco_eventossco_embarque_ida = '" .$id_emba."'
          AND eev.deleted = 0
          AND ev.deleted = 0
          ORDER BY CAST(num AS UNSIGNED) asc" ;
          $res_event = $GLOBALS['db']->query($eventos, true);
          $arr_fp    = array();
          $arr_fr    = array();
          $arr_fn    = array();
          $cont      = 0;
          while ($row_event = $GLOBALS['db']->fetchByAssoc($res_event)) {
            $evento_riesgo = "SELECT COUNT(r.name) as nombre_riesgo
           FROM sco_embarque as e
           INNER JOIN sco_embarque_sco_eventos_c as e_ev
           ON e.id = e_ev.sco_embarque_sco_eventossco_embarque_ida
           INNER JOIN sco_eventos_sco_riesgo_c as ev_ri
           on ev_ri.sco_eventos_sco_riesgosco_eventos_ida = e_ev.sco_embarque_sco_eventossco_eventos_idb
           INNER JOIN sco_riesgo as r
           on r.id = ev_ri.sco_eventos_sco_riesgosco_riesgo_idb
           WHERE  ev_ri.sco_eventos_sco_riesgosco_eventos_ida = '"  .$row_event['sco_embarque_sco_eventossco_eventos_idb']."'
           AND ev_ri.deleted = 0
           AND e.deleted =0
           AND e_ev.deleted = 0
           AND r.deleted = 0;
" ;
            $res_evento_riesgo = $GLOBALS['db']->query($evento_riesgo, true);
            $numero_evento     = $row_event['num'];

            $evento_problema = "SELECT COUNT(pr.name) as nombre_problema
           FROM sco_embarque as e
           INNER JOIN sco_embarque_sco_eventos_c as e_ev
           ON e.id = e_ev.sco_embarque_sco_eventossco_embarque_ida
           INNER JOIN sco_eventos_sco_problema_c as ev_pr
           ON ev_pr.sco_eventos_sco_problemasco_eventos_ida = e_ev.sco_embarque_sco_eventossco_eventos_idb
           INNER JOIN sco_problema as pr
           ON pr.id = ev_pr.sco_eventos_sco_problemasco_problema_idb
           WHERE ev_pr.sco_eventos_sco_problemasco_eventos_ida = '" .$row_event['sco_embarque_sco_eventossco_eventos_idb']."'
           AND e.deleted = 0
           AND ev_pr.deleted = 0
           AND pr.deleted = 0
           AND e_ev.deleted = 0 ;
" ;
            $res_evento_problema = $GLOBALS['db']->query($evento_problema, true);
            $numero_problema     = $row_event['num'];

            $fecha    = date("Y-m-d");
            $fecha    = explode('-', $fecha);
            $fecha_ac = $fecha[0]."-".$fecha[1]."-".$fecha[2];

            array_push($arr_fp, $row_event['fp']);
            array_push($arr_fr, $row_event['fr']);
            array_push($arr_fn, $row_event['fn']);

            if (!empty($row_event['fr'])) {
              //Color gris
              if ($row_event['fr'] > $row_event['fp']) {
                $html .= '<div id="actual" class="cuadrado" style="background:rgba(0,0,0,0.3); cursor: pointer; color:#FFF;" ><div id="retraso"></div>';
              } else {
                $html .= '<div id="actual" class="cuadrado" style="background:rgba(0,0,0,0.3); cursor: pointer; color:#FFF;" ><div id="atiempo">&#10004;</div>';
              }
              //color amarillo
            } elseif ($arr_fr[$cont] == '' && $arr_fn[$cont] == '' && $arr_fr[$cont+1] == '' && $arr_fn[$cont+1] == '' && $arr_fp[$cont-1] == '' && $fecha_ac == $arr_fp[$cont]) {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#FFF; background:#f0ad4e!important; cursor: pointer"><span style="margin-left: 9px;margin-top: 12px;font-size: 12px;position: absolute;"> &#9875;</span>';
            } elseif ($arr_fr[$cont] == '' && $arr_fr[$cont+1] == '' && $arr_fr[$cont-1] != '' && $fecha_ac == $arr_fn[$cont]) {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#FFF; background:#f0ad4e!important; cursor: pointer"><span style="margin-left: 9px;margin-top: 12px;font-size: 12px;position: absolute;"> &#9875;</span>';
              //Color Verder
            } elseif ($arr_fr[$cont] == '' && $arr_fr[$cont+1] == '' && $arr_fr[$cont-1] != '' && $fecha_ac < $arr_fn[$cont]) {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#FFF; background:rgba(92,173,92,1); cursor: pointer"><span style="margin-left: 9px;margin-top: 12px;font-size: 12px;position: absolute;"> &#9875;</span>';
            } elseif ($arr_fp[$cont] > $fecha_ac && $arr_fn[$cont] == '' && $arr_fr[$cont] == '' && $arr_fp[$cont-1] == '') {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#FFF; background:rgba(92,173,92,1); cursor: pointer"><span style="margin-left: 9px;margin-top: 12px;font-size: 12px;position: absolute;"> &#9875;</span>';
              //Color Rojo
            } elseif ($arr_fr[$cont] == '' && $arr_fr[$cont-1] != '' && $arr_fr[$cont+1] == '') {
              $html .= '<div id="actual" class="cuadrado" style="background:#DD4D2C; cursor: pointer; color:#FFF;" ><span style="margin-left: 9px;margin-top: 12px;font-size: 16px;position: absolute;color:red;"> &#9202;</span>';
            } elseif ($arr_fr[$cont] == '' && $arr_fn[$cont] == '' && $arr_fr[$cont+1] == '' && $arr_fn[$cont+1] == '' && $arr_fp[$cont-1] == '' && $fecha_ac > $arr_fp[$cont]) {
              $html .= '<div id="actual" class="cuadrado" style="background: #DD4D2C; cursor: pointer; color:#FFF;"><span style="margin-left: 9px;margin-top: 12px;font-size: 12px;position: absolute;color:red;"> &#9202;</span>';
              //Color Blanco
            } elseif ($arr_fr[$cont] == '' && $arr_fr[$cont+1] == '' && $arr_fr[$cont-1] == '' && $arr_fn[$cont-1] != '') {
              $html .= '<div id="actual" class="cuadrado" style="background:#FFF; cursor: pointer; color:#000;" >';
            } elseif ($arr_fr[$cont] == '' && $arr_fn[$cont] == '') {
              $html .= '<div id="actual" class="cuadrado"style="color:#000!important;background:rgba(255,255,255,1); cursor: pointer; color:#FFF;" >';
            } elseif ($fecha_ac == $arr_fn[$cont]) {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#FFF; background:rgba(92,173,92,1); cursor: pointer"><span style="margin-left: 9px;margin-top: 12px;font-size: 17px;position: absolute;"> &#9875;</span>';
            } elseif ($row_event['fn'] > $fecha_ac) {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#000 !important; background:rgba(255,255,255,1);cursor: pointer; color:#FFF;" >';
            } else {
              $html .= '<div id="hito-'.$i.'" class="cuadrado" style="color:#000 !important; background:rgba(255,255,255,1); cursor: pointer" >';
            }

            $html .= '<div id="tol" onclick=\'hitos("'.$row_event['sco_embarque_sco_eventossco_eventos_idb'].'","'.$row_event['name_evento'].'")\'>'.$numero_evento.' </div>
           <div class="navegacion">
                <h4 style="color:#FFF;">' .$row_event['name_evento'].'</h4>
                <p>Fecha plan: '  .$row_event['fp'].'</p>
                <p>Fecha real: '  .$row_event['fr'].'</p>
                <p>Fecha nueva: ' .$row_event['fn'].'</p>
           </div>
           '  ;
            while ($row_evento_riesgo = $GLOBALS['db']->fetchByAssoc($res_evento_riesgo)) {
              $nombre_riesgo = $row_evento_riesgo['nombre_riesgo']." ";
            }
            while ($row_evento_problema = $GLOBALS['db']->fetchByAssoc($res_evento_problema)) {
              $nombre_problema = $row_evento_problema['nombre_problema']." ";
            }
            if ($nombre_riesgo != 0 or $nombre_problema != 0) {
              if ($nombre_riesgo != 0) {
                $html .= '<div id="riesgo" style="cursor: pointer; " onclick="riesgo()">R</div>';
              }

              if ($nombre_problema != 0) {
                $html .= '<div id="peligro" style="cursor: pointer; " onclick="problema()">P</div>';
              }

              $html .= '<div class="riesg"><p>Riesgos: '.$nombre_riesgo.', Problemas: '.$nombre_problema.'</p></div>';
            }
            $html .= '</div>';
            $cont++;
          }
          $html .= '<script>
          function hitos(id, evento){
               //alert(id);
               window.location="index.php?module=SCO_Eventos&action=DetailView&record="+id;
               /*if(confirm("¿Ir al evento "+evento+"?")){

               }else{}*/
          }
          $("[data-toggle=\'tooltip\']").tooltip();

          function riesgo(){
               alert("riesgo");
          }
          function problema(){
               alert("problema");
          }
          </script>
          ' ;
          return $html;
          break;
        case '1':
          $html = '<div class="alert-warning" style="border-radius: 5px;padding:5px 10px; width:177px;">Embarque Solicitado <span style="position:absolute;font-size:20px;margin-top:-5.3px;margin-left: 8px;"> &#9872;</span></div>';
          return $html;
          break;
        case '3':
          $html = '<div class="alert-success" style="border-radius: 5px;padding:5px 10px; width:152px;">Embarque Cerrado <span style="position:absolute;font-size:20px;margin-top:-5.3px;margin-left: 8px;">&#9875;</span></div>';
          return $html;
          break;
        case '4':
          $html = '<div class="alert-danger" style="background:#d9534f; color:#FFF;border-radius: 5px;padding:5px 10px; width:152px;">Despacho Anulado <span style="position:absolute;font-size:20px;margin-top:-5.3px;margin-left: 8px;"> &#x02672;</span></div>';
          return $html;
          break;
        default:
          $html = '<div class="alert-danger" style="border-radius: 5px;padding:5px 10px; width:118px;">Sin Embarcar <span style="position:absolute;font-size:20px;margin-top:-5.3px;margin-left: 8px;">&#x026A0;</span></div>';
          return $html;
          break;
      }
    }
  }
}
?>
