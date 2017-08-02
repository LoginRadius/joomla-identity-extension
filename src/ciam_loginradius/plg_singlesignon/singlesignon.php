<?php

/**
 * @package     SingleSignOn.Plugin
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
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
            $loginFunction = 'jQuery(document).ready(function (e) {';         
            if ($islogout) {     
                $loginFunction .= 'var options= {};';
                $loginFunction .= 'options.onSuccess = function() {';
                $loginFunction .= 'window.location.href = "' . $domain . JRoute::_('index.php?option=com_ciamloginradius&view=logout') . '";';
                $loginFunction .= '};';
                $loginFunction .= 'LRObject.util.ready(function() {';
                $loginFunction .= 'LRObject.init("logout", options);';
                $loginFunction .= '});';                
            } else {
                  if (!JFactory::getUser()->id) {
                    $loginFunction .= 'if( jQuery(".interfacecontainerdiv").length ){ ';                 
                    $loginFunction .= 'var options= {};';
                    $loginFunction .= 'options.onSuccess = function(response) {';
                    $loginFunction .= 'var form = document.createElement("form");';
                    $loginFunction .= 'form.action = "' . $domain . JRoute::_('index.php?option=com_ciamloginradius&view=login') . '";';
                    $loginFunction .= 'form.method = "POST";';
                    $loginFunction .= 'var hidden = document.createElement("input");';
                    $loginFunction .= 'hidden.type = "hidden";';
                    $loginFunction .= 'hidden.type = "token";';
                    $loginFunction .= 'hidden.type = response;';
                    $loginFunction .= 'form.appendChild(hidden);';
                    $loginFunction .= 'document.body.appendChild(form);';
                    $loginFunction .= 'form.submit();';
                    $loginFunction .= '};';
                    $loginFunction .= 'LRObject.util.ready(function() {';
                    $loginFunction .= 'LRObject.init("ssoLogin", options);';
                    $loginFunction .= '});}';                    
                } else {     
                    $lrtoken = (isset($_COOKIE['lr-user--token']) && $_COOKIE['lr-user--token']!='') ? $_COOKIE['lr-user--token'] : '';
                    $loginFunction .= 'var check_options= {};';                            
                    $loginFunction .= 'check_options.onError = function(response) {';                                                 
                    $loginFunction .= 'if(response != "'.$lrtoken.'"){';
                    $loginFunction .= 'window.location.href = "' . $domain . JRoute::_('index.php?option=com_ciamloginradius&view=logout&flag=false') . '";';
                    $loginFunction .= '}else{;';   
                    $loginFunction .= 'window.location.href = "' . $domain . JRoute::_('index.php?option=com_ciamloginradius&view=logout') . '";';
                    $loginFunction .= '};';               
                    $loginFunction .= '};';               
                    $loginFunction .= 'LRObject.util.ready(function() {';
                    $loginFunction .= 'LRObject.init("ssoNotLoginThenLogout", check_options);';                    
                    $loginFunction .= '});';
                 
                } 
            }
            $loginFunction .= '});';
            $document->addScriptDeclaration($loginFunction);
        }
    }
}
