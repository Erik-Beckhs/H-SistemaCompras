<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'datos evento y dropdown', 'custom/modules/SCO_Embarque/evento.php','Clevento', 'Fnevento'); 
$hook_array['after_relationship_add'] = Array(); 
$hook_array['after_relationship_add'][] = Array(1, 'agregando_relacion_para_cambio_de_estado_despacho', 'custom/modules/SCO_Embarque/despacho.php','Cldespacho', 'Fndespacho'); 
$hook_array['after_relationship_add'][] = Array(1, 'agregando_relacion_con_productosdespachos_de_los_despachos', 'custom/modules/SCO_Embarque/productosdespachos.php','Clproductosdespachos', 'Fnproductosdespachos'); 
$hook_array['after_relationship_add'][] = Array(1, 'agregando_relacion_de_documentos_de_los_despachos_a_embarques', 'custom/modules/SCO_Embarque/documentosdespachos.php','Cldocumentosdespachos', 'Fndocumentosdespachos'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_documentos_despachos_con_id_de_despacho', 'custom/modules/SCO_Embarque/documentosdespachos_delte_r.php','Cldocumentosdespachos_delte_r', 'Fndocumentosdespachos_delte_r'); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_despachos', 'custom/modules/SCO_Embarque/despacho_delte_r.php','Cldespacho_delte_r', 'Fndespacho_delte_r'); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_Embarque_despachos_con_id_de_despacho', 'custom/modules/SCO_Embarque/productosdespachos_delte_r.php','Clproductosdespachos_delte_r', 'Fnproductosdespachos_delte_r'); 



?>