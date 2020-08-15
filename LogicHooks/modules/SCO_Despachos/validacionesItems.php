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
            </div>
            <div class="modal-footer">
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
        $('#subpanel_title_sco_despachos_sco_productosdespachos').after('<div class="validar"><button class="btn btn-sm btn-success" style="position: absolute; right: 0;" onclick="modalValidar()"> Ordenar items </button></div>')
    });

    function modalValidar() {
        $("#modalValProd").modal("show");
          $.ajax({
            type: "POST",
            url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
            data: {despacho},
            dataType:"json",
            success: function(productos) {
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
                            (productos[i]["prdes_cantidad"] * 1 * productos[i]["punitario"]).toFixed(2),productos[i]["idPro"],
                            productos[i]["prdes_codaio"]
                            ]
                }

                $('#productosDespacho1').jexcel({
                    data:data,
                    onchange:update,
                    colHeaders: ['#','Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal','idProducto','Cod SAP', ''],
                    colWidths: [20, 50, 170, 80, 40, 60, 60, 50, 50, 30],
                    columns: [
                        {type: 'text', readOnly:false},
                        {type: 'text'},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:false},
                        {type: 'hidden', readOnly:false},
                        {type: 'text', readOnly:false},
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
                
            }
          });

        var data2 = [['','','','','','']];

        $('#productosDespacho2').jexcel({
            data:data2,
            colHeaders: ['Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal'],
            colWidths: [ 50, 170, 60, 50, 80, 80],
            columns: [
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
        var data1 = $('#productosDespacho1').jexcel('getData');
        var data2 = $('#productosDespacho2').jexcel('getData');
        $('#productosDespacho1 td').css( "background-color", "#fff" );
        $('#productosDespacho2 td').css( "background-color", "#fff" );
        console.log(data1.length);
        console.log(data2.length);
        var arrayOrden = [];
        var oDiferentesDes1 = [];
        var oDiferentesDes2 = [];
        for (let filaDes1 = 0; filaDes1 < data1.length; filaDes1++) {        
            for (let filaDes2 = 0; filaDes2 < data2.length; filaDes2++) {             
                if (data1[filaDes1][1].trim() == data2[filaDes2][0].trim()) {
                  var oitem = {}
                    //arrayOrden[filaDes2]= data1[filaDes1];
                    //arrayOrden[filaDes2][0] == filaDes2 + 1;
                    //arrayOrden[filaDes2][7] == data1[filaDes1][7];

                    console.log("TRUE");
                    console.log(data1[filaDes1][1].trim() + " - " + filaDes1 + " == " + data2[filaDes2][0].trim() + " - " + filaDes2);

                    $('#productosDespacho1 #row-'+filaDes1).css( "background-color", "#CBFFC7" ); 
                    $('#productosDespacho1 #9-'+filaDes1).text('ok');
                    $('#productosDespacho1 #9-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #2-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #1-'+filaDes1).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho1 #0-'+filaDes1).text(parseInt(filaDes2)+1);

                    $('#productosDespacho2 #row-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #0-'+filaDes2).css( "background-color", "#CBFFC7" );
                    $('#productosDespacho2 #1-'+filaDes2).css( "background-color", "#CBFFC7" );
                    break;
                }else{
                    //$('#productosDespacho1 #row-'+filaDes1).css( "background-color", "red" );
                    //$('#productosDespacho2 #row-'+filaDes2).css( "background-color", "red" );
                  

                }
            }
        }
        console.log(oDiferentesDes1);
        console.log(oDiferentesDes2);

        $('#totalRegistroDes2').text(data2.length); 
        if(data2.length == data1.length){
           $('.pTotalRegistro').css( "color", "green" );
        }else{
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

        if(col == 3 || col == 4){
          var row = $(cel).prop('id').split('-')[1];
          //alert($(cel).prop('id'));
          var cant = $('#3-'+row).text().trim();
          var prec = $('#4-'+row).text().trim();
          var tot = cant * prec;
          $('#7-'+row).text(tot.toFixed(4));
          $('#5-'+row).text('0.00');
          $('#6-'+row).text('0.00');
        }

          var row = $(cel).prop('id').split('-')[1];
          var cant = $('#3-'+row).text().trim();
          var prec = $('#4-'+row).text().trim();
          var tot = cant * prec;

        if(col == 5){
          var des_por = $('#5-'+row).text().trim();
          var des_val = (tot * des_por)/100;
          $('#6-'+row).text(des_val.toFixed(4));
          var des_val = $('#6-'+row).text();
          var tot_t = tot - des_val;
          $('#7-'+row).text(tot_t.toFixed(4));
        }
        if (col == 6) {
          var des_val = $('#6-'+row).text();
          var des_por = (des_val * 100)/tot;
          $('#5-'+row).text(des_por.toFixed(4));

          var des_val = $('#6-'+row).text();
          var tot_t = tot - des_val;
          $('#7-'+row).text(tot_t.toFixed(4));
        }

        $('#8-'+row).bind({
            copy : function(){
                buscaproy($('#8-'+row).text(), row);
            },
        });
          if($('#8-'+row).text() != '' && col == 8){
            //buscaproy($('#8-'+row).text(), row);
            //alert("HOLA MUNDO");
            buscap($('#8-'+row).text(), row);
          }

        if($('#0-'+row).text() != '' && col == 0){
          buscap($('#0-'+row).text(), row);
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
    function buscap(nomp, row) {
        $.ajax({
            type: 'get',
            url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscap',
            data: {
                nomp
            },
            success: function(data) {
                //debugger;
                var sqlprod = $.parseJSON(data);  
                console.log(sqlprod);              
                if (Object.keys(sqlprod) != '') {
                    if (sqlprod.length == 1) {
                      console.log('DATOS ' + sqlprod);
                      alert('El item se registro exitosamente : Cod Provedor : '+ sqlprod[0]['name'] +' Descripcion:' +sqlprod[0]['proge_nompro']);
                      //$('#0-' + row).text(sqlprod[0]['name']);
                      //$('#1-' + row).text(sqlprod[0]['proge_nompro'].replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
                      //$('#2-' + row).text(sqlprod[0]['proge_unidad']);
                      //$('#4-' + row).text(sqlprod[0]['proge_preciounid']);
                      //$('#9-' + row).text(sqlprod[0]['id']);
                      //$('#12-' + row).text(sqlprod[0]['proge_codaio']);
                      //$('#4-'+row).text(sqlprod['proge_preciounid']);
                      //$('#0-' + row).css({
                      //    'background': '#FFF',
                      //    'color': '#000'
                      //});
                    }
                    if (sqlprod.length > 1) {
                      //alert("codigo duplicado");
                      var html = '';
                      $("#codprdup").modal("show");
                      for (var i = 0; i < sqlprod.length; i++) {
                        //html+= "<h5>"+sqlprod[i]["name"]+" "+sqlprod[i]["proge_nompro"]+" "+sqlprod[i]["proge_subgrupo"]+"-"+sqlprod[i]["proge_codaio"]+" <button type='button' name='button' class='btn btn-xs btn-success' onclick='codproducto("+i+","+row+","+"1"+")'>Seleccionar</button></h5>";
                      }
                      $("#duplicados").html(html);
                      dataArray = sqlprod;
                    }
                } else {
                    alert('El Codigo de SAP no existe');
                    $('#0-' + row).css({
                        'background': '#d9534f',
                        'color': '#FFF'
                    });
                    $('#1-' + row).text('');
                    $('#2-' + row).text('');
                    $('#4-' + row).text('');
                }
            }
        });
        return (false);
    }
</script>