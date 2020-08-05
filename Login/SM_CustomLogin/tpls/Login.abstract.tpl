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
    margin:0% !important;
    }
    body {
    font-family: 'Montserrat', sans-serif;
    font-size:14px;
    }
    .p0{
    padding: 0px !important;
    }
    .mt-20{
    margin-top:20px;
    }
    .mt-30{
    margin-top:30px;
    }
    .mt-50{
    margin-top:50px;
    }
    .login-bckgrd{
    width:100%;
    height:100%;
    background:url("modules/SM_CustomLogin/templates/abstract/background.jpeg");
    background-size:cover; 
    position:absolute;
    }
    .bck-grd{
    display: flex;
    align-items: center;
    }
    .container{
    width:30%;
    margin:auto;
    background:#fff;
    border-radius:6px;
    padding:30px;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.3);
    }
    .text-center{
    text-align:center;
    }
    .input-group{
    width: 100%;
    }
    .input-group label{
    color: #9e97b4;
    font-weight: 500;
    margin-bottom: 0px;
    }
    .login{
    width:100%;
    border:none !important;
    font-family: 'Montserrat', sans-serif;
    border-bottom:1px solid lightgray !important;
    font-size:16px;
    padding: 10px !important;
    background: #fff !important;
    overflow:hidden;
    border-radius: 0px !important;
    }
    .login:focus{
    outline: none !important;
    }
    .help{
    text-decoration:none;
    font-size:14px;
    float:right;
    }
    .help:hover{
    text-decoration:underline;
    color:#911710;
    }
    .back{
    text-decoration:none;
    font-size:14px;
    float:right;
    cursor:pointer !important;
    }
    .back:hover{
    text-decoration:underline;
    color:#911710;
    }
    .btn-login{
    border:1px solid #007397 !important;
    border-radius: 4px !important;
    background: #007397 !important;
    color: #fff !important;
    padding: 5px 85px !important;
    font-size:15px !important;
    height: auto !important;
    margin: auto !important;
    }
    .btn-login:hover{
    border-color: #004b63 !important;
    background: #004b63 !important;
    cursor:pointer;
    transition: all 0.5s;
    }
    .fa{
    font-size:30px !important;
    text-align:center;
    color:#911710;
    }
    .ico{
    margin:20px auto 20px;
    text-align:center;
    width: 200px;
    }
    .ico img{
    width: 100%;
    height: auto;
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
    background:#007397;
    height: 2px;
    -webkit-transition: left 0.3s ease-out, right 0.3s ease-out;
    transition: left 0.3s ease-out, right 0.3s ease-out;
    }
    .login:focus ~ .bar:before {
    left: 0;
    right: 0;
    }
    :required:invalid{
    box-shadow:none;
    }
    .bck-grd{
    width:100%;
    height:100%;
    background-size:cover;
    background:rgba(0,0,0,0.1);
    position:absolute;
    }
    @media screen and (max-width:500px){
    .container{
    min-width:95%;
    margin:auto;
    margin-top:20% !important;
    padding:20px;
    }
    }
    @media screen and (max-width:768px){
    .container{
    width:60%;
    margin:auto;
    margin-top:10%;
    }
    }
    .powered-by{
            display: inline-block;
            position: fixed;
            bottom: 20px;
            right: 45%;
            font-size: 12px;
            color: #fff;
     }
</style>
<script type='text/javascript'>
    var LBL_LOGIN_SUBMIT = '{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}';
    var LBL_REQUEST_SUBMIT = '{sugar_translate module="Users" label="LBL_REQUEST_SUBMIT"}';
    var LBL_SHOWOPTIONS = '{sugar_translate module="Users" label="LBL_SHOWOPTIONS"}';
    var LBL_HIDEOPTIONS = '{sugar_translate module="Users" label="LBL_HIDEOPTIONS"}';
</script>
{/literal}
{strip}
<div id="page">
    <div class="login-bckgrd">
        <div class="bck-grd">
            <div class="container" id="loginDiv">
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
                            <label for="user_name">USERNAME</label>
                            <input type="text" class="login" required id="user_name" name="user_name" value='' autocomplete="off">
                            <span class="bar"></span>
                        </div>
                        <br>
                        <div class="control-group input-group">
                            <label for="username_password">PASSWORD</label>
                            <input type="password" class="login" id="username_password" name="username_password" value='' autocomplete="off">
                            <span class="bar"></span>
                        </div>
                        <br>
                        <!-- <div class="p0 forgotpasslink" id="forgotpasslink" style="cursor: pointer; display:{$DISPLAY_FORGOT_PASSWORD_FEATURE};"
                            onclick='toggleDisplay("forgot_password_dialog");'>
                            <div class="controls" id="forgotPassword">
                               <a href='javascript:void(0)'>forgot password?</a>
                            </div>
                            </div> -->
                        <div class="control-group mt-20" style="display: flex;">
                            <input id="bigbutton" class="btn-login" type="submit" class="btn-login" value="Login" />
                        </div>
                    </form>
                </div>
                <div class="powered-by">Powered by <a href="#">Limberg Alcon</a></div>
                <div id="forgotPasswordDiv" style="display:none;">
                    <div id="forgot">
                        <div class="ico">
                            <span class="fa fa-newspaper-o"></span>
                        </div>
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
                                <div class="p0 forgotpasslink" id="forgotpasslink" style="cursor: pointer; display:{$DISPLAY_FORGOT_PASSWORD_FEATURE};"
                                    onclick='toggleDisplay("forgot_password_dialog");'>
                                    <div class="controls" id="forgotPassword">
                                        <a href='javascript:void(0)'>Back to Login</a>
                                    </div>
                                </div>
                                <div id='wait_pwd_generation'></div>
                                <input title="Email Temp Password" class="btn-login" type="button" style="display:inline" onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''" id="generate_pwd_button" name="fp_login" value="Submit" autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}
