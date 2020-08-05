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

<link rel="stylesheet" href="modules/SM_CustomLogin/public/css/jquery.minicolors.css" type="text/css"/>
<link rel="stylesheet" href="modules/SM_CustomLogin/public/css/custom_login.css" type="text/css"/>

<script type="text/javascript" src="modules/SM_CustomLogin/public/js/jquery.minicolors.js"></script>

<script type="text/javascript">

$(document).ready(function(){

    $('.input-color').minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        format: $(this).attr('data-format') || 'hex',
        keywords: $(this).attr('data-keywords') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom',
        swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
        change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
                console.log(value);
            }
        },
        theme: 'bootstrap',
    });

    jQuery('input[type="file"]').on('change', function(e){
        var value = e.target.files;
        var filename = value[0].name;
        jQuery(this).siblings('.file-upload-btn').children('span').text(filename);
    });

    $('.btn-save').on('click', function(){
        $('.btn-save').removeClass('active');
        $('.btn-save').val('Activate');
        var $this = $(this);
        var value = $this.data('value');
        $.ajax({
            type: 'GET',
            url: 'index.php?module=SM_CustomLogin&action=settings&show=Loginsettings',
            data: {
                value: value,
                module_action: 'template_change',
            },
            success: function(){
                $this.val('Activated');
                $this.addClass('active');
            },
        });
    });

    $('input[name="bg_options"]').on('change', function(){
        var value = $('input[name="bg_options"]:checked').val();
        if(value == 'background_color'){
            $('.bg-image-option').removeClass('active');
            $('.bg-color-option').addClass('active');
        } else if(value == 'background_image'){
            $('.bg-image-option').addClass('active');
            $('.bg-color-option').removeClass('active');
        }
    });

    $('input[name="bg_options_update"]').on('change', function(){
        var value = $('input[name="bg_options_update"][data-id="'+$(this).data('id')+'"]:checked').val();
        if(value == 'background_color'){
            $('.bg-image-option').removeClass('active');
            $('.bg-color-option').addClass('active');
        } else if(value == 'background_image'){
            $('.bg-image-option').addClass('active');
            $('.bg-color-option').removeClass('active');
        }
    });

    $('.add-template img').on('click', function(){
        $('.custom-modal').css('display', 'flex');
    });

    $('.custom-modal .close').on('click', function(){
        $('.custom-modal').hide();
    });

    $('.custom-update-modal .close').on('click', function(){
        $('.custom-update-modal').hide();
    });

    $('.btn-cancel').on('click', function(){
        $('.custom-modal').hide();
        $('.custom-update-modal').hide();
    });

    var storedFiles = [];
    $('#bg_img').on('change', handleFileSelect);

    function handleFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function(f) {
            storedFiles.push(f);
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.login-page').css({
                    'background-image': 'url("'+e.target.result+'")',
                });
            }
            reader.readAsDataURL(f); 
        });
        var filename = files[0].name;
        $(this).attr('data-value', filename);
    }

    $('input[name="file"]').on('change', handleFileSelectUpdate);

    function handleFileSelectUpdate(e) {
        var temp_name = $(this).parent().siblings('#template_name').val();
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function(f) {
            storedFiles.push(f);
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.custom-update-modal.'+temp_name+' .login-page').css({
                    'background-image': 'url("'+e.target.result+'")',
                });
            }
            reader.readAsDataURL(f); 
        });
        var filename = files[0].name;
        $(this).attr('data-value', filename);
    }

    $('.template-delete').on('click', function(){

        if( confirm("Are you sure you want to delete?") == true ) {
            var _this = $(this);
            var template_name = $(this).data('template');
            $.ajax({
                type: "GET",
                url: "",
                data: {
                    template_name: template_name,
                    template_action: 'delete',
                },
                success: function(){
                    $(_this).parent().parent().parent().parent().remove();
                    $('.btn-save[data-value="abstract"]').addClass('active');
                    $('.btn-save[data-value="abstract"]').val('Activated');
                },
            });
            return true;
        } else {
            return;
        }

    });

    $('.template-update').on('click', function(){
        var _this = $(this);
        var template_name = $(this).data('template');
        var temp_name = $(this).data('temp-name');
        $.ajax({
            type: "GET",
            url: "index.php?module=SM_CustomLogin&action=settings&show=Loginsettings",
            data: {
                temp_name: temp_name,
                template_name: template_name,
                template_update: 'update',
            },
            success: function(){
                $('.custom-update-modal.'+template_name).css('display', 'flex');
            },
        });
    });

    $('input[name="template_layout"]').on('change', function(){
        $('.login-page').attr('data-id', $(this).val());
    });

    $('input[name="template_layout_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .login-page').attr('data-id', $(this).val());
    });

    $('#bg_color').on('change', function(){
        $('.login-page').css({
            'background-color': $(this).val(),
        });
    });

    $('input[name="bg_color_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .login-page').css({
            'background-color': $(this).val(),
        });
    });

    $('#form_bg').on('change', function(){
        $('.form-container').css({
            'background-color': $(this).val(),
        });
    });

    $('input[name="form_bg_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container').css({
            'background-color': $(this).val(),
        });
    });

    $('#input_bg').on('change', function(){
        $('.form-container .form-group input').css({
            'background-color': $(this).val(),
        });
    });

    $('input[name="input_bg_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group input').css({
            'background-color': $(this).val(),
        });
    });

    $('#input_border').on('change', function(){
        $('.form-container .form-group input').css({
            'border-color': $(this).val(),
        });
    });

    $('input[name="input_border_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group input').css({
            'border-color': $(this).val(),
        });
    });

    $('#input_border_radius').on('change', function(){
        $('.form-container .form-group input').css({
            'border-radius': $(this).val()+'px',
        });
    });

    $('input[name="input_border_radius_update"]').on('change', function(){
        var temp_name = $(this).parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group input').css({
            'border-radius': $(this).val()+'px',
        });
    });

    $('#input_text_color').on('change', function(){
        $('.form-container .form-group input').css({
            'color': $(this).val(),
        });
    });

    $('input[name="input_text_color_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group input').css({
            'color': $(this).val(),
        });
    });

    $('#input_label_color').on('change', function(){
        $('.form-container .form-group label').css({
            'color': $(this).val(),
        });
    });

    $('input[name="input_label_color_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group label').css({
            'color': $(this).val(),
        });
    });

    $('#button_bg').on('change', function(){
        $('.form-container .form-group button').css({
            'background': $(this).val(),
        });
    });

    $('input[name="button_bg_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group button').css({
            'background': $(this).val(),
        });
    });

    $('#button_border').on('change', function(){
        $('.form-container .form-group button').css({
            'border-color': $(this).val(),
        });
    });

    $('input[name="button_border_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group button').css({
            'border': '1px solid '+$(this).val(),
        });
    });

    $('#button_border_radius').on('change', function(){
        $('.form-container .form-group button').css({
            'border-radius': $(this).val()+'px',
        });
    });

    $('input[name="button_border_radius_update"]').on('change', function(){
        var temp_name = $(this).parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group button').css({
            'border-radius': $(this).val()+'px',
        });
    });

    $('#button_text_color').on('change', function(){
        $('.form-container .form-group button').css({
            'color': $(this).val(),
        });
    });

    $('input[name="button_text_color_update"]').on('change', function(){
        var temp_name = $(this).parent().parent().siblings('#template_name').val();
        $('.custom-update-modal.'+temp_name+' .form-container .form-group button').css({
            'color': $(this).val(),
        });
    });

});

