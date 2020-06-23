<?php 
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/custom/modules/SCO_Embarque
*/

class SCO_EmbarqueController extends SugarController {  

	function action_SubPanelViewer() {          
		require_once 'include/SubPanel/SubPanelViewer.php';
		//script para actualizar la pagina cada momento que un subpanel tenga cambios
		if ($_REQUEST['subpanel'] == 'sco_embarque_sco_despachos'){   		         
			$js = "<script>
				window.location.reload(); 
			</script> "; 
			echo $js;                           
		}		
	}
}
?>
