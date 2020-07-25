$(document).ready(function(){
	var usuarioNombre = $("#usuarioNombre").val();
	var usuarioDivision = $("#usuarioDivision").val();
	var usuarioAmercado = $("#usuarioAmercado").val();
	var usuarioRol = $("#usuarioRol").val();
	$("#division").val(usuarioDivision);
	$("#aMercado").val(usuarioAmercado);
	console.log(usuarioNombre+usuarioDivision+usuarioAmercado+usuarioRol);
	//Fltro, filtra los datos de la division junto a area de mercado
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

	//Filtro, filtra el rango de fechas
	$('#datepicker').datepicker(); 
	$('#datepicker2').datepicker();

	$("#datepicker2").on('keyup', function (event) {
		var fecha_desde = $("#datepicker").val();
		var fecha_hasta = $("#datepicker2").val();
	  if (event.keyCode === 13) {
	    getData(fecha_desde,fecha_hasta,$("#division").val(),$("#aMercado").val());
	  }
	});
	$("#datepicker").on('keyup', function (event) {
		var fecha_desde = $("#datepicker").val();
		var fecha_hasta = $("#datepicker2").val();
	  if (event.keyCode === 13) {
	    getData(fecha_desde,fecha_hasta,$("#division").val(),$("#aMercado").val());
	  }
	});
	$("#buscarBackOrder").on("click", function(event){
		var fecha_desde = $("#datepicker").val();
		var fecha_hasta = $("#datepicker2").val();
		getData(fecha_desde,fecha_hasta,$("#division").val(),$("#aMercado").val());
	});

	
	function getData(fecha_desde,fecha_hasta,usuarioDivision,usuarioAmercado){
		fecha_desde = fecha_desde.split("/");
		fecha_desde = fecha_desde[2]+"-"+fecha_desde[0]+"-"+fecha_desde[1];

		fecha_hasta = fecha_hasta.split("/");
		fecha_hasta = fecha_hasta[2]+"-"+fecha_hasta[0]+"-"+fecha_hasta[1];

		console.log("Rango fechas: "+fecha_desde+", "+fecha_hasta + ", Division: "+ usuarioDivision +", Amercado: "+ usuarioAmercado);
		$.ajax({
	    type: 'POST',
	    url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=backorderdata',
	    datatype: 'json',
	    data: {
	    	filtro: 1,
	      	fecha_desde: fecha_desde,
	      	fecha_hasta: fecha_hasta,
	      	division: usuarioDivision,
	      	aMercado: usuarioAmercado,
	    },
	    beforeSend:function(){
            $(".loader").addClass("is-active");
        },
	    success: function (e) {
	    	$(".loader").removeClass("is-active");
	      	var res = JSON.parse(e);
	      	console.log(res);
	      	vistaTabla(res);	      		    
	    },
	    error: function (data) {
	      alert('ERROR, No se pudo conectar', data);
	    }
	  });
	}

	function vistaTabla(res){
		var html = '';
		var numeracion = 1;
		console.log(res.length);
		if(res.length > 0){
			res.forEach(function(elemento,indice,array){
				html += '<tr id="item">';
				html += '<td>' + numeracion + '</td>';
				html += '<td><a href="index.php?module=SCO_OrdenCompra&action=DetailView&record='+elemento.id+'" target="_blank" style="text-decoration: underline;">'+elemento.nombrecompra+'</a></td>';
				html += '<td><b>' + elemento.orc_fechaord + '</b></td>';
				html += '<td>' + elemento.orc_nomcorto + '</td>';
				html += '<td>' + elemento.orc_estado + '</td>';
				html += '<td>' + elemento.orc_tipoo + '</td>';
				html += '<td>' + elemento.orc_tipo + '</td>';
				html += '<td style="color:red">' + elemento.totalSaldo + '</td>';		
				html += '<td>' + parseFloat(elemento.subtotal).toFixed(2) + '</td>';
				html += '<td>' + elemento.orc_tcmoneda + '</td>';			
				html += '<td onclick=getProductos("'+elemento.id+'")><button type="button" class="btn btn-custom btn-info btn-sm">Productos <span class="badge badge-light badge-custom">' + elemento.cantidadRegistro + '</span></button></td>';
				html += '</tr>';
				numeracion++;
		  	});
		}else{
			html += '<tr>';
			html += '<td></td>';
			html += '<td><span class="label label-default badge-cerror" style="color:#FFF;">No se encontraron datos...</span></td>';
			html += '</tr>';
		}
	  	$('#tablaBackOrder').html(html);
	}

}); 



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
	    var htmlm = '';
	    htmlm += '<div class="modal fade" id="modalProductos">';
	    htmlm += '    <div class="modal-dialog">';
	    htmlm += '        <div class="modal-content">';
	    htmlm += '            <div class="modal-header">';
	    htmlm += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	    htmlm += '                <h4 class="modal-title">Lista de productos</h4>';
	    htmlm += '            </div>';
	    htmlm += '            <div class="modal-body">';
	    htmlm += '                <div class="panel panel-success">';
    	htmlm += '                    <div class="panel-heading">Productos de la Orden de Compra</div>';
    	htmlm += '                    <div class="panel-body">';
	    htmlm += '						<table class="list view table-responsive tablaProductos table-striped">';
		htmlm += '						<thead>';
		htmlm += '							<tr>';
		htmlm += '								<th>#</th>';
		htmlm += '								<th>Codigo Proveedor / SAP</th>';
		htmlm += '								<th>Descripcion</th>';
		htmlm += '								<th>Unidad</th>';
		htmlm += '								<th>Cantidad</th>';
		htmlm += '								<th>Precio unidad</th>';
		htmlm += '								<th>saldo</th>';
		htmlm += '							</tr>';
		htmlm += '						</thead>';
		htmlm += '						<tbody >';
		var numeracion = 1;
		res.forEach(function(elemento,indice,array){
	    htmlm += '							<tr id="item1_' + elemento.pro_nombre + '" >';
		htmlm += '								<td>' + numeracion + '</td>';
		htmlm += '								<td><b class="text-info">' + elemento.pro_nombre + '</b></td>';
		htmlm += '								<td>' + elemento.pro_descripcion + '</td>';
		htmlm += '								<td>' + elemento.pro_unidad + '</td>';
		htmlm += '								<td>' + elemento.pro_cantidad + '</td>';
		htmlm += '								<td><span class="text-success">' + elemento.pro_preciounid + '</span></td>';		
		htmlm += '								<td style="color:red;">' + elemento.pro_saldos + '</td>';
		htmlm += '							</tr>';
		numeracion++;
		});
		htmlm += '						</tbody>';
		htmlm += '					</table>';
		htmlm += '					</div>';
		htmlm += '				</div>';
	    htmlm += '            </div>';
	    htmlm += '            <div class="modal-footer">';
	    htmlm += '            <div class="row">';
	    htmlm += '            </div>';
	    htmlm += '        </div>';
	    htmlm += '    </div>';
	    htmlm += '</div>';    

    $("#ventanaModal").html(htmlm);
}
