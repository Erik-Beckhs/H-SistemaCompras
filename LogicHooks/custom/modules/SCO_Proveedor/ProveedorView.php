<?php
/**
*Esta clase realiza Modificaiones en la vista del modulo de SCO_PROVEEDOR
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license /var/www/html/custom/modules/SCO_Proveedor
*/
class ProveedorView {
  
	function fnview()
	{
		switch ($GLOBALS['app']->controller->action) 
		{
			case "EditView":	
				$js = "<script>
						$('#prv_dbpenombreb_label').hide();
		                $('#prv_dbpenombreb').hide();
		                $('#prv_dbswib_label').hide();
		                $('#prv_dbswib').hide();
		                $('#prv_dbdirb_label').hide();
		                $('#prv_dbdirb').hide();
		                $('#prv_dbabab_label').hide();
		                $('#prv_dbabab').hide();
		                $('#prv_dbctab_label').hide();
		                $('#prv_dbctab').hide();
		                $('#prv_dbibanb_label').hide();
		                $('#prv_dbibanb').hide();
		                $('#prv_dbpenombrei_label').hide();
		                $('#prv_dbpenombrei').hide();
		                $('#prv_dbdiri_label').hide();
		                $('#prv_dbdiri').hide();		         
		                $('#prv_dbswii_label').hide();
		                $('#prv_dbswii').hide();		                
		                $('#prv_dbabai_label').hide();
		                $('#prv_dbabai').hide();		                
		                $('#prv_dbctai_label').hide();
		                $('#prv_dbctai').hide();		                
		                $('#prv_dbibani_label').hide();
		                $('#prv_dbibani').hide();
				$('#prv_tipobanco').change(function(){
		            if($(this).val() == '1'){
		                $('#prv_dbpnban_label').fadeIn();
		                $('#prv_dbpnban').fadeIn();
		                $('#prv_dbpncuenta_label').fadeIn();
		                $('#prv_dbpncuenta').fadeIn();
		                $('#prv_dbswib_label').hide();
		                $('#prv_dbswib').hide();
		                $('#prv_dbpenombreb_label').hide();
		                $('#prv_dbpenombreb').hide();
		                $('#prv_dbabab_label').hide();
		                $('#prv_dbabab').hide();
		                $('#prv_dbdirb_label').hide();
		                $('#prv_dbdirb').hide();
		                $('#prv_dbctab_label').hide();
		                $('#prv_dbctab').hide();
		                $('#prv_dbibanb_label').hide();
		                $('#prv_dbibanb').hide();
		                $('#prv_dbpenombrei_label').hide();
		                $('#prv_dbpenombrei').hide();
		                $('#prv_dbdiri_label').hide();
		                $('#prv_dbdiri').hide();		                
		                $('#prv_dbswii_label').hide();
		                $('#prv_dbswii').hide();		                
		                $('#prv_dbabai_label').hide();
		                $('#prv_dbabai').hide();		                
		                $('#prv_dbctai_label').hide();
		                $('#prv_dbctai').hide();		            
		                $('#prv_dbibani_label').hide();
		                $('#prv_dbibani').hide();
		            }else{
		                $('#prv_dbpnban_label').hide();
		                $('#prv_dbpnban').hide();
		                $('#prv_dbpncuenta_label').hide();
		                $('#prv_dbpncuenta').hide();
		                $('#prv_dbpenombreb_label').fadeIn();
		                $('#prv_dbpenombreb').fadeIn();
		                $('#prv_dbswib_label').fadeIn();
		                $('#prv_dbswib').fadeIn();
		                $('#prv_dbdirb_label').fadeIn();
		                $('#prv_dbdirb').fadeIn();
		                $('#prv_dbabab_label').fadeIn();
		                $('#prv_dbabab').fadeIn();
		                $('#prv_dbctab_label').fadeIn();
		                $('#prv_dbctab').fadeIn();
		                $('#prv_dbibanb_label').fadeIn();
		                $('#prv_dbibanb').fadeIn();
		                $('#prv_dbpenombrei_label').fadeIn();
		                $('#prv_dbpenombrei').fadeIn();
		                $('#prv_dbdiri_label').fadeIn();
		                $('#prv_dbdiri').fadeIn();		                
		                $('#prv_dbswii_label').fadeIn();
		                $('#prv_dbswii').fadeIn();		                
		                $('#prv_dbabai_label').fadeIn();
		                $('#prv_dbabai').fadeIn();		                
		                $('#prv_dbctai_label').fadeIn();
		                $('#prv_dbctai').fadeIn();		            
		                $('#prv_dbibani_label').fadeIn();
		                $('#prv_dbibani').fadeIn();
		            }
		        });		        
				</script>";
				echo $js;
			break;
		}
	}
}
