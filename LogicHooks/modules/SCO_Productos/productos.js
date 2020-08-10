var inc = 0;
var dataArray = new Array();
function openProductPopup(inc) {
    console.log(inc);
    var popupRequestData = {
        "call_back_function": "set_return",
        "form_name": "form_ SubpanelQuickCreate_SCO_Productos",
        "field_to_name_array": {
            "sco_productos_sco_productoscomprassco_productoscompras_ida": "0-" + inc,
            "name": "1-" + inc,
        }
    };
    open_popup("SCO_ProductosCompras", 600, 400, "2", true, false, popupRequestData, true);
}
//Ajax de carga masiva
function buscap(nomp, row) {
    $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscap',
        data: {
            nomp
        },
        success: function(data) {
            //debugger;
            var sqlprod = $.parseJSON(data);
            if (Object.keys(sqlprod) != '') {
                if (sqlprod.length == 1) {
                  $('#0-' + row).text(sqlprod[0]['name']);
                  $('#1-' + row).text(sqlprod[0]['proge_nompro'].replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
                  $('#2-' + row).text(sqlprod[0]['proge_unidad']);
                  $('#4-' + row).text(sqlprod[0]['proge_preciounid']);
                  $('#9-' + row).text(sqlprod[0]['id']);
                  $('#12-' + row).text(sqlprod[0]['proge_codaio']);
                  //$('#4-'+row).text(sqlprod['proge_preciounid']);
                  $('#0-' + row).css({
                      'background': '#FFF',
                      'color': '#000'
                  });
                }
                if (sqlprod.length > 1) {
                  //alert("codigo duplicado");
                  var html = '';
                  $("#codprdup").modal("show");
                  for (var i = 0; i < sqlprod.length; i++) {
                    html+= "<h5>"+sqlprod[i]["name"]+" "+sqlprod[i]["proge_nompro"]+" "+sqlprod[i]["proge_subgrupo"]+"-"+sqlprod[i]["proge_codaio"]+" <button type='button' name='button' class='btn btn-xs btn-success' onclick='codproducto("+i+","+row+","+"1"+")'>Seleccionar</button></h5>";
                  }
                  $("#duplicados").html(html);
                  dataArray = sqlprod;
                }
            } else {
                alert('El Codigo de Proveedor no existe');
                $('#0-' + row).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
                $('#1-' + row).text('');
                $('#2-' + row).text('');
                $('#4-' + row).text('');
            }
        }
    });
    return (false);
}

function buscaproy(nomproy, row) {
    $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscaproy',
        data: {
            nomproy
        },
        success: function(data) {
            var sqlproy = $.parseJSON(data);
            if (Object.keys(sqlproy) != '') {
                debugger;
                $('#10-' + row).text(sqlproy['id']);
                $('#11-' + row).text(sqlproy['proyc_tipo']);
                $('#8-' + row).css({
                    'background': '#FFF',
                    'color': '#000'
                });
                //$('.action_buttons #SCO_Productos_subpanel_save_button').attr('disabled', false);
            } else {
                alert('El Proyecto no existe en la Base de Datos');
                $('#8-' + row).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
            }
        }
    });
    return (false);
}
//Ajax de carga individual

