<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended facebook events
if (count($this->getfacebookevents) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_FACEBOOK_EVENTS'), 'panel9');
    ?>
    <h2 class="head"><?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_EVENTS'); ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_NAME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_START_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_END_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_LOCATION'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_RSVP_STATUS'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_DESCRIPTION'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_UPDATED_DATE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_PRIVACY'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_OWNER_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EVENT_OWNER_NAME'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getfacebookevents, true); ?>
    </table>
    <?php
 endif;
    