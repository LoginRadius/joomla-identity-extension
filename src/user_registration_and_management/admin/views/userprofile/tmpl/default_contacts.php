<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

//extended social contacts
if (count($this->getcontacts) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_CONTACTS'), 'panel7');
    ?>
        <table class="form-table sociallogin_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_ID'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_NAME'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_EMAIL'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_PROFILEURL'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_IMAGEURL'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_STATUS'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_INDUSTRY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_COUNTRY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_GENDER'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_PHONE_NUMBER'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_DATEOFBIRTH'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_LOCATION'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CONTACT_PROVIDER'); ?></th>
                </tr>
            </thead>
            <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getcontacts, true); ?>
        </table>
    <?php
endif;
