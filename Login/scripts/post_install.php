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

if (! defined('sugarEntry') || ! sugarEntry) die('Not A Valid Entry Point');

function post_install() {

    global $sugar_version, $db, $dictionary, $current_user;

    require_once('include/utils.php');
    require_once('include/utils/file_utils.php');
    require_once('config.php');
    require_once('include/MVC/Controller/SugarController.php');
    require_once('modules/ModuleBuilder/controller.php');
    require_once('modules/ModuleBuilder/parsers/ParserFactory.php');

       $db->query("CREATE TABLE `sm_customlogin` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `modulename` tinytext,
          `template_module` tinytext,
          `template_name` tinytext ,
          `isenabled` enum('0','1') DEFAULT NULL,
          PRIMARY KEY (`id`))");

       $db->query("INSERT INTO sm_customlogin
               (id, modulename, template_module, template_name, isenabled) VALUES 
               ('1', 'abstract', 'abstract', 'Abstract', '0')");
       $db->query("INSERT INTO sm_customlogin
               (id, modulename, template_module, template_name, isenabled) VALUES 
               ('2', 'bebbles', 'bebbles', 'Bebbles', '1')");
       $db->query("INSERT INTO sm_customlogin
               (id, modulename, template_module, template_name, isenabled) VALUES
               ('3', 'business', 'business', 'Business', '0')");

        $db->query("CREATE TABLE `sm_customlogin_settings`(
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `template_name` varchar(100) DEFAULT NULL,
          `template_selected` varchar(100) DEFAULT NULL,
          PRIMARY KEY (`id`)
        )");   
        $db->query("INSERT INTO sm_customlogin_settings
               (id, template_name, template_selected) VALUES
               ('1', 'bebbles', 'bebbles')");

        $db->query("CREATE TABLE `sm_customlogin_template_options`(
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `template_name` varchar(100) DEFAULT NULL,
          `template_template` varchar(100) DEFAULT NULL,
          `template_layout` tinytext,
          `bg_options` tinytext,
          `page_bg` tinytext,
          `page_bg_img` tinytext,
          `form_bg` tinytext,
          `input_bg` tinytext,
          `input_border` tinytext,
          `input_border_radius` tinytext,
          `input_text_color` tinytext,
          `input_label_color` tinytext,
          `button_bg` tinytext,
          `button_border` tinytext,
          `button_border_radius` tinytext,
          `button_text_color` tinytext,
          PRIMARY KEY (`id`)
        )"); 


    global $sugar_version;
    if(preg_match( "/^6.*/", $sugar_version)) {
        echo "
            <script>
            document.location = 'index.php?module=SM_CustomLogin&action=settings';
            </script>"
        ;
    } else {
        echo "
            <script>
            var app = window.parent.SUGAR.App;
            window.parent.SUGAR.App.sync({callback: function(){
                app.router.navigate('#bwc/index.php?module=SM_CustomLogin&action=settings', {trigger:true});
            }});
            </script>"
        ;
    }
}

