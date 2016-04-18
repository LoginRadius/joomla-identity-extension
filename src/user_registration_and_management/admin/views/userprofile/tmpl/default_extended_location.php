<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended user location
foreach ($this->getextendedlocation as $key => $getextendedlocation):
    if (count($getextendedlocation) > 0): 
        $this->pagenotfound = false;      
        echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_EXTENDED_LOCATION_DATA'), 'panel2');
        UserRegistrationAndManagementModelUserProfile::displayProfile($getextendedlocation);
    endif;
    break;
endforeach;