</script>

{/literal}

{strip}

<div class="custom-login-header">
    <h1>White Label Login</h1>
</div>

<div class="login-page-template">

    {foreach from=$SELECTED_MODULE_NAME item=module_name key=template}
        <div class="login-li">
            <div class="box">
                <div class="template-img">
                    <img src="modules/SM_CustomLogin/templates/{$template_img_path[$template]}/box_image.png" />
                    {if $template_img_path[$template] eq 'custom'}
                        <div class="template-options">
                            <div title="Settings" data-template="{$module_name}" data-temp-name="{$template}" class="template-update"><span class="glyphicon glyphicon-cog"></span></div>
                            <div title="Delete" data-template="{$module_name}" class="template-delete"><span class="glyphicon glyphicon-trash"></span></div>
                        </div>
                    {/if}
                </div>
                <div class="template-details box-padding">
                    <h2>{$template}</h2>
                    <input type="button" class="btn btn-primary btn-save mt30 {if $module_name eq $SELECTED_TEMPLATE_NAME} active {/if}" data-value="{$module_name}" value="{if $module_name eq $SELECTED_TEMPLATE_NAME} Activated {else} Activate {/if}" />
                </div>
            </div>
        </div>
    {/foreach}

    <div class="login-li">
        <div class="box">
            <div class="template-details box-padding text-center add-template">
                <div class="template-img">
                    <img src="modules/SM_CustomLogin/public/img/add-circular-button.png" />
                </div>
                <h2>Create Template</h2>
            </div>
        </div>
    </div>
    
