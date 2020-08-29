function solicitar(est){
	if($("#list_subpanel_activities .list tbody .oddListRowS1").length == 0){
	//console.log($("#list_subpanel_activities .list tbody .oddListRowS1").length);
	est = "Concluido";
		$.ajax({
			type: "get",
			url: "index.php?to_pdf=true&module=SCO_Eventos&action=fecha_eventos&id="+id_ev,
			data: {est},
			beforeSend: function(){
			//alert("Procesando los datos");
				$("#btn-estados").css("pointer-events","none");
				$("#btn-estados").css("background","#CCC !important");
				$(".loader").addClass("is-active");
			},
			success: function(data) {
				debugger;
				$(".loader").removeClass("is-active");
				var estado = $.parseJSON(data);
  				console.log(estado);
  				if(estado != '404'){
  					if(estado != "error"){
			        	location.reload();
				    }
			    	else {
				      	//alert("No se puede concluir el evento si no coloco una fecha real o un transportista");
						var titulo = "Eventos / hito";
						var mensaje = "ERROR! No se puede concluir el evento.";
						var cuerpo = "<center><p>Verifique que los campos esten registrados adecuadamente.</p>";
							cuerpo += "<p>\"<strong>Fecha Real</strong>\" o \"<strong>Transportista / Agencia Aduanera / Otros:</strong>\"</p></center>"
						ventanaModal(data,titulo,cuerpo,mensaje);
						$('#modalEvento').modal('show');
			    	}
  				}else{
					var titulo = "Eventos / hito";
					var mensaje = "Error de conexion!!!... No se puede concluir el evento";
					var cuerpo = "<br>Consulte con su administrador de sistemas de compras.</p>";
					ventanaModal(data,titulo,cuerpo,mensaje);
					$('#modalEvento').modal('show');
  				}
			    
    		}
    	});
	}else{
			alert("Aun tiene Actividades pendientes");
	}
}


//Estructura de la vista de la venta modal
function ventanaModal(datos,titulo,cuerpo,mensaje){
    console.log(datos);
	    var htmlm = '';
	    htmlm += '<div class="modal fade" id="modalEvento" style="display: block;margin-top: 5%;">';
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
    $("#modalEmbarque").html(htmlm);
}
