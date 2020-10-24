$("#AreaMercado").on("change", function(){
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());
});

$("#Division").on("change", function(){              
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),'00');
});

function getInformacion(){
  alert('Peticion de getInformacion');
  $.ajax({
  type: 'get',
  url: '/custom/modules/Home/Dashlets/ReporteDiv01/ReporteGerencial.php',
  data: {
      Division: Division,
      AreaMercado: AreaMercado,
      Familia: Familia,
      Grupo:Grupo
  },
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