<?php
/**
*Esta clase realiza operaciones matemáticas.
*
*@author Limberg Alcon <lalcon@hansa.com.bo>
*@copyright 2018
*@license ruta: /var/www/html/include/generic/SugarWidgets/
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class SugarWidgetSubPanelEliminar extends SugarWidgetField
{
	function displayHeaderCell($layout_def)
	{
		return '&nbsp;';
	}

	function displayList($layout_def)
	{
		echo '<style>
          #eliminar{          
          background-color: #eee !important;
          color:#333 !important;
          border: 1px solid #cccccc;
          padding: 0;
          margin: 0 !important;
          display: inline-block;
          font-size: 1.1em !important;
          padding: 5px 10px;
          float:center;
        }
        #eliminar:hover{
          opacity: 0.8 !important;

      	</style>';

		$id = $layout_def['fields']['ID'];     
		$beand = BeanFactory::getBean('SCO_Despachos', $id);
		$origen = $beand->des_orig;
		$modtrans = $beand->des_modtra;
		$id_emba = $beand->sco_embarque_sco_despachossco_embarque_ida;
		$estado_des = $beand->des_est;
		switch ($estado_des) {
			case '2':
				
				break;
			case '3':
				
				break;
			default:
			
			global $app_strings;
	        global $subpanel_item_count;

			$unique_id = $layout_def['subpanel_id']."_remove_".$subpanel_item_count; //bug 51512
			
			$parent_record_id = $_REQUEST['record'];
			$parent_module = $_REQUEST['module'];

			$action = 'DeleteRelationship';
			$record = $layout_def['fields']['ID'];
			$current_module=$layout_def['module'];
			//in document revisions subpanel ,users are now allowed to 
			//delete the latest revsion of a document. this will be tested here
			//and if the condition is met delete button will be removed.
			$hideremove=false;
			if ($current_module=='DocumentRevisions') {
				if ($layout_def['fields']['ID']==$layout_def['fields']['LATEST_REVISION_ID']) {
					$hideremove=true;
				}
			}
			// Implicit Team-memberships are not "removeable" 
			elseif ($_REQUEST['module'] == 'Teams' && $current_module == 'Users') {
				if($layout_def['fields']['UPLINE'] != translate('LBL_TEAM_UPLINE_EXPLICIT', 'Users')) {
					$hideremove = true;
				}	
				
				//We also cannot remove the user whose private team is set to the parent_record_id value
				$user = new User();
				$user->retrieve($layout_def['fields']['ID']);
				if($parent_record_id == $user->getPrivateTeamID())
				{
				    $hideremove = true;
				}
			}
			
			
			$return_module = $_REQUEST['module'];
			$return_action = 'SubPanelViewer';
			$subpanel = $layout_def['subpanel_id'];
			$return_id = $_REQUEST['record'];
			if (isset($layout_def['linked_field_set']) && !empty($layout_def['linked_field_set'])) {
				$linked_field= $layout_def['linked_field_set'] ;
			} else {
				$linked_field = $layout_def['linked_field'];
			}
			$refresh_page = 0;
			if(!empty($layout_def['refresh_page'])){
				$refresh_page = 1;
			}
			$return_url = "index.php?module=$return_module&action=$return_action&subpanel=$subpanel&record=$return_id&sugar_body_only=1&inline=1";

			$icon_remove_text = $app_strings['LBL_ID_FF_REMOVE'];
			
	         if($linked_field == 'get_emails_by_assign_or_link')
	            $linked_field = 'emails';
			//based on listview since that lets you select records
			if($layout_def['ListView'] && !$hideremove) {
	            $retStr = "<a id=\"eliminar\"href=\"javascript:sub_p_rem('$subpanel', '$linked_field'" 
	                    .", '$record', $refresh_page);\"" 
				. ' class="listViewTdToolsS1"'
	            . "id=$unique_id"
				. " onclick=\"return sp_rem_conf();\""
				. ">$icon_remove_text</a>";
	        return $retStr;
	            
			}else{
				return '';
			}
		
				break;
		}		
	}
}
