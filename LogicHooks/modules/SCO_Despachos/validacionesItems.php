<script src="custom/modules/SCO_OrdenCompra/jquery.jexcel.js?"></script>
<link rel="stylesheet" href="custom/modules/SCO_OrdenCompra/jquery.jexcel.css?" type="text/css" />

<!-- MODAL VALIDACIONES -->
<div class="modal fade" id="modalValProd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" style="width: 75% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ordenar productos Despacho</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 row">
                    <div class="col-lg-6 table-responsive">
                        productos despacho
                        <div id="productosDespacho1"></div>
                    </div>
                    <div class="col-lg-6 table-responsive">
                        productos factura
                        <div id="productosDespacho2"></div>
                    </div>
                </div>
                <div class="row">
                    <center>
                        <button class="btn btn-xs btn-danger">Cancelar</button>
                        <button class="btn btn-success btn-xs">Validar</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FIN MODAL -->
<style>
    .validar {
        display: flex;
        width: 50px;
        float: right;
        margin-top: -34.5px;
        z-index: 1000;
        position: relative;
    }
</style>
<script>
    var despacho = '<?php echo $this->bean->id; ?>';
    var dataProductosDespacho = [];
    $(document).ready(function () {
        $('#subpanel_title_sco_despachos_sco_productosdespachos').after('<div class="validar"><button class="btn btn-xs btn-success" style="position: absolute; right: 0;" onclick="modalValidar()"> Ordenar items </button></div>')
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
                for (var i = 0; i < productos.length; i++) {
                    data[i] = [productos[i]["name"],productos[i]["prdes_descripcion"],'',productos[i]["prdes_cantidad"],productos[i]["punitario"],(productos[i]["prdes_cantidad"] * 1 * productos[i]["punitario"]).toFixed(2)]
                }
                console.log(data);
                $('#productosDespacho1').jexcel({
                    data:data,
                    colHeaders: ['Producto','Descripcion', 'Observacion','Cantidad', 'Prec Uni','Sub toal'],
                    colWidths: [ 140, 150, 60, 70, 80, 80],
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
          });
    }

    var data2 = [['','','','','','']];

    $('#productosDespacho2').jexcel({
        data:data2,
        colWidths: [ 140, 150, 60, 70, 80, 80],
        columns: [
            {type: 'text'},
            {type: 'text'},
            {type: 'text'},
            {type: 'text'},
            {type: 'text'},
            {type: 'text'}
        ]
    });

</script>