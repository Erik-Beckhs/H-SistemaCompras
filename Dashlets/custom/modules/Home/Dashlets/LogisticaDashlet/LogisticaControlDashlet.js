$(document).ready(function() {
 //-----------------------------------Filtros de cabecera------------------------------------//  
  var division = $("#division").val();
  var divCompra = $("#division").val();   
  var logistico = $("#logistico").val();
  var rol = $("#rol").val();
  var aMercado = $("#idamercado_c").val();  
  var estado = 1;

  
  var fecha_ac = $("#fecha_ac").val();
  /*
  if(division == '03'){
    estado = '1'
    $("#estadoEmbarque").val('1');
  }else{
    estado = '1'
  }  */
  if(rol == '1'){
    //divCompra = '01';
    //division = '00';
    aMercado = '00';
    console.log("ROL 1");
  }
  if(rol == '2'){
    division = '00';
    aMercado = '00';

  }
  if(rol == '3'){
    division = '00';   
  }
  //filtrando las areaas de mercado de acuerdo a la division.
  if(divCompra != 00){      
      $('.am').hide();
      $('.'+divCompra+'').show();
  }else{
    
  }
  
  //asigando valor a los filtros
  $("#divCompra").val(divCompra);  
  $("#estadoDiv").val(division);  
  $("#estadoEmbarque").val('1');
  $("#aMercado").val(aMercado);
  
  $("#divCompra").on("change", function(){              
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),'00');
    peticionData($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),'00');
  });
  $("#estadoDiv").on("change", function(){
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());  
    peticionData($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());
  });
  $("#estadoEmbarque").on("change", function(){
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());
    peticionData($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());               
  });
  $("#aMercado").on("change", function(){
    console.log($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());
    peticionData($("#divCompra").val(),$("#estadoDiv").val(),$("#estadoEmbarque").val(),$("#rol").val(),$("#aMercado").val());               
  });

  $("#divCompra").change(function() {  
    $('.am').show();   
    if ($(this).data('options') === undefined) {    
      $(this).data('options', $('#aMercado option').clone());
    }
    var id = $(this).val();  
    var options = $(this).data('options').filter('.'+id+'');      
    $('#aMercado').html(options);
    $('#aMercado').append('<option value="00" selected="selected">Todo</option>');    
  });
  
  //---------------------------------------------------------------------------------//
  $(".mo").hover(function() {    
    $('[data-toggle="popover"]').popover()
  });
  $(".mo").popover({ trigger: "hover" });
  //alert(divCompra+", "+division+", "+estado+", "+rol+", "+aMercado);
  
  peticionData(divCompra,division,estado,rol,aMercado); 
    
  function peticionData(divCompra,division,estadoEmbarque,rol,aMercado){ 
 
   //$('#loader-8').show();
    $.ajax({
          type: 'GET',
          ContentType : 'charset=UTF-8',
          //url: 'custom/modules/SCO_OrdenCompra/envioDatos.php',
          url: '/custom/modules/Home/Dashlets/LogisticaDashlet/LogisticaControlDashlet.data.php',
          data: {            
            divCompra: divCompra,
            division: division,
            estadoEmbarque:estadoEmbarque,
            rol: rol,
            aMercado: aMercado
            },
          async:true,
          beforeSend : function(){                                                    
            
            $("#tabla tbody > tr").remove();
            $('#estadoDiv').prop('disabled', true);
            $('#estadoEmbarque').prop('disabled', true);
            var html = '<tr><td><span style="color:green;font-size:20px;position:absolute;background:#FFF;padding:10px;width: 90%;margin-left: -4px;border-bottom: solid 1px #003c79;">Cargando...</span></td></tr>';
            $("#tabla tbody").append(html);
            $("#cantCompras").text(0);
            $("#cantEmbarques").text(0);
            $("#totalCompras").text(0);
            $("#totalEmbarque").text(0);
          },
          success: function (e) {
          console.log(e);
              //$('#loader-7').hide();               
               $('#estadoEmbarque').prop('disabled', false); 
                          
               if(logistico != 1){
                  $('#estadoDiv').prop('disabled', false);
                }else{
                  $('#estadoDiv').prop('disabled', true);
                  $("#estadoDiv").css("background", "#efe");
                }
              var res = JSON.parse(e);
              if(res != ''){
                listRecepcion(res);             
              }else{
              listRecepcion(res); 
                $("#tabla tbody > tr").remove();
                var html = '<tr><td><span style="color:red;font-size:20px;position:absolute;background:#FFF;padding:10px;width: 90%;margin-left: -4px;border-bottom: solid 1px #003c79;">Sin informacion.</span></td></tr>';
                $("#tabla tbody").append(html);                               
                console.log("Conexion con exito, datos vacios o erroneos");
              }
          },    
          error: function (data) {
              console.log('ERROR, No se pudo conectar', data);                                   
          }
      }).done(function(result){
             $("#estadoDiv").finish();
       $("#estadoEmbarque").finish();
          setTimeout(function(){
            $('.result .content').html(JSON.stringify(result));          
            // ahora escondo el loader
            $('.loader-6').hide();   
          }, 500);
      });
  }
  
  function listRecepcion(data){
      //¿console.log(typeof data == "object");
      var html = '';
      var x = 0;      

      var cantidadEbarque = [];
      data.forEach(function(element,indice,array){         
        element = JSON.parse(element);  
        console.log(element);
        var xEvento = element.eventos.length;
        x++;
        var stepper = '';
        var stepperBody = '';
        var y = 0;    
                                
        if(jQuery.inArray(element.idEmb, cantidadEbarque) >= 0){
        
        }else{
          cantidadEbarque.push(element.idEmb);          
        }
        //console.log("Cantidad Embarques ",cantidadEbarque, cantidadEbarque.length);
        
        //Armado de los hitos del embarque**************************************    
        element.eventos.forEach(function(el,indice,array){               
               
          switch(el.eve_estado) {
            case "Concluido":
              stepperBody += '<div class="md-stepper-horizontal plomo" id="md-stepper-horizontal'+element.idDes+'" >';
              stepperBody += '  <div class="md-step active">';
              stepperBody += '    <div class="md-step-circle mo blanco" data-container="body" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" title="Hito" data-content="'+el.nombre+'"><span style="color:#000!important;">'+el.nombre.substring(0,2)+'</span></div>'; 
              stepperBody += '    <div class="stepper-icono"style="color: #009202 !important;font-weight: bold;"> &#10004;</div>'; 
              stepperBody += '    <span id="evento'+el.id+'" class="subtitulo">'+el.nombre+'</span>';
              stepperBody += '  </div>';  
              stepperBody += '</div>';
              y++          
              break;
            default:
              if(el.eve_fechare > el.eve_fechaplan){              
                  stepperBody += '<div class="md-stepper-horizontal red">';   
                  stepperBody += '  <div class="md-step active">';
                  stepperBody += '    <div class="md-step-circle mo blanco" data-container="body" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top"  title="Hito" data-content="'+el.nombre+'"><span style="color:#000!important;">'+el.nombre.substring(0,2)+'</span></div>'; 
                  stepperBody += '    <div class="stepper-icono"> &#9202;</div>'; 
                  stepperBody += '    <span id="evento'+el.id+'" class="subtitulo">'+el.nombre+'</span>';
                  stepperBody += '  </div>'; 
                  stepperBody += '</div>';
                  
              }else if(el.eve_fechare > el.eve_fechaplan){
                  stepperBody += '<div class="md-stepper-horizontal naranja">';    
                  stepperBody += '  <div class="md-step active">';
                  stepperBody += '    <div class="md-step-circle mo blanco" data-container="body" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" title="Hito" data-content="'+el.nombre+'"><span style="color:#000!important;">'+el.nombre.substring(0,2)+'</span></div>';    
                  stepperBody += '    <span id="evento'+el.id+'" class="subtitulo">'+el.nombre+'</span>';
                  stepperBody += '  </div>'; 
                  stepperBody += '</div>';
              }else{
                  stepperBody += '<div class="md-stepper-horizontal blanco">';    
                  stepperBody += '  <div class="md-step active"  >';
                  stepperBody += '    <div class="md-step-circle mo blanco" data-container="body" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" title="Hito" data-content="'+el.nombre+'"><span style="color:#000!important;">'+el.nombre.substring(0,2)+'</span></div>';     
                  stepperBody += '    <span id="evento'+el.id+'" class="subtitulo">'+el.nombre+'</span>';
                  stepperBody += '  </div>'; 
                  stepperBody += '</div>';
              }
          }   
        });

        if(xEvento == y && element.emb_estado == 3){
          //stepper += '<h3 class="stepper-title"><span id="stepper-title'+element.idDes+'" style="color:#049807 !important;">Concluido</span></h3>'; 
          if( xEvento > 0 ){                         
            stepper += '<div class="hitos">'+stepperBody+'</div>';
          }else{
            stepper += '<center><div class="alert-danger" style="padding:5px 1px; width:170px; color:#fff;">Embarque sin eventos ';
            stepper += '  <span style="position:absolute;font-size:12px;margin-top:10px;margin-left: 25px;"> x</span>';
            stepper += '</div></center>';
          }
        }else{
          if( xEvento == 0 ){
            stepper += '<center><div class="alert-danger" style="padding:5px 1px; width:170px; color:red;">Embarque sin eventos (hitos)';
            stepper += '  <span style="position:absolute;font-size:12px;margin-top:10px;margin-left: 25px;"> x</span>';
            stepper += '</div></center>';
          }else
          {                         
          stepper += '<div class="hitos">'+stepperBody+'</div>';
          }
        }
        var emb_fechacrea = element.emb_fechacrea;
        var fecha_ac = $("#fecha_ac").val();
        //console.log(fecha_ac);
        //console.log(fecha_ac.diff(emb_fechacrea, 'days'), ' dias de diferencia');
        html += '<tr id="row' + element.idOc + '" class="'+element.idEmb+'">'; 
        html += '<td class="Modelo">'+ x +'</td>';
        html += '<td class="Modelo"><a href="index.php?module=SCO_OrdenCompra&action=DetailView&record='+element.idOc+'" target="_blank" style="text-decoration: underline;">' + element.nombreOc + '</a></td>';
        html += '<td class="orc_pronomemp">' + element.orc_pronomemp + '</td>';
        html += '<td class="orc_tcinco" style="color:#000;">' + element.orc_tcinco + '</td>';
        html += '<td class="emb_orig">' + element.emb_orig + '</td>';
        html += '<td class="emb_transp">' + element.emb_transp + '</td>';
        html += '<td class="Modelo">' + element.first_name+" "+element.last_name +'</td>';  
        html += '<td class="orc_fechaord">' + element.orc_fechaord + '</td>';
        html += '<td class="des_fechacrea">' + new Date(element.date_modified).toISOString().substring(0, 10); + '</td>';
        html += '<td class="emb_fechacrea">' + element.emb_fechacrea + '</td>'; 
        
        //importes Orden de compra, Despacho y Embarque**
        html += '<td id="orc_importet'+element.idDes+'" class="orc_importet" style="color:#000;font-weight: bold;"><input type="hidden" class="importeCompras" value="'+element.orc_importet+'">' + formatearNumero(element.orc_importet) + '</td>'; 
        html += '<td id="precioDesp'+element.idDes+'" class="precioDesp precioEmb  '+element.idEmb+'" style="color:#000;font-weight: bold;"><input type="hidden" class="importeEmbarque" value="'+element.precioDesp+'">' + formatearNumero(element.precioDesp).replace('null','<b style="color:red;">Sin Items</b>') + '</td>';
        
        var desParcial = (element.precioDesp / element.orc_importet) * 100;
         
        html += '<td class="desParcial '+element.idEmb+'" style="color:#000;font-weight: bold;"> % ' + Math.round(desParcial) + '</td>';        
        html += '<td class="'+element.idEmb+'" style="color:#000;font-weight: bold;"><input type="hidden"  value="'+element.precioEmb+'">' + formatearNumero(element.precioEmb) + '</td>';     
        //**
        //Hitos del embarque
        html += '<td class="idDes '+element.idEmb+'" id="stepper'+element.idDes+'">' + stepper + '</td>';  
        
        switch(element.emb_estado) {
            case "3":
              if(element.difFecha >= element.emb_diastran){
                html += '<td class="fechaDif '+element.idEmb+'" ><span class="badge"style="background:#DD4D2C;">' + element.difFecha+ ' / ' + element.emb_diastran + '</span></td>';
              }else{
                html += '<td class="fechaDif '+element.idEmb+'" ><span class="badge"style="background:#2da910;">' + element.difFecha+ ' / ' + element.emb_diastran + '</span></td>';
              }
            break;
            default:
              if(element.difFecha >= element.emb_diastran){
                html += '<td class="fechaDif '+element.idEmb+'" ><span class="badge"style="background:#DD4D2C;">' + element.difFecha+ ' / ' + element.emb_diastran + '</span></td>';
              }else{
                html += '<td class="emb_diastran '+element.idEmb+'">' + element.difFecha+ ' / ' + element.emb_diastran + '</td>';
              }
            break;
        }
        
        html += '<td class="des_fechaprev '+element.idEmb+'" style="color:#000;"><b>' + element.des_fechaprev + '</b></td>';  
        html += '<td class="idEmb '+element.idEmb+'"><a href="index.php?module=SCO_Embarque&action=DetailView&record='+element.idEmb+'" target="_blank" style="text-decoration: underline;">' + element.nombreEmb + '</a></td>'; 
                      
        switch(element.emb_estado) {
            case "3":
            html += '<td class="Modelo '+element.idEmb+'" style="color:green;"><b>Cerrado</b></td>';
            break;
            /*case "1":
            html += '<td class="Modelo '+element.idEmb+'" style="color:green;"><b>En curso</b></td>';
            break;*/
            default:
            html += '<td class="Modelo '+element.idEmb+'" style="color:#000;"><b>' + element.ultimoHito.replace("null","") + '</b></td>';
            }
        if(element.ultimoHito != "null"){
          var tdComnentario = '';
          var comentario = element.comentarios.replace("[","").replace("]","");
          comentario = comentario.split(",");
          for(var i = 0; i < comentario.length;i++){
            //console.log(comentario[i]);
            if(comentario[i].trim() != ''){
             tdComnentario += '<li><span class="re"> - </span> ' + comentario[i].replace("null","")+ '</li>'
            }else{
              tdComnentario += '<li>' + comentario[i].replace("null","")+ '</li>'
            }
          }
        }else{
           tdComnentario += '<li> - </li>'
        }
        html += '<td class="comentarios '+element.idEmb+'"><ul >'+ tdComnentario+'</ul><a class="suitepicon suitepicon-action-view" style="cursor: pointer;" onclick=showModal("'+element.idEmb+'")> ver...</a></td>';    
        html += '</tr>'     
      });
      $(".contenido").html(html);
      //Suma de totales de Orden de compra y Embarque**
      var orc_importet = 0;
      $(".importeCompras").each(function(){
      	orc_importet += parseInt($(this).val()) || 0;
      });
      $("#totalCompras").text(formatearNumero(orc_importet));
      console.log(orc_importet);
      
      var prdes_unidad = 0;
      $(".importeEmbarque").each(function(){
      	prdes_unidad += parseInt($(this).val()) || 0;
      });
      $("#totalEmbarque").text(formatearNumero(prdes_unidad));
      console.log(prdes_unidad);
      //**
      //popover 
      $(function () {
        $('[data-toggle="popover"]').popover()       
      });
      
      $('#buscar').keyup(function(){
			var rex = new RegExp($(this).val(), 'i');
			//console.log(rex);
	        $('.contenido tr').hide();
	        $('.contenido tr').filter(function () {
	            return rex.test($(this).text());
	        }).show();
       });	
      
      $("#cantCompras").text(data.length);
      $("#cantEmbarques").text(cantidadEbarque.length);
       var back = [
       "#fff",       
       "#eee"
       ];
       var cont = 0;
       cantidadEbarque.forEach(function(id, index) {      
          if(index % 2 == 0){
              $("."+id).css('background',back[0]);
          }else{                          
              $("."+id).css('background',back[1]);
          }              
       });
  }
 
});

