<script language="javaScript" type="text/javascript" src="modules/SCO_Despachos/paginado.js"></script>
<!--div style="margin-top: 10px;">
  <div class="yui-content">
    <div class="detail view  detail508 expanded">
      <table class="panelContainer" cellspacing="1"><tbody>
        <tr>
          <td width="12.5%" scope="col"></td>
          <td></td>
          <td width="37%" ></td>
          <td width="12.5%" scope="col">
            Dividir despacho
          </td>
          <td width="37.5%">
            <button class="btn btn-success btn-xs" onClick="mostrarModal()">Dividir</button>
          </td>
        </tr>
      </tbody></table>
    </div>
  </div>
</div-->

<div class="row detail-view-row" style="background:#FFF;margin-top:-15px;">
    <div class="col-xs-12 col-sm-6 detail-view-row-item">     
    </div>      
    <div class="col-xs-12 col-sm-6 detail-view-row-item">
          <div class="col-xs-12 col-sm-4 label col-1-label">
          Dividir despacho
          </div>        
          <div class="col-sm-7 detail-view-field " type="varchar">
            <span class="sugar_field">
              <button class="btn btn-success btn-xs" style="padding: 2px 5px;background: #42c5b4 !important;" onClick="mostrarModal()">Dividir Despacho</button>
            </span>
          </div>          
    </div>
  </div>   

<script>
var despacho = '<?php echo $this->bean->id; ?>' ;
var primera = 0;
var misDatos = new Array();

var idOC = '';
function datos(dato1,dato2,dato3,dato4,dato5,dato6,dato7){
 this.dato1 = dato1;
 this.dato2 = dato2;
 this.dato3 = dato3;
 this.dato4 = dato4;
 this.dato5 = dato5;
 this.dato6 = dato6;
 this.dato7 = dato7;
}
var encabeza = new Array("Producto","Descripcion","Proyecto","Cantidades","Agregar");
function mostrarModal()
{
  $("#modalDivir").modal("show");
  $.ajax({
    type: "POST",
    url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
    data: {despacho},
    dataType:"json",
    success: function(data) {
      var html = '';
      for (var i = 0; i < data.length; i++) {
        misDatos[i] = new datos(data[i]["name"]  ,data[i]["prdes_descripcion"]  ,data[i]["prdes_cantidad"]   ,data[i]["idPro"],data[i]["prdes_idproductos_co"] ,data[i]["idDespacho"] ,data[i]["proyecto"]  );
        $("#idOrdenCompra").val(data[i]["idOc"]);
        /*html += "<tr id='"+i+"'>"+
                  "<td>"+data[i]["name"]+"</td>"+
                  "<td>"+data[i]["prdes_descripcion"]+"</td>"+
                  "<td><div col-lg-12>"+
                      "<div class='col-lg-6'><input class='form-control' id='"+i+"saldo' type='number' name='' value='"+data[i]["prdes_cantidad"]+"' size='10' readonly></div>"+
                      "<div class='col-lg-6'><input class='form-control' id='"+i+"nuevoTotal' type='number' onkeyup='recalcular("+i+")' min='0' max='"+data[i]["prdes_cantidad"]+"' size='10'></div>"+
                      "<input id='"+i+"original' type='hidden' name='' value='"+data[i]["prdes_cantidad"]+"'>"+
                      "<input id='"+i+"idPro' type='hidden' name='' value='"+data[i]["idPro"]+"'>"+
                  "<div></td>"+
                  "<td><button type='button' class='btn btn-defaul btn-xs' onClick='moverItem("+i+")'>(+)</button></td>"+
                "</tr>";*/
      }
      //$("#listaProductosDespacho").empty();
      //$("#listaProductosDespacho").html(html);
      pinta(20,0);
    }
  });
}
function crearDespacho() {
  var formdata = new FormData($("#formNuevoDespacho")[0]);
  $.ajax({
    url:'index.php?to_pdf=true&module=SCO_Despachos&action=crearNuevoDespachoDividido',
    type:'POST',
    data: formdata,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function (data) {
      if (data[0] != 'error') {
        //location.href = data[0];
        //location.replace(data[0]);
        location.reload();
        //alert(data[0]);
      }
      else {
        alert('No se pueden crear despachos vacios!!');
      }
    }
  });
}
// esta funcion se ejecuta un segundo despues de que la pagina termina de cargarse
function mostrarOrigenes (){
    //en caso de no visualizar como sub panel se activa un ajax que obtiene los origenes y los muestra
    var origen = $('#des_orig').val();
    $.ajax({
    type: 'get',
    url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=3',
    data: {origen},
    dataType: 'json',
    success: function(data) {
              if(data){
                for(var i = 0; i < data.length; i++){
                  $("#des_orig").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                }
              }

          }
        });
      //en cuanto el select origen cambia de estado el modtrans cambia a los modos que le pertenece al origen
      $('#des_orig').change(function(){
      var origen = $('#des_orig').val();
      $.ajax({
      type: 'get',
      url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=1',
      data: {origen},
      success: function(data) {
      var modtrans = $.parseJSON(data);
      modtrans = modtrans.split('|');
      $('#des_modtra').find('option').remove().end();
      $("#des_modtra").append("<option value='0'>Selecione MT</option>");
                if(modtrans){
                  for(var i = 0; i < modtrans.length-1; i++){
                    $("#des_modtra").append("<option value='"+modtrans[i]+"'>"+modtrans[i]+"</option>");
                  }
                  $('#ndes_diasLlegada').val('');
              }
          }
        });
    });
    //si el modo de transporte cambia los dias trancitos tambien se recalculan
    $('#des_modtra').change(function(){
      var origen = $('#des_orig').val();
      var modtrans = $('#des_modtra').val();
      $.ajax({
      type: 'get',
      url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=2',
      data: {origen,modtrans},
      success: function(data) {
          var modtrans = $.parseJSON(data);
          $('#ndes_diasLlegada').val(modtrans);
          //alert(modtrans);
        }
        });
    });
}
function mostrarModTransporte()
{
  var origen = $('#des_orig').val();
  $.ajax({
  type: 'get',
  url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=1',
  data: {origen},
  success: function(data) {
  var modtrans = $.parseJSON(data);
  modtrans = modtrans.split('|');
  $('#des_modtra').find('option').remove().end();
        //$("#des_modtra").append("<option value='0'>Selecione MT</option>");
            if(modtrans){
              var mod = $("#des_modtra").val();
              $("#des_modtra").append("<option value='0'>Seleccione MT</option>");
              for(var i = 0; i < modtrans.length-1; i++){
                if (mod != modtrans[i]) {
                  $("#des_modtra").append("<option value='"+modtrans[i]+"'>"+modtrans[i]+"</option>");
                }
              }
          }
      }
    });
}
setTimeout('mostrarOrigenes()',500);
setTimeout('mostrarModTransporte()',600);