</div>

<div class="custom-modal">
    <div class="custom-modal-box">

    <div class="row" style="height: 100%;">
    <form id="page-bg-img" action="index.php?module=SM_CustomLogin&action=settings&show=Loginsettings" method="POST" enctype="multipart/form-data">
        <div class="col-md-8 template-section">

            <div class="img-preview">
                <div data-id="layout-two-column" class="login-page" style="background-color:#fdde83;background-image:url('');">
                    <div class="form-container" style="background-color: #fff;">
                        <div class="form">
                            <div class="form-group">
                                <label style="color: #303030;">Username</label>
                                <input type="text" class="" value="admin" style="border-color: #94eed6;background: #d1f8ee; color: #303030;" readonly />
                            </div>
                            <div class="form-group">
                                <label style="color: #303030;">Password</label>
                                <input type="text" class="" value="********" style="border-color: #94eed6;background: #d1f8ee; color: #303030;" readonly />
                            </div>
                            <div class="form-group">
                                <button class="btn-preview" style="background: #fa7871; color: #ffffff;">Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-top: 30px;">
                <div class="pull-right">
                    <input type="button" class="btn-default btn-cancel" value="Cancel" />
                    <input type="submit" class="btn-template-save" value="Save" />
                </div>
            </div>
        </div>
        <div class="col-md-4 template-sidebar">
            <div class="custom-modal-header">
                <h2 class="">Create Template <div class="close"><span>&times;</span></div></h2>
            </div>

                <input type="hidden" value="template_option" id="template_option" name="template_option" />
                <div class="form-group">
                    <label>Template Name</label>
                    <input class="form-control" type="text" name="template_name" id="template_name" value="" required="" />
                </div>
                <div class="form-group layout-group">
                    <label>Layout</label>
                    <div class="col-md-12">
                        <div class="radio-group col-md-5">
                            <input type="radio" id="layout-two-column" name="template_layout" value="layout-two-column" checked />
                            <label for="layout-two-column"><img src="modules/SM_CustomLogin/public/img/layout-two-column.png" /></label>
                        </div>
                        <div class="radio-group col-md-5 col-md-offset-1">
                            <input type="radio" id="layout-circle" name="template_layout" value="layout-circle" />
                            <label for="layout-circle"><img src="modules/SM_CustomLogin/public/img/layout-circle.png" /></label>
                        </div>
                        <div class="radio-group col-md-5">
                            <input type="radio" id="layout-default" name="template_layout" value="layout-default" />
                            <label for="layout-default"><img src="modules/SM_CustomLogin/public/img/layout-default.png" /></label>
                        </div>
                        <div class="radio-group col-md-5 col-md-offset-1">
                            <input type="radio" id="layout-right" name="template_layout" value="layout-right" />
                            <label for="layout-right"><img src="modules/SM_CustomLogin/public/img/layout-right.png" /></label>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="section-header section-heading">Page Options</h4>
                <div class="form-group">
                    <div class="bg-radio-group">
                        <input type="radio" name="bg_options" value="background_color" id="background_color" checked />
                        <label for="background_color">Background Color</label>
                    </div>
                    <div class="bg-radio-group">
                        <input type="radio" name="bg_options" value="background_image" id="background_image" />
                        <label for="background_image">Background Image</label>
                    </div>
                </div>
                <div class="form-group bg-color-option active">
                    <label>Background color</label>
                    <input type="text" id="bg_color" name="bg_color" class="form-control input-color" value="#FDDE83" />
                </div>
                <div class="form-group bg-image-option">
                    <label>Background image</label>
                    <input type="file" name="file" id="bg_img" class="form-control" accept=".jpg, .jpeg, .png" name="">
                    <div class="file-upload-btn"><p><i class="fa fa-upload"></i> Choose File </p><span>No file Choosen...</span></div>
                </div>
                <div class="form-group">
                    <label>Form background</label>
                    <input type="text" class="form-control input-color" id="form_bg" name="form_bg" value="#ffffff" />
                </div>
                <hr>
                <h4 class="section-header section-heading">Input Options</h4>
                <div class="form-group col-md-6">
                    <label>Input background</label>
                    <input type="text" class="form-control input-color" id="input_bg" name="input_bg" value="#D1F8EE" />
                </div>
                <div class="form-group col-md-6">
                    <label>Input border</label>
                    <input type="text" class="form-control input-color" id="input_border" name="input_border" value="#94EED6" />
                </div>
                <div class="form-group col-md-6">
                    <label>Input border radius</label>
                    <input type="number" class="form-control" min="0" max="25" id="input_border_radius" value="6" name="input_border_radius" />
                </div>
                <div class="form-group col-md-6">
                    <label>Input text color</label>
                    <input type="text" class="form-control input-color" id="input_text_color" name="input_text_color" value="#303030" />
                </div>
                <div class="form-group col-md-6">
                    <label>Input Label color</label>
                    <input type="text" class="form-control input-color" id="input_label_color" name="input_label_color" value="#303030">
                </div>
                <hr>
                <h4 class="section-header section-heading">Button Options</h4>
                <div class="form-group col-md-6">
                    <label>Button background</label>
                    <input type="text" class="form-control input-color" id="button_bg" name="button_bg" value="#FA7871" />
                </div>
                <div class="form-group col-md-6">
                    <label>Button border</label>
                    <input type="text" class="form-control input-color" id="button_border" name="button_border" value="#FA7871" />
                </div>
                <div class="form-group col-md-6">
                    <label style="white-space: nowrap;">Button border radius</label>
                    <input type="number" class="form-control" min="0" max="25" id="button_border_radius" value="0" name="button_border_radius" />
                </div>
                <div class="form-group col-md-6">
                    <label>Button text color</label>
                    <input type="text" class="form-control input-color" id="button_text_color" name="button_text_color" value="#ffffff" />
                </div>

        </div>
        </form>
    </div>

    </div>
