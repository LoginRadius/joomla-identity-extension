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
if (count($this->getlinkedincompanies) > 0):
    $this->pagenotfound = false;
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_LINKEDIN_COMPANIED'), 'panel10');
    ?>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_COMPANY_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_COMPANY'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getlinkedincompanies, true); ?>
    </table>
    <?php

endif;
