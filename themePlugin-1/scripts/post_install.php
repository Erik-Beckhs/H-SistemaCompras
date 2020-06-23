<?php
if (! defined('sugarEntry') || ! sugarEntry) die('Not A Valid Entry Point');


    function post_install() {
    require_once('modules/Configurator/Configurator.php');
    global $sugar_config;
    $cfg = new Configurator();
    $cfg->config['default_theme'] = 'TechBlue'; 
    $cfg->config['disabled_themes'] = 'SuiteP';
    $cfg->handleOverride();  
    
     require_once("modules/Administration/QuickRepairAndRebuild.php"); 
    $fta_qrr = new RepairAndClear(); 
    $fta_qrr ->repairAndClearAll(array('clearAll'), array(translate('LBL_ALL_MODULES')), FALSE, TRUE);
    
    if(!is_file(sugar_cached('dashlets/dashlets.php'))) {
			require_once('include/Dashlets/DashletCacheBuilder.php');

            $dc = new DashletCacheBuilder();
            $dc->buildCache();
		} 
		
global $sugar_version;
  
        echo "
            <script>
           document.location = 'index.php?module=Home&action=index';
            </script>"; 
}
