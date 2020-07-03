/**
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2020
*@license /var/www/html/modules/SCO_Consolidacion
*Descripcion: 
**/
var jsonDatos = {};
var div = '03';
vista();

//Carga dom de consolidacion.html
$("#consolidacion").load('modules/SCO_Consolidacion/consolidacionProductosList.html');

//despliega ventana emergente de Suitecrm Modulo Proveedor
function openProveedorPopup(ln) {
    var popupRequestData = {
        "call_back_function": "set_return",
        "form_name": "formPro",
        "field_to_name_array": {
            "id": "proy_id" + ln,
            "name": "pory_cod" + ln,
        }
    };
    open_popup('SCO_Proveedor', 600, 400, '&proyc_division_advanced[]='+div, true, false, popupRequestData, true);
}

//despliega ventana emergente de Suitecrm Modulo User
function openUsersPopup() {
    var popupRequestData = {
        "call_back_function": "set_return",
        "form_name": "formPro",
        "field_to_name_array": {
            "id": "solicitante_id",
            "name": "solicitante",
        }
    };
    open_popup('Users', 600, 400, '', true, false, popupRequestData, true);
}

//despliega ventana emergente de Suitecrm Modulo Contacts
function openContactsPopup() {
    var popupRequestData = {
        "call_back_function": "set_return",
        "form_name": "formPro",
        "field_to_name_array": {
            "id": "contactoProveedor_id",
            "name": "contactoProveedor",
        }
    };
    open_popup('Contacts', 600, 400, '&proyc_division_advanced[]='+div, true, false, popupRequestData, true);
}

//Serializa los datos del formulario
$("#formPro").submit(function(e) {
    jsonDatos.items = consProducto2;
    e.preventDefault();        
    var datos = $(this).serializeArray();
    $(datos).each(function(index, obj){        
        jsonDatos[obj.name] = obj.value;
    });
    //console.log(jsonDatos);  
    
    console.log(jsonDatos.items.length);
    if(jsonDatos.items.length != 0){
        $.validator.setDefaults({
          debug: true,
          success: "valid"
        });  
        var valida = validaCampos();
        if(valida == true){
            ventanaModal(jsonDatos);
            $('#modalConfirmacion').modal('show');
        }else{
            //alert("Error campos vacios");
        } 
    }else{
        alert("Tiene que consolidar al menos 1 item");
    }
});

//Validacion de fomrulario
function validaCampos(){  
    $("#formPro").validate({
        rules: {
            nombre: "required",
            solicitante: "required",
            solicitante_id: "required",
            desc: "required",
            contactoProveedor: "required",
            contactoProveedor_id: "required",
            modTransporte: "required",
            incoterm: "required",
            lugarEntrega: "required",
            moneda: "required",
            multas: "required",
            formaPago: "required",
            tiempoEntrega: "required",
            garantia: "required",
            cantidadTotal: "required",
            precioTotalFob: "required",
            proveedor: "required",
            proveedor_id: "required",
            nombreEmpresa: "required",
            fax: "required",
            box: "required",
            pais: "required",
            telefono: "required",
            direccion: "required"
        },
        messages: {
            nombre: "Campo requerido",
            solicitante: "Campo requerido",
            solicitante_id: "Campo requerido",
            desc: "Campo requerido",
            contactoProveedor: "Campo requerido",
            contactoProveedor_id: "Campo requerido",
            modTransporte: "Campo requerido",
            incoterm: "Campo requerido",
            lugarEntrega: "Campo requerido",
            moneda: "Campo requerido",
            multas: "Campo requerido",
            formaPago: "Campo requerido",
            tiempoEntrega: "Campo requerido",
            garantia: "Campo requerido",
            cantidadTotal: "Campo requerido",
            precioTotalFob: "Campo requerido",
            proveedor: "Campo requerido",
            proveedor_id: "Campo requerido",
            nombreEmpresa: "Campo requerido",
            fax: "Campo requerido",
            box: "Campo requerido",
            pais: "Campo requerido",
            telefono: "Campo requerido",
            direccion: "Campo requerido"
        },
        submitHandler: function(form) {
          form.submit();
        }
    });       

    if($("#formPro").valid()){        
        return true;
    }else{        
        return false;
    }
}

