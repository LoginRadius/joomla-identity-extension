<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 defined('_JEXEC') or die('Direct Access to this location is not allowed.');

  $yesUserData = $notUserData= "";

if ($this->settings['updateuserdata'] == '1')
{
    $yesUserData = "checked='checked'";
} else if ($this->settings['updateuserdata'] == '0')
{
    $notUserData = "checked='checked'";
} else
{
    $yesUserData = "checked='checked'";
}
?>
<!-- Form Box -->
<div>
    <table class="form-table sociallogin_table">
        <tr class="sociallogin_row_white">
            <th class="head" colspan="2"><?php echo JText::_('COM_RAAS_INTERFACE_CUSTOMIZATION'); ?></th>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_LABEL_STRING'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['lr_social_login_label_string']) ? htmlspecialchars($this->settings ['lr_social_login_label_string']) : ''); ?>"  name="settings[lr_social_login_label_string]">
            <p><?php echo JText::_('COM_SOCIALLOGIN_LABEL_STRING_HINT'); ?></p>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr class="sociallogin_row_white">
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_USER_EMAIL_POPUP_SETTING'); ?></th>
        </tr>
        <?php if((isset($this->settings['enableHostedPage']) && $this->settings['enableHostedPage'] != 'true') || (!isset($this->settings['enableHostedPage']))) {?>
        <tr class="sociallogin_row_white emailpopup">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_TITLE'); ?></span><br/><br/>
                <input size="60" type="text" class="span5 input_box" name="settings[popupemailtitle]" id="popupemailtitle" value="<?php echo $this->settings['popupemailtitle']; ?>"/>
                <br/>
            </td>
        </tr>
        <?php }?>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_USERPROFILEDATE_UPDATE'); ?></span><br/><br/>
                <input name="settings[updateuserdata]" type="radio" <?php echo $yesUserData; ?> value="1" style="margin:0"/> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="settings[updateuserdata]" type="radio" <?php echo $notUserData; ?> value="0" style="margin:0"/> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?>
            </td>
        </tr>
        <?php if (JPluginHelper::isEnabled('system', 'k2'))
        { ?>
            <tr class="sociallogin_row_white">
                <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2_DESC'); ?>(<strong><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2_DESC_TWO'); ?></strong>) </span><br/><br/>
                    <?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2'); ?>
                    <input type="text" class="span5 input_box" name="settings[k2group]" size="2" value="<?php echo(isset($this->settings ['k2group']) ? htmlspecialchars($this->settings ['k2group']) : '2'); ?>"/>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php if((isset($this->settings['enableHostedPage']) && $this->settings['enableHostedPage'] != 'true') || (!isset($this->settings['enableHostedPage']))) {?>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_RAAS_ADDITIONAL_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_FORM_VALIDATION_MSG'); ?></span><br/><br />
                <label for="enableFormValidationMsg-yes">
                    <input id="enableFormValidationMsg-yes" style="margin:0" type="radio" name="settings[LoginRadius_enableFormValidationMsg]" value="true" <?php echo(isset($this->settings['LoginRadius_enableFormValidationMsg']) && $this->settings['LoginRadius_enableFormValidationMsg'] == 'true') ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableFormValidationMsg-no">
                    <input id="enableFormValidationMsg-no" style="margin:0" type="radio" name="settings[LoginRadius_enableFormValidationMsg]" value="false" <?php echo(!isset($this->settings['LoginRadius_enableFormValidationMsg']) || $this->settings['LoginRadius_enableFormValidationMsg'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_TERMS_AND_CONDITION'); ?></span><br/><br />
            <textarea id="LoginRadius_termsAndCondition" name="settings[LoginRadius_termsAndCondition]" ><?php if (isset($this->settings['LoginRadius_termsAndCondition'])) { echo trim($this->settings['LoginRadius_termsAndCondition']);}?></textarea>
            </td>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_DELAY_TIME'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_formRenderDelay']) ? htmlspecialchars($this->settings ['LoginRadius_formRenderDelay']) : ''); ?>" id="LoginRadius_formRenderDelay" name="settings[LoginRadius_formRenderDelay]">
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_LENGTH_OF_PASSWORD'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="LoginRadius_passwordMinLength"><?php echo JText::_('COM_SOCIALLOGIN_MIN_PASSWORD_LENGTH'); ?></label>
                        </td>
                        <td>
                            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_passwordMinLength']) ? htmlspecialchars($this->settings ['LoginRadius_passwordMinLength']) : ''); ?>" id="LoginRadius_passwordMinLength" name="settings[LoginRadius_passwordMinLength]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="LoginRadius_passwordMaxLength"><?php echo JText::_('COM_SOCIALLOGIN_MAX_PASSWORD_LENGTH'); ?></label>
                        </td>
                        <td>
                            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_passwordMaxLength']) ? htmlspecialchars($this->settings ['LoginRadius_passwordMaxLength']) : ''); ?>" id="LoginRadius_passwordMaxLength" name="settings[LoginRadius_passwordMaxLength]">
                        </td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_GOOGLE_RECAPTCHA_KEY'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_GoogleRecapthaPublicKey']) ? htmlspecialchars($this->settings ['LoginRadius_GoogleRecapthaPublicKey']) : ''); ?>" id="LoginRadius_GoogleRecapthaPublicKey" name="settings[LoginRadius_GoogleRecapthaPublicKey]">
            </td>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_FORGOT_PASSWORD_EMAIL_TEMP'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_forgotEmailTemplate']) ? htmlspecialchars($this->settings ['LoginRadius_forgotEmailTemplate']) : ''); ?>" id="LoginRadius_forgotEmailTemplate" name="settings[LoginRadius_forgotEmailTemplate]">
            </td>
        </tr>         
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_EMAIL_VERIFICATION_TEMP'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_emailVerificationTemplate']) ? htmlspecialchars($this->settings ['LoginRadius_emailVerificationTemplate']) : ''); ?>" id="LoginRadius_emailVerificationTemplate" name="settings[LoginRadius_emailVerificationTemplate]">
            </td>
        </tr>
        <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_CUSTOM_OPTIONS'); ?></span><br/><br />
            <textarea id="LoginRadius_customOption" name="settings[LoginRadius_customOption]" ><?php if (isset($this->settings['LoginRadius_customOption'])) { echo trim($this->settings['LoginRadius_customOption']);}?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_EMAIL_VERIFICATION_OPTIONS'); ?></span><br/><br />
                <label for="emailVerification-required">
                    <input onchange="showAndHideUI();" class="emailVerificationOptions" id="emailVerification-required" style="margin:0" type="radio" name="settings[LoginRadius_emailVerificationOption]" value="0" <?php echo(!isset($this->settings['LoginRadius_emailVerificationOption']) || $this->settings['LoginRadius_emailVerificationOption'] == 0) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_REQUIRED_EMAIL_VERIFICATION'); ?>
                </label>
                <label for="emailVerification-optional">
                    <input onchange="showAndHideUI();" class="emailVerificationOptions" id="emailVerification-optional" style="margin:0" type="radio" name="settings[LoginRadius_emailVerificationOption]" value="1" <?php echo(isset($this->settings['LoginRadius_emailVerificationOption']) && $this->settings['LoginRadius_emailVerificationOption'] == 1) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_OPTIONAL_EMAIL_VERIFICATION'); ?>                    
                </label>
                <label for="emailVerification-disabled">
                    <input onchange="showAndHideUI();" class="emailVerificationOptions" id="emailVerification-disabled" style="margin:0" type="radio" name="settings[LoginRadius_emailVerificationOption]" value="2" <?php echo(isset($this->settings['LoginRadius_emailVerificationOption']) && $this->settings['LoginRadius_emailVerificationOption'] == 2) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_DISABLED_EMAIL_VERIFICATION'); ?>                    
                </label>
            </td>
        </tr>
        <tr class="enableLoginOnEmailVerification">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_LOGIN_UPON_EMAIL_VERIFICATION'); ?></span><br/><br />
                <label for="enableLoginOnEmailVerification-yes">
                    <input id="enableLoginOnEmailVerification-yes" style="margin:0" type="radio" name="settings[LoginRadius_enableLoginOnEmailVerification]" value="true" <?php echo(isset($this->settings['LoginRadius_enableLoginOnEmailVerification']) && $this->settings['LoginRadius_enableLoginOnEmailVerification'] == 'true') ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableLoginOnEmailVerification-no">
                    <input id="enableLoginOnEmailVerification-no" style="margin:0" type="radio" name="settings[LoginRadius_enableLoginOnEmailVerification]" value="false" <?php echo(!isset($this->settings['LoginRadius_enableLoginOnEmailVerification']) || $this->settings['LoginRadius_enableLoginOnEmailVerification'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>     
        <tr class="enablePromptPassword">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_PROMPT_PASSWORD'); ?></span><br/><br />
                <label for="enablePromptPassword-yes">
                    <input id="enablePromptPassword-yes" style="margin:0" type="radio" name="settings[LoginRadius_enablePromptPassword]" value="true" <?php echo(isset($this->settings['LoginRadius_enablePromptPassword']) && $this->settings['LoginRadius_enablePromptPassword'] == 'true') ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enablePromptPassword-no">
                    <input id="enablePromptPassword-no" style="margin:0" type="radio" name="settings[LoginRadius_enablePromptPassword]" value="false" <?php echo(!isset($this->settings['LoginRadius_enablePromptPassword']) || $this->settings['LoginRadius_enablePromptPassword'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>
        <tr class="enableLoginWithUsername">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_ENABLE_LOGIN_WITH_USERNAME'); ?></span><br/><br />
                <label for="enableLoginWithUsername-yes">
                    <input id="enableLoginWithUsername-yes" style="margin:0" type="radio" name="settings[LoginRadius_enableLoginWithUsername]" value="true" <?php echo(isset($this->settings['LoginRadius_enableLoginWithUsername']) && $this->settings['LoginRadius_enableLoginWithUsername'] == 'true') ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableLoginWithUsername-no">
                    <input id="enableLoginWithUsername-no" style="margin:0" type="radio" name="settings[LoginRadius_enableLoginWithUsername]" value="false" <?php echo(!isset($this->settings['LoginRadius_enableLoginWithUsername']) || $this->settings['LoginRadius_enableLoginWithUsername'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>      
        <tr class="askEmailForUnverified">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_ASK_EMAIL_FOR_UNVERIFIED'); ?></span><br/><br />
                <label for="askEmailForUnverified-yes">
                    <input id="askEmailForUnverified-yes" style="margin:0" type="radio" name="settings[LoginRadius_askEmailForUnverified]" value="true" <?php echo(isset($this->settings['LoginRadius_askEmailForUnverified']) && $this->settings['LoginRadius_askEmailForUnverified'] == 'true') ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>,(ask for email address every time an unverified user logs in)
                </label>
                <label for="askEmailForUnverified-no">
                    <input id="askEmailForUnverified-no" style="margin:0" type="radio" name="settings[LoginRadius_askEmailForUnverified]" value="false" <?php echo(!isset($this->settings['LoginRadius_askEmailForUnverified']) || $this->settings['LoginRadius_askEmailForUnverified'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>
    </table> 
    <table class="form-table sociallogin_table">
            <tr>
                <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_DEBUG_SETTING'); ?></th>
            </tr>
            <tr>
                <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE'); ?></span>
                    <br/><br />
                    <label for="debugEnable-yes">
                        <input id="debugEnable-yes" style="margin:0" type="radio" name="settings[debugEnable]" value="1" <?php echo(isset($this->settings['debugEnable']) && $this->settings['debugEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE_YES'); ?>
                    </label>
                    <label for="debugEnable-no">
                        <input id="debugEnable-no" style="margin:0" type="radio" name="settings[debugEnable]" value="0" <?php echo(!isset($this->settings['debugEnable']) || $this->settings['debugEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE_NO'); ?>                    
                    </label>

                </td>
            </tr>
        </table>
    <?php }?>
</div>
