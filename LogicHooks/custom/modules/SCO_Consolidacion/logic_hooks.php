<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(1, 'EliminaConsolidacion', 'custom/modules/SCO_Consolidacion/eliminaConolidacion.php','Celiminaconsolidacion', 'Feliminaconsolidacion'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_Consolidacion_y_ProductosCotizadosDeVenta', 'custom/modules/SCO_Consolidacion/eliminaRelacionPcv.php','Celiminarelacion', 'Felimina'); 



?>