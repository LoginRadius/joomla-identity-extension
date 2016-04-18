<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended twitter mentions
if (count($this->gettwittermentions) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_TWITTER_MENTIONS'), 'panel8');
    ?>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_TWEET'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_DATE_TIME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_LIKES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_PLACE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_SOURCE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_IMAGEURL'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_LINKURL'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_MENTIONS_MENTIONEDBY'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->gettwittermentions, true); ?>
    </table>
    <?php

endif;
        