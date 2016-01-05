<?php
/**
 * @package   	LoginRadiusSocialLoginAndSocialShare
 * @copyright 	Copyright 2012 http://www.loginradius.com - All rights reserved.
 * @license   	GNU/GPL 2 or later
 */
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.controller');

if (version_compare(JVERSION, '3.0', 'ge'))
{
    class LRController extends JControllerLegacy
    {
        public function display($cachable = false, $urlparams = array())
        {
            parent::display($cachable, $urlparams);
        }

    }

}
else if (version_compare(JVERSION, '2.5', 'ge'))
{
    class LRController extends JController
    {
        public function display($cachable = false, $urlparams = false)
        {
            parent::display($cachable, $urlparams);
        }

    }

}

/**
 * Get an instance of the controller
 */
$controller = LRController::getInstance ('LoginRadiusSocialLoginAndSocialShare');

/**
 * Perform the requested task
 */
$application = JFactory::getApplication();
$controller->execute ($application->input->get('task', 'display'));

/**
 * Redirect if set by the controller
 */
$controller->redirect();
