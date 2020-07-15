<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Despachos
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class SCO_DespachosViewDetail extends ViewDetail {

 	function SCO_DespachosViewDetail(){
 		parent::ViewDetail();
 	}

 	function display(){
    $html = '
    <div class="row detail-view-row" style="background:#FFF;">
      <br>
      <div class="col-xs-12 col-sm-6 detail-view-row-item">
        <div class="col-xs-12 col-sm-4 label col-1-label">
          Total precio productos:
        </div>        
        <div class="col-sm-2 detail-view-field " type="varchar" >
          <span class="sugar_field" ><b class="mostrarSubtotalProducto"></b></span>
        </div>  
      </div>
      
      <div class="col-xs-12 col-sm-6 detail-view-row-item">
        <div class="col-xs-12 col-sm-4 label col-1-label">
          Total cantidad productos:
        </div>      
        <div class="col-sm-2 detail-view-field " type="varchar">
          <span class="sugar_field"><b class="mostrarTotalItems"></b></span>
        </div>        
      </div>
    </div>    
    '  ;
    $js = '    
    var re;
    var valor = 0
    $(".subtotalProducto").each(function(index) {
    console.log($(this).text());
    re = $(this).text();
    console.log(re);
    valor += parseFloat(re)
    });
    console.log("suma " + valor);
    $(".mostrarSubtotalProducto").append(valor.toFixed(2));
    
    var re;
    var valor = 0
    $(".cantidad").each(function(index) {        
    re = $(this).val();
    console.log(re);
    valor += parseFloat(re)
    });
    console.log("suma " + valor)    
    $(".mostrarTotalItems").append(valor);
    ';

 		//$notificaciones = new Notifica();
 		//ID del DESPACHOS
 		$id_des = $this->bean->id;
 		$arr_estados =  array(1 => 'Borrador',2 =>'Solicitud de embarque',3 =>'En Transito',4 =>'Concluido');

		$estado = $this->bean->des_est;
		//CSS, para ocultar botones y Subpaneles
		echo "<style>#whole_subpanel_sco_productoscompras_sco_despachos {display:none;}</style>";
		$st ='<style>
			.cantidad{pointer-events:none;}
			.precio{pointer-events:none;}
			.gris{color: #ccc;}
			.gris:hover{color: #ccc;}
			.single{display: none;}
			.clickMenu {display: none;}
			#edit_button{pointer-events: none; cursor: default;}

			#whole_subpanel_sco_despachos_sco_documentodespacho .clickMenu {display: none;}
			#whole_subpanel_sco_despachos_sco_productosdespachos .clickMenu {display: none;}
			</style>';
		//agregando HTML en la vista de DESPACHOS de acuerdo a los estados del DESPACHO
 		switch ($estado) {
 			case '0':
 			//$notificaciones->FnnotificaDespacho($this->bean);
 			parent::display();
 			echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-15px;">
        <br>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              Anular Despacho:
              </div>          
              <div class="col-sm-3 detail-view-field " type="varchar" >
                <span class="sugar_field" >
                   <a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #d9534f !important;" onClick="solicitar(4)" value="Ver Reporte">Anular despacho con Productos</a>
                </span>
              </div>        
              <div class="col-sm-2 detail-view-field " type="varchar" >
                <span class="sugar_field" >
                   <a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #328fdb !important;" onClick="intangible(3)" value="Ver Reporte">Despacho intangible</a>
                </span>
              </div>        
        </div>      
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              Enviar a:
              </div>        
              <div class="col-sm-2 detail-view-field " type="varchar">
                <span class="sugar_field">
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #328fdb !important;" onClick="solicitar(1);" value="Ver Reporte">Solicitar Embarque</a>
                </span>
              </div>          
        </div>
      </div>       
      '.$html.'
      ';
			echo '<script>
        '.$js.'
				var id_des = "'.$id_des.'";
				function solicitar(est){
  					$.ajax({
    					type: "get",
    					url: "index.php?to_pdf=true&module=SCO_Despachos&action=estado&id="+id_des,
    					data: {est},
    					success: function(data) {
      						data = data.replace("$","");
      						var estado = $.parseJSON(data);
      						if(estado != 9){
      							location.reload();
      						}
                  else{
      							alert("Debe escribir un comentario en el campo de observaciones");
      						}
    		    	}
		    	  });
				}
        function intangible(est){
          if (confirm("Esta seguro de convertir a intangible este despcho?")) {
            $.ajax({
              type: "get",
              url: "index.php?to_pdf=true&module=SCO_Despachos&action=despachosIntangibles&id="+id_des,
              data: {est},
              dataType: "json",
              success: function(data) {
                  if(data[0] != 9){
                    location.reload();
                  }
                  else{
                    alert("Debe escribir un comentario en el campo de observaciones");
                  }
              }
            });
          }
				}        
			</script>';
 				break;
 			case '1':
 			echo $st;
 			//$notificaciones->FnnotificaDespacho($this->bean);
 			parent::display();
 			echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-15px;">
        <br>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              
              </div>          
              <div class="col-sm-3 " type="varchar" >
                <span class="sugar_field" >
                   
                </span>
              </div>               
        </div>      
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              Pasar a estado:
              </div>        
              <div class="col-sm-2 detail-view-field " type="varchar">
                <span class="sugar_field">
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #d9534f !important;" onClick="solicitar(0);" value="Cancelar">Cancelar solicitud</a>
                </span>
              </div>          
        </div>
      </div>       
      '.$html;
			echo '<script>
				var id_des = "'.$id_des.'";
				function solicitar(est){

					$.ajax({
					type: "get",
					url: "index.php?to_pdf=true&module=SCO_Despachos&action=estado&id="+id_des,
					data: {est},
					success: function(data) {
						data = data.replace("$","");

						var estado = $.parseJSON(data);
		    		}
		    	});
					location.reload();
				}
        '.$js.'
			</script>';

 				break;
 			case '2':
 			echo $st;
 			//$notificaciones->FnnotificaDespacho($this->bean);
      
 			parent::display();
      echo $html;
      echo '<script>'.$js.'</script>';
 				break;
 			case '3':
 			//$notificaciones->FnnotificaDespacho($this->bean);
 			echo $st;
 			parent::display();
 			echo '
      <div class="row detail-view-row" style="background:#FFF;margin-top:-15px;">
        <br>
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              
              </div>          
              <div class="col-sm-3" >
              
              </div>               
        </div>      
        <div class="col-xs-12 col-sm-6 detail-view-row-item">
              <div class="col-xs-12 col-sm-4 label col-1-label">
              Pasar a estado:
              </div>        
              <div class="col-sm-2 detail-view-field " type="varchar">
                <span class="sugar_field">
                  <a class="btn btn-sm btn-success" style="padding: 2px 5px;background: #d9534f !important;" onClick="solicitar(1);" value="Cancelar">Cancelar solicitud</a>
                </span>
              </div>          
        </div>
      </div>       
      '.$html;        
			echo '<script>
				var id_des = "'.$id_des.'";
				function solicitar(est){
					//debugger;
					$.ajax({
					type: "get",
					url: "index.php?to_pdf=true&module=SCO_Despachos&action=estado&id="+id_des,
					data: {est},
					success: function(data) {
						data = data.replace("$","");

						var estado = $.parseJSON(data);
		    		}
		    	});
					location.reload();
				}
        '.$js.'
			</script>';
 				break;

 			case '4':
 			//$notificaciones->FnnotificaDespacho($this->bean);
 			echo $st;
 			parent::display();
 				break;

 			default:
 			parent::display();
 				break;
 		}
    if($estado == 0){
    require_once("modules/SCO_Despachos/dividirdespacho.php");
    }
    require_once("modules/SCO_Despachos/validacionesItems.php");
 	}

 }
?>
