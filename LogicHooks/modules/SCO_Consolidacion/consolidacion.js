//var div = $("#division").val();

var div = '03';

//Carga dom de consolidacion.html
$("#capa").load('modules/SCO_Consolidacion/consolidacion.html');

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


function consolidacionGuardar(){
    
    var datos = JSON.stringify($("#myForm").serializeArray());
    alert("Sending Json"+datos);
}
