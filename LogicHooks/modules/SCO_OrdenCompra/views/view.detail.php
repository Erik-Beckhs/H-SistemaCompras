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
  #list_subpanel_sco_ordencompra_sco_productos .oddListRowS1{background:red;}
  #whole_subpanel_sco_ordencompra_sco_productoscompras {display:none;}
  #whole_subpanel_sco_productoscotizados_sco_ordencompra {display:none;}

  </style>";
    $htmlpp ='<div id="alertapp"></div>';
    echo $sty.$htmlpp;
    $cantidad_prd = "SELECT SUM(pro_cantidad) as total_cantidad FROM sco_productos_co WHERE pro_idco = '".$this->bean->id."'; ";
    $obj_pro = $GLOBALS['db']->query($cantidad_prd, true);
    $row = $GLOBALS['db']->fetchByAssoc($obj_pro);

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
    if($row_pd['cantidad_pr']!=0){$c = $row_pd['cantidad_pr'];}else{$c = 0;}
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
  #script para cambio Reporte
  echo '
  <script>
    function showreport(num)
    {
      var url1 = "/modules/reportes/ordencompra.php?id='.$this->bean->id.'&ejec='.time().'";
      window.open(url1,"","width=1220,height=650");
    }
    function imprimir()
    {
      var url2 = "/modules/reportes/descargaoc.php?id='.$this->bean->id.'&name='.$this->bean->name.'&nameprov='.$this->bean->orc_nomcorto.'&ejec='.time().'";
      window.open(url2,"","");
    }
  </script>';
    #script para cambio de estados
  echo "
    <script>
    function estado(est){
      var id = '".$this->bean->id."';
      var num = est;
      var respuesta = verificarObs(id,num);
      if ( respuesta == true) {

        $.ajax({
          type: 'get',
          url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ordencompra&id='+id,
          data: {num},
          beforeSend: function(){
            //alert('Procesando los datos');
            $('#btn-estados').css('pointer-events','none');
            $('#btn-estados').css('background','#CCC');
          },
          success: function(data) {
            data = data.replace('$','');
            var desctot = $.parseJSON(data);
                desctot = desctot.split('~');
              if(desctot[0] == 100){
                    if(desctot[1] == 0){
                        if(desctot[2] == desctot[3]){
                            console.log('conexion exitosa, num = ' + desctot);
                            location.reload(true);
                        }else{
                            $('#alertapp').append('<div class=\"alert alert-danger\"><button type=\"button\" style=\" background: transparent !important; color: #000000!important; padding-left: 10px; \" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Verifique los precios totales de <strong>Productos</strong> '+ desctot[3] + ' y <strong>Plan de Pagos</strong> '+ desctot[2] + '</div>');
                            $('#btn-estados').css('pointer-events','visible');
                        }
                    }else{
                        console.log('conexion exitosa, num = ' + desctot);
                        console.log('Error Proyecto');
                        if(desctot[1] == 1){
                          $('#alertapp').append('<div class=\"alert alert-danger\"><button type=\"button\" style=\" background: transparent !important; color: #000000!important; padding-left: 10px; \" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button> <strong>Tiene</strong> ' + desctot[1] + ' Proyecto mal registrado en Productos</div>');
                          $('#btn-estados').css('pointer-events','visible');
                        }else{
                          $('#alertapp').append('<div class=\"alert alert-danger\"><button type=\"button\" style=\" background: transparent !important; color: #000000!important; padding-left: 10px; \" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button> <strong>Tiene</strong> ' + desctot[1] + ' Proyectos mal registrados en Productos</div>');
                          $('#btn-estados').css('pointer-events','visible');
                        }
                    }
                }else{
                  $('#alertapp').append('<div class=\"alert alert-danger\"><button type=\"button\" style=\" background: transparent !important; color: #000000!important; padding-left: 10px; \" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button> <strong>Complete el 100 % del Plan de Pagos</strong>. PP = ' + desctot['0'] + ' %  </div>');
                  $('#btn-estados').css('pointer-events','visible');
                }
            }
          });
      }
      else {
        alert(respuesta);
      }
    }
    function verificarObs(id,estado)
    {
      //var estado = false;
      var Resp = true;
      $.ajax({
          url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=verificarEstado&id='+id,
          type: 'GET',
          data: {estado},
          dataType: 'json',
          success:function(data) {
            Resp = data['r'];
          }
        });
      return Resp;
    }
  </script>";
  echo "
  <script>
    var htmlpro = '';
    htmlpro += '<table id=\"idpro\" class=\"table table-hover\">';
    htmlpro += '<thead>';
    htmlpro += '  <tr>';
      htmlpro += '<th style=\"width: 10%;\">Cod Pro</th>'+
                 '<th style=\"width: 40%;\">Descripcion</th>'+
                 '<th style=\"width: 5%;\">Unidad</th>'+
                 '<th style=\"width: 5%;\">Cantidad</th>'+
                 '<th style=\"width: 5%;\">Prec Uni</th>'+
                 '<th style=\"width: 5%;\">Desc %</th>'+
                 '<th style=\"width: 5%;\">Desc valor</th>'+
                 '<th style=\"width: 5%;\">Sub total</th>'+
                 '<th style=\"width: 10%;\">Proy C/O</th>';
    htmlpro += '  </tr>';
    htmlpro += '</thead>';
    htmlpro += '<tbody id=\"valtr\">';
    htmlpro += '</tbody>';
    htmlpro += '</table>';

    window.onload = function(){
      $(\"#subpanel_title_sco_despachos_sco_ordencompra tr\").append(\"<div style='background: #FFF; color:#000; padding:2px; margin-top:3px; width: 360px; border-radius:0px; margin-right: 600px; border: solid 2px #CCC;'>Productos en despacho = <span class='label label-success'>".$c."</span> / <span class='label label-primary'>".$row['total_cantidad']."</span>, Restantes = <span class='label label-danger'>".$disponible."</span> </div>\");
      var b = [];
      var cont = 0;

      $('#list_subpanel_sco_ordencompra_sco_productos .list  tbody .oddListRowS1').each(function(){
        if(cont == 0){
          var a = $(this).text();
          b = a.split('|');
          cont++;
        }else{cont++;}
      });

      var c = b[0].replace(/['\"]+/g, \"'\");
          c = c.replace(/[*][*]/g,'\"');
          c = c.replace(/',/g,'~');
          c = c.replace('[[','');
          c = c.replace(']]','');
          c = c.replace(/'/g,'');
          c = c.split('],[');
          var d = '';var fila = '';

      var c2 = b[1];
          c2 = c2.split(',');
        //Llenamos los datos de la lista de productos
        for(var i = 0 ; i < c.length;i++){
          d = c[i].split('~');
          var celda = '';
          //Imprimimos los accesos directos a los productos  en etiquetas TD separadas referencia la variable (celda)
          var tamanio = 0;
          if (d.length == 12) {
            tamanio = 3;
          }else{
            tamanio = 4;
          }

          for(var j = 0; j< d.length - tamanio;j++){
            if(d[j] != ''){
              if(j == 0){
                //si el no es un servicio imprimimos el codigo del producto con acceso directo a su vista
                celda += '<td style=\"text-align: justify; color: #1e3c75;\"><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record='+d[9]+'\">'+d[j]+'</a></td>';
              }
              else{
                //en caso de no ser el espacio que contiene el codigo del productoimprimimos el contenido del array
                celda += '<td style=\"text-align: justify; color: #1e3c75;\">'+d[j]+'</td>';
              }
            }
            else{
              celda += '<td style=\"border:solid 1px #FFF;background: #d9534f;  color: #1e3c75;\">'+d[j]+'</td>';
            }
          }
          //imprimimos las cantidades de transito, Resivido y Saldos en etiquetas TD separadas referencia a la variable (fila)

          if(estado_oc == 1 || estado_oc == 6){
            for(k = 0; k <= recibido.length ;k++)

              {
                var b = d[1].replace(/['\"]+/g, '');
                if(String(descripicion[k]).replace(/&quot;/g,'') == b && (cantidadProducto[k]*1) == (d[3]*1)  && d[8] == nomProy[k])
                  {
                    if(saldos[k] == 0){
                      fila += '<tr>'+
                                  '<td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+
                                  '<td style=\"background: #FFF;\" class=\"text-success\">'+cantidad[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" >'+recibido[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" class=\"text-info\">'+saldos[k]+'</td>'+
                              '</tr>';
                    }
                    else{
                      fila += '<tr>'+
                                  '<td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+
                                  '<td style=\"background: #FFF;\" class=\"text-warning\">'+cantidad[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" >'+recibido[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" class=\"text-danger\">'+saldos[k]+'</td>'+
                              '</tr>';
                   }
              }
            }
          }
          else{
             fila += '<tr><td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+'</tr>';
          }
        }

      var htmlpro = '';
      //generamos las cabeceras de as tablas
      htmlpro += '<table id=\"idpro\" class=\"table table-striped\">';
      htmlpro += '<thead>';
      htmlpro += '  <tr style=\"border-bottom: 1px solid #888;\">';
      if(estado_oc == 1 || estado_oc == 6){
        htmlpro +=  '<th style=\"width:1%;\">#</th>'+
                    '<th style=\"width: 10%;\">Cod Pro</th>'+
                    '<th style=\"width: 40%;\">Descripcion</th>'+
                    '<th style=\"width: 7%;\">Unidad</th>'+
                    '<th style=\"width: 7%;\">Cantidad</th>'+
                    '<th style=\"width: 7%;\">Prec Uni</th>'+
                    '<th style=\"width: 7%;\">Desc %</th>'+
                    '<th style=\"width: 7%;\">Desc valor</th>'+
                    '<th style=\"width: 7%;\">Sub total</th>'+
                    '<th style=\"width: 7%;\">Proy C/O</th>'+
                    '<th style=\"width: 7%;\">Transito</th>'+
                    '<th style=\"width: 7%;\">Recibido</th>'+
                    '<th style=\"width: 7%;\">Saldos</th>';
        }else{
            htmlpro +=  '<th style=\"width:1%;\">#</th>'+
                        '<th style=\"width: 10%;\">Cod Pro</th>'+
                        '<th style=\"width: 40%;\">Descripcion</th>'+
                        '<th style=\"width: 7%;\">Unidad</th>'+
                        '<th style=\"width: 7%;\">Cantidad</th>'+
                        '<th style=\"width: 7%;\">Prec Uni</th>'+
                        '<th style=\"width: 7%;\">Desc %</th>'+
                        '<th style=\"width: 7%;\">Desc valor</th>'+
                        '<th style=\"width: 7%;\">Sub total</th>'+
                        '<th style=\"width: 7%;\">Proy C/O</th>';
        }
      htmlpro += '  </tr>';
      htmlpro += '</thead>';
      htmlpro += '<tbody>';
      htmlpro += fila;
      htmlpro += '</tbody>';
      htmlpro += '<tfoot>';
      htmlpro += '<tr>';
      htmlpro += '<td></td><td><b>SubTotal</b></td><td>'+c2[0]+' <b>".$moneda."</b></td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
      htmlpro += '<td></td><td><b>Descuento Valor</b></td><td>'+c2[1]+' </td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
      htmlpro += '<td></td><td><b>Descuento %</b></td><td>'+c2[2]+' %</td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
      htmlpro += '<td></td><td><b>Total</b></td><td>'+c2[3]+' <b>".$moneda."</b></td>';
      htmlpro += '</tr>';
      htmlpro += '</table>';

        $('#list_subpanel_sco_ordencompra_sco_productos #sco_ordencompra_sco_productos_nuevo_button').on('click',function(){
        $('#idpro').fadeOut();
      });
        //$('#list_subpanel_sco_ordencompra_sco_productos .list tbody .oddListRowS1').hide();
        $('#list_subpanel_sco_ordencompra_sco_productos').append(htmlpro);
    };

    var b = [];
    var cont = 0;

      $('#list_subpanel_sco_ordencompra_sco_productos .list tbody .oddListRowS1').each(function(){
        if(cont == 0){
            var a = $(this).text().trim();
            b = a.split('|');
            cont++;
        }else{
          cont++;
        }
      });
      //console.log(b[0]);
        var cantidad = ".$cantidad.";
        var recibido = ".$recibido.";
        var saldos = ".$saldos.";
        var estado_oc = ".$estado.";
        var cantidadProducto = ".$cantidadProducto.";
        var descripicion = ".$descripicion.";
        var nombre = ".$nombre.";
        var nomProy = ".$nomProyecto.";
        var c = b[0].replace(/['\"]+/g, \"'\");

            c = c.replace(/[*][*]/g,'\"');
            c = c.replace(/',/g,'~');
            c = c.replace('[[','');
            c = c.replace(']]','');
            c = c.replace(/'/g,'');
            c = c.split('],[');
        var c2 = b[1];
            c2 = c2.split(',');
      var d = ''; var fila = '';
      for(var i = 0 ; i < c.length;i++){
        d = c[i].split('~');
        var celda = '';
        var tamanio = 0;
        if (d.length == 12) {
          tamanio = 3;
        }else{
          tamanio = 4;
        }
        for(var j = 0; j< d.length - tamanio;j++){
           if(d[j] != ''){
                if(j == 0){
                  celda += '<td style=\"text-align: justify;  color: #1e3c75;\"><a href=\"index.php?module=SCO_ProductosCompras&action=DetailView&record='+d[9]+'\">'+d[j]+'</a></td>';
                }
                else{
                  celda += '<td style=\"text-align: justify;  color: #1e3c75;\">'+d[j]+'</td>';
                }
             }
             else{
               celda += '<td style=\"border:solid 1px #FFF;background: #d9534f;  color: #1e3c75;\">'+d[j]+'</td>';
             }
          }
          if(estado_oc == 1 || estado_oc == 6){
            for(k = 0; k <= recibido.length ;k++)
              {
                debugger;
                var b = d[1].replace(/['\"]+/g, '');
                if(String(descripicion[k]).replace(/&quot;/g,'') == b && (cantidadProducto[k]*1) == (d[3]*1)  && d[8] == nomProy[k])
                  {
                    if(saldos[k] == 0){
                      fila += '<tr>'+
                                  '<td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+
                                  '<td style=\"background: #FFF;\" class=\"text-success\">'+cantidad[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" >'+recibido[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" class=\"text-info\">'+saldos[k]+'</td>'+
                              '</tr>';
                    }
                    else{
                      fila += '<tr>'+
                                  '<td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+
                                  '<td style=\"background: #FFF;\" class=\"text-warning\">'+cantidad[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" >'+recibido[k]+'</td>'+
                                  '<td style=\"background: #FFF;\" class=\"text-danger\">'+saldos[k]+'</td>'+
                              '</tr>';
                   }
                }
            }
          }else{
              fila += '<tr><td width=\"1%\" style=\"text-align: center; background: #FFF;color: #000;\">'+(i+1)+'</td>'+celda+'</tr>';
          }
      }

      var htmlpro = '';

    htmlpro += '<table id=\"idpro\" class=\"table table-striped\">';
    //Generamos los encabezados de la tabla
    htmlpro += '<thead>';
    htmlpro += '  <tr style=\"border-bottom: 1px solid #888;\">';

      if(estado_oc == 1 || estado_oc == 6){
          htmlpro += '<th style=\"width:1%;\">#</th><th style=\"width: 10%;\">Cod Pro</th>'+
                     '<th style=\"width: 40%;\">Descripcion</th><th style=\"width: 7%;\">Unidad</th>'+
                     '<th style=\"width: 7%;\">Cantidad</th><th style=\"width: 7%;\">Prec Uni</th>'+
                     '<th style=\"width: 7%;\">Desc %</th>'+
                     '<th style=\"width: 7%;\">Desc valor</th>'+
                     '<th style=\"width: 7%;\">Sub total</th>'+
                     '<th style=\"width: 7%;\">Proy C/O</th>'+
                     '<th style=\"width: 7%;\">Transito</th>'+
                     '<th style=\"width: 7%;\">Recibido</th>'+
                     '<th style=\"width: 7%;\">Saldos</th>';
      }else{
          htmlpro += '<th style=\"width:1%;\">#</th><th style=\"width: 10%;\">Cod Pro</th>'+
                     '<th style=\"width: 40%;\">Descripcion</th><th style=\"width: 7%;\">Unidad</th>'+
                     '<th style=\"width: 7%;\">Cantidad</th><th style=\"width: 7%;\">Prec Uni</th>'+
                     '<th style=\"width: 7%;\">Desc %</th>'+
                     '<th style=\"width: 7%;\">Desc valor</th>'+
                     '<th style=\"width: 7%;\">Sub total</th>'+
                     '<th style=\"width: 7%;\">Proy C/O</th>';
      }
    htmlpro += '  </tr>';
    htmlpro += '</thead>';
    //comenzamos a generar en contenido de la tabla
    htmlpro += '<tbody>';
      htmlpro += fila;
    htmlpro += '</tbody>';
    // Generamos en total de los productos
    htmlpro += '<tfoot>';
      htmlpro += '<tr>';
        htmlpro += '<td></td><td><b>SubTotal</b></td><td>'+c2[0]+' <b>".$moneda."</b></td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
        htmlpro += '<td></td><td><b>Descuento Valor</b></td><td>'+c2[1]+' </td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
        htmlpro += '<td></td><td><b>Descuento %</b></td><td>'+c2[2]+' %</td>';
      htmlpro += '</tr>';
      htmlpro += '<tr>';
        htmlpro += '<td></td><td><b>Total</b></td><td>'+c2[3]+' <b>".$moneda."</b></td>';
      htmlpro += '</tr>';
      htmlpro += '</tfoot>';
    htmlpro += '</table>';

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
            echo "
            <script>//$('#btn-estados').hide();
            $('#desc_por').prop('disabled', true);
            $('#desc_val').prop('disabled', true);</script>";
      parent::display();
      echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
        <table class="panelContainer" cellspacing="1"><tbody>
        <tr>
            <td width="12.5%" scope="col">
              <b>Estado Actual:</b>
            </td>
            <td>
              <span style="color:green"><b>'.$arr_estado[1].'</b></span>
            </td>
            <td width="12.5%" >
            <!--<input type="button" id="btn-estados" class="btn btn-sm btn-success"onClick="estado(2);" value="'.$arr_estado[2].'">-->
            </td>
            <td width="12.5%" scope="col">
              <b>Reportes:</b>
            </td>
              <td width="37.5%">
              <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
                <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
            </td>
          </tr>
      </tbody></table>
         </div></div></div>';
      #echo $js.$st;
      break;
    case '2':
        echo "<style>#whole_subpanel_sco_despachos_sco_ordencompra{display:none;}</style>";
      parent::display();
      echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
        <table class="panelContainer" cellspacing="1"><tbody>
        <tr>
          <td width="12.5%" scope="col">
          <b>Estado Actual:</b>
        </td>
        <td>
          '.$arr_estado[2].'
        </td>
        <td width="28%" >
        <button id="btn-estados" class="btn btn-success btnCompra" onClick="estado(3);" >'.$arr_estado[3].'</button>
        </td>
          <td width="12.5%" scope="col">
            <b>Reportes:</b>
          </td>
          <td width="37.5%">
            <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
              <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
          </td>
        </tr>
        </tbody></table>
       </div></div></div>';
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
        echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
          <table class="panelContainer" cellspacing="1"><tbody>
          <tr>
            <td width="12.5%" scope="col">
            <b>Estado Actual:</b>
          </td>
          <td>
            <span style="color:#ff0000;">'.$arr_estado[3].'</span>
          </td>
          <td width="26.5%" >
            ';
            global $current_user;
               $id_usuario = $current_user->id;
               if($id_usuario == $this->bean->user_id_c || $id_usuario == '1'){
          echo '<button id="btn-estados" class="btn btn-success btnCompra" style="background:#ff4747" onClick="estado(5);" >Anular Orden</button>';
             }
          echo '
        </td>
        <td width="12.5%" scope="col">
          <b>Reportes:</b>
        </td>
          <td width="37.5%">
          <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
            <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
        </td>
        </tr>
        </tbody></table>
         </div></div></div>';
      echo "<style>#list_subpanel_sco_ordencompra_sco_documentos .sugar_action_button{display:block;}</style>";
        break;
    case '4':
      parent::display();
      echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
        <table class="panelContainer" cellspacing="1"><tbody>
        <tr>
          <td width="12.5%" scope="col">
          <b>Estado Actual:</b>
        </td>
        <td>
          '.$arr_estado[4].'
        </td>
        <td width="12.5%" >
        <input type="button" id="btn-estados" class="btn btn-sm btn-success" onClick="estado(6);" value="'.$arr_estado[6].'">
        </td>
        <td width="12.5%" scope="col">
          <b>Reportes:</b>
        </td>
          <td width="37.5%">
          <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
            <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
        </td>
      </tr>
      </tbody></table>
         </div></div></div>';
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
    echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
      <table class="panelContainer" cellspacing="1"><tbody>
      <tr>
        <td width="12.5%" scope="col">
          <b>Estado Actual:</b>
        </td>
        <td>
          <span style="color:red"><b>'.$arr_estado[5].'</b></span>
        </td>
        <td width="12.5%" >
        <!--<input type="button" id="btn-estados" class="btn btn-sm btn-success" onClick="estado(2);" value="'.$arr_estado[2].'">-->
      </td>
      <td width="12.5%" scope="col">
          <b>Reportes:</b>
        </td>
      <td width="37.5%">
          <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
          <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
        </td>
      </tr>
      </tbody></table>
       </div></div></div>';
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
      echo '<div class="yui-navset detailview_tabs yui-navset-top"><div class="yui-content"><div class="detail view  detail508 expanded">
        <table class="panelContainer" cellspacing="1"><tbody>
        <tr>
          <td width="12.5%" scope="col">
          <b>Estado Actual:</b>
        </td>
        <td>
          <span style="color:#333"><b>'.$arr_estado[6].'</b></span>
        </td>
        <td width="12.5%" >

        </td>
        <td width="12.5%" scope="col">
          <b>Reportes:</b>
        </td>
          <td width="37.5%">
          <a class="btn btn-success btn-sm" style="padding: 2px 5px;" onClick="imprimir();">Descargar</a>
            <a class="btn btn-sm btn-success" style="padding: 2px 5px;" onClick="showreport(Math.random());" value="Ver Reporte">Ver Reporte</a>
        </td>
      </tr>
      </tbody></table>
         </div></div></div>';
      #echo $js.$st;
      break;
    default:
      parent::display();
      break;
    }
  }
}
?>
