<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/modules/SCO_OrdenCompra
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class SCO_OrdenCompraViewDetail extends ViewDetail {

  function SCO_OrdenCompraViewDetail(){
    parent::ViewDetail();
    $this->useForSubpanel = true;
  }

  public function preDisplay(){
      parent::preDisplay();
    }

  function display(){
    global $current_user;    

    echo '<link href="modules/SCO_Consolidacion/css-loader.css?'.time().'" rel="stylesheet" type="text/css" />';
    echo '<script src="modules/SCO_OrdenCompra/viewdetail.js?'.time().'"></script>';
  $sty = "<style>
     .btnCompra {
        /* padding: 10px !important; */
        padding: 0px 10px 0px 10px !important;
        /* font-size: 10px !important; */
    }
    #alertapp{
      position: fixed;
      float: left;
      margin-top: 10px;
      margin-left:35%;
      z-index:1;
    }
    #idpro tbody tr{
      border-bottom: 1px solid #ccc;
      background-color: #f2f2f2;
    }
    .search-form{
      display: none;
    }
    .label{
      font-size: 11.5px;
    }
    .campopersonalizado{
      margin-top:10px;
    }
    #list_subpanel_sco_ordencompra_sco_productos .oddListRowS1{background:red;}
    #whole_subpanel_sco_ordencompra_sco_productoscompras {display:none;}
    #whole_subpanel_sco_productoscotizados_sco_ordencompra {display:none;}

    </style>";
    $htmlpp ='<div id="alertapp"></div> 
              <div id="ventanaModal"></div>
              <div id="ventanaModalPM"></div>
              ';
    echo $sty.$htmlpp;
    if($this->bean->orc_tipoo == '1'){
        echo "<style>
             #whole_subpanel_sco_despachos_sco_ordencompra {display:none;}
           </style>";
      }
    #Consultas SQL para obtecion de informacion de productos
    $cantidad_prd = "SELECT SUM(pro_cantidad) as total_cantidad FROM sco_productos_co WHERE pro_idco = '".$this->bean->id."'; ";
    $obj_pro = $GLOBALS['db']->query($cantidad_prd, true);
    $row = $GLOBALS['db']->fetchByAssoc($obj_pro);

    #Suma total 
    $cantidad_pd = "SELECT SUM(pd.prdes_cantidad) as cantidad_pr
    FROM sco_productosdespachos as pd
    INNER JOIN sco_despachos_sco_productosdespachos_c as dpd
    ON pd.id = dpd.sco_despachos_sco_productosdespachossco_productosdespachos_idb
    INNER JOIN sco_despachos_sco_ordencompra_c as d_oc
    ON d_oc.sco_despachos_sco_ordencomprasco_despachos_idb = dpd.sco_despachos_sco_productosdespachossco_despachos_ida
    INNER JOIN sco_despachos as d
    ON d.id = d_oc.sco_despachos_sco_ordencomprasco_despachos_idb
    WHERE d_oc.sco_despachos_sco_ordencomprasco_ordencompra_ida = '".$this->bean->id."'
    AND pd.deleted = 0
    AND dpd.deleted = 0
    AND d.deleted = 0;";
    $obj_pd = $GLOBALS['db']->query($cantidad_pd, true);
    $row_pd = $GLOBALS['db']->fetchByAssoc($obj_pd);
    
    if($row_pd['cantidad_pr']!=0){
      $c = $row_pd['cantidad_pr'];
    }else{
      $c = 0;
    }
    $disponible = $row['total_cantidad'] - $c;

    $cantidades_p = "SELECT  pro_cantidad,pro_nombre,pro_descripcion,pro_saldos,pro_canttrans,pro_preciounid,pro_cantresivida,pro_nomproyco
    FROM sco_productos_co
    WHERE pro_idco = '".$this->bean->id."' AND deleted = 0 order by pro_saldos";
    $obj_cantidades_p = $GLOBALS['db']->query($cantidades_p, true);
    $arr_cantidades_p = array();
    $arr_recibido_p = array();
    $arr_saldos_p = array();
    $arr_nombre = array();
    $arr_descripcion = array();
    $arr_cantidadProducto = array();
    $arr_nomProy = array();
    while($row_cantidades_p = $GLOBALS['db']->fetchByAssoc($obj_cantidades_p)){
      array_push($arr_cantidades_p, $row_cantidades_p['pro_canttrans']);
      array_push($arr_recibido_p, $row_cantidades_p['pro_cantresivida']);
      array_push($arr_saldos_p, $row_cantidades_p['pro_saldos']);
      array_push($arr_nombre, $row_cantidades_p['pro_nombre']);
      array_push($arr_descripcion, $row_cantidades_p['pro_descripcion']);
      array_push($arr_cantidadProducto, $row_cantidades_p['pro_cantidad']);
      array_push($arr_nomProy, $row_cantidades_p['pro_nomproyco']);
    }
    $cantidad = json_encode($arr_cantidades_p);
    $recibido = json_encode($arr_recibido_p);
    $saldos = json_encode($arr_saldos_p);
    $cantidadProducto = json_encode($arr_cantidadProducto);
    $descripicion = json_encode($arr_descripcion);
    $nombre = json_encode($arr_nombre);
    $nomProyecto = json_encode($arr_nomProy);
    #echo $cantidad;

    echo "<script>$(\"#subpanel_title_sco_despachos_sco_ordencompra tr \").append(\"<div style='background: #FFF; color:#000; padding:2px; margin-top:3px; width: 360px; border-radius:0px; margin-right: 600px; border: solid 2px #CCC;'>Productos en despacho = <span class='label label-success'>".$c."</span> / <span class='label label-primary'>".$row['total_cantidad']."</span>, Restantes = <span class='label label-danger'>".$disponible."</span> </div>\");</script>";

    $estado = $this->bean->orc_estado;
    $arr_estado = array(1 => 'En curso',2=>'Borrador ', 3 =>'Solicitar Aprobacion ', 4 => 'Aprobado ' ,5 => 'Anulado ', 6 =>'Cerrado ');
    $moneda = $this->bean->orc_tcmoneda;
    $st ='<style>
              .gris{color: #ccc;}
              .gris:hover{color: #ccc;}
              .single{display: none;}
              #sugar_action_button, .sugar_action_button{display: none;}
              #whole_subpanel_sco_ordencompra_sco_productos tbody td a {pointer-events: none; cursor: default;}
              #whole_subpanel_sco_ordencompra_sco_contactos tbody td a {pointer-events: none; cursor: default;}
              #whole_subpanel_sco_ordencompra_sco_aprobadores tbody td a {pointer-events: none; cursor: default;}
              #whole_subpanel_sco_ordencompra_sco_plandepagos tbody td a {pointer-events: none; cursor: default;}
              .footable-first-visible{display:none}
              .footable-last-visible{display:none}
          </style>';
  #Obteniendo la relacion del la Orden de compra con Consolidacion de Cotizaciones
  $beanOC = BeanFactory::getBean('SCO_OrdenCompra', $this->bean->id);
  $beanOC->load_relationship('sco_consolidacion_sco_ordencompra');
  $relatedBeans = $beanOC->sco_consolidacion_sco_ordencompra->getBeans();
  reset($relatedBeans);
  $parentBean   = current($relatedBeans);
  #id Consolidacion Cotizaciones
  $idConsolidacion = $parentBean->id;
  $beanCons = BeanFactory::getBean('SCO_Consolidacion', $idConsolidacion);
  $consolidacion   = '<a class="suitepicon suitepicon-action-view"href="index.php?module=SCO_Consolidacion&action=DetailView&record='.$idConsolidacion.'"> '.$beanCons->name.'</a>';          
  #script para cambio Reporte
   if($current_user->iddivision_c == '03'){
    $reporteGerencial = '
      <div class="col-xs-12 col-sm-6 detail-view-row-item">
          <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -20px;">
            <b>Reporte Gerencial:</b>
          </div>        
          <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 40px;">
              <a class="btn btn-success btn-sm" style="padding: 2px 5px;background: #42c5b4 !important;" onClick=descargaReporteGerencialDiv03("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descarga Excel</a>
              <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showReporteGerencialDiv03("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
          </div>                 
      </div>
      <div class="col-xs-12 col-sm-6 detail-view-row-item">     
          <div class="col-sm-4 col-sm-7 campopersonalizado" style="margin-left: 25px;">
             '.$consolidacion.'
          </div> 
      </div>    
    ';
  }else{
    $reporteGerencial = '';
  }

    #script para cambio de estados
  echo "
    <script>
    var nombreOC = '".$this->bean->name."';
    var nombreCortoOC = '".$this->bean->orc_nomcorto."';
    var idOc = '".$this->bean->id."';   
    var divisionSolicitante = '".$this->bean->orc_division."';
  </script>";
  echo "
  <script>
    $('#list_subpanel_sco_ordencompra_sco_productos #sco_ordencompra_sco_productos_nuevo_button').on('click',function(){
    $('#idpro').fadeOut();
    });
    //$('#list_subpanel_sco_ordencompra_sco_productos .list tbody .oddListRowS1').hide();
    $('#list_subpanel_sco_ordencompra_sco_productos').append(htmlpro);
    </script>";

        // echo "<script src=\"modules/SCO_Productos/jquery.bdt.min.js?".time()."\"></script>";
        // echo "<script>$('#idpro').bdt();</script>";
        echo "<style>#list_subpanel_sco_ordencompra_sco_productos .list{display:none;}</style>";
    switch ($estado) {
      case '1':
          echo "<style>#list_subpanel_sco_ordencompra_sco_productos table .clickMenu{display:none;}
                   #list_subpanel_sco_ordencompra_sco_plandepagos table .clickMenu{display:none;}
                   #list_subpanel_sco_ordencompra_sco_contactos table .clickMenu{display:none;}
                   #list_subpanel_sco_ordencompra_sco_aprobadores table .clickMenu{display:none;}
                   #whole_subpanel_sco_ordencompra_sco_plandepagos  tbody td a {pointer-events: none; cursor: default;}
                   #detail_header_action_menu {pointer-events: none; cursor: default;}
                   #whole_subpanel_sco_ordencompra_sco_documentos table .clickMenu{display:none;}
                 </style>";
          /*echo "
            <style>
              #whole_subpanel_sco_ordencompra_sco_documentos tbody td a {pointer-events: none; cursor: default;}
            </style>";*/
            if($divisionUser != '06'){
              echo "<style>
                   #whole_subpanel_sco_ordencompra_sco_documentos table .clickMenu{display:inline;}
                 </style>";
            }
            echo "
            <script>//$('#btn-estados').hide();
            $('#desc_por').prop('disabled', true);
            $('#desc_val').prop('disabled', true);</script>";
      parent::display();
      echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
                Estado Actual:
              </div>          
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                <span style="color:blue"><b>'.$arr_estado[1].'</b></span>
              </div>                          
        </div>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
                <b>Reporte Compra:</b>
              </div>        
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 55px;">
                  <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;"onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar pdf</a>
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
              </div>          
        </div>
        '.$reporteGerencial.'
      </div>
      ';
      #echo $js.$st;
      break;
    case '2':
        echo "<style>#whole_subpanel_sco_despachos_sco_ordencompra{display:none;}</style>";
      parent::display();
      echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
                <b>Estado Actual:</b>
              </div>          
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                <span style="color:#333">
                  <b>'.$arr_estado[2].' </b>                  
                </span>
                <button id="btn-estados" class="btn btn-success btnCompra" onClick=estado(3,idOc); >'.$arr_estado[3].'</button>
              </div>                          
        </div>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
                <b>Reportes:</b>
              </div>        
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 55px;">
                  <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;"onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar pdf</a>
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
              </div>          
        </div>
        '.$reporteGerencial.'
      </div>
      ';
      break;
    case '3':
           echo "<style>#list_subpanel_sco_ordencompra_sco_productos table .clickMenu{display:none;}
                         #list_subpanel_sco_ordencompra_sco_plandepagos table .clickMenu{display:none;}
                         #list_subpanel_sco_ordencompra_sco_contactos table .clickMenu{display:none;}
                         #list_subpanel_sco_ordencompra_sco_aprobadores table .clickMenu{display:none;}
                         #whole_subpanel_sco_ordencompra_sco_plandepagos  tbody td a {pointer-events: none; cursor: default;}
                         #detail_header_action_menu {pointer-events: none; cursor: default;}
                         #whole_subpanel_sco_despachos_sco_ordencompra{display:none;}
                 </style>";
        parent::display();
        echo '
        <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
          <div class="col-xs-12 col-sm-6 detail-view-row-item">
                <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
                  <b>Estado Actual:</b>
                </div>          
                <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                  <span style="color:blue">
                    <b>'.$arr_estado[3].' </b>                  
                  </span>
                  ';
                    global $current_user;
                       $id_usuario = $current_user->id;
                       if($id_usuario == $this->bean->user_id_c || $id_usuario == '1'){
                  echo '<button id="btn-estados" class="btn btn-success btnCompra" style="background:#ff4747" onClick="estado(5,idOc);" >Anular Orden</button>';
                     }
                  echo '
                </div>                          
          </div>
          <div class="col-xs-12 col-sm-6 detail-view-row-item">
                <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
                  <b>Reportes:</b>
                </div>        
                <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 55px;">
                    <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;" onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar pdf</a>
                    <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
                </div>          
          </div>
          '.$reporteGerencial.'
        </div>
       ';
      echo "<style>#list_subpanel_sco_ordencompra_sco_documentos .sugar_action_button{display:block;}</style>";
      if($divisionUser != '06'){
        echo "<style>
             #whole_subpanel_sco_ordencompra_sco_documentos table .clickMenu{display:inline;}
           </style>";
      }
        break;
    case '4':
      parent::display();
      echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
                <b>Estado Actual:</b>
              </div>          
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                <span style="color:#ff0000">
                  <b>'.$arr_estado[4].' </b>                  
                </span>
                <input type="button" id="btn-estados" class="btn btn-sm btn-success" onClick="estado(6);" value="'.$arr_estado[6].'">
              </div>                          
        </div>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
                <b>Reportes:</b>
              </div>        
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 55px;">
                  <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;" onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar pdf</a>
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
              </div>          
        </div>
        '.$reporteGerencial.'
      </div>
     ';
       echo $js.$st."<script>$('#list_subpanel_sco_ordencompra_sco_documentos .sugar_action_button').show();</script>";
      break;
  case '5':
    /*echo "
      <style>
        #whole_subpanel_sco_ordencompra_sco_documentos tbody td a {pointer-events: none; cursor: default;}
      </style>";*/
      echo "
      <script>//$('#btn-estados').hide();
      $('#desc_por').prop('disabled', true);
      $('#desc_val').prop('disabled', true);</script>";
    parent::display();
    echo '
    <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
      <div class="col-xs-12 col-sm-6 detail-view-row-item">
            <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
              <b>Estado Actual:</b>
            </div>          
            <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
              <span style="color:#ff0000">
                <b>'.$arr_estado[5].' </b>                  
              </span>
            </div>                          
      </div>
      <div class="col-xs-12 col-sm-6 detail-view-row-item">
            <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
              <b>Reportes:</b>
            </div>        
            <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar"style="margin-left: 55px;">
                <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;"onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar</a>
                <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
            </div>          
      </div>
      '.$reporteGerencial.'
    </div>
    ';
    echo $js.$st;
    break;
    case '6':
          echo "<style>#list_subpanel_sco_ordencompra_sco_productos table .clickMenu{display:none;}
         #list_subpanel_sco_ordencompra_sco_plandepagos table .clickMenu{display:none;}
         #list_subpanel_sco_ordencompra_sco_contactos table .clickMenu{display:none;}
         #list_subpanel_sco_ordencompra_sco_aprobadores table .clickMenu{display:none;}
         #whole_subpanel_sco_ordencompra_sco_plandepagos  tbody td a {pointer-events: none; cursor: default;}
            #list_subpanel_sco_despachos_sco_ordencompra table .clickMenu{display:none;}
         #detail_header_action_menu {pointer-events: none; cursor: default;}
         #whole_subpanel_sco_ordencompra_sco_documentos table .clickMenu{display:none;}
         </style>";
      /*echo "
        <style>
          #whole_subpanel_sco_ordencompra_sco_documentos tbody td a {pointer-events: none; cursor: default;}
        </style>";*/
        echo "
        <script>//$('#btn-estados').hide();
        $('#desc_por').prop('disabled', true);
        $('#desc_val').prop('disabled', true);</script>";
      parent::display();
      echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label" style="margin-left: -35px;">
                <b>Estado Actual:</b>
              </div>          
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                <span style="color:green">
                  <b>'.$arr_estado[6].' </b>                  
                </span>
              </div>                          
        </div>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label"style="margin-left: -35px;">
                <b>Reportes:</b>
              </div>        
              <div class="col-sm-4 col-sm-7 campopersonalizado" type="varchar" style="margin-left: 55px;">
                  <a class="btn btn-sm" style="padding: 2px 5px;background: #ffc107!important;color:#fff;" onClick=imprimir("'.$this->bean->id.'",nombreOC,nombreCortoOC);>Descargar</a>
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick=showreport("'.$this->bean->id.'"); value="Ver Reporte">Ver Reporte</a>
              </div>          
        </div>
        '.$reporteGerencial.'
      </div>
      ';
      #echo $js.$st;
      if($divisionUser != '06'){
        echo "<style>
             #whole_subpanel_sco_ordencompra_sco_documentos table .clickMenu{display:inline;}
           </style>";
      }
      break;
    default:
      parent::display();
      break;
    }
  }
}
?>
