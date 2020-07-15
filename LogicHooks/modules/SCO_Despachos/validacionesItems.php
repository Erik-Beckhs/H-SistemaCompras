<script src="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/js/jquery.jexcel.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/css/jquery.jexcel.min.css"
    type="text/css" />
<!-- MODAL VALIDACIONES -->
<div class="modal fade" id="modalValProd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" style="width: 75% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ordenar productos Despacho</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 container">
                    <div class="col-lg-6 table-responsive">
                        productos despacho
                        <div id="my"></div>
                        <div id="productosDespacho1"></div>

                    </div>
                    <div class="col-lg-6 table-responsive">
                        prductos factura
                    </div>
                </div>
            </div>

            <div class="container">
                <center>
                    <button type="button" class="btn  btn-xs btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-xs">Validar</button>
                </center>
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
    $(document).ready(function () {
        $('#subpanel_title_sco_despachos_sco_productosdespachos').after('<div class="validar"><button class="btn btn-xs btn-success" style="position: absolute; right: 0;" onclick="modalValidar()"> Ordenar items </button></div>')
    });
    function modalValidar() {
        $("#modalValProd").modal("show");
        $('#my').jexcel({
            data: data,
            colHeaders: ['Country', 'Description', 'Type', 'Stock', 'Next purchase'],
            colWidths: [300, 80, 100, 60, 120],
            columns: [
                { type: 'autocomplete', url: '/jexcel/countries' },
                { type: 'text' },
                { type: 'dropdown', source: [{ 'id': '1', 'name': 'Fruits' }, { 'id': '2', 'name': 'Legumes' }, { 'id': '3', 'name': 'General Food' }] },
                { type: 'checkbox' },
                { type: 'calendar' },
            ]
        });
        //   $.ajax({
        //     type: "POST",
        //     url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
        //     data: {despacho},
        //     dataType:"json",
        //     success: function(data) {
        //         var html = '';
        //         html += '<table class="table">';
        //         html += '<thead>';
        //         html += '<th>Producto</th>';
        //         html += '<th>Descripcion</th>';
        //         html += '<th>Observacion</th>';
        //         html += '<th>Cantidad</th>';
        //         html += '<th>Precio</th>';
        //         html += '<th>Subtotal</th>';
        //         html += '</thead>';
        //         html += '<tbody>';
        //         for (var i = 0; i < data.length; i++) {
        //             html += '<tr>';
        //             html += '<td>'+data[i]["name"]+'</td>';
        //             html += '<td>'+data[i]["prdes_descripcion"]+'</td>';
        //             html += '<td></td>';
        //             html += '<td>'+data[i]["prdes_cantidad"]+'</td>';
        //             html += '<td>'+data[i]["punitario"]+'</td>';
        //             html += '<td>'+(data[i]["prdes_cantidad"] * 1 * data[i]["punitario"])+'</td>';
        //             html += '</tr>';
        //         }
        //         html += '</tbody>';
        //         html += '</table>';
        //         $("#productosDespacho1").html(html);
        //     }
        //   });
    }


    data = [
        [3, 'Classe A', 'Cheese', 1, '2017-01-12'],
        [1, 'Classe B', 'Apples', 1, '2017-01-12'],
        [2, 'Classe A', 'Carrots', 1, '2017-01-12'],
        [1, 'Classe C', 'Oranges', 0, '2017-01-12'],
    ];

    $('#my').jexcel({
        data: data,
        colHeaders: ['Country', 'Description', 'Type', 'Stock', 'Next purchase'],
        colWidths: [300, 80, 100, 60, 120],
        columns: [
            { type: 'autocomplete', url: '/jexcel/countries' },
            { type: 'text' },
            { type: 'dropdown', source: [{ 'id': '1', 'name': 'Fruits' }, { 'id': '2', 'name': 'Legumes' }, { 'id': '3', 'name': 'General Food' }] },
            { type: 'checkbox' },
            { type: 'calendar' },
        ]
    });

</script>