{*
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
*}
{literal}
<style>
.login-form{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
}
.form-container{
    align-self: center;
    padding: 15px;
    background: #fff;
    border-radius: 6px;
    position: relative;
}
.ico{
    width: 200px;
    margin: auto;
}
.ico img{
    width: 100%;
    height: auto;
}
#bigbutton{
    width: 100%;
    line-height: 1;
    font-weight: bold;
    border: 1px solid;
    margin-top: 10px;
    height: 40px;
}
.input-group input{
    border: 1px solid transparent;
    outline: none;
    height: 40px;
}

.template-style-layout-two-column .login-form{
    background-position: left center !important;
    background-size: cover;
    background-repeat: no-repeat;
    justify-content: flex-end;
    padding: 0;
}

.template-style-layout-two-column .login-form .form-container{
    width: 50%;
    height: 100vh;
    border-radius: 0;
    display: inline-grid;
}

.template-style-layout-two-column .login-form .form-container form{
    max-width: 300px;
    margin: auto;
}

.template-style-layout-two-column .login-form .form-container form .input-group{
    width: 100%;
}

.template-style-layout-two-column .powered-by{
    position: absolute;
    bottom: 20px;
    right: 35%;
}

.template-style-layout-circle .login-form{
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    justify-content: center;
    padding: 50px;
}

.template-style-layout-circle .powered-by{
    position: absolute;
    bottom: 30px;
    right: 35%;
}

.template-style-layout-circle .login-form .form-container{
    padding: 130px;
    border-radius: 50%;
}

.template-style-layout-default .login-form{
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    justify-content: center;
    padding: 50px;
}

.template-style-layout-default .login-form .form-container{
    padding: 50px;
    border-radius: 6px;
}

.template-style-layout-default .powered-by{
    position: absolute;
    bottom: 10px;
    right: 30%;
}

.template-style-layout-right .login-form{
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    justify-content: flex-start;
    padding: 80px;
}

.template-style-layout-right .login-form .form-container{
    padding: 40px;
    border-radius: 6px;
    height: 80vh;
    display: inline-grid;
}

.template-style-layout-right .powered-by{
    position: absolute;
    bottom: 15px;
    right: 30%;
}

</style>
{/literal}

{strip}

<div class="template-style-{$template_layout_class}" id="page">
	<div class="login-form" style="background-color: {$page_bg}; background-image: url('cache/images/{$page_bg_img}');">
		<div class="form-container" style="background-color: {$form_bg};">

			<div class="ico">
                {$LOGIN_IMAGE}
            </div>
            <div id="signin">
                <form class="form-signin" role="form" action="index.php" method="post" name="DetailView" id="form"
                    onsubmit="return document.getElementById('cant_login').value == ''" autocomplete="off">
                    <span class="error" id="browser_warning" style="display:none">
                    {sugar_translate label="WARN_BROWSER_VERSION_WARNING"}
                    </span>
                    <span class="error" id="ie_compatibility_mode_warning" style="display:none">
                    {sugar_translate label="WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING"}
                    </span>
                    {if $LOGIN_ERROR !=''}
                    <span class="error">{$LOGIN_ERROR}</span>
                    {if $WAITING_ERROR !=''}
                    <span class="error">{$WAITING_ERROR}</span>
                    {/if}
                    {else}
                    <span id='post_error' class="error"></span>
                    {/if}
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="Authenticate">
                    <input type="hidden" name="return_module" value="Users">
                    <input type="hidden" name="return_action" value="Login">
                    <input type="hidden" id="cant_login" name="cant_login" value="">
                    {foreach from=$LOGIN_VARS key=key item=var}
                    <input type="hidden" name="{$key}" value="{$var}">
                    {/foreach}
                    {if !empty($SELECT_LANGUAGE)}
                    <div class="login-language-chooser" >
                        {sugar_translate module="Users" label="LBL_LANGUAGE"}:
                        <select name='login_language' onchange="switchLanguage(this.value)">{$SELECT_LANGUAGE}</select>
                    </div>
                    {/if}
                    <br>
                    <div class="control-group input-group">
                        <label for="user_name" style="color: {$input_label_color};">USERNAME</label>
                        <input type="text" class="login" required id="user_name" name="user_name" value='' autocomplete="off" style="background-color: {$input_bg}; border-color: {$input_border}; border-radius: {$input_border_radius}px; color: {$input_text_color};">
                        <span class="bar"></span>
                    </div>
                    <br>
                    <div class="control-group input-group">
                        <label for="username_password" style="color: {$input_label_color};">PASSWORD</label>
                        <input type="password" class="login" id="username_password" name="username_password" value='' autocomplete="off" style="background-color: {$input_bg}; border-color: {$input_border}; border-radius: {$input_border_radius}px; color: {$input_text_color};">
                        <span class="bar"></span>
                    </div>
                    <br>
                    <div class="control-group mt-20" style="display: flex;">
                        <input id="bigbutton" class="btn-login" type="submit" class="btn-login" value="Login" style="background-color: {$button_bg}; border-color: {$button_border}; border-radius: {$button_border_radius}px; color: {$button_text_color};" />
                    </div>
                </form>
            </div>
            <div class="powered-by">Powered by <a href="#">Limberg Alcon</a></div>		
		</div>
	</div>
</div>

{/strip}
