<?php

/**
 * @package     SingleSignOn.Plugin
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
class plgSystemSingleSignOn extends JPlugin {

    /**
     * Constructor Loads the plugin settings and assigns them to class variables
     */
    public function __construct(&$subject) {
        parent::__construct($subject);
        $this->singleSignOnScript();
    }

    public function onLoginRadiusSSOLogout($error) {
        return $this->singleSignOnScript(true);
    }
  
    /**
     * social9 get saved setting from db
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
     * SignOnScript Script call functionality
     * 
     * @param $islogout
     * @return string
     */
    private function singleSignOnScript($islogout = false) {
        if (!JFactory::getApplication()->isSite()) {
            return;
        }
        $settings = $this->getSettings();
        if (isset($settings['enableSingleSignOn']) && $settings['enableSingleSignOn'] == 'true') {
            $sitename = isset($settings['sitename']) ? $settings['sitename'] : '';
            $path = parse_url(JURI::base());
            $domain = $path['scheme'] . '://' . $path['host'];
            $document = JFactory::getDocument();
            $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
            $document->addScript('//cdn.loginradius.com/hub/prod/js/LoginRadiusRaaS.js');
            $document->addScript('//cdn.loginradius.com/hub/prod/js/LoginRadiusSSO.js');
            $loginFunction = 'jQuery(document).ready(function () {';
            $loginFunction .= 'LoginRadiusSSO.init("' . $sitename . '");';
            $loginFunction .= 'if(jQuery(\'#popupinner\').length){return;}';
            if ($islogout) {
                $loginFunction .= 'LoginRadiusSSO.logout(function() {"' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=logout') . '"});';
            } else {
                if (!JFactory::getUser()->id) {
                    $loginFunction .= 'if (typeof LoginRadiusRaaS != "undefined") {';
                    $loginFunction .= 'if (!LoginRadiusRaaS.loginradiushtml5passToken) {';
                    $loginFunction .= 'LoginRadiusRaaS.loginradiushtml5passToken = function (token) {';
                    $loginFunction .= 'if (token) {';
                    $loginFunction .= 'window.location.href = "' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=login') . '";';
                    $loginFunction .= '}}}}';
                    $loginFunction .= 'LoginRadiusSSO.login("' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=login') . '");';
                } else {
                    $loginFunction .= 'LoginRadiusSSO.isNotLoginThenLogout(function () {';
                    $loginFunction .= 'window.location.href = "' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=logout') . '";';
                    $loginFunction .= '});';
                }
            }
            $loginFunction .= 'jQuery("#lr-loading").hide();});';
            $document->addScriptDeclaration($loginFunction);
        }
    }
}
