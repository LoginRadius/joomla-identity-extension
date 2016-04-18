<?php
/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
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
require_once JPATH_COMPONENT . '/helpers/route.php';
// Get an instance of the controller prefixed by User Registration
$controller = LRController::getInstance('UserRegistrationAndManagement');
// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));
// Redirect if set by the controller
$controller->redirect();
