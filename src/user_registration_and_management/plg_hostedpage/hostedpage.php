<?php

/**
 * @package     HostedPage.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * Class plgContentSingleSignOn
 */
class plgSystemHostedPage extends JPlugin {

    /**
     * Constructor Loads the plugin settings and assigns them to class variables
     */
    public function __construct(&$subject) {
        parent::__construct($subject);
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

    /**
     * Hosted Page Enable functionality
     * 
     * @param $islogout
     * @return string
     */
    function lrHostedPageInitialise($pagename) {

        $mainframe = JFactory::getApplication();
        $settings = $this->getSettings();
        $sitename = isset($settings['sitename']) ? $settings['sitename'] : '';
        $app = JFactory::getApplication();

        if (isset($pagename) && $pagename == 'login' || $pagename == 'register' || $pagename == 'forgotpassword') {
            $url = JRoute::_('https://' . $sitename . '.hub.loginradius.com/auth.aspx?action=' . $pagename . '&return_url=' . JURI::base(), false);
            $app->redirect($url);
        } else if (isset($pagename) && $pagename == 'profile' || $pagename == 'changepassword') {
            $url = JRoute::_('https://' . $sitename . '.hub.loginradius.com/auth.aspx?action=' . $pagename, false);
            $app->redirect($url);
        } elseif (isset($pagename) && $pagename == 'logout') {
            $app = JFactory::getApplication();
            $error = $app->logout();
            $url = JRoute::_('https://' . $sitename . '.hub.loginradius.com/auth.aspx?action=logout&return_url=' . JURI::base(), false);
            $app->redirect($url);
        }
    }

}
