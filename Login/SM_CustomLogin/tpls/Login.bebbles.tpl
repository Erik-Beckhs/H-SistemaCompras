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
    @import url('https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900');
    #page{
    padding-top:0%;
    }
    body{
    font-family: 'Montserrat', sans-serif;
    font-weight:300 !important;
    font-size:16px;
    }
    .p0{
    padding: 0px !important;
    }
    .ico{
    margin: 20px auto 20px;
    text-align: center;
    width: 200px;
    }
    .ico img{
    width: 100%;
    height: auto;
    }
    .login-form{
    width:100%;
    background:url("modules/SM_CustomLogin/templates/bebbles/background.jpeg") no-repeat center center;
    background-size:cover;
    height:100%;
    color:white;
    position:absolute !important;
    }
    .bck-grd{
    width:100%;
    height:100%;
    background-size:cover;
    background:rgba(0,0,0,0.1);
    position:absolute;
    align-items: center;
    } 
    .text-center{
    text-align: center;
    }
    .container {
    width:400px;
    padding:0px;
    z-index:1000;
    opacity:1 !important;
    }
    .input-group{
    width: 100%;
    margin-bottom: 15px;
    }
    .input-group label{
    font-weight: 500;
    }
    .login {
    padding: 10px 15px !important;
    border: none !important;
    border-bottom: 1px solid #ddd !important;
    border-radius: 0px !important;
    background:#f2f2f2 !important;
    color:#303030;
    font-family: 'Montserrat', sans-serif;
    }
    .login:focus {
    outline: none !important;
    background: #fff !important;
    }
    .done{
    font-family: 'Montserrat', sans-serif;
    background: #244668 !important;
    color: white !important;
    padding: 7px 25px !important;
    border: 2px solid #244668 !important;
    font-size: 16px !important;
    margin-top: 15px !important;
    font-weight: 700 !important;
    height: auto !important;
    width: 100%;
    }
    .done:hover{
    background-color: #244668 !important;
    border-color: #244668 !important;
    cursor:pointer;
    }
    .mt-30{
    margin-top:30px;
    }
    .help .help:focus{ 
    font-size:16px;
    color:white;
    text-decoration:none;
    }
    .help:hover{
    color:#bcbaba;
    }
    .signin {
    position: relative;
    width:100%;
    margin-bottom: 30px;
    }
    .signin input {
    font-family: 'Montserrat', sans-serif;
    font-weight:400 !important;
    border: none;
    border-bottom: 1px solid #bcbaba;
    padding:10px;
    display: block;
    width:100%;
    background: #bcbaba !important;
    overflow:hidden;
    font-size:16px !important;
    color:#fff;
    }
    .signin input:focus {
    outline: none;
    }
    .signin input:focus ~ label, .signin input:valid ~ label {
    top: -10px;
    font-size: 14px;
    color:#bcbaba;
    }
    .signin label {
    font-family: 'Montserrat', sans-serif;
    font-weight:400 !important;
    position: absolute;
    color: #fff;
    top: 12px;
    left: 0;
    -webkit-transition: 0.2s ease all;
    transition: 0.2s ease all;
    }
    .bar {
    position: relative;
    display: block;
    width: 100%;
    }
    .bar:before {
    content: "";
    position: absolute;
    left: 50%;
    right: 50%;
    bottom: 0;
    background:#fff;
    height: 2px;
    -webkit-transition: left 0.5s ease-out, right 0.5s ease-out;
    transition: left 0.5s ease-out, right 0.5s ease-out;
    }
    input:focus ~ .bar:before {
    left: 0;
    right: 0;
    }
    :required:invalid{
    box-shadow:none;
    }
    .align-left{
    float:left;
    }
    .background-bg{
    height: 100%;
    width: 50%;
    position: absolute;
    right: 0;
    background: hsl(225 100% 7% / 0.58);
    display: flex;
    align-items: center;
    justify-content: center;
    }
    @media screen and (max-width: 620px){
    .container{
    width:100%;
    overflow:hidden;
    margin-right: auto;
    padding: 30px;
    }
    .background-bg{
    width: 100%;
    height: auto;
    }
    }
    .powered-by{
        display: inline-block;
        position: fixed;
        bottom: 20px;
        right: 180px;
        font-size: 12px;
     }
     .powered-by a{
        
     }
</style>
{/literal}
{strip}
<div id="page">
    <div class="login-form">
        <div class="bck-grd">
            <div class="background-bg">
                <div class="container">
                    <div class="ico">
                        {$LOGIN_IMAGE}
                    </div>
                    <div class=""  id="loginDiv">
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
                                <label for="user_name">USUARIO</label>
                                <input type="text" class="login" required id="user_name" name="user_name" value='' autocomplete="off">
                            </div>
                            <div class="control-group input-group">
                                <label for="username_password">PASSWORD</label>
                                <input type="password" class="login" id="username_password" name="username_password" value='' autocomplete="off">
                            </div>
                            <!-- <div class="p0 forgotpasslink" id="forgotpasslink" style="cursor: pointer;"
                                onclick='toggleDisplay("forgot_password_dialog");'>
                                <div class="controls" id="forgotPassword">
                                   <a href='javascript:void(0)'>forgot password?</a>
                                </div>
                                </div> -->
                            <div class="control-group">
                                <input id="bigbutton" class="done" type="submit" class="btn-login" value="Iniciar sesion" />
                            </div>
                        </form>
                        <div class="powered-by">Powered by <a href="#">Limberg Alcon</a></div>
                    </div>
                    <div class="login"   id="forgotPasswordDiv" style="display:none;">
                        <form class="form-signin passform" role="form" action="index.php" method="post" name="DetailView" id="form" name="fp_form" id="fp_form" autocomplete="off">
                            <div id="forgot_password_dialog" style="display:none">
                                <input type="hidden" name="entryPoint" value="GeneratePassword">
                                <div id="generate_success" class='error' style="display:inline;"></div>
                                <br>
                                <div class="control-group input-group">
                                    <label for="fp_user_name">User name</label>
                                    <input type="text" class="login" size='26' id="fp_user_name" name="fp_user_name"
                                        value='' placeholder="Username" autocomplete="off">
                                    <span class="bar"></span>
                                </div>
                                <br>
                                <div class="control-group input-group">
                                    <label for="fp_user_mail">Email</label>
                                    <input type="text" class="login" size='26' id="fp_user_mail" name="fp_user_mail" value='' placeholder="Email" autocomplete="off">
                                    <span class="bar"></span>
                                </div>
                                <br>
                                <div class="p0 forgotpasslink" id="forgotpasslink" style="cursor: pointer;"
                                    onclick='toggleDisplay("forgot_password_dialog");'>
                                    <div class="controls" id="forgotPassword">
                                        <a href='javascript:void(0)'>Back to Login</a>
                                    </div>
                                </div>
                                <div id='wait_pwd_generation'></div>
                                <input title="Email Temp Password" class="done" type="button" style="display:inline" onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''" id="generate_pwd_button" name="fp_login" value="Submit" autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
