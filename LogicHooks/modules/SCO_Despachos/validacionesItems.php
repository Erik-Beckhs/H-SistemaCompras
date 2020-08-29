<script src="custom/modules/SCO_OrdenCompra/jquery.jexcel.js?"></script>
<link rel="stylesheet" href="custom/modules/SCO_OrdenCompra/jquery.jexcel.css?" type="text/css" />

<!-- MODAL VALIDACIONES -->
<div class="modal fade" id="modalValProd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" style="width: 93% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ordenar productos Despacho</h4>
            </div>
            <div class="modal-body">
                <div id="ventanaModal"></div> 
                <div class="col-lg-12 row">
                    <div class="col-lg-6 table-responsive">                        
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Productos despacho
                            </div>
                            <div class="panel-body">
                                <div class="detail view  detail508 expanded">
                                     <div id="productosDespacho1"></div>
                                </div>  
                            </div>
                        </div>                            
                    </div>
                    <div class="col-lg-6 table-responsive">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Factura
                            </div>
                            <div class="panel-body">
                                <div class="detail view  detail508 expanded">
                                    <div id="productosDespacho2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6 "> 
                    <div class="row">
                       <br>
                      <div class="col-lg-6 text-left">   
                          <p class="pTotalRegistro">Total Registros: <span id="totalRegistroDes1"></span></p>
                      </div>
                      <div class="col-lg-3 text-left">   
                          <p class="pTotalCantidad">Total Cantidad: <span id="totalCantidadDes1"></span></p>
                      </div>
                      <div class="col-lg-3 text-left">   
                          <p class="pTotalPrecio">Total Precio: <span id="totalPrecioDes1"></span></p>
                      </div>
                    </div>
                </div>
                <div class="col-lg-6 ">   
                    <div class="row">
                       <br>
                      <div class="col-lg-6 text-left">   
                          <p class="pTotalRegistro">Total Registros: <span id="totalRegistroDes2"></span></p>
                      </div>
                      <div class="col-lg-3 text-left">   
                          <p class="pTotalCantidad">Total Cantidad: <span id="totalCantidadDes2"></span></p>
                      </div>
                      <div class="col-lg-3 text-left">   
                          <p class="pTotalPrecio">Total Precio: <span id="totalPrecioDes2"></span></p>
                      </div>
                    </div>
                    <div class="row">                     
                      <div class="col-lg-6 text-left"> 
                          <p class="pTotalValidacion2">Registros encontrados: <span id="pTotalValidacion2"></span></p>
                      </div>
                      
                    </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-lg-6 "> 
                  <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>
                </div>  
                <div class="col-lg-6 ">   
                  <button type="button" class="btn btn-sm btn-verde" onclick="validarItems()">Validar</button>
                </div>
              </div>                             
            </div>            
        </div>
    </div>
</div>

