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
        <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_RAAS_SSO'); ?></th>
    </tr>
    <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_ENABLE_SSO'); ?></span><br/><br />
                <label for="enableSingleSignOn-yes">
                    <input id="enableSingleSignOn-yes" style="margin:0" type="radio" name="settings[enableSingleSignOn]" value="true" <?php echo(isset($this->settings['enableSingleSignOn']) && $this->settings['enableSingleSignOn'] == 'true') ? "checked" : "checked"; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableSingleSignOn-no">
                    <input id="enableSingleSignOn-no" style="margin:0" type="radio" name="settings[enableSingleSignOn]" value="false" <?php echo(!isset($this->settings['enableSingleSignOn']) || $this->settings['enableSingleSignOn'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
    </tr>   
</table>