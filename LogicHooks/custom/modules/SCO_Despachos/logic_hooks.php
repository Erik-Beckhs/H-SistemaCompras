<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'carga_de_productos_de_OC_a_Despachos', 'custom/modules/SCO_Despachos/proddes.php','Clproddes', 'Fnproddes'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(1, 'Eliminar_relacion_de_productos_despachos', 'custom/modules/SCO_Despachos/prodelimina.php','Clprodelimina', 'Fnprodelimina'); 



?>