<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'datosPP', 'custom/modules/SCO_PlandePagos/PlanPagos.php','PlanPagos', 'Fndatospp'); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'datosPP2', 'custom/modules/SCO_PlandePagos/PlanPagos2.php','PlanPagos2', 'Fndatospp2'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'datosPP eliminando la relacion', 'custom/modules/SCO_PlandePagos/DeletePP.php','ClDeletePP', 'FnDeletePP'); 



?>