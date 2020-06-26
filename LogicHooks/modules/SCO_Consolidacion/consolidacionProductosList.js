// Filtro inicial
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