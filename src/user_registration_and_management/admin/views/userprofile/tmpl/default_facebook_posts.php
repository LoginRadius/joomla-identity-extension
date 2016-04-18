<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended facebook post
if (count($this->getfacebookposts) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_FACEBOOK_POST'), 'panel5');
    ?>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_FROM'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_TITLE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_STATE_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_UPDATE_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_MESSAGE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_PLACE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_PICTURE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_LIKES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_SHARE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POST_POST'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getfacebookposts, true); ?>
    </table>
    <?php
endif;