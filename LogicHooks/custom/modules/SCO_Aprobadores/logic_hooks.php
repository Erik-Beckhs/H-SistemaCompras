<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'datosAprobadores', 'custom/modules/SCO_Aprobadores/datosAprobadores.php','CldatosA', 'Fndatosa'); 
$hook_array['after_save'][] = Array(2, 'correlativoAprobadores', 'custom/modules/SCO_Aprobadores/correlativo.php','Clcorrelativo', 'Fncorrelativo'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'correlativoAprobadoresDeleted', 'custom/modules/SCO_Aprobadores/correlativoDeleted.php','ClcorrelativoDel', 'FncorrelativoDel'); 



?>