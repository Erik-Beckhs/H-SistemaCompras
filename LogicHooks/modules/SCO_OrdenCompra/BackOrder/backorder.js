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

		alert(fecha_desde+" == "+fecha_hasta);
		$.ajax({
	    type: 'POST',
	    url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=backorderdata',
	    datatype: 'json',
	    data: {
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
	res.forEach(function(elemento,indice,array){
  		console.log("valor : " + elemento.nombrepro);
		html += '<tr id="item1_' + elemento.nombrepro + '">';
		html += '<td>' + numeracion + '</td>';
		html += '<td><b>' + elemento.codigoProveedor + '</b></td>';
		html += '<td>' + elemento.pro_codaio + '</td>';
		html += '<td>' + elemento.pro_descripcion + '</td>';
		html += '<td>' + elemento.pro_saldos + '</td>';
		html += '<td>' + elemento.pro_preciounid + '</td>';		
		html += '<td>' + elemento.pro_subtotal + '</td>';		
		html += '<td>' + elemento.orc_fechaord + '</td>';
		html += '<td><a href="index.php?module=SCO_OrdenCompra&action=DetailView&record='+elemento.id+'" target="_blank" style="text-decoration: underline;">'+elemento.nombrecompra+'</a></td>';
		html += '<td></td>';
		html += '</tr>';
		numeracion++;
  	});
  	$('#tablaBackOrder').html(html);
}