</div>

{foreach from=$template_row item=update_template_row }

<div class="custom-update-modal {$update_template_row.template_name}">
    <div class="custom-modal-box">
        <div class="row" style="height: 100%;">
        <form id="page-bg-img-update" action="index.php?module=SM_CustomLogin&action=settings&show=Loginsettings" method="POST" enctype="multipart/form-data">
            <div class="col-md-8 template-section">
                <div class="img-preview">
                    <div data-id="{$update_template_row.template_layout}" class="login-page" style="background-color: {$update_template_row.page_bg};background-image:url('cache/images/{$update_template_row.page_bg_img}');">
                        <div class="form-container" style="background-color: {$update_template_row.form_bg};">
                            <div class="form">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="" value="admin" style="border-color: {$update_template_row.input_border};background: {$update_template_row.input_bg}; color: {$update_template_row.input_text_color}; border-radius: {$update_template_row.input_border_radius}px;" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" class="" value="********" style="border-color: {$update_template_row.input_border};background: {$update_template_row.input_bg}; color: {$update_template_row.input_text_color}; border-radius: {$update_template_row.input_border_radius}px;" readonly />
                                </div>
                                <div class="form-group">
                                    <button class="btn-preview" style="background: {$update_template_row.button_bg}; color:{$update_template_row.button_text_color}; border-color: {$update_template_row.button_border}; border-radius: {$update_template_row.button_border_radius}px;">Login</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12" style="margin-top: 30px;">
                    <div class="pull-right">
                        <input type="button" class="btn-default btn-cancel" value="Cancel" />
                        <input type="submit" class="btn-template-update" value="Save" />
                    </div>
                </div>

            </div>
            <div class="col-md-4 template-sidebar">

                <div class="custom-modal-header">
                    <h2 class="">Update Template <div class="close"><span>&times;</span></div></h2>
                </div>

                    <input type="hidden" value="template_option_update" id="template_option_update" name="template_option_update" />
                    <input type="hidden" name="template_name" id="template_name" value="{$update_template_row.template_name}" />

                    <div class="form-group">
                        <label>Template Name</label>
                        <input class="form-control" type="text" name="template_name_update" id="template_name_update" value="{$update_template_row.template_template}" readonly required />
                    </div>

                    <div class="form-group col-md-12 layout-group">
                        <label>Layout</label>
                        <div class="col-md-12">
                            <div class="radio-group col-md-5">
                                <input type="radio" class="template_layout_update" id="layout-two-column-update_{$update_template_row.template_name}" name="template_layout_update" value="layout-two-column" {if $update_template_row.template_layout eq "layout-two-column"}checked{/if} />
                                <label for="layout-two-column-update_{$update_template_row.template_name}"><img src="modules/SM_CustomLogin/public/img/layout-two-column.png" /></label>
                            </div>
                            <div class="radio-group col-md-5 col-md-offset-1">
                                <input type="radio" class="template_layout_update" id="layout-circle-update_{$update_template_row.template_name}" name="template_layout_update" value="layout-circle" {if $update_template_row.template_layout eq "layout-circle"}checked{/if} />
                                <label for="layout-circle-update_{$update_template_row.template_name}"><img src="modules/SM_CustomLogin/public/img/layout-circle.png" /></label>
                            </div>
                            <div class="radio-group col-md-5">
                                <input type="radio" class="template_layout_update" id="layout-default-update_{$update_template_row.template_name}" name="template_layout_update" value="layout-default" {if $update_template_row.template_layout eq "layout-default"}checked{/if} />
                                <label for="layout-default-update_{$update_template_row.template_name}"><img src="modules/SM_CustomLogin/public/img/layout-default.png" /></label>
                            </div>
                            <div class="radio-group col-md-5 col-md-offset-1">
                                <input type="radio" class="template_layout_update" id="layout-right-update_{$update_template_row.template_name}" name="template_layout_update" value="layout-right" {if $update_template_row.template_layout eq "layout-right"}checked{/if} />
                                <label for="layout-right-update_{$update_template_row.template_name}"><img src="modules/SM_CustomLogin/public/img/layout-right.png" /></label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="section-header section-heading">Page Options</h4>
                    <div class="form-group">
                        <div class="bg-radio-group">
                            <input type="radio" name="bg_options_update" data-id="{$update_template_row.template_name}" value="background_color" id="background_color_{$update_template_row.template_name}" {if $update_template_row.bg_options eq 'background_color'}checked{/if} />
                            <label for="background_color_{$update_template_row.template_name}">Background Color</label>
                        </div>
                        <div class="bg-radio-group">
                            <input type="radio" name="bg_options_update" data-id="{$update_template_row.template_name}" value="background_image" id="background_image_{$update_template_row.template_name}" {if $update_template_row.bg_options eq 'background_image'}checked{/if} />
                            <label for="background_image_{$update_template_row.template_name}">Background Image</label>
                        </div>
                    </div>
                    <div class="form-group bg-color-option {if $update_template_row.bg_options eq 'background_color'}active{/if}">
                        <label>Background color</label>
                        <input type="text" id="bg_color_update_{$update_template_row.template_name}" name="bg_color_update" class="form-control input-color" value="{$update_template_row.page_bg}" />
                    </div>
                    <div class="form-group bg-image-option {if $update_template_row.bg_options eq 'background_image'}active{/if}">
                        <label>Background image</label>
                        <input type="file" id="file" class="form-control" name="file" accept=".jpg, .jpeg, .png" value="{$update_template_row.page_bg_img}">
                        <div class="file-upload-btn"><p><i class="fa fa-upload"></i> Choose File </p><span>{if $update_template_row.page_bg_img}{$update_template_row.page_bg_img}{else}No file Choosen...{/if}</span></div>
                    </div>
                    <div class="form-group">
                        <label>Form background</label>
                        <input type="text" class="form-control input-color" id="form_bg_update_{$update_template_row.template_name}" name="form_bg_update" value="{$update_template_row.form_bg}" />
                    </div>
                    <hr>
                    <h4 class="section-header section-heading">Input Options</h4>
                    <div class="form-group col-md-6">
                        <label>Input background</label>
                        <input type="text" class="form-control input-color" id="input_bg_update_{$update_template_row.template_name}" name="input_bg_update" value="{$update_template_row.input_bg}" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Input border</label>
                        <input type="text" class="form-control input-color" id="input_border_update_{$update_template_row.template_name}" name="input_border_update" value="{$update_template_row.input_border}" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Input border radius</label>
                        <input type="number" class="form-control" min="0" max="25" id="input_border_radius_update_{$update_template_row.template_name}" value="{$update_template_row.input_border_radius}" name="input_border_radius_update" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Input text color</label>
                        <input type="text" class="form-control input-color" id="input_text_color_update_{$update_template_row.template_name}" name="input_text_color_update" value="{$update_template_row.input_text_color}" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Input Label color</label>
                        <input type="text" class="form-control input-color" id="input_label_color_update_{$update_template_row.input_label_color}" name="input_label_color_update" value="{$update_template_row.input_label_color}">
                    </div>
                    <hr>
                    <h4 class="section-header section-heading">Button Options</h4>
                    <div class="form-group col-md-6">
                        <label>Button background</label>
                        <input type="text" class="form-control input-color" id="button_bg_update_{$update_template_row.template_name}" name="button_bg_update" value="{$update_template_row.button_bg}" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Button border</label>
                        <input type="text" class="form-control input-color" id="button_border_update_{$update_template_row.template_name}" name="button_border_update" value="{$update_template_row.button_border}" />
                    </div>
                    <div class="form-group col-md-6">
                        <label style="white-space: nowrap;">Button border radius</label>
                        <input type="number" class="form-control" min="0" max="25" id="button_border_radius_update_{$update_template_row.template_name}" value="{$update_template_row.button_border_radius}" name="button_border_radius_update" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Button text color</label>
                        <input type="text" class="form-control input-color" id="button_text_color_update_{$update_template_row.template_name}" name="button_text_color_update" value="{$update_template_row.button_text_color}" />
                    </div>

            </div>
        </form>
        </div>
    </div>
</div>

{/foreach}

{/strip}

