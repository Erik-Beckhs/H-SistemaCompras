<?php
/**
*Esta clase realiza operaciones matemÃ¡ticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Despachos
*
*@author Reynaldo Kantuta <rkantuta@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Despachos
*/
if (! defined ( 'sugarEntry' ) || ! sugarEntry) die ( 'Not A Valid Entry Point' );

require_once ('include/MVC/View/views/view.edit.php');
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

class SCO_DespachosViewEdit extends ViewEdit {

  function SCO_DespachosViewEdit() {
    parent::ViewEdit ();
    $this->useForSubpanel = true;
  }

  function display(){
  	//CSS, ocultando subpanel y botones
  	echo "<style>
  		#form_SubpanelQuickCreate_SCO_Despachos_tabs #name{display:inline;}
  		#SCO_Despachos_subpanel_full_form_button{display:none;}
  	</style>";
  	echo '<script src="/modules/SCO_Despachos/javajs.js?'.time().'" type="text/javascript" ></script>';
  	//Query, Obteniedno los nombres de los evnetos del modulo CNF_EVENTOS
  	$query = "SELECT DISTINCT(name) FROM sco_cnf_eventos ORDER BY name asc";
  	$obj = $GLOBALS['db']->query($query, true);
  	$cont = 1;
  	$origen = [];
  	//Query, numero de Correlativo del despacho en la ORDEN DE COMPRA
  	$query_oc = "SELECT SUM(orc_1) as correlativo_despacho FROM sco_ordencompra WHERE deleted = 0";
  	$obj_oc = $GLOBALS['db']->query($query_oc, true);
  	$row_oc = $GLOBALS['db']->fetchByAssoc($obj_oc);
  	$correlativo_despacho = $row_oc['correlativo_despacho'];
  	$correlativo_despacho++;
	while($row = $GLOBALS['db']->fetchByAssoc($obj))
	{
		//JS, agregando la funcionalidad del campo ListaDesplegable de origen y modalidad de transporte
	  	echo "<script>
		var origen = '".$this->bean->des_orig."';
		var transp = '".$this->bean->des_modtra."';

		window.onload = function(){
			if(origen == ''){
		  		$(\"#des_orig\").append(\"<option value='".$row['name']."' >".$row['name']."</option>\");
		  	}else{
		  		$(\"#des_orig\").append(\"<option value='\"+origen+\"' selected>\"+origen+\"</option>\");
		  		$(\"#des_modtra\").append(\"<option value='\"+transp+\"' selected>\"+transp+\"</option>\");
		  	}
		$(\"#emb_diastran\").css(\"width\",\"70px\");
	  	$(\"#emb_volumen\").css(\"width\",\"70px\");
	  	$(\"#emb_peso\").css(\"width\",\"70px\");
	  	$(\"#emb_unidad\").css(\"width\",\"70px\");
	  	$(\"#des_diasllegada\").css({\"width\":\"70px\",\"background\":\"#eee\"});
	  	}
	  	if(origen == ''){
		  	$(\"#des_orig\").append(\"<option value='".$row['name']."'>".$row['name']."</option>\");
	  	}else{
	  		$(\"#des_orig\").append(\"<option value='\"+origen+\"' selected>\"+origen+\"</option>\");
	  		$(\"#des_modtra\").append(\"<option value='\"+transp+\"' selected>\"+transp+\"</option>\");
	  	}
	  	$(\"#emb_diastran\").css(\"width\",\"70px\");
	  	$(\"#emb_volumen\").css(\"width\",\"70px\");
	  	$(\"#emb_peso\").css(\"width\",\"70px\");
	  	$(\"#emb_unidad\").css(\"width\",\"70px\");
	  	$(\"#des_diasllegada\").css({\"width\":\"70px\",\"background\":\"#eee\"});
	  	</script>";
	  	$cont++;
	  	array_push($origen, $row['name']);
	}
	//JS, Genereando el nombre del campo name.
	echo "<script>

	var correlativo_despacho = '".$correlativo_despacho."';
	var des = '';
  var incoterm = $(\"#SCO_OrdenCompra_detailview_tabs #orc_tcinco\").val();
  $(\"#form_SubpanelQuickCreate_SCO_Despachos #des_incoterm\").val(incoterm);

  	$(\"#form_SubpanelQuickCreate_SCO_Despachos #name\").val(\"DES -\" + $(\".module-title-text\").text());
  	//$(\"#form_SubpanelQuickCreate_SCO_Despachos #name\").after('<h4>' + des+$(\".module-title-text\").text() + '</h4>');

  	$(\"#form_SubpanelQuickCreate_SCO_Despachos #name\").on('blur',function(){
  		var nameD = $(\"#form_SubpanelQuickCreate_SCO_Despachos #name\").val();
        if(nameD == '')
        {
        	$(\"#form_SubpanelQuickCreate_SCO_Despachos #name\").val(des+$(\".module-title-text\").text())
        }
      });
  	/*$(\"#des_orig\").on('click',function(){
  		var origen = $('#des_orig').val();

	});*/
	// esta funcion se ejecuta un segundo despues de que la pagina termina de cargarse
	function mostrarOrigenes (){
		//para velidar que la funcion no este como subpanel condicionamos la existencia del input orden de compra
		if($('#sco_despachos_sco_ordencompra_name').length > 0)
		{
			//en caso de no visualizar como sub panel se activa un ajax que obtiene los origenes y los muestra
			var origen = $('#des_orig').val();
			$.ajax({
			type: 'get',
			url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=3',
			data: {origen},
			dataType: 'json',
			success: function(data) {
		        		if(data){
			        		for(var i = 0; i < data.length; i++){
			        			$(\"#des_orig\").append(\"<option value='\"+data[i]+\"'>\"+data[i]+\"</option>\");
			        		}
			        	}

		    		}
		    	});
		    //en cuanto el select origen cambia de estado el modtrans cambia a los modos que le pertenece al origen
		    $('#des_orig').change(function(){
				var origen = $('#des_orig').val();
				$.ajax({
				type: 'get',
				url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=1',
				data: {origen},
				success: function(data) {
				var modtrans = $.parseJSON(data);
				modtrans = modtrans.split('|');
				$('#des_modtra').find('option').remove().end();
							$(\"#des_modtra\").append(\"<option value='0'>Selecione MT</option>\");
			        		if(modtrans){
				        		for(var i = 0; i < modtrans.length-1; i++){
				        			$(\"#des_modtra\").append(\"<option value='\"+modtrans[i]+\"'>\"+modtrans[i]+\"</option>\");
				        		}
				        		$('#des_diasllegada').val('');
				        }
			    	}
			    });
			});
			//si el modo de transporte cambia los dias trancitos tambien se recalculan
			$('#des_modtra').change(function(){
				var origen = $('#des_orig').val();
				var modtrans = $('#des_modtra').val();
				$.ajax({
				type: 'get',
				url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=2',
				data: {origen,modtrans},
				success: function(data) {
						debugger;
						var modtrans = $.parseJSON(data);
						$('#des_diasllegada').val(modtrans);
					}
			    });
			});
		}
	}
function mostrarModTransporte()
{
  var origen = $('#des_orig').val();
  $.ajax({
  type: 'get',
  url: 'index.php?to_pdf=true&module=SCO_Despachos&action=mod_transporte&id=1',
  data: {origen},
  success: function(data) {
  var modtrans = $.parseJSON(data);
  modtrans = modtrans.split('|');
  $('#des_modtra').find('option').remove().end();
        //$(\"#des_modtra\").append(\"<option value='0'>Selecione MT</option>\");
            if(modtrans){
              var mod = $(\"#des_modtra\").val();
              for(var i = 0; i < modtrans.length-1; i++){
                if (mod != modtrans[i]) {
                  $(\"#des_modtra\").append(\"<option value='\"+modtrans[i]+\"'>\"+modtrans[i]+\"</option>\");
                }
              }
          }
      }
    });
}
	setTimeout('mostrarOrigenes()',500);
  setTimeout('mostrarModTransporte()',600);
  	</script>";
  	parent::display();
  }
}
