<?php
/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//Advance option
$yesLoginForm = $notLoginForm = $yesSendEmail = $notSendEmail = $yesDummyEmail = $notDummyEmail = $yesUserData = $notUserData = $yesIconSize = $notIconSize = "";

//Advanced option

if ($this->settings['sendemail'] == '1')
{
    $yesSendEmail = "checked='checked'";
} else if ($this->settings['sendemail'] == '0')
{
    $notSendEmail = "checked='checked'";
} else
{
    $yesSendEmail = "checked='checked'";
}
if ($this->settings['loginform'] == '0')
{
    $notLoginForm = "checked='checked'";
} else
{
    $yesLoginForm = "checked='checked'";
}

if ($this->settings['dummyemail'] == '0')
{
    $yesDummyEmail = "checked='checked'";
} else if ($this->settings['dummyemail'] == '1')
{
    $notDummyEmail = "checked='checked'";
} else
{
    $notDummyEmail = "checked='checked'";
}

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

if ($this->settings['iconsize'] == 'medium')
{
    $yesIconSize = "checked='checked'";
} else if ($this->settings['iconsize'] == 'small')
{
    $notIconSize = "checked='checked'";
} else
{
    $notIconSize = "checked='checked'";
}
?>
<!-- Form Box -->
<div>
    <table class="form-table sociallogin_table">
        <tr class="sociallogin_row_white">
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_INTERFACE_CUSTOMIZATION'); ?></th>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"> <?php echo JText::_('COM_SOCIALLOGIN_ICON_SIZE'); ?></span>
                <br/><br/>
                <input style="margin-bottom:5px" name="settings[iconsize]" type="radio" <?php echo $yesIconSize; ?> value="medium"/> <?php echo JText::_('COM_SOCIALLOGIN_MEDIUM'); ?>&nbsp;&nbsp;&nbsp;
                <input style="margin-bottom:5px" name="settings[iconsize]" type="radio" <?php echo $notIconSize; ?> value="small"/> <?php echo JText::_('COM_SOCIALLOGIN_SMALL'); ?>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_ICONS_PER_ROW'); ?></span>
                <br/><br/>
                <input name="settings[iconsperrow]" type="text" class="span5 input_box" value="<?php echo $this->settings['iconsperrow']; ?>"/>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_INTERFACE_BACKGROUND'); ?></span>
                <br/><br/>
                <input name="settings[interfacebackground]" type="text" class="span5 input_box" value="<?php echo $this->settings['interfacebackground']; ?>"/>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr class="sociallogin_row_white">
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_USER_EMAIL_POPUP_SETTING'); ?></th>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_LOGINFORM_SETTING'); ?></span><br/><br/>
                <input name="settings[loginform]" type="radio" <?php echo $yesLoginForm; ?> value="1" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="settings[loginform]" type="radio" <?php echo $notLoginForm; ?> value="0" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_DESC'); ?></span><br/><br/>
                <input name="settings[sendemail]" type="radio" <?php echo $yesSendEmail; ?> value="1" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="settings[sendemail]" type="radio" <?php echo $notSendEmail; ?> value="0" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_REQUIRED_DESC'); ?></span><br/><br/>
                <input name="settings[dummyemail]" type="radio" <?php echo $notDummyEmail; ?>value="1" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_EMAIL_YES'); ?><br/><br/>
                <input name="settings[dummyemail]" type="radio" <?php echo $yesDummyEmail; ?>value="0" class="defaultmargin"/> <?php echo JText::_('COM_SOCIALLOGIN_EMAIL_NO'); ?>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_TITLE'); ?></span><br/><br/>
                <input size="60" type="text" class="span5 input_box" name="settings[popupemailtitle]" id="popupemailtitle" value="<?php echo $this->settings['popupemailtitle']; ?>"/>
                <br/>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_TITLE_MESSAGE'); ?></span><br/><br/>
                <input size="60" type="text" class="span5 input_box" name="settings[popupemailmessage]" id="popupemailmessage" value="<?php echo $this->settings['popupemailmessage']; ?>"/>
                <br/>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2">
                <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ERROR_EMAIL_TITLE_MESSAGE'); ?></span><br/><br/>
                <input size="60" type="text" class="span5 input_box" name="settings[popuperroremailmessage]" id="popuperroremailmessage" value="<?php echo $this->settings['popuperroremailmessage']; ?>"/>
            </td>
        </tr>
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
</div>
