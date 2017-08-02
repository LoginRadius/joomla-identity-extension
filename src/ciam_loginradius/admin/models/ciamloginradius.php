<?php

/**
 * @package     CiamLoginRadius.Plugin
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.modellist');

require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'LoginRadiusException.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Utility' . DS . 'Functions.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Utility' . DS . 'SOTT.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Social' . DS . 'SocialLoginAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Authentication' . DS . 'UserAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Management' . DS . 'AccountAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'IHttpClient.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'DefaultHttpClient.php');

use LoginRadiusSDK\LoginRadiusException;

/**
 * CiamLoginRadius Model.
 */
class CiamLoginRadiusModelCiamLoginRadius extends JModelList {

  /**
   * @param $variable
   * @param $data
   * @return string
   */
  public static function selectDisplaySection($variable, $data) {
    $result = 'none';
    if (in_array($variable, $data)) {
      $result = 'block';
    }
    return $result;
  }

  /**
   * Save Settings.
   * 
   * @param type $view
   * @return type
   */
  public function saveSettings($view) {
    //Get database handle
    $table = '';
    $settings = JRequest::getVar('settings');
    if ($view == 'ciamloginradius') {
      //Read Settings
      $settings['apikey'] = isset($settings['apikey']) ? trim($settings['apikey']) : "";
      $settings['apisecret'] = isset($settings['apisecret']) ? trim($settings['apisecret']) : "";
      $settings['useapi'] = $this->getApiMethod();

      $result = $this->saveConfiguration(array_merge($this->getSettings(true), $settings));

      if ($result['status'] == 'message') {
        $table = 'loginradius_settings';
      }
    }
    $this->updateSetting($table, $settings);
    return $result;
  }

