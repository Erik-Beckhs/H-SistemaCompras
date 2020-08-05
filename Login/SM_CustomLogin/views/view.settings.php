<?php
/******************************
 * Extension Name: White Label Login for SuiteCRM
 * Description: White Label your Suite CRM to create your own branded custom login page.
 * Version: 1.0
 *
 *This 3rd party module should retain the "Powered by Smackcoders" logo and link in an appropriate manner in its location.
 *
 *Portions created by Smackcoders, Inc.
 *All Rights Reserved.
 *Contributor(s): Smackcoders **
 *Author URI: https://www.smackcoders.com
 */

/*********************************************************************************
 * White Label Login is a Tool for SuiteCRM to create branded login page
 * developed by Smackcoders. Copyright (C) 2019 Smackcoders.
 *
 * This module is a free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3
 * as published by the Free Software Foundation with the addition of the
 * following permission added to Section 15 as permitted in Section 7(a): FOR
 * ANY PART OF THE COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY SMACKCODERS,
 * SMACKCODERS DISCLAIMS THE WARRANTY OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program; if not, see http://www.gnu.org/licenses or write
 * to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 *
 * You can contact Smackcoders at email address projects@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * Smackcoders copyright notice. If the display of the logo is not reasonably
 * feasible for technical reasons, the Appropriate Legal
 * Notices must display the words
 * "Copyright Smackcoders. 2019. All rights reserved".
 ********************************************************************************/

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');

class ViewSettings extends SugarView{

    protected function _getModuleTitleParams($browserTitle = false){
        global $mod_strings;

        return array(
           "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
           translate('LBL_CUSTOMLOGIN','Administration'),
           );
    }


    public function preDisplay(){

        global $current_user;

        if(!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }

    }

