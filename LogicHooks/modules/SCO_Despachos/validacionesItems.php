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
                <div class="row">
                    <center>
                        <button class="btn btn-xs btn-danger">Cancelar</button>
                        <button class="btn btn-success btn-xs" onclick="validarItems()">Validar</button>
                    </center>
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
                    colHeaders: ['#','Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal','idProducto','Cod SAP'],
                    colWidths: [20,50, 170, 80, 40, 60, 60,50,50],
                    columns: [
                        {type: 'text', readOnly:false},
                        {type: 'text'},
                        {type: 'text', readOnly:false},
                        {type: 'text', readOnly:true},
                        {type: 'text', readOnly:true},
                        {type: 'text', readOnly:true},
                        {type: 'text', readOnly:true},
                        {type: 'hidden', readOnly:true},
                        {type: 'text', readOnly:true},
                    ]
                });
            }
          });
    }

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
    function validarItems() {
        var data1 = $('#productosDespacho1').jexcel('getData');
        var data2 = $('#productosDespacho2').jexcel('getData');
        console.log(data1.sort());
        console.log(data2.sort());
        var arrayOrden = [];
        for (let index = 0; index < data2.length; index++) {
            for (let i = 0; i < data1.length; i++) {
                console.log(data1[i][1].trim() + " == " + data2[index][0].trim());
                console.log(data1[i][1].trim() == data2[index][0].trim());
                if (data1[i][1].trim() == data2[index][0].trim()) {
                    arrayOrden[index]= data1[i];
                    arrayOrden[index][0] == index + 1;
                    arrayOrden[index][7] == data1[i][7];
                    console.log("es verdad la condicion");
                    $('#productosDespacho1 #row-'+i).css( "background-color", "greenyellow" );        
                    $('#productosDespacho2 #row-'+index).css( "background-color", "greenyellow" );                 
                }else{
                    $('#productosDespacho1 #row-'+i).css( "background-color", "red" ); 
                    $('#productosDespacho2 #row-'+index).css( "background-color", "red" );
                }
            }
        }
        /*$('#productosDespacho1').jexcel({
                    data:arrayOrden,
                    colHeaders: ['#','Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal','idProducto'],
                    colWidths: [20,140, 150, 60, 70, 80, 80],
                    columns: [
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'hidden'},
                    ]
                });*/
    }
</script>