   /**
   * 
   * @param type $table
   * @param type $settings
   */
  private function updateSetting($table, $settings) {
    if (!empty($table)) {
       $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__'.$table));       
        $db->setQuery($query);
        $db->execute();
      //Insert new settings
      foreach ($settings as $k => $v) {
                $columns = array('setting', 'value');
                $values = array($db->quote($k), $db->quote($v));
                $query = $db->getQuery(true)
                        ->insert($db->quoteName('#__' . $table))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

  /**
   * Read Settings
   * 
   * @param type $advance
   * @return type
   */
  public function getSettings($advance = false) {
    $settings = array();
    $db = $this->getDbo();    
        
    $sql = "SELECT * FROM #__loginradius_settings";
    if ($advance) {     
      $sql = "SELECT * FROM #__loginradius_advanced_settings";
    }
    
    $db->setQuery($sql);        
    $rows = $db->LoadAssocList();

    if (is_array($rows)) {
      foreach ($rows AS $key => $data) {
        $settings [$data['setting']] = $data ['value'];
      }
    }
    return $settings;
  }

  /**
   * check server connection method
   * 
   * @return string
   */
  private function getApiMethod() {
    if (function_exists('curl_version')) {
      return '1';
    }
    return '0';
  }

  /**
   * 
   * @param type $settings
   * @return type
   */
  public function saveConfiguration($settings) {     
    if (empty($settings['apikey']) && empty($settings['apisecret'])) {
      $results['status'] = "error";
      $results['message'] = JText::_('COM_CIAM_ADVANCE_MESSAGE_12008');
    }
    elseif (empty($settings['apikey']) && !empty($settings['apisecret'])) {
      $results['status'] = "error";
      $results['message'] = JText::_('COM_CIAM_ADVANCE_MESSAGE_APIKEY');
    }
    elseif (empty($settings['apisecret']) && !empty($settings['apikey'])) {
      $results['status'] = "error";
      $results['message'] = JText::_('COM_CIAM_ADVANCE_MESSAGE_SECRETKEY');
    }
    else {
      
      $result = $this->saveApiSettings($settings);
      $results = $this->loginRadiusApiClient($result['url'], JURI::buildQuery($result['data']));
    }
    return $results;
  }

  /**
   * 
   * @param type $settings
   * @return string
   */
  private function saveApiSettings($settings) {
    $jversion = new JVersion();
    $result['url'] = 'https://api.loginradius.com/api/v2/app/validate?apikey=' . rawurlencode($settings['apikey']) . '&apisecret=' . rawurlencode($settings['apisecret']);

    $basicSettingOne = array('loginredirection', 'popupemailtitle');
    $string = $this->loginradius_get_string_format(1, $basicSettingOne, $settings);
    $jinput = JFactory::getApplication()->input;
    $agentstring = $jinput->server->get('HTTP_USER_AGENT', '', '');
    $clientip = $jinput->server->get('REMOTE_ADDR', '', '');
           
    $result['data'] = array(
      'addon' => $jversion->getLongVersion(),
      'version' => '6.0.1',
      'agentstring' => $agentstring,
      'clientip' => $clientip,
      'configuration' => $string
    );
    return $result;
  }

  /**
   * format setting string to save on LoginRadius server
   * 
   * @param type $tabNo
   * @param type $array
   * @param type $settings
   * @return type
   */
  function loginradius_get_string_format($tabNo, $array, $settings) {
    $string = "~" . $tabNo . "#";
    for ($i = 0; $i < count($array); $i++) {
      $settings[$array[$i]] = isset($settings[$array[$i]]) ? $settings[$array[$i]] : '';
      if (is_numeric($settings[$array[$i]]))
        $string .= '|' . $settings[$array[$i]];
      elseif (@unserialize($settings[$array[$i]]))
        $string .= '|' . json_encode(@unserialize($settings[$array[$i]]));
      elseif (is_string($settings[$array[$i]]))
        $string .= '|"' . $settings[$array[$i]] . '"';
    }
    return $string . '|';
  }

  /**
   * @param $validateUrl
   * @return mixed|string
   */
  private function loginRadiusApiClient($validateUrl, $data) {
     $defaultHttpClient = new LoginRadiusSDK\Clients\DefaultHttpClient();
    if ($this->getApiMethod()) {     
     $response = json_decode($defaultHttpClient->request($validateUrl, $data));
    }
    else {
      $response = json_decode($defaultHttpClient->request($validateUrl, $data));    
    }
    
    $results['status'] = "error";
    $message = isset($response->Messages) ? $response->Messages : array();
    $status = isset($response->Status) ? $response->Status : false;

    if ($status) {
      $results['status'] = "message";
      $results['message'] = JText::_('COM_CIAM_SETTING_SAVED');
    }    
    elseif (in_array('API_KEY_NOT_VALID', $message)) {      
      $results['message'] = JText::_('COM_CIAM_SAVE_SETTING_ERROR_ONE');
    }
    elseif (in_array('API_KEY_NOT_FORMATED', $message) && in_array('API_SECRET_NOT_FORMATED', $message)) {  
        $results['message'] = JText::_('COM_CIAM_SAVE_SETTING_ERROR_FIVE');
    }
    elseif (in_array('API_KEY_NOT_FORMATED', $message)) {      
        $results['message'] = JText::_('COM_CIAM_SAVE_SETTING_ERROR_THREE');
    }
    elseif (in_array('API_SECRET_NOT_VALID', $message)) {      
        $results['message'] = JText::_('COM_CIAM_SAVE_SETTING_ERROR_TWO');
    }
    else {       
      $results['message'] = JText::_('COM_CIAM_SAVE_SETTING_ERROR_FOUR');
    }
    return $results;
  }

  /**
   * Plugin class function that get Uid from db
   * 
   * get Uid
   */
  public static function lrRaasGetUid($acid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('Uid')
                ->from('#__loginradius_users')
                ->where('id = ' . $db->Quote($acid));
        $db->setQuery($query);
        return $db->loadResult();
    }
}