function agregarTotalesDespacho() {
  $.ajax({
    type: "POST",
    url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
    data: {despacho},
    dataType:"json",
    success: function(data) {
      var totalProductos = 0;
      var totalPunitarios = 0;
      var html = '';
      for (var i = 0; i < data.length; i++) {
        totalProductos = (totalProductos * 1) + (data[i]["prdes_cantidad"] * 1);
        totalPunitarios = (totalPunitarios * 1) + (data[i]["punitario"] * 1);
      }
      imprimirTotales(totalProductos,totalPunitarios);
    }
  });
}
function imprimirTotales(totalPro,totalCosto) {
  var htmlTfoot = '<div style="background-color:#F5F5F5;padding:1%"><center><span class="label label-success" style="color:#fff"><b>Cantidad productos:</b> '+totalPro.toFixed(2)+'</span> <span class="label label-info" style="color:#fff"><b>Monto total:</b> '+totalCosto.toFixed(2)+'</center></div>';
  $("#list_subpanel_sco_despachos_sco_productosdespachos .list ").after(htmlTfoot);
}
agregarTotalesDespacho();
//$("#list_subpanel_sco_despachos_sco_productosdespachos .list ").append(htmlTfoot);
</script>
<style>
div#paginacion {padding-left: 30%;}
.pagination li {float: left;padding: 2px;}
.pagination li a {margin-left: 3px;margin-right: 3px;}
#contenido table,#formNuevoDespacho table{width: -webkit-fill-available;width: -moz-available;}
#contenido table tbody tr td {padding: 3px 10px;font-size: 12px;color: #555;}
#contenido td button {border-radius: 50%;height: 25px;width: 25px;color: #fff;background: #2263a5;}
#nuevoDespacho tr td {padding: 3px 10px;font-size: 12px;}
#nuevoDespacho button.btn.btn-xs {background: red;color: #fff;border-radius: 50%;}
#contenido table tbody tr td .form-control {height: 20px !important;width: 70px !important;}
#nuevoDespacho tr td .form-control {height: 20px !important;width: 70px !important;}
</style>
<input type="hidden" id="idOrdenCompra" value="">
<div class="modal fade" id="modalDivir" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" style="width: 95% !important;">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#003C79 !important; color:#ffffff!important;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" style="color:#fff!important;"id="">Dividir Despacho</h4>
      </div>
      <div class="modal-body">
        <div class="col-lg-12 container">
          <div class="col-lg-6 table-responsive">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <center>Productos</center>
                </div>
                <div class="panel-body">
                    <div id="contenido">Paginación en local<br></div>
                    <center><div id="paginacion"></div></center> 
                </div>
            </div> 
          </div>

          <div class="col-lg-6 table-responsive" >
            <div class="panel panel-info">
                <div class="panel-heading">
                    <center>Nuevo despacho</center>
                </div>
                <div class="panel-body">
                    <form id="formNuevoDespacho" method="post" enctype="multipart/form-data">                      
                      <table class="table-bordered ">
                        <thead>
                          <tr>
                            <th>Productos</th>
                            <th>Descripción</th>
                            <th>Cantidades</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="nuevoDespacho">

                        </tbody>
                      </table>
                      
                      </br>
                      
                      <table>
                        <tr>
                          <td>
                            <strong>Origen</strong>
                          </td>
                          <td><select id="des_orig"  name="origen">
                            </select></td>
                        </tr>
                        <tr>
                          <td><strong>Modalidad Transporte</strong></td>
                          <td><select id="des_modtra"  name="mod_trans">
                          </select></td>
                        </tr>
                        <tr>
                          <td><strong>Dias llegada</strong></td>
                          <td><input type="text" placeholder="" name="dias_trans" id="ndes_diasLlegada" readonly></td>
                        </tr>
                      </table>
                    </form>
                </div>
            </div>             
          </div>
        </div>
      </div>
      <br>
      <div class="container">
          <center>
            <button type="button" class="btn  btn-xs" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-default btn-xs" onclick="crearDespacho()">Crear Nuevo despacho en modo borrador</button>
          </center>
      </div>
      <br>
    </div>
  </div>
</div>
