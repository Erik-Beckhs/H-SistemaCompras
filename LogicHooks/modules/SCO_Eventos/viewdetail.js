//Filtro, filtra el rango de fechas
$(document).ready(function(){
	$('#datepicker').datepicker();
});


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

function ventanaModalFecha(){
	    var htmlf = '';
	    htmlf += '<div class="modal fade" id="modalEventoFecha" style="display: block;margin-top: 5%;">';
	    htmlf += '    <div class="modal-dialog">';
	    htmlf += '        <div class="modal-content">';
	    htmlf += '            <div class="modal-header">';
	    htmlf += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	    htmlf += '                <h4 class="modal-title">Registro de fecha</h4>';
	    htmlf += '            </div>';
	    htmlf += '            <div class="modal-body" >';
	    htmlf += '                <div class="panel panel-info">';
    	htmlf += '                    <div class="panel-heading">Mensaje</div>';
    	htmlf += '                    <div class="panel-body"style="padding: 10px;">';
	    htmlf += '                        <div class="row">';
	    htmlf += '                            <div class="col-sm-12">  ';
	    htmlf += '                                <div class="form-group">                                    ';
	    htmlf += '                                    <div class="col-sm-4 " >Fecha real:<span class="required">*</span></div>';
	    htmlf += '                                    <div class="col-sm-8 " >';
    	htmlf += '                                        <input type="text" class="custom-input" id="datepicker" name="fechaReal">';
	    htmlf += '                                    </div>';
	    htmlf += '                                </div>  ';
	    htmlf += '';
	    htmlf += '                            </div>';
	    htmlf += '                        </div>';
	    htmlf += '                        <div class="row">';
	    htmlf += '                            <div class="col-sm-12">';
	    htmlf += '                                <div class="form-group">';
	    htmlf += '                                    <div class="col-sm-4 " >Contacto de Proveedor:<span class="required">*</span></div>';
	    htmlf += '                                    <div class="col-sm-6 " >';
	    htmlf += '                                        <input type="text" id="contactoProveedor" name="contactoProveedor" class="desabilidato">';
	    htmlf += '                                        <input type="hidden" name="contactoProveedor_id" id="contactoProveedor_id" size="20" maxlength="50" >';
	    htmlf += '                                        <button class="btn-success btn-sm cons-btn"  title="Seleccionar" accesskey="T" type="button" tabindex="116"  onclick="openContactsPopup();"><img src="themes/default/images/id-ff-select.png" alt="Seleccionar"></button>';
	    htmlf += '                                    </div>';
	    htmlf += '                                </div>';
	    htmlf += '                            </div>';
	    htmlf += '                        </div>';
	    htmlf += '                        <br>';
	    htmlf += '                    </div>';


    	htmlf += '            <div class="row">';
		htmlf += '               <div class="col-sm-6">';
		htmlf += '                   <button type="button" class="btn btn-sm btn-danger" style="width: 100%;background: #dc3545;color:#fff;border:solid 1px#dc3545;" data-dismiss="modal">Cancelar</button>';
		htmlf += '               </div>';
		htmlf += '               <div class="col-sm-6">';
		htmlf += '                   <button type="button" class="btn btn-sm btn-verde" style="width: 100%;background: #31708f;color:#fff;" onclick=envioDeformulario();>Confirmar y Enviar</button>';
		htmlf += '               </div>';
		htmlf += '            </div>';

		htmlf += '					  </div>';
		htmlf += '				   </div>';
	    htmlf += '            </div>';
	    htmlf += '        </div>';
	    htmlf += '    </div>';
	    htmlf += '</div>';    
    $("#modalFecha").html(htmlf);
    $('#datepicker').datepicker();
    $('#modalEventoFecha').modal('show');
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
