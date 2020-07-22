$(document).ready(function(){
	$('#datepicker').datepicker(); 
	$('#datepicker2').datepicker();

	$("#datepicker2").on('keyup', function (event) {
		var fecha_desde = $("#datepicker").val();
		var fecha_hasta = $("#datepicker2").val();
	  if (event.keyCode === 13) {
	    getData(fecha_desde,fecha_hasta);
	  }
	});

	function getData(fecha_desde,fecha_hasta){
		fecha_desde = fecha_desde.split("/");
		fecha_desde = fecha_desde[2]+"-"+fecha_desde[0]+"-"+fecha_desde[1];

		fecha_hasta = fecha_hasta.split("/");
		fecha_hasta = fecha_hasta[2]+"-"+fecha_hasta[0]+"-"+fecha_hasta[1];

		alert("LAS FECHAS SON: "+fecha_desde+" == "+fecha_hasta);
		$.ajax({
	    type: 'POST',
	    url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=backorderdata',
	    datatype: 'json',
	    data: {
	    	filtro: 1,
	      	fecha_desde: fecha_desde,
	      	fecha_hasta: fecha_hasta,
	    },
	    beforeSend:function(){
            $(".loader").addClass("is-active");
        },
	    success: function (e) {
	    	$(".loader").removeClass("is-active");
	      	var res = JSON.parse(e);
	      	vistaTabla(res);	      		    
	    },
	    error: function (data) {
	      alert('ERROR, No se pudo conectar', data);
	    }
	  });
	}

}); 

function vistaTabla(res){
	var html = '';
	var numeracion = 1;
	console.log(res.length);
	if(res.length > 0){
		res.forEach(function(elemento,indice,array){
	  		console.log("valor : " + elemento.nombrepro);
			html += '<tr id="item1_' + elemento.nombrepro + '" >';
			html += '<td>' + numeracion + '</td>';
			html += '<td><a href="index.php?module=SCO_OrdenCompra&action=DetailView&record='+elemento.id+'" target="_blank" style="text-decoration: underline;">'+elemento.nombrecompra+'</a></td>';
			html += '<td><b>' + elemento.orc_fechaord + '</b></td>';
			html += '<td>' + elemento.orc_nomcorto + '</td>';
			html += '<td>' + elemento.orc_tipoo + '</td>';
			html += '<td>' + elemento.orc_tipo + '</td>';
			html += '<td>' + elemento.totalSaldo + '</td>';		
			html += '<td>' + elemento.subtotal + '</td>';		
			html += '<td onclick=getProductos("'+elemento.id+'")> <button type="button" class="btn btn-info btn-sm">Productos <span class="badge badge-light">' + elemento.totalSaldo + '</span></button></td>';
			html += '</tr>';
			numeracion++;
	  	});
	}else{
		html += '<tr>';
		html += '<td><span class="label label-warning" style="color:#FFF;">No se encontraron datos...</span></td>';
		html += '</tr>';
	}
  	$('#tablaBackOrder').html(html);
}

function getProductos(idco){
	$.ajax({
    type: 'POST',
    url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=backorderdata',
    datatype: 'json',
    data: {
    	filtro: 2,
      	idco: idco,
    },
    beforeSend:function(){
        $(".loader").addClass("is-active");
    },
    success: function (e) {
    	$(".loader").removeClass("is-active");
      	var res = JSON.parse(e);
      	ventanaModalProductos(res);
      	$('#modalProductos').modal('show');
      	console.log("datos"+res.length);		    
    },
    error: function (data) {
      alert('ERROR, No se pudo realizar la peticion', data);
    }
  });
}

//Estructura de la vista de la venta modal
function ventanaModalProductos(res){  
    console.log(res);
	    var html = '';
	    html += '<div class="modal fade" id="modalProductos">';
	    html += '    <div class="modal-dialog">';
	    html += '        <div class="modal-content">';
	    html += '            <div class="modal-header">';
	    html += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	    html += '                <h4 class="modal-title">Guardar registros</h4>';
	    html += '            </div>';
	    html += '            <div class="modal-body">';
	    html += '                <div class="panel panel-info">';
    	html += '                    <div class="panel-heading">Consolidacion</div>';
    	html += '                    <div class="panel-body">';
	    html += '						<table class="list view table-responsive tablaProductos table-striped">';
		html += '						<thead>';
		html += '							<tr>';
		html += '								<th>#</th>';
		html += '								<th>Codigo Proveedor / SAP</th>';
		html += '								<th>Descripcion</th>';
		html += '								<th>Unidad</th>';
		html += '								<th>Cantidad</th>';
		html += '								<th>Precio unidad</th>';
		html += '								<th>saldo</th>';
		html += '							</tr>';
		html += '						</thead>';
		html += '						<tbody id="tablaBackOrder">';
		var numeracion = 1;
		res.forEach(function(elemento,indice,array){
	    html += '							<tr id="item1_' + elemento.pro_nombre + '" >';
		html += '								<td>' + numeracion + '</td>';
		html += '								<td><b>' + elemento.pro_nombre + '</b></td>';
		html += '								<td>' + elemento.pro_descripcion + '</td>';
		html += '								<td>' + elemento.pro_unidad + '</td>';
		html += '								<td>' + elemento.pro_cantidad + '</td>';
		html += '								<td>' + elemento.pro_preciounid + '</td>';		
		html += '								<td>' + elemento.pro_saldos + '</td>';		
		html += '							</tr>';
		numeracion++;
		});
		html += '						</tbody>';
		html += '					</table>';
		html += '					</div>';
		html += '				</div>';
	    html += '            </div>';
	    html += '            <div class="modal-footer">';
	    html += '            <div class="row">';
	    html += '            </div>';
	    html += '        </div>';
	    html += '    </div>';
	    html += '</div>';    

    $("#ventanaModal").html(html);
}
