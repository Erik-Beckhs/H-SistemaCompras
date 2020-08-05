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

global $db;
$template = new Sugar_Smarty();
$template_selected = $db->query("SELECT * FROM sm_customlogin_settings WHERE id='1'");
$row = $db->fetchByAssoc($template_selected);
$template_selected = $row['template_selected'];

$login_image = is_file('custom/include/images/sugar_md.png') ?
    '<IMG src="custom/include/images/sugar_md.png" alt="Sugar" width="340" height="25">' :
    '<IMG src="include/images/sugar_md_open.png" alt="Sugar" width="340" height="25" style="margin: 5px 0;">';

$login_image_url = SugarThemeRegistry::current()->getImageURL('company_logo.png');
$login_image = '<IMG src="' . $login_image_url . '" alt="SuiteCRM" style="margin: 5px 0;">';

$template->assign('LOGIN_IMAGE', $login_image);

$template_layout = $db->query("SELECT * FROM sm_customlogin WHERE isenabled='1'");
$layout_row = $db->fetchByAssoc($template_layout);
$template_layout = $layout_row['template_module'];

$template_layout_selected = $db->query("SELECT * FROM sm_customlogin_template_options WHERE template_name = '".$template_layout."'");
$row_class = $db->fetchByAssoc($template_layout_selected);
$template_layout_selected = $row_class['template_layout'];
$page_bg = $row_class['page_bg'];
$page_bg_img = $row_class['page_bg_img'];
$form_bg = $row_class['form_bg'];
$input_bg = $row_class['input_bg'];
$input_border = $row_class['input_border'];
$input_border_radius = $row_class['input_border_radius'];
$input_text_color = $row_class['input_text_color'];
$input_label_color = $row_class['input_label_color'];
$button_bg = $row_class['button_bg'];
$button_border = $row_class['button_border'];
$button_border_radius = $row_class['button_border_radius'];
$button_text_color = $row_class['button_text_color'];

$template->assign('template_layout_class', $template_layout_selected);
$template->assign('page_bg', $page_bg);
$template->assign('page_bg_img', $page_bg_img);
$template->assign('form_bg', $form_bg);
$template->assign('input_bg', $input_bg);
$template->assign('input_border', $input_border);
$template->assign('input_border_radius', $input_border_radius);
$template->assign('input_text_color', $input_text_color);
$template->assign('input_label_color', $input_label_color);
$template->assign('button_bg', $button_bg);
$template->assign('button_border', $button_border);
$template->assign('button_border_radius', $button_border_radius);
$template->assign('button_text_color', $button_text_color);

$template->display('modules/SM_CustomLogin/tpls/Login.'.$template_selected.'.tpl');
