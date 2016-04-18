<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if ($this->pagenotfound):
    echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_EMPTY_USERDATA'), 'panel0');
endif;