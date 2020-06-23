<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/modules/views/SCO_Embarque
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class SCO_EmbarqueViewDetail extends ViewDetail {

 	function SCO_EmbarqueViewDetail(){
 		parent::ViewDetail();
 	}

 	function display(){
    $html = '
    <div class="row detail-view-row" style="background:#fff;">
    <br>
    <div class="col-xs-12 col-sm-6 detail-view-row-item">
    <div class="col-xs-12 col-sm-4 label col-1-label">
    Total precio productos:
    </div>

    <div class="col-sm-2 detail-view-field " type="varchar>
    <span class="sugar_field"><b class="mostrarSubtotalProducto"></b></span>
    </div>
  
    </div>
    <div class="col-xs-12 col-sm-6 detail-view-row-item">
    <div class="col-xs-12 col-sm-4 label col-1-label">
    Total cantidad productos:
    </div>
    <div class="col-sm-2 detail-view-field " type="varchar">
    <span class="sugar_field" ><b class="mostrarTotalItems"></b></span>
    </div>
    </div>
    </div>    
    '  ;
    $js = "   
    var re;
    var valor = 0
    $('.subtotalProducto').each(function(index) {
    console.log($(this).text());
    re = $(this).text();
    console.log(re);
    valor += parseFloat(re)
    });
    console.log('suma ' + valor);
    $('.mostrarSubtotalProducto').append(valor.toFixed(2));
    
    var re;
    var valor = 0
    $('.cantidad').each(function(index) {        
    re = $(this).text();
    console.log(re);
    valor += parseFloat(re)
    });
    console.log('suma ' + valor)    
    $('.mostrarTotalItems').append(valor);
    ";
    
 		echo "<script>
        ".$js."
				var hrefReporte = '';
  			function mostrarBtnRep()
			 {
			 	URLactual = window.location;
	  	  	 	var href = $('.detail-view #reporte a').text();
	  	  	 	var cadena = String(URLactual),
				    separador = '/',
				    arregloDeSubCadenas = cadena.split(separador);
				hrefReporte = String(arregloDeSubCadenas[0])+'/'+String(arregloDeSubCadenas[1])+'/'+String(arregloDeSubCadenas[2])+href;
	  			$('.detail-view  #reporte a').attr({
												   'title': 'Ver reporte de embarque',
												   'href': '#',
												   'html': 'Reporte',
												   'class': 'btn btn-xs btn-primary',
												   'onClick':'mostrarReporte()'
												});
				 $('.detail-view  #reporte a').html('Reporte');
			 }
			 var mostrarReporte = function()
				{
					var url1 = hrefReporte;
					window.open(url1,'','width=1220,height=650');
				}
			setTimeout('mostrarBtnRep()',500);
      window.onload = function(){
      ".$js."
      }
      
  		  </script>";

 		//estado del EMBARQUE
    
 		$estado = $this->bean->emb_estado;
 		switch ($estado) {
 			case '3':
 				//css. ocultando botones
 				echo "<style>
 					#whole_subpanel_sco_embarque_sco_despachos .clickMenu {display: none;}
 					#whole_subpanel_sco_embarque_sco_facturasproveedor .clickMenu {display: none;}
 					#whole_subpanel_sco_embarque_sco_documentodespacho .clickMenu {display: none;}
 				</style>";
 				break;
 			default:
 				break;
 		}
 		parent::display();
    echo $html;
 	}
 }
?>
