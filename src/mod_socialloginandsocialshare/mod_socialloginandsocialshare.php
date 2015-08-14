<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
ini_set("display_errors", "1");
  error_reporting(E_ALL);
// Include the login functions only once
require_once __DIR__ . '/helper.php';

$params->def('greeting', 1);
$type = modSocialLoginAndSocialShareHelper::getType();
$settings = modSocialLoginAndSocialShareHelper::getSettings();
$return = modSocialLoginAndSocialShareHelper::getReturnURL($params, $type);
$user = JFactory::getUser();
$usersConfig = JComponentHelper::getParams('com_users');
require JModuleHelper::getLayoutPath('mod_socialloginandsocialshare', $params->get('layout', 'default'));
