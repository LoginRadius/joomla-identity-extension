<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended user status
if (count($this->getstatus) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_STATUS_MESSAGES'), 'panel4');
    ?>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_DATA_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_LIKES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_PLACES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_SOURCES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_IMAGES_URL'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_LINK_URL'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_PROVIDER'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getstatus, true); ?>
    </table>
    <?php

endif;