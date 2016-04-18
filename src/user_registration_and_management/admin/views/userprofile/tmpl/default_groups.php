<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended social groups
    if (count($this->getsocialgroups) > 0):     
        $this->pagenotfound = false;
        echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_GROUPS'), 'panel6');
        ?>
        <table class="form-table sociallogin_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_ID'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_NAME'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_TYPE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_DESCRIPTION'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_EMAIL'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_COUNTRY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_POSTAL_CODE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_LOGO'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_IMAGE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_MEMBER_COUNT'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_GROUPS_PROVIDER'); ?></th>
                </tr>
            </thead>
            <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getsocialgroups, true); ?>
        </table>
    <?php
endif;
