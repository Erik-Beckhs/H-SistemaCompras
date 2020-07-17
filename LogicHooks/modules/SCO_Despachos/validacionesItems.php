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
                        <div id="spreadsheet"></div>
                        <div id="productosDespacho1"></div>
                    </div>
                    <div class="col-lg-6 table-responsive">
                        prductos factura
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
          $.ajax({
            type: "POST",
            url: "index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=4",
            data: {despacho},
            dataType:"json",
            success: function(data) {
                var html = '';
                html += '<table class="table">';
                html += '<thead>';
                html += '<th>Producto</th>';
                html += '<th>Descripcion</th>';
                html += '<th>Observacion</th>';
                html += '<th>Cantidad</th>';
                html += '<th>Precio</th>';
                html += '<th>Subtotal</th>';
                html += '</thead>';
                html += '<tbody>';
                for (var i = 0; i < data.length; i++) {
                    html += '<tr>';
                    html += '<td>'+data[i]["name"]+'</td>';
                    html += '<td>'+data[i]["prdes_descripcion"]+'</td>';
                    html += '<td></td>';
                    html += '<td>'+data[i]["prdes_cantidad"]+'</td>';
                    html += '<td>'+data[i]["punitario"]+'</td>';
                    html += '<td>'+(data[i]["prdes_cantidad"] * 1 * data[i]["punitario"])+'</td>';
                    html += '</tr>';
                }
                html += '</tbody>';
                html += '</table>';
                $("#productosDespacho1").html(html);
            }
          });
    }

    var data = [
    ['Jazz', 'Honda', '2019-02-12', '', true, '$ 2.000,00', '#777700'],
    ['Civic', 'Honda', '2018-07-11', '', true, '$ 4.000,01', '#007777'],
];

$('#spreadsheet').jexcel({
    data:data,
    columns: [
        {
            type: 'text',
            title:'Car',
            width:90
        },
        {
            type: 'dropdown',
            title:'Make',
            width:120,
            source:[
                "Alfa Romeo",
                "Audi",
                "Bmw",
                "Chevrolet",
                "Chrystler",
                "Dodge",
                "Ferrari",
                "Fiat",
                "Ford",
                "Honda",
                "Hyundai",
                "Jaguar",
                "Jeep",
                "Kia",
                "Mazda",
                "Mercedez-Benz",
                "Mitsubish",
                "Nissan",
                "Peugeot",
                "Porsche",
                "Subaru",
                "Suzuki",
                "Toyota",
                "Volkswagen"
              ]
        },
        {
            type: 'text',
            title:'Available',
            width:120
        },
        {
            type: 'text',
            title:'Photo',
            width:120
        },
        {
            type: 'checkbox',
            title:'Stock',
            width:80
        },
        {
            type: 'numeric',
            title:'Price',
            mask:'$ #.##,00',
            width:80,
            decimal:','
        },
        {
            type: 'color',
            width:80,
            render:'square',
        },
     ]
});

</script>