<!-- FIN MODAL -->
<style>
    .validar {display: flex;width: 50px;float: right;margin-top: -34.5px;z-index: 1000;position: relative;}
    .encontrado{background-color: greenyellow;}
    .noEncontrado{background-color: red;}
    .modal {margin-top: 3%;}
    .jexcel>tbody>tr>td {font-size: 12px;color:#444;}
    .jexcel>thead>tr>td {font-size: 12px;}
    .jexcel .detail tr td{color:#444;}
    .panel-info>.panel-heading {text-align: center;}
    .jexcel>tbody>tr>td.readonly {color: rgb(7 76 41 / 80%);}
    #modalValProd p{font-size: 12px;}
    #modalValProd button.btn.btn-sm.btn-verde {background: #28a745; color: #fff;width: 100%;}
    #modalValProd .btn-danger{background: #dc3545;width: 100%;border: 1px solid #dc3545;}
</style>
<script>
    var despacho = '<?php echo $this->bean->id; ?>';
    var dataProductosDespacho = [];
    $(document).ready(function () {
        $('#subpanel_title_sco_despachos_sco_productosdespachos').after('<div class="validar"><button class="btn btn-sm btn-success" style="position: absolute; right: 0;" onclick="modalValidar()"> Ordenar items </button></div>');
    });

    function modalValidar() {
        $("#modalValProd").modal("show");
          $.ajax({
            type: "POST",
            url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
            data: {despacho},
            dataType:"json",
            beforeSend:function(){
                $(".loader").addClass("is-active");
            },
            success: function(productos) {
                $(".loader").removeClass("is-active");
                console.log(productos);
                var data = [];
                var numeracion = 0;
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

                $('#productosDespacho1').jexcel({
                    data:data,
                    onchange:update,
                    colHeaders: ['#','Producto','Descripcion', 'Observacion','Cantidad', 'Prec / U','Sub toal','idProductoCotizadoVenta', 'idProDes','Cod SAP', 'Validado'],
                    colWidths: [20, 50, 170, 80, 40, 60, 60, 50, 50, 50, 30],
                    columns: [
                        {type: 'text', readOnly:false},
                        {type: 'text'},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'hidden', readOnly:false},
                        {type: 'hidden', readOnly:false},
                        {type: 'text', readOnly:false},
                    ]
                });
                //total cantidad de registros del listado de productos despachos
                $('#totalRegistroDes1').text(data.length);

                //total cantidad de items del listado de productos despachos
                var b, c = 0;
                for(var a = 0; a < data.length ; a++){
                  b = data[a][4];              
                  c = parseInt(b) + parseInt(c);
                }
                $('#totalCantidadDes1').text(c);

                //total precio de subtotales del listado de productos despachos
                for(var a = 0; a < data.length ; a++){
                  b = data[a][6];
                  c = parseFloat(b) + parseFloat(c);
                }
                //console.log($('#my tbody tr').length);                
                $('#totalPrecioDes1').text(c.toFixed(2));                
            },
            error: function (data) {
              alert('ERROR, No se pudo conectar', data);
            }
          });

        var data2 = [['','','','','','']];

        $('#productosDespacho2').jexcel({
            data:data2,
            colHeaders: ['Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal','Validado'],
            colWidths: [ 50, 170, 60, 50, 80, 80],
            columns: [
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'}
            ]
        });
    }

    function validarItems() {        
        $(document).ready(function () {
            $(".loader").addClass("is-active")
        });

        var data1 = $('#productosDespacho1').jexcel('getData');
        var data2 = $('#productosDespacho2').jexcel('getData');
        $('#productosDespacho1 td').css( "background-color", "#fff" );
        $('#productosDespacho2 td').css( "background-color", "#fff" );
        console.log(data1.length);
        console.log(data2.length);
        var arrayOrden = [];
        var oDiferentesDes1 = [];
        var oDiferentesDes2 = [];
        for (let filaDes2 = 0; filaDes2 < data2.length; filaDes2++) {        
            for (let filaDes1 = 0; filaDes1 < data1.length; filaDes1++) {    
              var oitem = {};
              var oitem2 = {};         
                if (data1[filaDes1][1].trim() == data2[filaDes2][0].trim()) {
                    //arrayOrden[filaDes2]= data1[filaDes1];
                    //arrayOrden[filaDes2][0] == filaDes2 + 1;
                    //arrayOrden[filaDes2][7] == data1[filaDes1][7];

                    console.log("TRUE");
                    console.log(data1[filaDes1][1].trim() + " - " + filaDes1 + " == " + data2[filaDes2][0].trim() + " - " + filaDes2);

                    oitem2.nombre = data1[filaDes2][1].trim();
                    oitem2.descripcion = data1[filaDes2][2].trim();
                    oitem2.posicion = parseInt(filaDes2) + 1;
                    oDiferentesDes2.push(oitem2);

                    $('#productosDespacho1 #row-'+filaDes1).css( "background-color", "#CBFFC7" ); 
                    $('#productosDespacho1 #9-'+filaDes1).text("Existe");
                    $('#productosDespacho1 #9-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #6-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #5-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #4-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #2-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #1-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #0-'+filaDes1).text(parseInt(filaDes2)+1);

                    $('#productosDespacho2 #row-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #0-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #1-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #3-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #4-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #5-'+filaDes2).css( "background-color", "#CBFFC7" ); 
                    $('#productosDespacho2 #6-'+filaDes2).text('Encontrado'); 
                    $('#productosDespacho2 #6-'+filaDes2).css( "background-color", "#CBFFC7" );                
                    break;
                }else{
                    //$('#productosDespacho1 #row-'+filaDes1).css( "background-color", "red" );
                    //$('#productosDespacho2 #row-'+filaDes2).css( "background-color", "red" );

                    oitem.nombre = data1[filaDes1][1].trim();
                    oitem.descripcion = data1[filaDes1][2].trim();
                    oitem.posicion = parseInt(filaDes1) + 1;
                    oDiferentesDes1.push(oitem);                    

                    $('#productosDespacho2 #6-'+filaDes2).text('No existe');
                    $('#productosDespacho2 #6-'+filaDes2).css( "background-color", "#ffc7c7" );

                    if($('#productosDespacho2 #6-'+filaDes2).text() != 'Encontrado'){
                      console.log("ESTO SE ECONTRO"+$('#productosDespacho2 #6-'+filaDes2).text());
                      $('#productosDespacho2 #0-'+filaDes2).css( "background-color", "#ffc7c7" );
                      $('#productosDespacho2 #1-'+filaDes2).css( "background-color", "#ffc7c7" );
                      $('#productosDespacho2 #3-'+filaDes2).css( "background-color", "#ffc7c7" );
                      $('#productosDespacho2 #4-'+filaDes2).css( "background-color", "#ffc7c7" );
                      $('#productosDespacho2 #5-'+filaDes2).css( "background-color", "#ffc7c7" );
                    }else{
                      $('#productosDespacho2 #6-'+filaDes2).css( "background-color", "#CBFFC7" );
                    }
                }
            }
        }
        console.log(oDiferentesDes1);
        console.log(oDiferentesDes2);
        if(oDiferentesDes2.length == data1.length){
            $('#pTotalValidacion2').text(oDiferentesDes2.length + ' / ' + data1.length + ' encontrados');
            $('.pTotalValidacion2').css( "color", "green" );
        }else{
            $('#pTotalValidacion2').text(oDiferentesDes2.length + ' / ' + data1.length + ' encontrados');
            $('.pTotalValidacion2').css( "color", "red" );
        }
        $(".loader").removeClass("is-active");
        $('#totalRegistroDes2').text(data2.length); 
        if(data2.length == data1.length){
          $('.pTotalRegistro').html("Total Registros: <b> &#10004;</b>");
          $('.pTotalRegistro').css( "color", "green" );
        }else{
          $('.pTotalRegistro').html("Total Registros: <b> &#x1f5f4;</b>");
          $('.pTotalRegistro').css( "color", "red" );
        }
        //total cantidad de items del listado de productos despachos
        var b, c = 0;
        for(var a = 0; a < data2.length ; a++){
          b = data2[a][3];                
          c = parseInt(b) + parseInt(c);                  
        }

        $('#totalCantidadDes2').text(c);
        if($('#totalCantidadDes1').text() == c){
          $('.pTotalCantidad').css( "color", "green" );
        }else{
          $('.pTotalCantidad').css( "color", "red" );
        }        

        //total precio de subtotales del listado de productos despachos
        var x, y = 0;
        for(var a = 0; a < data2.length ; a++){
          x = data2[a][5];
          y = parseFloat(x) + parseFloat(y);
        }
        //console.log($('#my tbody tr').length);                
        $('#totalPrecioDes2').text(y.toFixed(2));
        if($('#totalPrecioDes1').text() == y){
          $('.pTotalPrecio').css( "color", "green" );
        }else{
          $('.pTotalPrecio').css( "color", "red" );
        }
    }

    update = function (obj, cel, row) {
        function checkPos(pos) {
            return pos == producto;
        }

        val = $('#my').jexcel('getValue', $(row));
        var col = $(cel).prop('id').split('-')[0];

        if(col == 0){
          var row = $(cel).prop('id').split('-')[1];
          //$('#0-'+row).on('keydown',openProductPopup());
        }

        var row = $(cel).prop('id').split('-')[1];
        var cant = $('#3-'+row).text().trim();
        var prec = $('#4-'+row).text().trim();
        var tot = cant * prec;

        $('#8-'+row).bind({
            copy : function(){
                buscaproy($('#8-'+row).text(), row);
            },
        });
        if($('#9-'+row).text() != '' && col == 9){
            //buscaproy($('#8-'+row).text(), row);
            //alert("HOLA MUNDO");
            buscaProductoYAcutualizaItem($('#7-'+row).text(), $('#8-'+row).text(), $('#9-'+row).text(), row);
        }

        if($('#0-'+row).text() != '' && col == 0){
            buscaProductoYAcutualizaItem($('#7-'+row).text(), $('#8-'+row).text(), $('#9-'+row).text(), row);
            $('#0-'+row).text()
        }

        var b = '';
        var c = 0;
        for(var a=0; a<$('#my tbody tr').length; a++){
          b = $('#7-'+[a]).text();
          c = parseFloat(b) + c;
        }
        //console.log($('#my tbody tr').length);
        $('#pro_subto').val(c.toFixed(4));
        $('#pro_total').val(c.toFixed(4));
      }

    //Busca el producto conectandose al Servicio SOAP de SAP para traer el item
    function buscaProductoYAcutualizaItem(idPcv,idProDes,nomp, row) {
        console.log(idPcv, idProDes,nomp, row);
        $.ajax({
            type: 'get',
            url: 'index.php?to_pdf=true&module=SCO_Despachos&action=buscaProducto',
            data: {
                idPcv,idProDes,nomp
            },
            success: function(data) {                
                //debugger;
                var sqlprod = $.parseJSON(data);   
                console.log(sqlprod); 
                console.log('devuelve datos'+sqlprod.respuesra_servicio);                 
                console.log(sqlprod);              
                if (sqlprod.respuesra_servicio == '200') {
                    $('#9-' + row).css( "background-color", "#CBFFC7" );                                      
                } else {
                    $('#9-' + row).css( "background-color", "#ffc7c7" );
                }
            }
        });
        return (false);
    }

    function modalUpdateItemAio(sqlprod){
      console.log(sqlprod)
      var html = '';
      html += '<div class="modal fade" id="modalUpdateItemAio">';
      html += '    <div class="modal-dialog">';
      html += '        <div class="modal-content">';
      html += '            <div class="modal-header">';
      html += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
      html += '                <h4 class="modal-title">Guardar registros</h4>';
      html += '            </div>';
      html += '            <div class="modal-body">';
      html += '               <center><span>Seguro que desea guardar la informacion?</span></center></br>';
      html += '               <div class="row">';
      html += '                   <div class="form-group"> ';
      html += '                   <div class="col-sm-6">';
      html += '                       Consolidacion:';
      html += '                   </div>';
      html += '                   <div class="col-sm-6">';
      html += '                       <input type="text"  id="nombreConsolidacion" name="nombreConsolidacion" value="'+sqlprod[0]['name']+'" disabled>';
      html += '                   </div>';
      html += '                   </div>';
      html += '               </div>';
      html += '               <div class="row">';
      html += '                   <div class="form-group"> ';
      html += '                   <div class="col-sm-6">';
      html += '                       Orden de Compra:';
      html += '                   </div>';
      html += '                   <div class="col-sm-6">';
      html += '                       <input type="text"  id="nombreOc" name="nombreOc" value="'+sqlprod[0]['name']+'" disabled>';
      html += '                   </div>';
      html += '                   </div>';
      html += '               </div>';
      html += '            </div>';
      html += '            <div class="modal-footer">';
      html += '            <div class="row">';
      html += '               <div class="col-sm-6">';
      html += '                   <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>';
      html += '               </div>';
      html += '               <div class="col-sm-6">';
      html += '                   <button type="button" class="btn btn-sm btn-verde" onclick=envioDeDatos(jsonDatos);>Confirmar y Guardar</button>';
      html += '               </div>';
      html += '            </div>';
      html += '        </div>';
      html += '    </div>';
      html += '</div>';    

      $("#ventanaModal").html(html);
    }
</script>