function formatNumber2 (n) {
	n = String(n).replace(/\D/g, "");
  return n === '' ? n : Number(n).toLocaleString();
}

/*Formateando digitos con comas y puntos*/
function formatearNumero(num) {
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num + '.' + cents);
}
function showModal(id){  
  historial(id);  
}
 function historial(id){
    var html = '<h1>hola'+id+'<h1>';
    $.ajax({
	        type: 'GET',     
	        url: '/custom/modules/Home/Dashlets/LogisticaDashlet/LogisticaListEvento.data.php',       
	        data: {
	        	id:id
	        	},
	        async:false,
	        success: function (e) {
	        	var res = JSON.parse(e);
            listaEventos(res);
	        },
	        error: function (data) {
	            console.log('ERROR, No se pudo conectar', data);
	            $('#miVentanaErrConx').modal('show');
	        }
	    });	
}
function listaEventos(data){  
  var html = ''; 
  data.forEach(function(elemento,indice,array) {    
    var arr = elemento.split('***');    
		html += '<li>';
		html += '<div class="row modalrow">';
    html += '<table  class="table table-bordered modalTabla" style="font-size: 10px">';
    html += '<tbdoy>';
    html += '<tr>';
		html += '<div class="col-sm-6">';
    if(arr[2].trim() == 'Pendiente'){
      $("#parrafo").css("background-color", "#ccc");
      html += '<span class="modalList" style="color:#999;font-weight: 500;">'+arr[0].substring(2,100)+'<span class="text-danger" style="font-size:9px;margin-top:3px;margin-left: 10px;"> Pendiente </span></span>';
    }else{
      html += '<span class="modalList"><b>'+arr[0].substring(2,100)+'</b><span class="stepper-icono"style="color:#009202 !important;margin-top: 2px;"> &#10004;</span></span>';
    }
		html += '</div>';
    html += '</tr>';
    html += '<tr>';
		html += '<div class="col-sm-6">';
    var x = arr[1].replace("[","").replace("]","");     
    if(x.trim() == ""){      
      html += '<p class="text-left" style="font-size:9px;margin-top:3px;">Sin comentarios</p>';
    }else{
      html += '<table  class="table table-bordered modalTabla" style="font-size: 10px;width: 80%;text-align: center;margin: auto;">';
      html += '<tbdoy>';
      html += '<tr><th>Nombre de comentario</th><th>Descripcion</th><th>fecha (m-d-y h:m:s)</th></tr>';
      console.log(arr[0]);     
      var par = JSON.parse(arr[1]);
      par.forEach(function(ele,indice,array) {
          console.log(ele.nombre);
          console.log(ele.d);
          console.log(fechaTimestamp(ele.date_modified));
          html += '<tr>';
          html += '<td>'+ele.nombre+'</td>';
          html += '<td>'+ele.d+'</td>';
          html += '<td>'+fechaTimestamp(ele.date_modified)+'</td>';
          html += '</tr>';
      });
  		html += '</tbdoy></table>';
    }        
    html += '</div>';
    html += '</tr>';
    html += '</tbdoy></table>';
		html += '</div>';			                    
  });  
  $(".listHistorial").html(html);
  $("#myModal").modal({show:true});    
}

function fechaTimestamp(t)
{
  var h = new Date(t).toLocaleTimeString("en-US");
  var d = new Date(t).toLocaleDateString("en-US");
  var fecha = d.replace(/[/]/g,"-")+" "+h;
  console.log(fecha);
  return fecha;  
}





