var jsonProductos = [{"Productos":"123456789"},{"Productos2":"1asd"}];
vistaHtml();
function vistaHtml(){
  var html = '';
  html += '<div class="container-fluid">';
  html += '<div class="row">';
  html += '    <div class="col-sm-3">';
  html += '        <div class="input-group">';
  html += '            <div class="input-group">';
  html += '                <span class="input-group-addon">Fabricante</span>';
  html += '                <select class="form-control filter" id="idFabricante" name="idFabricante">';
  html += '                    <option value="00" selected="selected">Todo</option>';
  html += '                    </select>';
  html += '            </div>';
  html += '        </div>';
  html += '    </div>';
  html += '    <div class="col-sm-3">';
  html += '        <div class="input-group">';
  html += '            <div class="input-group">';
  html += '                <span class="input-group-addon">Nro. cotizaci&oacute;n: </span>';
  html += '                <select class="form-control filter" id="nroCotizacion" name="nroCotizacion">';
  html += '                    <option value="00" selected="selected">Todo</option>';
  html += '                    <div id="nroCotizacionOption"></div>';
  html += '                </select>';
  html += '            </div>';
  html += '        </div>';
  html += '    </div>';
  html += '    <div class="col-sm-3">';
  html += '        <div class="input-group">';
  html += '            <div class="input-group">';
  html += '                <span class="input-group-addon">Código AIO</span>';
  html += '                <select class="form-control filter" id="codAioProduct" name="codAioProduct">';
  html += '                    <option value="00" selected="selected">Todo</option>';
  html += '                    <div id="codAioProductOption"></div>';
  html += '                </select>';
  html += '            </div>';
  html += '        </div>';
  html += '    </div>';
  html += '    <div class="col-sm-3">';
  html += '        <div class="input-group">';
  html += '            <div class="input-group">';
  html += '                <span class="input-group-addon">Cliente</span>';
  html += '                <select class="form-control filter" id="idCliente" name="idCliente">';
  html += '                    <option value="00" selected="selected">Todo</option>';
  html += '                </select>';
  html += '            </div>';
  html += '        </div>';
  html += '    </div>';
  html += '    <div class="col-sm-3">';
  html += '        <div class="input-group">';
  html += '            <div class="input-group">';
  html += '                <span class="input-group-addon">Familia</span>';
  html += '                <select class="form-control filter" id="aMercado" name="aMercado">';
  html += '                    <option value="00" selected="selected">Todo</option>';
  html += '                </select>';
  html += '            </div>';
  html += '        </div>';
  html += '    </div>';
  html += '</div>';
  html += '';
  html += '<div class="row">';
  html += '    <div class=" col-md-6">';
  html += '        <div class="tabla1">';
  html += '        </div>';
  html += '    </div>';
  html += '    <div class="col-md-6">';
  html += '        <div class="tabla2">';
  html += '        </div>';
  html += '    </div>';
  html += '</div>';
  html += '</div>';

  $("#consolidacion").append(html);
}
 
// Filtro inicialz
$( document ).ready(function() {  
  $.ajax({
    type: 'POST',
    url: 'index.php?to_pdf=true&module=SCO_ProductosCotizados&action=CotizacionesList',
    datatype: 'json',
    data: {
      filtro:"fabricanteList"
    },
    async: false,
    success: function (e) {    
      console.log(e);
      var res = JSON.parse(e);
      
      var html = '<option value="00">todo</option>';
      for(var i = 0;i < res.length;i++){
        html += '<option value="'+res[i]["pcv_proveedoraio"]+'">'+res[i]["pcv_nombreproveedor"]+'</option>';
      }
      $('#idFabricante').html(html);
    },
    error: function (data) {
      console.log('ERROR, No se pudo conectar', data);
    }
  });
});
// Detector de cambios filtro de fabricante
$("#idFabricante").on('change', function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  if(pcv_proveedoraio != '00'){
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizados&action=CotizacionesList',
      datatype: 'json',
      data: {
        pcv_proveedoraio: pcv_proveedoraio,
        filtro:"fabricante"
      },
      async: false,
      success: function (e) {
        console.log(e);
        var res = JSON.parse(e);
        var html = '<option value="00">todo</option>';
        for(var i = 0;i < res.length;i++){
          html += '<option value="'+res[i]["pcv_numerocotizacion"]+'">'+res[i]["pcv_numerocotizacion"]+'</option>';
        }
        $('#nroCotizacion').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
})
// Detector de filtro de nro cotización 
$('#nroCotizacion').on('change', function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if(nroCotizacion != '00'){
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizados&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio:pcv_proveedoraio,
        filtro:"cotizacion"
      },
      async: false,
      success: function (e) {
        console.log(e);
        var res = JSON.parse(e);
        var html = '<option value="00">todo</option>';
        for(var i = 0;i < res.length;i++){
          html += '<option value="'+res[i]["name"]+'">'+res[i]["name"]+'</option>';
        }
        $('#codAioProduct').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});
// Detector de filtro de nro cotización 
$('#nroCotizacion').on('change', function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if(nroCotizacion != '00'){
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizados&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio:pcv_proveedoraio,
        filtro:"cliente"
      },
      async: false,
      success: function (e) {
        console.log(e);
        var res = JSON.parse(e);
        var html = '<option value="00">todo</option>';
        for(var i = 0;i < res.length;i++){
          html += '<option value="'+res[i]["pcv_clienteaio"]+'">'+res[i]["pcv_cliente"]+'</option>';
        }
        $('#idCliente').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});