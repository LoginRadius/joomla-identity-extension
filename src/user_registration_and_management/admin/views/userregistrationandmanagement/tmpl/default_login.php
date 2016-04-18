<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
<table class="form-table sociallogin_table">
    <tr class="sociallogin_row_white">
        <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API'); ?></th>
    </tr>
    <tr class="sociallogin_row_white">
        <td colspan="2"><span class="sociallogin_subhead"> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY_DESC'); ?>
                (<a href="http://ish.re/AEFD" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY_DESC_TWO'); ?></a>)
            </span>
            <br/><br/>
            <div>
                <div class="sociallogin_subhead span2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_SITE_NAME'); ?></div>
                <div>
                    <input size="60" type="text" name="settings[sitename]" id="sitename" class="span5 input_box" value="<?php echo isset($this->settings ['sitename']) ? $this->settings ['sitename'] : '' ; ?>"/>
                </div>
            </div>
            <div>
                <div class="sociallogin_subhead span2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY'); ?></div>
                <div>
                    <input size="60" type="text" name="settings[apikey]" id="apikey" class="span5 input_box" value="<?php echo $this->settings ['apikey']; ?>"/>
                </div>
            </div>
            <div>
                <div class="sociallogin_subhead span2">
                    <?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_SECRET'); ?>
                </div>
                <div>
                    <input size="60" type="text" name="settings[apisecret]" id="apisecret" class="span5 input_box" value="<?php echo $this->settings ['apisecret']; ?>"/>
                </div>
            </div>
        </td>
    </tr>
</table>

<table class="form-table sociallogin_table">
    <tr class="sociallogin_row_white">
        <th class="head" colspan="2"><?php echo JText::_('COM_RAAS_BASIC_SETTING'); ?></th>
    </tr>
    <tr class="sociallogin_row_white">
        <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC_REDIRECT_DESC'); ?></span><br/><br/>
            <select id="loginredirection" name="settings[loginredirection]">
                <option value="" selected="selected">---Default---</option>
                <?php echo $this->loginRedirection; ?>
            </select>
        </td>
    </tr>
</table>
<table class="form-table sociallogin_table">
    <tr class="sociallogin_row_white">
        <th class="head" colspan="2"><?php echo JText::_('COM_RAAS_ACCOUNT_LINKING_SETTING'); ?></th>
    </tr>
    <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_RAAS_ENABLE_LINKING'); ?></span><br/><br />
                <label for="enableAccountLinking-yes">
                    <input id="enableAccountLinking-yes" style="margin:0" type="radio" name="settings[enableAccountLinking]" value="true" <?php echo(isset($this->settings['enableAccountLinking']) && $this->settings['enableAccountLinking'] == 'true') ? "checked" : "checked"; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableAccountLinking-no">
                    <input id="enableAccountLinking-no" style="margin:0" type="radio" name="settings[enableAccountLinking]" value="false" <?php echo(!isset($this->settings['enableAccountLinking']) || $this->settings['enableAccountLinking'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
    </tr>
    <tr>            
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_RAAS_LINKING_HEADING'); ?></span><br/><br />
            <input style="margin:0" type="text" value="<?php echo (isset($this->settings ['Loginradius_linkingIdentityString']) ? htmlspecialchars($this->settings ['Loginradius_linkingIdentityString']) : ''); ?>"  name="settings[Loginradius_linkingIdentityString]">
            <p><?php echo JText::_('COM_RAAS_LINKING_HEADING_HINT'); ?></p>
            </td>
    </tr>
</table>