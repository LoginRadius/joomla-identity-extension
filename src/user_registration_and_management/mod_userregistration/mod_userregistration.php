<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the login functions only once
$params->def('greeting', 1);
$usersConfig = JComponentHelper::getParams('com_users');
require JModuleHelper::getLayoutPath ('mod_userregistration',$params->get('layout', 'default'));