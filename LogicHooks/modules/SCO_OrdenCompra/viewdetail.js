var d = new Date();
var n = d.getTime();

function showreport(id){
	var url1 = "/modules/reportes/ordencompra.php?id="+id+"&ejec="+n;
	window.open(url1,"","width=1220,height=650");
}
function imprimir(id,nombre,orc_nomcorto){
	var url2 = "/modules/reportes/descargaoc.php?id="+id+"&name="+nombre+"&nameprov="+orc_nomcorto+"&ejec="+n;
	window.open(url2,"","");
}
function showReporteGerencialDiv03(id){
	var url1 = "/modules/reportes/reporteGerencialDiv03.php?id="+id+"&ejec="+n;
	window.open(url1,"","width=1220,height=650");
}
function descargaReporteGerencialDiv03(id,nombre,orc_nomcorto){
	var url2 = "/modules/reportes/descargaReporteGerencialDiv03.php?id="+id+"&name="+nombre+"&nameprov="+orc_nomcorto+"&ejec="+n;
	window.open(url2,"","");
}
function estado(est,id){
	console.log("estado :" +est + ", Id:" + id);
	var num = est;
	var respuesta = verificarObs(id,num);

	if ( respuesta == true) {
		$.ajax({
		type: 'get',
		url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=ordencompra&id='+id,
		data: {num},
		beforeSend: function(){
			//alert('Procesando los datos');
			$('#btn-estados').css('pointer-events','none');
			$('#btn-estados').css('background','#CCC');
			$('.loader').addClass('is-active');
		},
		success: function(data) {	
			debugger;		
			$('.loader').removeClass('is-active');
			data = data.replace('$','');
			var desctot = $.parseJSON(data);
			desctot = desctot.split('~');
			if(desctot[0] == 100){
				if(desctot[1] == 0){
	        		if(desctot[2] == desctot[3]){
	        			if(desctot[4] == '200'){
	        				console.log('conexion exitosa, num = ' + desctot);
	    	        		location.reload(true);
	        			}else{
	        				$('#btn-estados').css('pointer-events','visible');
		     				var titulo = "Aprobadores";				
							var mensaje = "Alerta!!! No se pudo enviar la informacion.";
							var cuerpo = "<br><center > <p class='text-info'><strong>No se realizo el envio de la Orden de compra a ProccessMaker.</strong></p>";
							cuerpo += "<br><p >Consulte con su Administrador</p> </center>";
							ventanaModal(data,titulo,cuerpo,mensaje);
			      			$('#modalOrdenCompra').modal('show');
	        			}	    
	  				}else{
	     				//$('#alertapp').append('<div class="alert alert-danger"><button type="button" style=" background: transparent !important; color: #000000!important; padding-left: 10px; " class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Verifique los precios totales de <strong>Productos</strong> '+ desctot[3] + ' y <strong>Plan de Pagos</strong> '+ desctot[2] + '</div>');
	     				$('#btn-estados').css('pointer-events','visible');
	     				var titulo = "Productos - Plan de Pagos";				
						var mensaje = "Alerta!!! Los montos totales de sus Productos y Plan de pago no son iguales";
						var cuerpo = "<br><center > <p class='text-info'><strong>Para contiuar sus montos deben ser iguales.</strong></p>";
						cuerpo += "<br><p >  Productos precio Total = " + desctot['3'] + ", Plan de pagos suma de los Montos = " + desctot['2'] + " </p> </center>";
						ventanaModal(data,titulo,cuerpo,mensaje);
		      			$('#modalOrdenCompra').modal('show');
	  				}
				}else{
					console.log('conexion exitosa, num = ' + desctot);
					console.log('Error Proyecto');
					if(desctot[1] == 1){
	  					//$('#alertapp').append('<div class="alert alert-danger"><button type="button" style=" background: transparent !important; color: #000000!important; padding-left: 10px; " class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <strong>Tiene</strong> ' + desctot[1] + ' Proyecto mal registrado en Productos</div>');
	  					$('#btn-estados').css('pointer-events','visible');
	  					var titulo = "Productos";				
						var mensaje = "<strong>Alerta!!! Tiene " + desctot[1] + " Proyecto no registrado</strong>";
						var cuerpo = "<br><center > <p class='text-info'><strong>Todos sus items deben estar relacionados \"registrados\" con un Proyeco / CO existente en el sistema.</strong></p>";
						cuerpo += "<br><p > Para cambiar de estado <b>\"Borrador\"</b> a \"Aprobacion\" de su Orden de compra, registre los proyectos.</p> </center>";
						ventanaModal(data,titulo,cuerpo,mensaje);
			  			$('#modalOrdenCompra').modal('show');
					}else{	
	  					//$('#alertapp').append('<div class="alert alert-danger"><button type="button" style=" background: transparent !important; color: #000000!important; padding-left: 10px; " class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <strong>Tiene</strong> ' + desctot[1] + ' Proyectos mal registrados en Productos</div>');
	   					$('#btn-estados').css('pointer-events','visible');
	   					var titulo = "Productos";				
						var mensaje = "<strong>Alerta!!! Tiene " + desctot[1] + " Proyecto no registrado</strong>";
						var cuerpo = "<br><center > <p class='text-info'><strong>Todos sus items deben estar relacionados \"registrados\" con un Proyeco / CO existente en el sistema.</strong></p>";
						cuerpo += "<br><p > Para cambiar de estado <b>\"Borrador\"</b> a \"Aprobacion\" de su Orden de compra, registre los proyectos.</p> </center>";
						ventanaModal(data,titulo,cuerpo,mensaje);
			  			$('#modalOrdenCompra').modal('show');
					}
				}
			}else{
				//$('#alertapp').append('<div class="alert alert-danger"><button type="button" style=" background: transparent !important; color: #000000!important; padding-left: 10px; " class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
				$('#btn-estados').css('pointer-events','visible');
				var titulo = "Plan de Pagos";				
				var mensaje = " La suma de los porcetajes de su <b>plan de pagos</b>, no coincide con el 100% = " + desctot['3'] + " del momnto toal de <b>Productos</b>";
				var cuerpo = "<br><center > <p class='text-info'><strong>Complete el 100 % del Plan de Pagos</strong></p>";
				cuerpo += "<br><p >  Suma de % de plan de pagos = " + desctot['0'] + " %, equivalente a un monto de " + desctot['2'] + " </p> </center>";
				ventanaModal(data,titulo,cuerpo,mensaje);
      			$('#modalOrdenCompra').modal('show');
			}
		}
		});
	}
	else {
		alert(respuesta);
	}
}

