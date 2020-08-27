<?php 
/**
*Esta clase realiza carga de las pantallas de acuerdo a los cambio que sufra un subpanel
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_OrdenCompra
*/
class SCO_OrdenCompraController extends SugarController {  

	function action_reporte() {
		$this-> view = 'reporte';
	}

	function action_backorder() {
		$this-> view = 'backorder';
	}
  
	function action_SubPanelViewer() {          
		require_once 'include/SubPanel/SubPanelViewer.php';
		//script para actualizar la pagina cada momento desde el Subpanel de DOCUMENTOS
		if ($_REQUEST['subpanel'] == 'sco_ordencompra_sco_documentos'){   		         
			$js = "<script>
				window.location.reload(); 
			</script> "; 
			echo $js;                           
		}
		//script para actualizar la pagina cada momento desde los cambios del subpanel de PRODUCTOS
		if ($_REQUEST['subpanel'] == 'sco_ordencompra_sco_productos'){             
			$js = "<script>
				window.location.reload(); 
			</script> "; 
			echo $js;
		}
		//script para actualizar la pagina cada momento desde el subpanel de DESPACHOS
		if($_REQUEST['subpanel'] == 'sco_despachos_sco_ordencompra'){
			$js = "<script>
				window.location.reload(); 
			</script> "; 
			echo $js;  
		}
	}
}
?>