//Estructura de la vista de Orden de compra
function vista(){
    var html = '';
    html += '<div class="container-fluid">';
    html += '    <form id="formPro" method="post" >';
    html += '        <div class="row">';
    html += '            <div class="col-sm-4">';
    html += '                <!--<button type="button" class="btn-sm btn-danger">Cancelar</button>-->';
    html += '            </div>';
    html += '            <div class="col-sm-4">';
    html += '            </div>';
    html += '            <div class="col-sm-4">';
    html += '                <input type="submit" class="btn-sm btn-info" value="Guardar">';
    html += '            </div>';
    html += '        </div>';
    html += '        <div class="row">';
    html += '            <div class="col-sm-8">';
    html += '                <div class="panel panel-info">';
    html += '                    <div class="panel-heading">Consolidacion</div>';
    html += '                    <div class="panel-body">';
    html += '                        <div class="row">                           ';
    html += '                            <div class="col-sm-6">                              ';
    html += '                                <div class="form-group"> ';
    html += '                                    <div class="col-sm-4 " >Nombre:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >';
    html += '                                        <input type="text" name="nombre" id="nombre"  maxlength="255" value="" title="" >';
    html += '                                    </div>';
    html += '                                </div>                              ';
    html += '                            </div>';
    html += '                            <div class="col-sm-6">';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Solicitante:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-6 " >                                        ';
    html += '                                        <input type="text"   autocomplete="off" type="text" name="solicitante" id="solicitante" class="desabilidato">';
    html += '                                        <input type="hidden" name="solicitante_id" id="solicitante_id" size="20" maxlength="50" value="">';
    html += '                                        <button class="btn-success btn-sm cons-btn"  title="Seleccionar" accesskey="T" type="button" tabindex="116"  onclick="openUsersPopup();"><img src="themes/default/images/id-ff-select.png" alt="Seleccionar"></button>';
    html += '                                    </div>';
    html += '                                </div>';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <div class="row">                           ';
    html += '                            <div class="col-sm-6">  ';
    html += '                                <div class="form-group">                                    ';
    html += '                                    <div class="col-sm-4 " >Descripción:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >';
    html += '                                        <textarea  rows="4" cols="5" id="desc"  maxlength="255" name="desc"></textarea>';
    html += '                                    </div>';
    html += '                                </div>  ';
    html += '';
    html += '                            </div>';
    html += '                            <div class="col-sm-6">';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Contacto de Proveedor:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-6 " >';
    html += '                                        <input type="text" id="contactoProveedor" name="contactoProveedor" class="desabilidato">';
    html += '                                        <input type="hidden" name="contactoProveedor_id" id="contactoProveedor_id" size="20" maxlength="50" >';
    html += '                                        <button class="btn-success btn-sm cons-btn"  title="Seleccionar" accesskey="T" type="button" tabindex="116"  onclick="openContactsPopup();"><img src="themes/default/images/id-ff-select.png" alt="Seleccionar"></button>';
    html += '                                    </div>';
    html += '                                </div>';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <div class="row">                           ';
    html += '                            <div class="col-sm-6">  ';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Modalidad transporte:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >                                        ';
    html += '                                        <input type="text"  id="modTransporte" name="modTransporte"  >';
    html += '                                    </div>      ';
    html += '                                </div>                      ';
    html += '                            </div>';
    html += '                            <div class="col-sm-6">';
    html += '';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <br>';
    html += '                    </div>';
    html += '                </div>';
    html += '                <div class="panel panel-info">';
    html += '                    <div class="panel-heading">Terminos de compra</div>';
    html += '                    <div class="panel-body">';
    html += '                        <div class="row">                                               ';
    html += '                            <div class="col-sm-6">';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Incoterm:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >                                        ';
    html += '                                        <select name="incoterm" id="incoterm" title="">';
    html += '                                            <option label="No aplica" value="No aplica">No aplica</option>';
    html += '                                            <option label="CIF" value="CIF">CIF</option>';
    html += '                                            <option label="EXW" value="EXW">EXW</option>';
    html += '                                            <option label="FOB" value="FOB">FOB</option>';
    html += '                                            <option label="DAP" value="DAP">DAP</option>';
    html += '                                            <option label="DDP" value="DDP">DDP</option>';
    html += '                                            <option label="FCA" value="FCA">FCA</option>';
    html += '                                            <option label="CFR" value="CFR">CFR</option>';
    html += '                                            <option label="CPT" value="CPT">CPT</option>';
    html += '                                            <option label="CIP" value="CIP">CIP</option>';
    html += '                                            <option label="DAT" value="DAT">DAT</option>';
    html += '                                            <option label="FAS" value="FAS">FAS</option>';
    html += '                                        </select>';
    html += '                                    </div>                             ';
    html += '                                </div>  ';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Lugar de entrega:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >';
    html += '                                        <input type="text"  id="lugarEntrega" name="lugarEntrega">';
    html += '                                    </div>      ';
    html += '                                </div>                                  ';
    html += '                            </div>';
    html += '                            <div class="col-sm-6">';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Meneda:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >                                        ';
    html += '                                        <select name="moneda" id="moneda" title="">';
    html += '                                            <option label="USD" value="USD">USD</option>';
    html += '                                            <option label="BOB" value="BOB">BOB</option>';
    html += '                                            <option label="EUR" value="EUR">EUR</option>';
    html += '                                        </select>';
    html += '                                    </div>      ';
    html += '                                </div>  ';
    html += '                                <div class="form-group">';
    html += '                                    <div class="col-sm-4 " >Multas:<span class="required">*</span></div>';
    html += '                                    <div class="col-sm-8 " >';
    html += '                                        <input type="text"  id="multas" name="multas">';
    html += '                                    </div>      ';
    html += '                                </div>  ';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-2 " >Forma de pago:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-3 " >';
    html += '                                    <input type="text"  id="formaPago" name="formaPago">';
    html += '                                </div>      ';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <div class="row">   ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-2 " >Tiempo de entrega:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-3 " >';
    html += '                                    <input type="text"  id="tiempoEntrega" name="tiempoEntrega">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-2 " >Garantia:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-3 " >';
    html += '                                    <input type="text"  id="garantia" name="garantia">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                    </div>';
    html += '                    <br>';
    html += '                </div>';
    html += '            </div>';
    html += '            <div class="col-sm-4">';
    html += '                <div class="panel panel-success">';
    html += '                    <div class="panel-heading">Items</div>';
    html += '                    <div class="panel-body">';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-4 " >Cantidad total:</div>';
    html += '                                <div class="col-sm-2 " >';
    html += '                                    <input type="text"  id="cantidadTotal" id="cantidadTotal" name="cantidadTotal" value="" class="desabilidato">';
    html += '                                </div>      ';
    html += '                            </div>';
    html += '                        </div>';
    html += '                        <div class="row">                   ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-4 " >Precio FOB total:</div>';
    html += '                                <div class="col-sm-2 " >';
    html += '                                    <input type="text"  id="precioTotalFob" id="precioTotalFob" name="precioTotalFob" value="" class="desabilidato">';
    html += '                                </div>  ';
    html += '                            </div>';
    html += '                        </div>';
    html += '                    </div>  ';
    html += '                    <br>                ';
    html += '                </div>';
    html += '                <div class="panel panel-success">';
    html += '                    <div class="panel-heading">Proveedor</div>';
    html += '                    <div class="panel-body">';
    html += '                        <div class="row">                   ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-4 " >Proveedor:</div>';
    html += '                                <div class="col-sm-7 " >';
    html += '                                    <input type="text"  id="proveedor" name="proveedor" value="NOMBRE DE PROVEEDOR" class="desabilidato">';
    html += '                                    <input type="hidden"  id="proveedor_id" name="proveedor_id" value="NOMBRE DE PROVEEDOR" >';
    html += '                                </div>  ';
    html += '                            </div>';
    html += '                        </div>';
    html += '                    </div>';
    html += '                    <br>';
    html += '                </div>';
    html += '                <div class="panel panel-success">';
    html += '                    <div class="panel-heading">Datos de facturación</div>';
    html += '                    <div class="panel-body">';
    html += '                        <div class="row">                           ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >Nombre empresa:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="nombreEmpresa" name="nombreEmpresa" value="Sociedad Comercial e Industrial Hansa Ltda.">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                        <div class="row">                       ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >Fax:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="fax" name="fax" value="(591-2) 211 2264">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >P.O.Box:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="box" name="box" value="Box 10800">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >Pais:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="pais" name="pais" value="La Paz - Bolivia">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >Telefefono:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="telefono" name="telefono" value="(591-2) 214 9000">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                        <div class="row">';
    html += '                            <div class="form-group">';
    html += '                                <div class="col-sm-3 " >Direccion:<span class="required">*</span></div>';
    html += '                                <div class="col-sm-9 " >';
    html += '                                    <input type="text"  id="direccion" name="direccion" value="Calle Mercado Nro. 1004">';
    html += '                                </div>      ';
    html += '                            </div>  ';
    html += '                        </div>';
    html += '                    </div>';
    html += '                    </br>';
    html += '                </div>';
    html += '            </div>';
    html += '        </div>';
    html += '        <div class="row">';
    html += '            <div class="col-sm-4">';
    html += '                <!--<button type="button" class="btn-sm btn-danger">Cancelar</button>-->';
    html += '            </div>';
    html += '            <div class="col-sm-4">';
    html += '            </div>';
    html += '            <div class="col-sm-4">';
    html += '                <input type="submit" class="btn-sm btn-info" value="Guardar">';
    html += '            </div>';
    html += '        </div></br></br></br></br></br></br></br>';
    html += '        </div>';
    html += '    </form>';
    html += '</div>';

    $("#ordeCompra").append(html);
}

