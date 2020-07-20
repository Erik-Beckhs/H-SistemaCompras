
// Variables globales
var consProducto1 = [];
var consProducto2 = [];
var cantTotal = 0;
var fobTotal = 0;
var proveedores = [];
// Filtro inicial
$(document).ready(function () {
  $.ajax({
    type: 'POST',
    url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
    datatype: 'json',
    data: {
      filtro: "fabricante"
    },
    async: false,
    success: function (e) {
      var res = JSON.parse(e);
      var html = '<option value="">Todo</option>';
      for (var i = 0; i < res.length; i++) {
        html += '<option value="' + res[i]["sco_proveedor_id_c"] + '">' + res[i]["pcv_nombreproveedor"] + '</option>';
      }
      proveedores = res;
      $('#idFabricante').html(html);
    },
    error: function (data) {
      console.log('ERROR, No se pudo conectar', data);
    }
  });
});
// Detector de cambios filtro de fabricante
$("#nroCotizacion").on('keyup', function (event) {
  if (event.keyCode === 13) {
    // buscarPorCotizacion();
    buscar();
  }
});
function buscar() {
  // buscarPorCotizacion();
  buscarcodaio();
  buscarClientes();
  buscarPlazoEntrega();
  mostrarTabla1();
}
function buscarPorCotizacion() {
  var nroCotizacion = $('#nroCotizacion').val();
  if (nroCotizacion != '') {
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
      datatype: 'json',
      data: {
        pcv_numerocotizacion: nroCotizacion,
        filtro: "fabricante"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["sco_proveedor_id_c"] + '">' + res[i]["pcv_nombreproveedor"] + '</option>';
        }
        proveedores = res;
        $('#idFabricante').html(html);
        mostrarTabla1();
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
}
// Detector de filtro de nro cotización 
function buscarcodaio() {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if (nroCotizacion != '') {
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio: pcv_proveedoraio,
        filtro: "cotizacion"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["name"] + '">' + res[i]["pcv_codigoproveedor"] + '</option>';
        }
        $('#codAioProduct').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
}
// Detector de filtro de nro cotización a Cliente
function buscarClientes() {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if (nroCotizacion != '') {
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio: pcv_proveedoraio,
        filtro: "cliente"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["pcv_clienteaio"] + '">' + res[i]["pcv_cliente"] + '</option>';
        }
        $('#idCliente').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
}
// Detector de datos del fabricante en formulario envio de consolidacion
$('#idFabricante').on('change', function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  for (let index = 0; index < proveedores.length; index++) {
    if (proveedores[index]["sco_proveedor_id_c"] == pcv_proveedoraio) {
      var codigoProv = proveedores[index]["sco_proveedor_id_c"];
      var nombreProv = proveedores[index]["pcv_nombreproveedor"];
      $('#proveedor').val(nombreProv);
      $('#proveedor_id').val(codigoProv);
    }
  }
  if (pcv_proveedoraio == '') {
    $('#proveedor').val('');
    $('#proveedor_id').val('');
  }

});
// Detector de filtro de nro cotización a plazo entrega
function buscarPlazoEntrega() {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if (nroCotizacion != '') {
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio: pcv_proveedoraio,
        filtro: "plazo"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["pcv_plzentrega"] + '">' + res[i]["pcv_plzentrega"] + '</option>';
        }
        $('#plazoEntrega').html(html);
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
}
// Detector de filtro de nro cotización a familia
$('#idFabricante').on('change', function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  if (nroCotizacion != '') {
    $.ajax({
      type: 'POST',
      url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
      datatype: 'json',
      data: {
        nroCotizacion: nroCotizacion,
        pcv_proveedoraio: pcv_proveedoraio,
        filtro: "familia"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["pcv_familia"] + '">' + res[i]["pcv_familia"] + '</option>';
        }
        $('#idFamilia').html(html);
        mostrarTabla1();
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});
// Detector de filtro codAioProduct
$('#codAioProduct').on('change', function () {
  mostrarTabla1();
});
// Detector de filtro idCliente
$('#idCliente').on('change', function () {
  mostrarTabla1();
});
// Detector de filtro idFamilia
$('#plazoEntrega').on('change', function () {
  mostrarTabla1();
})
// Detector de filtro idFamilia
$('#idFamilia').on('change', function () {
  mostrarTabla1();
})
// Genera tabla productos sin consolidar
var mostrarTabla1 = function () {
  var pcv_proveedoraio = $("#idFabricante").val();
  var nroCotizacion = $('#nroCotizacion').val();
  var name = $('#codAioProduct').val();
  var pcv_clienteaio = $('#pcv_clienteaio').val();
  var plazoEntrega = $('#plazoEntrega').val();
  var total1 = 0;
  var fob1 = 0;
  $.ajax({
    type: 'POST',
    url: 'index.php?to_pdf=true&module=SCO_ProductosCotizadosVenta&action=CotizacionesList',
    datatype: 'json',
    data: {
      nroCotizacion: nroCotizacion,
      pcv_proveedoraio: pcv_proveedoraio,
      name: name,
      pcv_clienteaio: pcv_clienteaio,
      plazoEntrega: plazoEntrega,
      filtro: "tabla1"
    },
    async: false,
    success: function (e) {
      var res = JSON.parse(e);
      console.log(res);
      
      var jres = [];
      var html = '';
      for (var i = 0; i < res.length; i++) {
        if (buscarItem(res[i]["name"], consProducto2) == false) {
          html += '<tr id="item1_' + res[i]["name"] + '">';
          html += '<td>' + res[i]["pcv_numerocotizacion"] + '</td>';
          html += '<td>' + res[i]["pcv_familia"] + '</td>';
          html += '<td>' + res[i]["pcv_codigoproveedor"] + '</td>';
          html += '<td>' + res[i]["pcv_nombreproveedor"] + '</td>';
          html += '<td>' + res[i]["pcv_descripcion"] + '</td>';
          html += '<td>' + res[i]["pcv_vendedor"] + '</td>';
          html += '<td>' + res[i]["pcv_cliente"] + '</td>';
          html += '<td>' + res[i]["pcv_cantidad"] + '</td>';
          html += '<td>' + Number.parseFloat(res[i]["pcv_preciofob"]).toFixed(2) + '</td>';
          html += '<input type="hidden" id="cantidad_' + res[i]["name"] + '" value="' + res[i]["pcv_cantidadsaldo"] + '" max="' + res[i]["pcv_cantidadsaldo"] + '" min="0" onkeyup="validaSaldoEnlinea(' + res[i]["name"] + ',' + res[i]["pcv_cantidadsaldo"] + ')">';
          html += '<td id="subtotal1_' + res[i]["name"] + '">' + (res[i]["pcv_cantidadsaldo"] * 1 * res[i]["pcv_preciofob"]) + '</td>';
          html += '<td><button class="btn btn-primary btn-xs" onclick="enviarProducto(' + "'" + String(res[i]["name"]).trim() + "'" + ')">+</button></td>';
          html += '</tr>';
          total1 = (total1 * 1) + (res[i]["pcv_cantidad"] * 1);
          fob1 = (fob1 * 1) + (res[i]["pcv_preciofob"] * 1 * res[i]["pcv_cantidadsaldo"]);
          jres.push(res[i]);
        } else if (verificarSaldo(res[i]["name"], consProducto2) != false) {
          var item = verificarSaldo(res[i]["name"], consProducto2);
          html += '<tr id="item1_' + item["name"] + '">';
          html += '<td>' + item["pcv_numerocotizacion"] + '</td>';
          html += '<td>' + item["pcv_familia"] + '</td>';
          html += '<td>' + item["pcv_codigoproveedor"] + '</td>';
          html += '<td>' + item["pcv_nombreproveedor"] + '</td>';
          html += '<td>' + item["pcv_descripcion"] + '</td>';
          html += '<td>' + item["pcv_vendedor"] + '</td>';
          html += '<td>' + item["pcv_cliente"] + '</td>';
          html += '<td>' + item["pcv_cantidad"] + '</td>';
          html += '<td>' + Number.parseFloat(item["pcv_preciofob"]).toFixed(2) + '</td>';
          html += '<input type="hidden" id="cantidad_' + item["name"] + '" value="' + item["pcv_cantidadsaldo"] + '" max="' + item["pcv_cantidadsaldo"] + '" min="0" onkeyup="validaSaldoEnlinea(' + res[i]["name"] + ',' + res[i]["pcv_cantidadsaldo"] + ')">';
          html += '<td id="subtotal1_' + item["name"] + '">' + (item["pcv_cantidadsaldo"] * 1 * item["pcv_preciofob"]) + '</td>';
          html += '<td><button class="btn btn-primary btn-xs" onclick="enviarProducto(' + "'" + String(item["name"]).trim() + "'" + ')">+</button></td>';
          html += '</tr>';
          total1 = (total1 * 1) + (item["pcv_cantidad"] * 1);
          fob1 = (fob1 * 1) + (item["pcv_preciofob"] * 1 * item["pcv_cantidadsaldo"]);
          jres.push(item);
        }

      }
      consProducto1 = jres;
      $('#tabla1').empty();
      $('#tabla1').html(html);
      $('#totalFob1').val(Number.parseFloat(fob1).toFixed(2));
      $('#cantidadTabla1').val(total1);
    },
    error: function (data) {
      console.log('ERROR, No se pudo conectar', data);
    }
  });
}
// Envio de productos de t1 a t2
function enviarProducto(idProducto) {
  // Data producto tabla 1 en variables
  var cantidadItem = $('#cantidad_' + idProducto).val();
  var dataProducto = [];
  var buscarT2 = buscarItem(idProducto, consProducto2);
  if (validarSelectProveedor() == true) {
    // Eliminando item de lista de productos 1
    for (let index = 0; index < consProducto1.length; index++) {
      if (consProducto1[index]["name"] == idProducto) {
        dataProducto = consProducto1[index];
        if (dataProducto["pcv_cantidadconsolidado"] == undefined) { dataProducto["pcv_cantidadconsolidado"] = 0; }
        // Verificar si la cantidad es menor al saldo
        if (cantidadItem < Number(dataProducto["pcv_cantidadsaldo"])) {
          dataProducto["pcv_cantidadconsolidado"] = (dataProducto["pcv_cantidadconsolidado"] * 1) + (cantidadItem * 1);
          dataProducto["pcv_cantidadsaldo"] = (dataProducto["pcv_cantidadsaldo"] * 1) - (cantidadItem * 1);
          $('#cantidad_' + idProducto).val(dataProducto["pcv_cantidadsaldo"]);
          consProducto1[index] = dataProducto;
          if (buscarT2 == true) {
            actualizarItemT2(dataProducto);
          } else if (buscarT2 == false) {
            consProducto2.push(dataProducto);
          }
        }
        // Si la cantidad en igual se elimina el item de la primera tabla
        else if (cantidadItem == Number(dataProducto["pcv_cantidadsaldo"])) {
          dataProducto["pcv_cantidadsaldo"] = 0;
          dataProducto["pcv_cantidadconsolidado"] = (dataProducto["pcv_cantidadconsolidado"] * 1) + (cantidadItem * 1);
          if (buscarT2 == true) {
            consProducto1.splice(index, 1)[0]
            actualizarItemT2(dataProducto);
          } else if (buscarT2 == false) {
            consProducto2.push(consProducto1.splice(index, 1)[0])
          }
          // Solo en caso de que la cantidad sea todo se elimina de la primera tabla
          $('#item1_' + idProducto).remove();
        }
      }
    }
    // Construción del td de tabla 2
    var html = '';
    if (buscarT2 == false) {
      html += '<tr id="item2_' + dataProducto["name"] + '">';
    }
    html += '<td>' + dataProducto["pcv_numerocotizacion"] + '</td>';
    html += '<td>' + dataProducto["pcv_familia"] + '</td>';
    html += '<td>' + dataProducto["pcv_codigoproveedor"] + '</td>';
    html += '<td>' + dataProducto["pcv_nombreproveedor"] + '</td>';
    html += '<td>' + dataProducto["pcv_descripcion"] + '</td>';
    html += '<td>' + dataProducto["pcv_vendedor"] + '</td>';
    html += '<td>' + dataProducto["pcv_cliente"] + '</td>';
    html += '<td>' + Number.parseFloat(dataProducto["pcv_preciofob"]).toFixed(2) + '</td>';
    html += '<td>' + dataProducto["pcv_cantidadconsolidado"] + '</td>';
    html += '<td>' + (dataProducto["pcv_cantidadconsolidado"] * 1 * dataProducto["pcv_preciofob"]).toFixed(2) + '</td>';// sub total
    html += '<td><button class="btn btn-danger btn-xs" onclick="regresarProducto(' + "'" + String(dataProducto["name"]).trim() + "'" + ')">-</button></td>';
    if (buscarT2 == false) {
      html += '</tr>';
      $('#tabla2').append(html);
    }
    if (buscarT2 == true) {
      $('#item2_' + dataProducto["name"]).html(html);
    }
    // Recalculando totales tabla 1
    // FOB
    var totalFob1 = $('#totalFob1').val();
    totalFob1 = (totalFob1 * 1) - (dataProducto["pcv_preciofob"] * 1 * cantidadItem);
    $('#totalFob1').val(Number.parseFloat(totalFob1).toFixed(2));
    
    // Cantidad
    var cantTotal1 = $('#cantidadTabla1').val();
    cantTotal1 = (cantTotal1 * 1) - (cantidadItem * 1);
    $('#cantidadTabla1').val(cantTotal1);
    // Recalculando totales tabla 2
    // FOB
    var totalFob2 = $('#totalFob2').val();
    totalFob2 = (totalFob2 * 1) + (dataProducto["pcv_preciofob"] * 1 * cantidadItem);
    $('#totalFob2').val(Number.parseFloat(totalFob2).toFixed(2));
    $('#precioTotalFob').val(Number.parseFloat(totalFob2).toFixed(2));
    fobTotal = totalFob2;
    // Cantidad
    var cantTotal2 = $('#cantidadTabla2').val();
    cantTotal2 = (cantTotal2 * 1) + (cantidadItem * 1);
    $('#cantidadTabla2').val(cantTotal2);
    $('#cantidadTotal').val(cantTotal2);
    cantTotal = cantTotal2;
    // Recalculando el subtotal de la tabla 1
    $('#subtotal1_' + idProducto).html((dataProducto["pcv_cantidadsaldo"] * 1 * dataProducto["pcv_preciofob"]));
    validarProveedor();
  }
}
// Envio de productos de t2 a t1
function regresarProducto(idProducto) {
  // Data producto tabla 2 en variables
  var dataProducto = [];
  var buscarT1 = buscarItem(idProducto, consProducto1);
  var cantConsolidado = 0;
  // Eliminando item de lista de productos 2
  for (let index = 0; index < consProducto2.length; index++) {
    if (consProducto2[index]["name"] == idProducto) {
      dataProducto = consProducto2[index];
      // Recalculando las cantidades
      cantConsolidado = (dataProducto["pcv_cantidadconsolidado"] * 1);
      dataProducto["pcv_cantidadsaldo"] = (dataProducto["pcv_cantidadsaldo"] * 1) + (dataProducto["pcv_cantidadconsolidado"] * 1);
      dataProducto["pcv_cantidadconsolidado"] = 0;
      // validacion para actualizar o agregar item en la 1ra tabla
      if (buscarT1 == true) {
        consProducto2.splice(index, 1)[0]
        actualizarItemT1(dataProducto);
      } else if (buscarT1 == false) {
        consProducto1.push(consProducto2.splice(index, 1)[0])
      }
    }
  }
  // Construción del td de tabla 2
  var html = '';
  if (buscarT1 == false) {
    html += '<tr id="item1_' + dataProducto["name"] + '">';
  }
  html += '<td>' + dataProducto["pcv_numerocotizacion"] + '</td>';
  html += '<td>' + dataProducto["pcv_familia"] + '</td>';
  html += '<td>' + dataProducto["pcv_codigoproveedor"] + '</td>';
  html += '<td>' + dataProducto["pcv_nombreproveedor"] + '</td>';
  html += '<td>' + dataProducto["pcv_descripcion"] + '</td>';
  html += '<td>' + dataProducto["pcv_vendedor"] + '</td>';
  html += '<td>' + dataProducto["pcv_cliente"] + '</td>';
  html += '<td>' + dataProducto["pcv_cantidad"] + '</td>';
  html += '<td>' + Number.parseFloat(dataProducto["pcv_preciofob"]).toFixed(2) + '</td>';
  html += '<input type="hidden" id="cantidad_' + dataProducto["name"] + '" max="' + dataProducto["pcv_cantidadsaldo"] + '" value="' + dataProducto["pcv_cantidadsaldo"] + '" onkeyup="validaSaldoEnlinea(' + dataProducto["name"] + ',' + dataProducto["pcv_cantidadsaldo"] + ')">';
  html += '<td id="subtotal1_' + dataProducto["name"] + '" >' + (dataProducto["pcv_cantidadsaldo"] * 1 * dataProducto["pcv_preciofob"]) + '</td>';
  html += '<td><button class="btn btn-primary btn-xs" onclick="enviarProducto(' + "'" + String(dataProducto["name"]).trim() + "'" + ')">+</button></td>';
  if (buscarT1 == false) {
    html += '</tr>';
    $('#tabla1').append(html);
  }
  if (buscarT1 == true) {
    $('#item1_' + idProducto).html(html);
  }
  $('#item2_' + idProducto).remove();

  // Recalculando totales tabla 2
  // FOB
  var totalFob1 = $('#totalFob1').val();
  totalFob1 = (totalFob1 * 1) + (cantConsolidado * 1 * dataProducto["pcv_preciofob"]);
  $('#totalFob1').val(Number.parseFloat(totalFob1).toFixed(2));
  // Cantidad
  var cantTotal1 = $('#cantidadTabla1').val();
  cantTotal1 = (cantTotal1 * 1) + (cantConsolidado * 1);
  $('#cantidadTabla1').val(cantTotal1);
  // Recalculando totales tabla 1
  // FOB
  var totalFob2 = $('#totalFob2').val();
  totalFob2 = (totalFob2 * 1) - (cantConsolidado * 1 * dataProducto["pcv_preciofob"]);
  $('#totalFob2').val(Number.parseFloat(totalFob2).toFixed(2));
  fobTotal = totalFob2;
  // Cantidad
  var cantTotal2 = $('#cantidadTabla2').val();
  cantTotal2 = (cantTotal2 * 1) - (cantConsolidado * 1);
  $('#cantidadTabla2').val(cantTotal2);
  cantTotal = cantTotal2;
  validarProveedor()
}
// Envio masivo
function enviarTodo() {
  var array = [];
  for (let index = 0; index < consProducto1.length; index++) {
    array.push(consProducto1[index]);
  }
  for (let index = 0; index < array.length; index++) {
    enviarProducto(array[index]["name"]);
  }
}
// Regreso masivo
function regresarTodo() {
  var array = [];
  for (let index = 0; index < consProducto2.length; index++) {
    array.push(consProducto2[index]);
  }
  for (let index = 0; index < array.length; index++) {
    regresarProducto(array[index]["name"]);

  }
}
// Función para buscar item en el json de las tablas 1 y 2
function buscarItem(idItem, tabla) {
  var encontrado = false;
  for (let index = 0; index < tabla.length; index++) {
    if (tabla[index]["name"] == idItem) {
      encontrado = true;
    }
  }
  return encontrado;
}
// Verificar saldo
function verificarSaldo(idItem, tabla) {
  var item = false;
  for (let index = 0; index < tabla.length; index++) {
    if (tabla[index]["name"] == idItem) {
      if (Number(tabla[index]["pcv_cantidadsaldo"]) > 0) {
        item = tabla[index];
      }
    }
  }
  return item;
}
// Actualizar item en tabla
function actualizarItemT2(item) {
  for (let index = 0; index < consProducto2.length; index++) {
    if (item["id"] == consProducto2[index]["id"]) {
      consProducto2[index] = item;
    }
  }
}
// Actualizar item en tabla
function actualizarItemT1(item) {
  for (let index = 0; index < consProducto1.length; index++) {
    if (item["id"] == consProducto1[index]["id"]) {
      consProducto1[index] = item;
    }
  }
}
// Validar Proveedor
function validarProveedor() {
  if (consProducto2.length > 0) {
    $('#idFabricante option:not(:selected)').attr('disabled', true);
    // $('#nroCotizacion').attr('disabled', true);
  } else {
    $('#idFabricante option:not(:selected)').attr('disabled', false);
    // $('#nroCotizacion').attr('disabled', false);
  }

}
function validaSaldoEnlinea(id, saldo) {
  var cantidad = $('#cantidad_' + id).val();
  if (cantidad > saldo) {
    $('#tituloMensaje').html('Cantidad incorrecta');
    var html = '<h2> ' + cantidad + ' es mayor al saldo ' + saldo + '</h2>';
    $('#mensajeModal').html(html);
    $('#modalMensaje').modal("show");
    $('#cantidad_' + id).val(saldo);
  }
}
function validarSelectProveedor() {
  var validar = true;
  var idProv = $('#proveedor_id').val();  
  if (idProv == '') {
    validar = false;
    $('#tituloMensaje').html('Proveedor no seleccionado');
    var html = '<h2> Es necesario seleccionar un proveedor</h2>';
    $('#mensajeModal').html(html);
    $('#modalMensaje').modal("show");
  }
  return validar;
}