function buscaind(pronum) {
    var nomp = document.getElementById('pro_nombre' + pronum).value;
    nomp = nomp.trim();
    $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscap',
        data: {
            nomp
        },
        success: function(data) {
            var sqlprod = $.parseJSON(data);
            console.log(sqlprod);
            if (Object.keys(sqlprod) != '') {
                //debugger;
                if (sqlprod.length == 1) {
                  $('#producto_id' + pronum).val(sqlprod[0]['id']);
                  $('#pro_nombre' + pronum).val(sqlprod[0]['name']);
                  $('#pro_codaio' + pronum).val(sqlprod[0]['proge_codaio']);
                  $('#pro_unidad' + pronum).val(sqlprod[0]['proge_unidad']);
                  $('#pro_descripcion' + pronum).val(sqlprod[0]['proge_nompro'].replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
                  $('#pro_precio' + pronum).val(sqlprod[0]['proge_preciounid']);
                  $('#pro_nombre' + pronum).css({
                      'background': '#FFF',
                      'color': '#000'
                  });
                  $('#pro_unidad' + pronum).css({
                      'background': '#FFF',
                      'color': '#000'
                  });
                  $('#pro_descripcion' + pronum).css({
                      'background': '#FFF',
                      'color': '#000'
                  });
                }
                if (sqlprod.length > 1) {
                  //alert("codigo duplicado");
                  var html = '';
                  $("#codprdup").modal("show");
                  for (var i = 0; i < sqlprod.length; i++) {
                    html+= "<h5>"+sqlprod[i]["name"]+" "+sqlprod[i]["proge_nompro"]+" "+sqlprod[i]["proge_subgrupo"]+"-"+sqlprod[i]["proge_codaio"]+" <button type='button' name='button' class='btn btn-xs btn-success' onclick='codproducto("+i+","+pronum+","+"0"+")'>Seleccionar</button></h5>";
                  }
                  $("#duplicados").html(html);
                  dataArray = sqlprod;
                }

            } else {
                if($('#pro_nombre' + pronum).val() != ''){
                  alert('El Codigo de Proveedor no existe');
                }
                $('#proge_codaio' + pronum).val('');
                $('#producto_id' + pronum).val('');
                $('#pro_unidad' + pronum).val('');
                $('#pro_descripcion' + pronum).val('');
                $('#pro_nombre' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
                $('#pro_unidad' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
                $('#pro_descripcion' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
            }
        }
    });
}
function codproducto(index,num,tipo) {
  // console.log(dataArray[index]);
  // alert(index+","+dataArray[index]["proge_unidad"]+" num"+num);
  if (tipo == 0) {
    $("#codprdup").modal("hide");
    $('#producto_id' + num).val(dataArray[index]['id']);
    $('#pro_codaio' + num).val(dataArray[index]['proge_codaio']);
    $('#pro_unidad' + num).val(dataArray[index]['proge_unidad']);
    $('#pro_descripcion' + num).val(dataArray[index]['proge_nompro'].replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
    $('#pro_precio' + num).val(dataArray[index]['proge_preciounid']);
    $('#pro_nombre' + num).css({
        'background': '#FFF',
        'color': '#000'
    });
    $('#pro_unidad' + num).css({
        'background': '#FFF',
        'color': '#000'
    });
    $('#pro_descripcion' + num).css({
        'background': '#FFF',
        'color': '#000'
    });
  }
  if (tipo == 1) {
    $("#codprdup").modal("hide");
    $('#1-' + num).text(dataArray[index]['proge_nompro'].replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
    $('#2-' + num).text(dataArray[index]['proge_unidad']);
    $('#4-' + num).text(dataArray[index]['proge_preciounid']);
    $('#9-' + num).text(dataArray[index]['id']);
    $('#12-' + num).text(dataArray[index]['proge_codaio']);
    //$('#4-'+row).text(sqlprod['proge_preciounid']);
    $('#0-' + num).css({
        'background': '#FFF',
        'color': '#000'
    });
  }

}

function buscanombreprod(pronum) {
    var descrp = document.getElementById('pro_descripcion' + pronum).value;
     $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscanombreprod',
        data: {
            descrp, pronum
        },
        success: function(data) {
            //debugger;
            console.log(data);
            if(data != ''){
              $('#search_desc' + pronum).html('<div style="width: 500px; heigth:100px; margin-left:-225px; border: solid 1px #ccc; position: absolute; overflow: auto; background: #FFF; padding: 5px; z-index: 1000;">'+data+'</div>');
              $('#pro_nombre' + pronum).css({
                    'background': '#FFF',
                    'color': '#000'
                });
                $('#pro_unidad' + pronum).css({
                    'background': '#FFF',
                    'color': '#000'
                });
                $('#pro_descripcion' + pronum).css({
                    'background': '#FFF',
                    'color': '#000'
                });
            }else{
              $('#search_desc' + pronum).html('<span style="font-size:12px;"class="label label-danger">No existen coincidencias</span>');
            }
            $("#content").on('click', function(){
              $('#search_desc' + pronum).html('');
            });
        }
    });
}
function productos(pronum,id, name, proge_nompro, proge_unidad, proge_preciounid){
  //debugger;
  $('#pro_nombre' + pronum).val(name);
  $('#producto_id' + pronum).val(id);
  $('#pro_descripcion' + pronum).val(proge_nompro.replace(/&quot;/g,'\"').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
  $('#pro_unidad' + pronum).val(proge_unidad);
  $('#pro_precio' + pronum).val(proge_preciounid);
  console.log(id, name, proge_nompro, proge_unidad, proge_preciounid);
}

function buscaindpy(pronum) {
    var nomproy = document.getElementById('pory_cod' + pronum).value;
    $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscaproy',
        data: {
            nomproy
        },
        success: function(data) {
            //debugger;
            var sqlproy = $.parseJSON(data);
            if (Object.keys(sqlproy) != '') {
                $('#proy_id' + pronum).val(sqlproy['id']);
                $('#tipo_proy' + pronum).val(sqlproy['proyc_tipo']);
                $('#pory_cod' + pronum).css({
                    'background': '#FFF',
                    'color': '#000'
                });
                $('#tipo_proy' + pronum).css({
                    'background': '#EEE',
                    'color': '#000'
                });
                //$('.action_buttons #SCO_Productos_subpanel_save_button').attr('disabled', false);
            } else {
                 $('#proy_id' + pronum).val('');
                $('#tipo_proy' + pronum).val('');
                $('#pory_cod' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
                $('#tipo_proy' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
            }
        }
    });
}
function buscaindpy2(pronum) {
    var nomproy = document.getElementById('pory_cod' + pronum).value;
    $.ajax({
        type: 'get',
        url: 'index.php?to_pdf=true&module=SCO_Productos&action=buscaproy',
        data: {
            nomproy
        },
        success: function(data) {
            //debugger;
            var sqlproy = $.parseJSON(data);
            if (Object.keys(sqlproy) != '') {
                $('#proy_id' + pronum).val(sqlproy['id']);
                $('#tipo_proy' + pronum).val(sqlproy['proyc_tipo']);
                $('#pory_cod' + pronum).css({
                    'background': '#FFF',
                    'color': '#000'
                });
                $('#tipo_proy' + pronum).css({
                    'background': '#EEE',
                    'color': '#000'
                });
                //$('.action_buttons #SCO_Productos_subpanel_save_button').attr('disabled', false);
            } else {
                alert('El proyecto no existe');
                $('#proy_id' + pronum).val('');
                $('#tipo_proy' + pronum).val('');
                $('#pory_cod' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
                $('#tipo_proy' + pronum).css({
                    'background': '#d9534f',
                    'color': '#FFF'
                });
            }
        }
    });
}
