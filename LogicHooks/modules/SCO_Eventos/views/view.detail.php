<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Eventos
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class SCO_EventosViewDetail extends ViewDetail {

 	function SCO_EventosViewDetail(){
 		parent::ViewDetail();
 	}

 	function display(){
 		$id_ev = $this->bean->id;
 		$arr_estados =  array(1 => 'Borrador',2 =>'Solicitud de embarque',3 =>'En Transito',4 =>'Concluido');
		$estado = $this->bean->eve_estado;

		$st ='<style>
			.cantidad{pointer-events:none;}
			.precio{pointer-events:none;}
			.gris{color: #ccc;}
			.gris:hover{color: #ccc;}
			.single{display: none;}
			#whole_subpanel_sco_despachos_sco_productosdespachos tbody td a {pointer-events: none; cursor: default;}
			#whole_subpanel_sco_eventos_sco_riesgo .SugarActionMenu {display:none;}
			#whole_subpanel_sco_eventos_sco_problema .SugarActionMenu {display:none;}
			#whole_subpanel_history .SugarActionMenu {display:none;}
			#whole_subpanel_activities .SugarActionMenu {display:none;}
			</style>';
			//#edit_button{pointer-events: none; cursor: default;}


 		switch ($estado) {
 			case '':
 			parent::display();
 			echo '<div style="margin-top: 10px;"><div class="yui-content"><div class="detail view  detail508 expanded">
				<table class="panelContainer" cellspacing="1">
				<tbody>
				<tr>
				<td width="12.5%" scope="col">
 				</td>
 				<td>
 					<a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #5cb85c !important;" onClick="solicitar(2);" value="Ver Reporte">Concluir Evento</a>
 				</td>
 				<td width="28%" >
				</td>
            	<td width="12.5%" scope="col">

		 		</td>
             	<td width="37.5%">

 				</td>
	 			</tr>
	 			</tbody></table>
			</div></div></div>';
			echo '<script>
				var id_ev = "'.$id_ev.'";
				function solicitar(est){

          est = "Concluido";
					$.ajax({
					type: "get",
					url: "index.php?to_pdf=true&module=SCO_Eventos&action=fecha_eventos&id="+id_ev,
					data: {est},
					beforeSend: function(){
					//alert("Procesando los datos");
						$("#btn-estados").css("pointer-events","none");
						$("#btn-estados").css("background","#CCC !important");
					},
					success: function(data) {

						var estado = $.parseJSON(data);
		    		}
		    	});
					// location.reload();
				}
			</script>';
 				break;
 			case 'Pendiente':
 				parent::display();
 			echo '<div style="margin-top: 10px;"><div class="yui-content"><div class="detail view  detail508 expanded">
				<table class="panelContainer" cellspacing="1">
				<tbody>
				<tr>
				<td width="12.5%" scope="col">
 				</td>
 				<td>
 					<a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #5cb85c !important;" onClick="solicitar(2);" value="Ver Reporte">Concluir Evento</a>
 				</td>
 				<td width="28%" >
				</td>
            	<td width="12.5%" scope="col">

		 		</td>
             	<td width="37.5%">

 				</td>
	 			</tr>
	 			</tbody></table>
			</div></div></div>';
			echo '<script>
				var id_ev = "'.$id_ev.'";

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
						},
						success: function(data) {
							var estado = $.parseJSON(data);
              console.log(estado);
                if(estado != "error"){
                    location.reload();
                }
                else {
                  alert("No se puede concluir el evento si no coloco una fecha real o un transportista")
                }
			    		}
			    	});
				}else{
						alert("Aun tiene Actividades pendientes");
				}
				}
			</script>';
 				break;
 			case 'Concluido':
 			//echo $st;
 			parent::display();
			echo '<script>
			var id_ev = "'.$id_ev.'";
			function solicitar(est){
				debugger;
        est = "Concluido";
				$.ajax({
					type: "get",
					url: "index.php?to_pdf=true&module=SCO_Eventos&action=fecha_eventos&id="+id_ev,
					data: {est},
					beforeSend: function(){
					//alert("Procesando los datos");
						$("#btn-estados").css("pointer-events","none");
						$("#btn-estados").css("background","#CCC !important");
					},
					success: function(data) {
						debugger;
						var estado = $.parseJSON(data);
		    			}
					// location.reload();
				});
			}
			</script>';
 				break;
 			default:
 			parent::display();
 				break;
 		}

 	}
 }
?>