function verificarObs(id,estado){
	//var estado = false;
	var Resp = true;
	$.ajax({
		url: 'index.php?to_pdf=true&module=SCO_OrdenCompra&action=verificarEstado&id='+id,
		type: 'GET',
		data: {estado},
		dataType: 'json',
		success:function(data) {
			Resp = data['r'];
		}
	});
	return Resp;
}

//Estructura de la vista de la venta modal
function ventanaModal(datos,titulo,cuerpo,mensaje){
    console.log(datos);
	    var htmlm = '';
	    htmlm += '<div class="modal fade" id="modalOrdenCompra" style="display: block;margin-top: 5%;">';
	    htmlm += '    <div class="modal-dialog">';
	    htmlm += '        <div class="modal-content">';
	    htmlm += '            <div class="modal-header" style="background:#a94442 !important;">';
	    htmlm += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	    htmlm += '                <h4 class="modal-title">'+titulo+'</h4>';
	    htmlm += '            </div>';
	    htmlm += '            <div class="modal-body" >';
	    htmlm += '                <div class="panel panel-danger">';
    	htmlm += '                    <div class="panel-heading">' + mensaje+ '</div>';
    	htmlm += '                    <div class="panel-body"style="padding: 10px;">';
    	htmlm += '						<div id="datosModal">' + cuerpo + '</div>';
		htmlm += '					  </div>';
		htmlm += '				   </div>';
	    htmlm += '            </div>';
	    htmlm += '        </div>';
	    htmlm += '    </div>';
	    htmlm += '</div>';    
    $("#ventanaModal").html(htmlm);
}





