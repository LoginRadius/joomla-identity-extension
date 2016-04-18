<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//basic user profile
foreach ($this->getbasicuserprofile as $key => $getbasicuserprofile):
    if (count($getbasicuserprofile) > 0):
      $this->pagenotfound = false;
        echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_BASICPROFILE_DATA'), 'panel1');
        UserRegistrationAndManagementModelUserProfile::displayProfile($getbasicuserprofile);
    endif;
        if (count($this->getbasicuseremails) > 0):?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EMAILS_BASICPROFILE_DATA');?></h2>
            <table class="form-table sociallogin_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_BASIC_PROFILE_EMAIL_TYPE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_BASIC_PROFILE_EMAIL_VALUE'); ?></th>
                </tr>
            </thead>
            <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getbasicuseremails, true); ?>
        </table>
            <?php
        endif;
    break;
endforeach;