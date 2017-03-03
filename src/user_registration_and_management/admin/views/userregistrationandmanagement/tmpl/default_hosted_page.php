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
        <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_RAAS_HOSTED_PAGE'); ?></th>
    </tr>
    <tr>
            <td colspan="2"><span class="sociallogin_subhead" style="cursor: pointer;"><?php echo JText::_('COM_SOCIALLOGIN_ENABLE_HOSTED_PAGE'); ?></span><br/><br />
                <label for="enableHostedPage-yes">
                    <input id="enableHostedPage-yes" style="margin:0" type="radio" name="settings[enableHostedPage]" value="true" <?php echo(isset($this->settings['enableHostedPage']) && $this->settings['enableHostedPage'] == 'true') ? "checked" : "checked"; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="enableHostedPage-no">
                    <input id="enableHostedPage-no" style="margin:0" type="radio" name="settings[enableHostedPage]" value="false" <?php echo(!isset($this->settings['enableHostedPage']) || $this->settings['enableHostedPage'] == 'false') ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
    </tr>   
</table>
