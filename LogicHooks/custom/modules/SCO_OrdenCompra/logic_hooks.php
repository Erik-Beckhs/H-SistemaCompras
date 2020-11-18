<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'datosOrdenCompra', 'custom/modules/SCO_OrdenCompra/datoso.php','Cldatoso', 'Fndatoso'); 
$hook_array['after_save'][] = Array(2, 'datosContactos', 'custom/modules/SCO_OrdenCompra/datosco.php','Cldatosco', 'Fndatosco'); 
$hook_array['after_save'][] = Array(3, 'datosAprobadores', 'custom/modules/SCO_OrdenCompra/datosap.php','Cldatosap', 'Fndatosap'); 
$hook_array['after_save'][] = Array(4, 'contactosAprobadores', 'custom/modules/SCO_OrdenCompra/contap.php','Clcontap', 'Fncontap'); 
$hook_array['after_save'][] = Array(5, 'notificacionAprobadores', 'custom/modules/SCO_OrdenCompra/Notifica.php','Clnotifica', 'Fnnotifica'); 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'clonarOrdenCompraModulos', 'custom/modules/SCO_OrdenCompra/clonar.php','Clclonar', 'Fnclonar'); 
$hook_array['after_ui_frame'] = Array(); 
$hook_array['after_ui_frame'][] = Array(1, 'vistas de orden de compra con js', 'custom/modules/SCO_OrdenCompra/viewoc.php','Clviewoc', 'Fnviewoc'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_despachos', 'custom/modules/SCO_OrdenCompra/deselimina.php','Cldeselimina', 'Fndeselimina'); 
$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(1, 'EliminaOrdenDeCompra', 'custom/modules/SCO_OrdenCompra/elimnaOrdenCompra.php','ClelimanOrdenCompra', 'Fnelimina'); 



?>