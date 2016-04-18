<?php
/**
 * @package     UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.controller');
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
$controller = LRController::getInstance('UserRegistrationAndManagement');

/**
 * Perform the requested task
 */
$controller->execute(JRequest::getCmd('task', 'display'));

/**
 * Redirect if set by the controller
 */
$controller->redirect();
