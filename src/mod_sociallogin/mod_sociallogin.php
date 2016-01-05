<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_sociallogin
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Include the login functions only once
require_once __DIR__ . '/helper.php';

$params->def('greeting', 1);
$type = modSocialLoginHelper::getType();
$settings = modSocialLoginHelper::getSettings();
$return = modSocialLoginHelper::getReturnURL($params, $type);
$user = JFactory::getUser();
$usersConfig = JComponentHelper::getParams('com_users');
require JModuleHelper::getLayoutPath('mod_sociallogin', $params->get('layout', 'default'));
