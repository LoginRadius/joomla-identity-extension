<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended linkedin companied
    if (count($this->getfacebooklikes) > 0):
        $this->pagenotfound = false;
        echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_FACEBOOK_LIKES'), 'panel11');
        ?>
        <table class="form-table sociallogin_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_ID'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_NAME'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_CATEGORY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_CREATED_DATE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_WEBSITE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LIKES_DESCRIPTION'); ?></th>
                </tr>
            </thead>
            <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getfacebooklikes, true); ?>
        </table>
    <?php
endif;