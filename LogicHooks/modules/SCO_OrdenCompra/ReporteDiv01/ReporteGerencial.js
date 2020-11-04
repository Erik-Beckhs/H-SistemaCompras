var usuarioNombre = $("#usuarioNombre").val();
var usuarioDivision = $("#usuarioDivision").val();
var usuarioAmercado = $("#usuarioAmercado").val();
var usuarioRol = $("#usuarioRol").val();
$("#division").val(usuarioDivision);
$("#aMercado").val(usuarioAmercado);

if(usuarioDivision != 00){      
    $('.am').hide();
    $('.'+usuarioDivision+'').show();
}
$("#division").change(function() {  
    $('.am').show();   
    if ($(this).data('options') === undefined) {    
      $(this).data('options', $('#aMercado option').clone());
    }
    var id = $(this).val();  
    var options = $(this).data('options').filter('.'+id+'');      
    $('#aMercado').html(options);
    $('#aMercado').append('<option value="00" selected="selected">Todo</option>');    
});


$('#aMercado').on('change', function () {
  var aMercado = $("#aMercado").val();
  var division = $('#division').val();
  console.log(division+"--"+aMercado);
  if (division != null) {
    $.ajax({
      type: 'GET',
      url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ReporteGerencial',
      datatype: 'json',
      data: {
        division: division,
        aMercado: aMercado,
        filtro: "familia"
      },
      async: false,
      success: function (e) {
        console.log(e);
        var res = JSON.parse(e);

        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["idfmilia_c"] + '">' + res[i]["idfamilia_c_name"] + '</option>';
        }
        $('#familia').html(html); 
        $('#grupo').html('<option value="">Todo</option>');        
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});
$('#familia').on('change', function () {
  var aMercado = $("#aMercado").val();
  var division = $('#division').val();
  var familia = $('#familia').val();
  console.log(division+"--"+aMercado+"--"+familia);
  if (division != null && aMercado != null ) {
    $.ajax({
      type: 'GET',
      url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ReporteGerencial',
      datatype: 'json',
      data: {
        division: division,
        aMercado: aMercado,
        familia: familia,
        filtro: "grupo"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["idgrupo_c"] + '">' + res[i]["idgrupo_c_name"] + '</option>';
        }
        $('#grupo').html(html);        
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});
$('#grupo').on('change', function () {
  var aMercado = $("#aMercado").val();
  var division = $('#division').val();
  var familia = $('#familia').val();
  var grupo = $('#grupo').val();
  console.log(division+"--"+aMercado+"--"+familia+"--"+grupo);
  if (division != null && aMercado != null && familia != null ) {
    $.ajax({
      type: 'GET',
      url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ReporteGerencial',
      datatype: 'json',
      data: {
        division: division,
        aMercado: aMercado,
        familia: familia,
        grupo: grupo,
        filtro: "subgrupo"
      },
      async: false,
      success: function (e) {
        var res = JSON.parse(e);
        var html = '<option value="">Todo</option>';
        for (var i = 0; i < res.length; i++) {
          html += '<option value="' + res[i]["idsubgrupo_c"] + '">' + res[i]["idsubgrupo_c_name"] + '</option>';
        }
        $('#subgrupo').html(html);        
      },
      error: function (data) {
        console.log('ERROR, No se pudo conectar', data);
      }
    });
  }
});


$("#buscar").on("click", function(event){
    var aMercado = $("#aMercado").val();
    var division = $('#division').val();
    var familia = $('#familia').val();
    var grupo = $('#grupo').val();
    console.log(division,aMercado,familia,grupo);
    obtenerInformacion(division,aMercado,familia,grupo);
});

/*Exporta la tabla en Excel*/
$("#btnExportar").on("click",function(){
    alert("Se exportara en excel");
    //downloadExcel($("#aMercado").val());
    var aMercado = $("#aMercado").val();
    window.open('index.php?to_pdf=true&module=SCO_OrdenCompra&action=ReporteGerencial&'+aMercado+'=&filtro=2','','');
});

function obtenerInformacion(division,aMercado,familia,grupo){
$("#mostrarDatos").html("<div id='cargando' class='loader'></div> ");
  $.ajax({
  type: 'get',
  url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ReporteGerencial',
  data: {
        aMercado:aMercado,
        division:division,
        familia:familia,
        grupo:grupo,      
        filtro:1,
  },
  success: function(data) {
        var productos = $.parseJSON(data);
        $("#cargando").remove();
        console.log(productos);
        var data = [];
        for (var i = 0; i < productos.length; i++) {
            if (productos[i]["prdes_numeracion"] == null) {
                numeracion = i * 1 + 1;
            }else{numeracion = productos[i]["prdes_numeracion"];}
            data[i] = [
                    productos[i]["AreaMercado"],
                    productos[i]["Familia"],
                    productos[i]["Grupo"],
                    productos[i]["SubGrupo"],
                    productos[i]["IdProducto"],
                    productos[i]["CodigoProveedor"],
                    productos[i]["Producto"],
                    productos[i]["PrecioVta"],
                    productos[i]["SaldoStock"],
                    productos[i]["StockRango180"],
                    productos[i]["SalidaAutorizada"],
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    productos[i]["VentaCantidad3AnioAtras"],
                    productos[i]["VentaCantidad2AnioAtras"],
                    productos[i]["VentaCantidad1AnioAtras"],
                    (Math.round(productos[i]["VentaCantidad1AnioAtras"] / 12)),
                    productos[i]["VentaCantidad0AnioAtras"],
                    ''
                    ]
        }
        var mes = 'Ingre. Pedido JUN 20';
        jexcel(document.getElementById('mostrarDatos'), {
            data:data,
            tableOverflow:true,
            columnSorting:true,
            csvHeaders:true,
            search:true,
            tableWidth: '100%',
            tableHeight: '700px',
            //lazyLoading:true,
            //loadingSpin:true,
            //filters: true,
            allowComments:true,
            pagination: 50,
            freezeColumns: 6,
            columns: [
                { 
                    type: 'text', 
                    title:'AreaMercado', 
                    width:20
                },
                { 
                    type: 'text', 
                    title:'Familia', 
                    width:20
                },
                { 
                    type: 'text', 
                    title:'Grupo', 
                    width:20 
                },
                { 
                    type: 'text', 
                    title:'SubGrupo', 
                    width:20 
                },
                { 
                    type: 'text', 
                    title:'CodigoSpa', 
                    width:100 
                },
                { 
                    type: 'text', 
                    title:'Codigo Proveedor', 
                    width:100 
                },
                { 
                    type: 'text', 
                    title:'Descripcion', 
                    width:250 
                },
                { 
                    type: 'text',
                    title:'Precio Vta USD',
                    width:70 
                },
                { 
                    type: 'text',
                    title:'Saldo Stock Disp',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Stock Rango >180 ',
                    width:50 
                },
                { 
                    type: 'text',
                    title:'SA',
                    width:50
                },
                { 
                    type: 'text',
                    title:mes,
                    width:60 
                },
                { 
                    type: 'text',
                    title:'Total Disp JUN 20',
                    width:60 
                },
                { 
                    type: 'text',
                    title:'Ingre. Pedido JUL 20',
                    width:60 
                },
                { 
                    type: 'text',
                    title:'Pedidos Confirm Fabrica',
                    width:60 
                },
                { 
                    type: 'text',
                    title:'Pend. Por Enviar',
                    width:60 
                },
                { 
                    type: 'text',
                    title:'Venta Total 2017',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Venta Total 2018',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Venta Total 2019',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Venta Mensual Promed 2019',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Venta Total 2020',
                    width:80 
                },
                { 
                    type: 'text',
                    title:'Pedido Sugerido',
                    width:80 
                },
             ],
             text:{
                noRecordsFound: 'No se encontraron resultados',
                showingPage: 'Pagina {0} de {1}',
                show: 'Mostrar ',
                search: 'Buscar',
                entries: ' Entradas',
                columnName: 'Nombre Columna',
                insertANewColumnBefore: 'Insertar una nueva columna antes',
                insertANewColumnAfter: 'Insert a new column after',
                deleteSelectedColumns: 'Delete selected columns',
                renameThisColumn: 'Rename this column',
                orderAscending: 'Order ascending',
                orderDescending: 'Order descending',
                insertANewRowBefore: 'Insert a new row before',
                insertANewRowAfter: 'Insert a new row after',
                deleteSelectedRows: 'Delete selected rows',
                editComments: 'Edit comments',
                addComments: 'Add comments',
                comments: 'Comments',
                clearComments: 'Clear comments',
                copy: 'Copy...',
                paste: 'Paste...',
                saveAs: 'Save as...',
                about: 'About',
                areYouSureToDeleteTheSelectedRows: 'Are you sure to delete the selected rows?',
                areYouSureToDeleteTheSelectedColumns: 'Are you sure to delete the selected columns?',
                thisActionWillDestroyAnyExistingMergedCellsAreYouSure: 'This action will destroy any existing merged cells. Are you sure?',
                thisActionWillClearYourSearchResultsAreYouSure: 'This action will clear your search results. Are you sure?',
                thereIsAConflictWithAnotherMergedCell: 'There is a conflict with another merged cell',
                invalidMergeProperties: 'Invalid merged properties',
                cellAlreadyMerged: 'Cell already merged',
                noCellsSelected: 'No cells selected',
            },
        })
     }
    });
}


var options = {
    minDimensions:[10,10],
    tableOverflow:true,
}

var data = [];
//dataJexcel(data);
//$('#spreadsheet').jexcel(options); 
jexcel(document.getElementById('mostrarDatos'), {
    data:data,
    tableOverflow:true,
    columnSorting:true,
    csvHeaders:true,
    search:true,
    tableWidth: '100%',
    //tableHeight: '500px',
    //lazyLoading:true,
    //loadingSpin:true,
    //filters: true,
    allowComments:true,
    pagination: 50,
    freezeColumns: 5,
    columns: [
        { 
            type: 'text', 
            title:'AreaMercado', 
            width:20
        },
        { 
            type: 'text', 
            title:'Familia', 
            width:20
        },
        { 
            type: 'text', 
            title:'Grupo', 
            width:20 
        },
        { 
            type: 'text', 
            title:'SubGrupo', 
            width:20 
        },
        { 
            type: 'text', 
            title:'CodigoSpa', 
            width:100 
        },
        { 
            type: 'text', 
            title:'Codigo Proveedor', 
            width:100 
        },
        { 
            type: 'text', 
            title:'Descripcion', 
            width:250 
        },
        { 
            type: 'text',
            title:'Precio Vta USD',
            width:70 
        },
        { 
            type: 'text',
            title:'Saldo Stock Disp',
            width:80 
        },
        { 
            type: 'text',
            title:'Stock Rango >180 ',
            width:50 
        },
        { 
            type: 'text',
            title:'SA',
            width:50
        },
        { 
            type: 'text',
            title:'Ingre. Pedido JUN 20',
            width:60 
        },
        { 
            type: 'text',
            title:'Total Disp JUN 20',
            width:60 
        },
        { 
            type: 'text',
            title:'Ingre. Pedido JUL 20',
            width:60 
        },
        { 
            type: 'text',
            title:'Pedidos Confirm Fabrica',
            width:60 
        },
        { 
            type: 'text',
            title:'Pend. Por Enviar',
            width:60 
        },
        { 
            type: 'text',
            title:'Venta Total 2017',
            width:80 
        },
        { 
            type: 'text',
            title:'Venta Total 2018',
            width:80 
        },
        { 
            type: 'text',
            title:'Venta Total 2019',
            width:80 
        },
        { 
            type: 'text',
            title:'Venta Mensual Promed 2019',
            width:80 
        },
        { 
            type: 'text',
            title:'Venta Total 2020',
            width:80 
        },
        { 
            type: 'text',
            title:'Pedido Sugerido',
            width:80 
        },
     ],
      text:{
                noRecordsFound: 'No se encontraron resultados',
                showingPage: 'Pagina {0} de {1}',
                show: 'Mostrar ',
                search: 'Buscar',
                entries: ' Entradas',
            },
 })