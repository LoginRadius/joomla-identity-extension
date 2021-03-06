<?php

/**
 * @package     CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.controller');

/**
 * General Controller of ciam component
 */
class CiamLoginRadiusController extends LRController {

    /**
     * 
     * @param type $cachable
     * @param type $urlparams
     * @return type
     */
    public function display($cachable = false, $urlparams = false) {
        // Get the document object.
        $document = JFactory::getDocument();
        $settings = $this->getSettings();

        // Set the default view name and format from the Request.   
        $vFormat = $document->getType();
        $vName = JRequest::getVar('view', 'login');
        preg_match("/[^\/]+$/", JUri::current(), $matches);
        $pagename = $matches[0];
        if (in_array($pagename, array('register', 'profile', 'updateprofile', 'forgotpassword', 'changepassword', 'login', 'logout'))) {
            if (JFactory::getUser()->id != 0) {
                if (in_array($pagename, array('updateprofile', 'profile', 'changepassword', 'logout'))) {
                     
                        if ($pagename == 'logout') {
                            $app = JFactory::getApplication();
                            // Perform the log out.
                            $error = $app->logout();
                            if (JVERSION < 3) {
                                $dispatcher = JDispatcher::getInstance();
                            } else {
                                $dispatcher = JEventDispatcher::getInstance();
                            }     
                            if (!isset($_GET['flag'])) {
                            $dispatcher->trigger('onLoginRadiusSSOLogout', array($error));
                        }
                    }
                        $lName = JRequest::getVar('layout', 'default_' . $pagename);
               
                } else {
                    $this->setRedirect(JRoute::_('index.php?option=com_ciamloginradius&view=profile', false));
                }
            } else {
                $lName = JRequest::getVar('layout', 'default_' . $pagename);                
            }
        } else {
            if (JFactory::getUser()->id) {
                $lName = JRequest::getVar('layout', 'default');
            } else {
                $this->setRedirect(JRoute::_('index.php?option=com_ciamloginradius&view=login', false));
            }
        }
        if ($view = $this->getView($vName, $vFormat)) {
            // Do any specific processing by view.
            switch ($vName) {
                // Handle view specific models.
                case 'profile':
                    // If the user is a guest, redirect to the login page.
                    $user = JFactory::getUser();

                    if ($user->get('guest') == 1 && $pagename == 'profile') {
                        // Redirect to login page.
                        $this->setRedirect(JRoute::_('index.php?option=com_ciamloginradius&view=login', false));
                        return;
                    }
                    $model = $this->getModel($vName);
                    break;
                default:
                    $model = $this->getModel('Login');
                    break;
            }

            // Push the model into the view (as default).

            $view->setModel($model, true);
            $view->setLayout($lName);

            // Push document object into the view.
            $view->document = $document;
            $view->display();
        }
    }

    /**
     * login radius get saved setting from db
     * 
     * @return array
     */
    private function getSettings() {
        $db = JFactory:: getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
                ->from('#__loginradius_settings');
        $db->setQuery($query);
        $rows = $db->LoadAssocList();
        $settings = array();

        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data['value'];
            }
        }
        return $settings;
    }

}
