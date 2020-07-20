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
		alert(fecha_desde+" == "+fecha_hasta);
		$.ajax({
	    type: 'POST',
	    url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=backorderdata',
	    datatype: 'json',
	    data: {
	      fecha_desde: fecha_desde,
	      fecha_hasta: fecha_hasta,
	    },
	    async: false,
	    success: function (e) {
	      	var res = JSON.parse(e);
	      	var datos = ''; 
	      	for (var i = 0; i < res.length; i++) {
	      		datos += res[i]["nombrepro"];
	  		}
	  		alert(datos);
	    },
	    error: function (data) {
	      alert('ERROR, No se pudo conectar', data);
	    }
	  });
	}
}); 
