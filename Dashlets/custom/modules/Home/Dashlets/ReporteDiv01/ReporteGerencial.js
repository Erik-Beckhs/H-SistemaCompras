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
$("#aMercado").on("change", function(){
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());
});

$("#division").on("change", function(){              
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),'00');
});
$("#buscar").on("click", function(event){
    console.log($("#aMercado").val());
    obtenerInformacion($("#aMercado").val());
});

function obtenerInformacion(aMercado){
  debugger;
  $.ajax({
  type: 'get',
  url: '/custom/modules/Home/Dashlets/ReporteDiv01/ReporteGerencial.php',
  data: {
      aMercado: aMercado,
      filtro:1,
  },
  success: function(data) {
      debugger;
      var productos = $.parseJSON(data);
      console.log(productos);
      var data = [];
      for (var i = 0; i < productos.length; i++) {
            if (productos[i]["prdes_numeracion"] == null) {
                numeracion = i * 1 + 1;
            }else{numeracion = productos[i]["prdes_numeracion"];}
            data[i] = [
                    numeracion,
                    productos[i]["name"],
                    productos[i]["prdes_descripcion"],
                    '',
                    productos[i]["prdes_cantidad"],
                    productos[i]["punitario"],
                    (productos[i]["prdes_cantidad"] * 1 * productos[i]["punitario"]).toFixed(2),
                    productos[i]["prdes_idproductocotiazdo"],
                    productos[i]["idPro"],
                    productos[i]["prdes_codaio"]
                    ]
        }
     }
    });
}






var options = {
    minDimensions:[10,10],
    tableOverflow:true,
}

var data = [
    ['1010587', 'MEGAFLOW 100-250 K 2', 'MEGAFLOW 100-250 K 02 A743CF8M', '11467.03', '0', '0', '0', '0','0','0','0','0','0','0','0','0','0'],
    ['1010587', 'MEGAFLOW 100-250 K 2', 'MEGAFLOW 100-250 K 02 A743CF8M', '11467.03', '0', '0', '0', '0','0','0','0','0','0','0','0','0','9'],
    ['1010587', 'MEGAFLOW 100-250 K 2', 'MEGAFLOW 100-250 K 02 A743CF8M', '11467.03', '0', '0', '0', '0','0','0','0','0','0','0','0','0','9'],
    ['1010587', 'MEGAFLOW 100-250 K 2', 'MEGAFLOW 100-250 K 02 A743CF8M', '11467.03', '0', '0', '0', '0','0','0','0','0','0','0','0','0','9']
];

//$('#spreadsheet').jexcel(options); 
jexcel(document.getElementById('spreadsheet'), {
    data:data,
    //tableOverflow:true,
    csvHeaders:true,
    //search:true,
    tableWidth: '500px',
    pagination:20,
    columns: [
        { 
            type: 'text', 
            title:'CodigoSpa', 
            width:100 
        },
        { 
            type: 'text', 
            title:'Codigo Proveedor', 
            width:150 
        },
        { 
            type: 'text', 
            title:'Descripcion', 
            width:200 
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
     ],
     text:{
        noRecordsFound: 'No records found',
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