//Estructura de la vista de la venta modal
function ventanaModal(jsonDatos){  
    console.log(jsonDatos)
    var html = '';
    html += '<div class="modal fade" id="modalConfirmacion">';
    html += '    <div class="modal-dialog">';
    html += '        <div class="modal-content">';
    html += '            <div class="modal-header">';
    html += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    html += '                <h4 class="modal-title">Guardar registros</h4>';
    html += '            </div>';
    html += '            <div class="modal-body">';
    html += '               <center><span>Seguro que desea guardar la informacion?</span></center></br>';
    html += '               <div class="row">';
    html += '                   <div class="form-group"> ';
    html += '                   <div class="col-sm-6">';
    html += '                       Consolidacion:';
    html += '                   </div>';
    html += '                   <div class="col-sm-6">';
    html += '                       <input type="text"  id="nombreConsolidacion" name="nombreConsolidacion" value="'+jsonDatos.nombre+'" disabled>';
    html += '                   </div>';
    html += '                   </div>';
    html += '               </div>';
    html += '               <div class="row">';
    html += '                   <div class="form-group"> ';
    html += '                   <div class="col-sm-6">';
    html += '                       Orden de Compra:';
    html += '                   </div>';
    html += '                   <div class="col-sm-6">';
    html += '                       <input type="text"  id="nombreOc" name="nombreOc" value="'+jsonDatos.nombre+'" disabled>';
    html += '                   </div>';
    html += '                   </div>';
    html += '               </div>';
    html += '            </div>';
    html += '            <div class="modal-footer">';
    html += '            <div class="row">';
    html += '               <div class="col-sm-6">';
    html += '                   <button type="button" class="btn-sm btn-danger" data-dismiss="modal">Cancelar</button>';
    html += '               </div>';
    html += '               <div class="col-sm-6">';
    html += '                   <button type="button" class="btn-sm btn-primary" onclick=envioDeDatos(jsonDatos);>Confirmar y Guardar</button>';
    html += '               </div>';
    html += '            </div>';
    html += '        </div>';
    html += '    </div>';
    html += '</div>';    

    $("#ventanaModal").html(html);
}

//Envio de datos al archivo consolidacionDatos.php
function envioDeDatos(jsonDatos){        
    //alert(jsonDatos);
    console.log("Datos enviados" + jsonDatos);   
    $.ajax({
        type: "POST",
        url: "index.php?to_pdf=true&module=SCO_Consolidacion&action=consolidacionDatos",        
        data: {id:jsonDatos},
        beforeSend:function(){
            $(".loader").addClass("is-active");
        },
        success: function(e) {
            console.log("datos de vuelta" + e);
            var id = e;
            $(".loader").removeClass("is-active");
            $("#modalConfirmacion").modal("hide"); 
            alert("Se redireccionara a su vista creada"+id);
            //$(location).attr("href","index.php?module=SCO_Consolidacion&action=DetailView&record="+id);
        },
        error: function(data) {
            $(".loader").removeClass("is-active");
            alert("Error");
        }
    });
}