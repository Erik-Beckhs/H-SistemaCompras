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
 		$fechaReal = $this->bean->eve_fechare;
 		$aduana = $this->bean->sco_cnf_eventos_list_id_c;

 		$arr_estados =  array(1 => 'Borrador',2 =>'Solicitud de embarque',3 =>'En Transito',4 =>'Concluido');
		$estado = $this->bean->eve_estado;

		echo '<link href="modules/SCO_Consolidacion/css-loader.css?'.time().'" rel="stylesheet" type="text/css" />  
			  <link rel="stylesheet" href="modules/SCO_OrdenCompra/BackOrder/bootstrap-datetimepicker.min.css?'.time().'">';

		echo '<script src="modules/SCO_Eventos/viewdetail.js?'.time().'"></script>
			  <script src="modules/SCO_OrdenCompra/BackOrder/bootstrap-datetimepicker.min.js?'.time().'"></script>';

		echo '<div id="modalEmbarque"></div>
			  <div id="modalFecha"></div>
			  <div class="loader loader-default" data-text="Enviando datos"></div>';
			  
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
				var fechaReal = "'.$fechaReal.'";
				var fechaReal2 = fechaReal.split("/");
				var fechaReal3 = fechaReal2[1] + "/"+fechaReal2[0]+ "/"+fechaReal2[2]
				
			</script>';
 				break;
 			case 'Pendiente':
 				parent::display();
 			echo '
			 <div class="row detail-view-row" style="background:#FFF;margin-top:-20px;">
		        <div class="col-xs-12 col-sm-6 detail-view-row-item">
		              <div class="col-xs-12 col-sm-5 label col-1-label" style="margin-left: -35px;">
		                <b>Actualizar registros</b>
		              </div>          
		              <div class="col-sm-4 col-sm-5 campopersonalizado" type="varchar" style="margin-left: 15px;">
		                <a class="btn btn-sm btn-info" style="padding: 2px 5px;background: #244668 !important;" onClick="ventanaModalFecha();" >Registrar Fecha Real</a>
		              </div>                          
		        </div>
		        <div class="col-xs-12 col-sm-6 detail-view-row-item">
		              <div class="col-xs-12 col-sm-5 label col-1-label"style="margin-left: -35px;">
		                <b>Finalizar el evento</b>
		              </div>        
		              <div class="col-sm-4 col-sm-5 campopersonalizado" type="varchar"style="margin-left: 15px;">
		               		<a class="btn btn-sm btn-info" style="padding: 2px 5px;background: #3c763d !important;" onClick="solicitar(2);" value="Ver Reporte">Concluir Evento</a>
		              </div>          
		        </div>
		      </div>
			';
			echo '<script>
				var id_ev = "'.$id_ev.'";
				var aduana = "'.$aduana.'";
				var fechaReal = "'.$fechaReal.'";
				if(fechaReal != ""){
					var fechaReal2 = fechaReal.split("/");
					var fechaReal3 = fechaReal2[1] + "/"+fechaReal2[0]+ "/"+fechaReal2[2]
				}else{
					var fechaReal3 = "";
				}
			</script>';
 				break;
 			case 'Concluido':
 			//echo $st;
 			parent::display();
			echo '<script>
			var id_ev = "'.$id_ev.'";			
			</script>';
 				break;
 			default:
 			parent::display();
 				break;
 		}

 	}
 }
?>