    public function display(){

    	if(!isset($_GET['show']))
			header('location:index.php?module=SM_CustomLogin&action=settings&show=Loginsettings');

		global $db;

		global $file;

		$file = $_FILES['file']['name'];
		$target = 'cache/images/'.$file;
		move_uploaded_file( $_FILES['file']['tmp_name'], $target);

		$template_option = $_REQUEST['template_option'];

		$template_option_update = $_REQUEST['template_option_update'];

		if($template_option == 'template_option'){

			$temp_name = $_REQUEST['template_name'];
			$small = ucfirst($temp_name);
			$name = strtolower($small);
			$data = preg_replace('/\s+/', '_', $name);
			$temp_layout = $_REQUEST['template_layout'];
			$page_bg = $_REQUEST['bg_color'];
			if($_FILES['file']['name']){
				$page_bg_img = $_FILES['file']['name'];
			}
			$form_bg = $_REQUEST['form_bg'];
			$input_bg = $_REQUEST['input_bg'];
			$input_border = $_REQUEST['input_border'];
			$input_border_radius = $_REQUEST['input_border_radius'];
			$input_text_color = $_REQUEST['input_text_color'];
			$input_label_color = $_REQUEST['input_label_color'];
			$button_bg = $_REQUEST['button_bg'];
			$button_border = $_REQUEST['button_border'];
			$button_border_radius = $_REQUEST['button_border_radius'];
			$button_text_color = $_REQUEST['button_text_color'];

			$db->query("INSERT INTO sm_customlogin (modulename, template_module, template_name, isenabled) values ('custom', '".$data."', '".$temp_name."', '0')");
			$db->query("INSERT INTO sm_customlogin_template_options (template_name, template_template, template_layout, form_bg, input_bg, input_border, input_border_radius, input_text_color, input_label_color, button_bg, button_border, button_border_radius, button_text_color) values ('".$data."', '".$temp_name."', '".$temp_layout."', '".$form_bg."', '".$input_bg."', '".$input_border."', '".$input_border_radius."', '".$input_text_color."', '".$input_label_color."', '".$button_bg."', '".$button_border."', '".$button_border_radius."', '".$button_text_color."')");
			$db->query("UPDATE sm_customlogin_template_options set bg_options = '".$_REQUEST['bg_options']."'");
			if($_REQUEST['bg_options'] == 'background_color'){
				$db->query("UPDATE sm_customlogin_template_options set page_bg = '".$page_bg."'");
			}
			if($_REQUEST['bg_options'] == 'background_image'){
				$db->query("UPDATE sm_customlogin_template_options set page_bg_img = '".$page_bg_img."'");
			}
		}

		$template_action = $_REQUEST['template_action'];

		if($template_action == 'delete'){			
			$temp_name = $_REQUEST['template_name'];
			$db->query("DELETE from sm_customlogin where template_module = '".$temp_name."'");
			$db->query("DELETE from sm_customlogin_template_options where template_name = '".$temp_name."'");
			$db->query("UPDATE sm_customlogin_settings set template_name = 'abstract', template_selected = 'abstract' where template_name = '".$temp_name."'");
			return true;
		}

		if($template_option_update == 'template_option_update'){

			$template_name = $_REQUEST['template_name'];
			$temp_name_update = $_REQUEST['template_name_update'];
			$small_update = ucfirst($temp_name_update);
			$name_update = strtolower($small_update);
			$data_update = preg_replace('/\s+/', '_', $name_update);
			$temp_layout_update = $_REQUEST['template_layout_update'];
			$page_bg_update = $_REQUEST['bg_color_update'];
			if($_FILES['file']['name'] != ""){
				$page_bg_img_update = $_FILES['file']['name'];
			}
			$form_bg_update = $_REQUEST['form_bg_update'];
			$input_bg_update = $_REQUEST['input_bg_update'];
			$input_border_update = $_REQUEST['input_border_update'];
			$input_border_radius_update = $_REQUEST['input_border_radius_update'];
			$input_text_color_update = $_REQUEST['input_text_color_update'];
			$input_label_color_update = $_REQUEST['input_label_color_update'];
			$button_bg_update = $_REQUEST['button_bg_update'];
			$button_border_update = $_REQUEST['button_border_update'];
			$button_border_radius_update = $_REQUEST['button_border_radius_update'];
			$button_text_color_update = $_REQUEST['button_text_color_update'];

			$db->query("UPDATE sm_customlogin set template_module = '".$data_update."', template_name = '".$temp_name_update."' WHERE template_module = '".$template_name."'");

			$db->query("UPDATE sm_customlogin_settings set template_name = '".$data_update."' where template_name = '".$template_name."'");

			$db->query("UPDATE sm_customlogin_template_options SET 
				template_name = '".$data_update."', 
				template_template = '".$temp_name_update."',
				template_layout = '".$temp_layout_update."',   
				form_bg = '".$form_bg_update."', 
				input_bg = '".$input_bg_update."', 
				input_border = '".$input_border_update."', 
				input_border_radius = '".$input_border_radius_update."', 
				input_text_color = '".$input_text_color_update."', 
				input_label_color = '".$input_label_color_update."', 
				button_bg = '".$button_bg_update."', 
				button_border = '".$button_border_update."', 
				button_border_radius = '".$button_border_radius_update."', 
				button_text_color = '".$button_text_color_update."'
			WHERE template_name = '".$template_name."'");

			$db->query("UPDATE sm_customlogin_template_options set bg_options = '".$_REQUEST['bg_options_update']."'");

			if($_REQUEST['bg_options_update'] == 'background_color'){
				$db->query("UPDATE sm_customlogin_template_options SET page_bg = '".$page_bg_update."' WHERE template_name = '".$template_name."'");
				$db->query("UPDATE sm_customlogin_template_options SET page_bg_img = 'No file Choosen...' WHERE template_name = '".$template_name."'");
			} else if($_REQUEST['bg_options_update'] == 'background_image'){
				if(!empty($page_bg_img_update)){
					$db->query("UPDATE sm_customlogin_template_options SET page_bg = 'NULL' WHERE template_name = '".$template_name."'");
					$db->query("UPDATE sm_customlogin_template_options SET page_bg_img = '".$page_bg_img_update."' WHERE template_name = '".$template_name."'");
				}
			}

		}

		$action = $_REQUEST['module_action'];
		$module_value = $_REQUEST['value'];
		if($action === 'template_change'){
			$db->query("UPDATE sm_customlogin SET isenabled='0'");
			$db->query("UPDATE sm_customlogin SET isenabled='1' WHERE template_module = '".$module_value."'");

			$module_selected = $db->query("SELECT * FROM sm_customlogin WHERE template_module='".$module_value."'");
			$row = $db->fetchByAssoc($module_selected);
			$module_selected = $row['modulename'];
			$module_selected_name = $row['template_module'];

			$db->query("UPDATE sm_customlogin_settings SET template_selected = '".$module_selected."', template_name = '".$module_selected_name."'");
			return true;
		}

		$module_name = $template_img_path = array();

		$count_query = $db->query("SELECT * FROM sm_customlogin ORDER BY id asc");

		while($custom_login_template = $db->fetchByAssoc($count_query)){
			$module_name[$custom_login_template['template_name']] = $custom_login_template['template_module'];
			$template_img_path[$custom_login_template['template_name']] = $custom_login_template['modulename'];
		}

		$template_selected = $db->query("SELECT * FROM sm_customlogin_settings WHERE id='1'");
		$row = $db->fetchByAssoc($template_selected); 
		$template_selected = $row['template_selected'];
		$template_selected_name = $row['template_name'];
		// echo $template_selected;

		$template_row = $db->query("SELECT * FROM sm_customlogin_template_options ORDER BY id asc");
		// print_r($template_count); 
		// echo '<br> hai '.$template_count["num_rows"];
		$array_values = array();
		while($custom_template_options = $db->fetchByAssoc($template_row)){
			array_push($array_values, $custom_template_options);
		}
		// echo '<pre>'; print_r($array_values);
		$this->ss->assign('template_row', $array_values);

		$this->ss->assign('template_img_path', $template_img_path);
		$this->ss->assign('SELECTED_MODULE_NAME', $module_name);
		$this->ss->assign('SELECTED_TEMPLATE', $template_selected);
		$this->ss->assign('SELECTED_TEMPLATE_NAME', $template_selected_name);

		$this->ss->display('modules/SM_CustomLogin/tpls/Index.tpl');
	}
}
