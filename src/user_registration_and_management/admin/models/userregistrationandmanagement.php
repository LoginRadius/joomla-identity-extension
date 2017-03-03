<?php

/**
 * @package     UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.modellist');

require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'LoginRadius.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'LoginRadiusException.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'SocialLogin' . DS . 'SocialLoginAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'SocialLogin' . DS . 'GetProvidersAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'UserAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'CustomObjectAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'AccountAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'IHttpClient.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'DefaultHttpClient.php');

use LoginRadiusSDK\LoginRadiusException;

/**
 * UserRegistrationAndManagement Model.
 */
class UserRegistrationAndManagementModelUserRegistrationAndManagement extends JModelList {

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
    if ($view == 'userregistrationandmanagement') {
      //Read Settings
      $shareProvider = array("facebook", "twitter", "pinterest", "googleplus", "linkedin");
      $counterProvider = array("Facebook Like", "Twitter Tweet", "Google+ Share", "LinkedIn Share");

      $settings['apikey'] = isset($settings['apikey']) ? trim($settings['apikey']) : "";
      $settings['apisecret'] = isset($settings['apisecret']) ? trim($settings['apisecret']) : "";
      $settings['useapi'] = $this->getApiMethod();
      $LoginRadius_profileData = JRequest::getVar('LoginRadius_profileData');
      $LoginRadius_twitterStatusLoginEnable = JRequest::getVar('LoginRadius_twitterStatusLoginEnable');
      $LoginRadius_linkedinPostLoginEnable = JRequest::getVar('LoginRadius_linkedinPostLoginEnable');
      $LoginRadius_facebookStatusLoginEnable = JRequest::getVar('LoginRadius_facebookStatusLoginEnable');
      $LoginRadius_twitterDMLoginEnable = JRequest::getVar('LoginRadius_twitterDMLoginEnable');
      $LoginRadius_linkedinDMLoginEnable = JRequest::getVar('LoginRadius_linkedinDMLoginEnable');
      $LoginRadius_googleDMLoginEnable = JRequest::getVar('LoginRadius_googleDMLoginEnable');
      $LoginRadius_yahooDMLoginEnable = JRequest::getVar('LoginRadius_yahooDMLoginEnable');

      $settings['LoginRadius_profileData'] = (sizeof($LoginRadius_profileData) > 0 ? serialize($LoginRadius_profileData) : "");
      $settings['LoginRadius_twitterStatusLoginEnable'] = (sizeof($LoginRadius_twitterStatusLoginEnable) > 0 ? serialize($LoginRadius_twitterStatusLoginEnable) : "");
      $settings['LoginRadius_linkedinPostLoginEnable'] = (sizeof($LoginRadius_linkedinPostLoginEnable) > 0 ? serialize($LoginRadius_linkedinPostLoginEnable) : "");
      $settings['LoginRadius_facebookStatusLoginEnable'] = (sizeof($LoginRadius_facebookStatusLoginEnable) > 0 ? serialize($LoginRadius_facebookStatusLoginEnable) : "");
      $settings['LoginRadius_twitterDMLoginEnable'] = (sizeof($LoginRadius_twitterDMLoginEnable) > 0 ? serialize($LoginRadius_twitterDMLoginEnable) : "");
      $settings['LoginRadius_linkedinDMLoginEnable'] = (sizeof($LoginRadius_linkedinDMLoginEnable) > 0 ? serialize($LoginRadius_linkedinDMLoginEnable) : "");
      $settings['LoginRadius_googleDMLoginEnable'] = (sizeof($LoginRadius_googleDMLoginEnable) > 0 ? serialize($LoginRadius_googleDMLoginEnable) : "");
      $settings['LoginRadius_yahooDMLoginEnable'] = (sizeof($LoginRadius_yahooDMLoginEnable) > 0 ? serialize($LoginRadius_yahooDMLoginEnable) : "");


      $result = $this->saveConfiguration(array_merge($this->getSettings(true), $settings));

      if ($result['status'] == 'message') {
        $table = 'loginradius_settings';
      }
    }
    elseif ($view == 'socialsharing') {

      //Read Settings
      $shareProvider = array("facebook", "twitter", "pinterest", "googleplus", "linkedin");
      $counterProvider = array("Facebook Like", "Twitter Tweet", "Google+ Share", "LinkedIn Share");

      $settings['horizontalArticles'] = (sizeof(JRequest::getVar('horizontalArticles')) > 0 ? serialize(JRequest::getVar('horizontalArticles')) : "");
      $settings['verticalArticles'] = (sizeof(JRequest::getVar('verticalArticles')) > 0 ? serialize(JRequest::getVar('verticalArticles')) : "");
      $settings['horizontal_rearrange'] = (sizeof(JRequest::getVar('horizontal_rearrange')) > 0 ? serialize(JRequest::getVar('horizontal_rearrange')) : serialize($shareProvider));
      $settings['vertical_rearrange'] = (sizeof(JRequest::getVar('vertical_rearrange')) > 0 ? serialize(JRequest::getVar('vertical_rearrange')) : serialize($shareProvider));
      $settings['horizontalcounter'] = (sizeof(JRequest::getVar('horizontalcounter')) > 0 ? serialize(JRequest::getVar('horizontalcounter')) : serialize($counterProvider));
      $settings['verticalcounter'] = (sizeof(JRequest::getVar('verticalcounter')) > 0 ? serialize(JRequest::getVar('verticalcounter')) : serialize($counterProvider));

      if (!isset($settings['choosehorizontalshare'])) {
        $settings['shareontoppos'] = '1';
        $settings['shareonbottompos'] = '1';
      }

      $settings['horizontalScript'] = json_encode($this->horizontalShare($settings));
      $settings['verticalScript'] = json_encode($this->verticalShare($settings));

      $result = $this->saveConfiguration(array_merge($this->getSettings(), $settings));

      if ($result['status'] == 'message') {
        $table = 'loginradius_advanced_settings';
      }
    }
    elseif ($view == 'apilog') {    
            $settings = array_merge($this->getSettings(), $settings);       
            $result = $this->saveConfiguration($settings);

            if ($result['status'] == 'message') {
                $table = 'loginradius_settings';
            }
        }

    $this->updateSetting($table, $settings);
    return $result;
  }

  /**
   * generate horizontal sharing script
   * 
   * @param $oss_settings
   * @return string
   */
  private function horizontalShare($settings) {
    $shareType = '';
    $sharingScript = 'var shareWidget = new OpenSocialShare();';
    $sharingScript .= 'shareWidget.init({';
    $sharingScript .= 'isHorizontalLayout: 1,';
    switch ($settings['choosehorizontalshare']) {
      case 6:
        $sharingScript .= 'widgetIconSize: "32",';
        $sharingScript .= 'widgetStyle: "responsive",';
        $shareType = 'share';
        break;
      case 1:
        $sharingScript .= 'widgetIconSize: "16",';
        $sharingScript .= 'widgetStyle: "square",';
        $shareType = 'share';
        break;
      case 2:
        $sharingScript .= 'widgetIconSize: "32",';
        $sharingScript .= 'widgetStyle: "image",';
        $shareType = 'image';
        break;
      case 3:
        $sharingScript .= 'widgetIconSize: "16",';
        $sharingScript .= 'widgetStyle: "image",';
        $shareType = 'image';
        break;
      case 4:
        $sharingScript .= 'isCounterWidgetTheme: 1,';
        $sharingScript .= 'isHorizontalCounter: 1,';
        $shareType = 'counter';
        break;
      case 5:
        $sharingScript .= 'isCounterWidgetTheme: 1,';
        $sharingScript .= 'isHorizontalCounter: 0,';
        $shareType = 'counter';
        break;
      default :
        $sharingScript .= 'widgetIconSize: "32",';
        $sharingScript .= 'widgetStyle: "square",';
        $shareType = 'share';
    }

    $emailmessage = trim(str_replace('"', "'", $settings['emailmessage']));
    $emailmessage = preg_replace('/[\t]+/', '', preg_replace('/[\r\n]+/', " ", $emailmessage));


    if (($shareType == 'share') || ($shareType == 'image')) {
      if ($shareType == 'share') {
        $sharingScript .= 'isMobileFriendly: ' . (($settings['mobilefriendly'] != '1') ? "false" : "true") . ',';
        $sharingScript .= 'isTotalShare: ' . (($settings['sharecount'] != '1') ? "false" : "true") . ',';
      }
      $sharingScript .= 'isEmailContentReadOnly: ' . (($settings['emailreadonly'] != '1') ? "false" : "true") . ',';
      $sharingScript .= ($settings['emailsubject'] != '') ? ('emailSubject: "' . $settings['emailsubject'] . '",') : '';

      $sharingScript .= ($emailmessage != '') ? ('emailMessage: "' . $emailmessage . '",') : '';
      $sharingScript .= 'isShortenUrl: ' . (($settings['shorturl'] != '1') ? "false" : "true") . ',';
      $sharingScript .= 'isOpenSingleWindow: ' . (($settings['singlewindow'] != '1') ? "false" : "true") . ',';
      if (($settings['custompopup'] == '1') && ($settings['popupwidth'] != '') && ($settings['popupheight'] != '')) {
        $sharingScript .= "popupWindowSize:{height:" . $settings['popupheight'] . ",width :" . $settings['popupwidth'] . "},";
      }
      $sharingScript .= ($settings['facebookappid'] != '') ? ('facebookAppId: "' . $settings['facebookappid'] . '",') : '';
      $sharingScript .= ($settings['twittermention'] != '') ? ('twittermention: "' . $settings['twittermention'] . '",') : '';
      $sharingScript .= ($settings['twitterhashtag'] != '') ? ('twitterhashtag: "' . $settings['twitterhashtag'] . '",') : '';
    }

    if ($settings['customoptions'] != '') {
      $customOption = json_decode($settings['customoptions'], true);
      if (!is_array($customOption)) {
        $sharingScript .= ($settings['customoptions']);
      }
      else {
        foreach ($customOption as $key => $value) {
          $sharingScript .= $key . ': ' . (is_array($value) ? json_encode($value) : "'" . $value . "'") . ',';
        }
      }
    }
    $sharingScript .= 'theme: \'OpenSocialShareDefaultTheme\',';
    $sharingScript .= $this->getSelectedProvider('horizontal', $shareType, $settings);
    $sharingScript = substr($sharingScript, 0, -1);
    $sharingScript .= '});';
    $sharingScript .= 'shareWidget.injectInterface(".openSocialShareHorizontalSharing");';
    $sharingScript .= 'shareWidget.setWidgetTheme(".openSocialShareHorizontalSharing");';

    return $sharingScript;
  }

  private function getSelectedProvider($interface, $type, $settings) {
    $providers = '';
    if ($type == 'counter') {
      $providers .= "widgets: { top: ";
      $providers .= json_encode(unserialize($settings[$interface . 'counter']));
      $providers .= "},";
    }
    else if ($type == 'share') {
      $providers .= "providers: { top: ";
      $providers .= json_encode(unserialize($settings[$interface . '_rearrange']));
      $providers .= "},";
    }
    return $providers;
  }

  /**
   * generate verical sharing script
   * 
   * @param $settings
   * @return string
   */
  private function verticalShare($settings) {
    $shareType = '';
    $sharingScript = 'var shareWidget = new OpenSocialShare();';
    $sharingScript .= 'shareWidget.init({';
    $sharingScript .= 'isHorizontalLayout: 0,';
    switch ($settings['chooseverticalshare']) {
      case 2:
        $sharingScript .= 'isCounterWidgetTheme: 1,';
        $sharingScript .= 'isHorizontalCounter: 1,';
        $shareType = 'counter';
        break;
      case 3:
        $sharingScript .= 'isCounterWidgetTheme: 1,';
        $sharingScript .= 'isHorizontalCounter: 0,';
        $shareType = 'counter';
        break;
      case 1:
        $sharingScript .= 'widgetIconSize: "16",';
        $sharingScript .= 'widgetStyle: "square",';
        $shareType = 'share';
        break;
      default :
        $sharingScript .= 'widgetIconSize: "32",';
        $sharingScript .= 'widgetStyle: "square",';
        $shareType = 'share';
    }
    $emailmessage = trim(str_replace('"', "'", $settings['emailmessage']));
    $emailmessage = preg_replace('/[\t]+/', '', preg_replace('/[\r\n]+/', " ", $emailmessage));
    if ($shareType == 'share') {
      $sharingScript .= 'isEmailContentReadOnly: ' . (($settings['emailreadonly'] != '1') ? "false" : "true") . ',';
      $sharingScript .= ($settings['emailsubject'] != '') ? ('emailSubject: "' . $settings['emailsubject'] . '",') : '';
      $sharingScript .= ($emailmessage != '') ? ('emailMessage: "' . $emailmessage . '",') : '';
      $sharingScript .= 'isShortenUrl: ' . (($settings['shorturl'] != '1') ? "false" : "true") . ',';
      $sharingScript .= 'isTotalShare: ' . (($settings['sharecount'] != '1') ? "false" : "true") . ',';
      $sharingScript .= 'isOpenSingleWindow: ' . (($settings['singlewindow'] != '1') ? "false" : "true") . ',';
      $sharingScript .= ($settings['facebookappid'] != '') ? ('facebookAppId: "' . $settings['facebookappid'] . '",') : '';
      $sharingScript .= ($settings['twittermention'] != '') ? ('twittermention: "' . $settings['twittermention'] . '",') : '';
      $sharingScript .= ($settings['twitterhashtag'] != '') ? ('twitterhashtag: "' . $settings['twitterhashtag'] . '",') : '';
      if (($settings['custompopup'] == '1') && ($settings['popupwidth'] != '') && ($settings['popupheight'] != '')) {
        $sharingScript .= "popupWindowSize:{height:" . $settings['popupheight'] . ",width :" . $settings['popupwidth'] . "},";
      }
    }

    if ($settings['customoptions'] != '') {
      $customOption = json_decode($settings['customoptions'], true);
      if (!is_array($customOption)) {
        $sharingScript .= ($settings['customoptions']);
      }
      else {
        foreach ($customOption as $key => $value) {
          $sharingScript .= $key . ': ' . (is_array($value) ? json_encode($value) : "'" . $value . "'") . ',';
        }
      }
    }

    $sharingScript .= 'theme: \'OpenSocialShareDefaultTheme\',';
    $sharingScript .= $this->getSelectedProvider('vertical', $shareType, $settings);
    $sharingScript = substr($sharingScript, 0, -1);
    $sharingScript .= '});';
    $sharingScript .= 'shareWidget.injectInterface(".openSocialShareVerticalSharing");';
    $sharingScript .= 'shareWidget.setWidgetTheme(".openSocialShareVerticalSharing");';
    return $sharingScript;
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
    if (!isset($settings['choosehorizontalshare'])) {
      $settings['shareontoppos'] = '1';
      $settings['shareonbottompos'] = '1';
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
      $results['message'] = JText::_('COM_SOCIALLOGIN_ADVANCE_MESSAGE_12008');
    }
    elseif (empty($settings['apikey']) && !empty($settings['apisecret'])) {
      $results['status'] = "error";
      $results['message'] = JText::_('COM_SOCIALLOGIN_ADVANCE_MESSAGE_APIKEY');
    }
    elseif (empty($settings['apisecret']) && !empty($settings['apikey'])) {
      $results['status'] = "error";
      $results['message'] = JText::_('COM_SOCIALLOGIN_ADVANCE_MESSAGE_SECRETKEY');
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

    $basicSettingOne = array('loginredirection');
    $basicSettingTwo = array('sharehorizontal', 'choosehorizontalshare', 'shareontoppos', 'shareonbottompos', 'horizontalcounter', 'horizontalrearrange', 'horizontalarticles', 'sharevertical', 'chooseverticalshare', 'verticalsharepos', 'verticalcounter', 'verticalrearrange', 'verticalarticles');
    $basicSettingThree = array('iconsize', 'iconsperrow', 'interfacebackground', 'showinterface', 'dummyemail', 'popupemailtitle', 'popupemailmessage', 'popuperroremailmessage', 'updateuserdata');
    $advanceSettingOne = array('basic', 'exlocation', 'exprofile', 'followcompanies', 'fbprofile', 'statusmessage', 'fbpost', 'twittermentions', 'groups', 'socialcontacts', 'fblike');
    $advanceSettingTwo = array('LoginRadius_facebookStatusEnable', 'LoginRadius_facebookStatusUrl', 'LoginRadius_facebookStatusTitle', 'LoginRadius_facebookDescription', 'LoginRadius_facebookStatus', 'LoginRadius_twitterStatusEnable', 'LoginRadius_twitterTweet', 'LoginRadius_linkedinPostEnable', 'LoginRadius_linkedinPostTitle', 'LoginRadius_linkedinPostTitle', 'LoginRadius_linkedinPostUrl', 'LoginRadius_linkedinPostImageUrl', 'LoginRadius_linkedinPostMessage');
    $advanceSettingThree = array('LoginRadius_twitterDMEnable', 'twitterMessageFriends', 'LoginRadius_twitterDMSubject', 'LoginRadius_twitterDMMessage', 'LoginRadius_linkedinDMEnable', 'linkedinMessageFriends', 'LoginRadius_linkedinDMSubject', 'LoginRadius_linkedinDMMessage', 'LoginRadius_googleDMEnable', 'googleMessageFriends', 'LoginRadius_googleDMSubject', 'LoginRadius_googleDMMessage', 'LoginRadius_yahooEmailEnable','LoginRadius_yahooDMEnable', 'yahooMessageFriends', 'LoginRadius_yahooDMSubject', 'LoginRadius_yahooDMMessage');

    $string = $this->loginradius_get_string_format(1, $basicSettingOne, $settings);
    $string .= $this->loginradius_get_string_format(2, $basicSettingTwo, $settings);
    $string .= $this->loginradius_get_string_format(3, $basicSettingThree, $settings);
    $string .= $this->loginradius_get_string_format(4, $advanceSettingOne, $settings);
    $string .= $this->loginradius_get_string_format(5, $advanceSettingTwo, $settings);
    $string .= $this->loginradius_get_string_format(6, $advanceSettingThree, $settings);

    $jinput = JFactory::getApplication()->input;
    $agentstring = $jinput->server->get('HTTP_USER_AGENT', '', '');
    $clientip = $jinput->server->get('REMOTE_ADDR', '', '');
           
    $result['data'] = array(
      'addon' => $jversion->getLongVersion(),
      'version' => '5.0.1',
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
      $results['message'] = JText::_('COM_SOCIALLOGIN_SETTING_SAVED');
    }    
    elseif (in_array('API_KEY_NOT_VALID', $message)) {      
      $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_ONE');
    }
    elseif (in_array('API_KEY_NOT_FORMATED', $message) && in_array('API_SECRET_NOT_FORMATED', $message)) {  
        $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_FIVE');
    }
    elseif (in_array('API_KEY_NOT_FORMATED', $message)) {      
        $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_THREE');
    }
    elseif (in_array('API_SECRET_NOT_VALID', $message)) {      
        $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_TWO');
    }
    else {       
      $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_FOUR');
    }
    return $results;
  }
 
  /**
   * clear api's logs
   */  
  
   function clearLog() {    
    $mainframe = JFactory::getApplication();
    $cid = JRequest::getVar('cid');
    $loggeduser = JFactory::getUser();
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $messagedisplay = 0;
    $message = JText::_('COM_SOCIALLOGIN_APILOG_NOTDELETED');
    $megtype = 'error';   
    
        $query->delete($db->quoteName('#__loginradius_log'));       
        $db->setQuery($query);
        $db->execute();
   
          $messagedisplay++;
          $message = JText::_('COM_SOCIALLOGIN_APILOG_DELETED');
          $megtype = '';        
  
    $mainframe->redirect('index.php?option=com_userregistrationandmanagement&view=apilog', $message, $megtype);
  }

  /**
   * delete user data
   */  
  
  function remove() {
    $settings = self::getSettings();
    $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
    $mainframe = JFactory::getApplication();
    $cid = JRequest::getVar('cid');
    $loggeduser = JFactory::getUser();
    $db = JFactory::getDBO();
    $messagedisplay = 0;
    $message = JText::_('COM_SOCIALLOGIN_USERS_NOTDELETED');
    $megtype = 'error';   

    foreach ($cid as $key => $value):        
      $self = $loggeduser->id == $value;
      if (!$self):  
        $raas_uid = self::lrRaasGetUid($value);   
        if (isset($raas_uid) && !empty($raas_uid)) {     
          try {        
            $response = $raas_account->deleteAccount($raas_uid);
          }
          catch (LoginRadiusException $e) {    
          }
        }    
        
        $conditions = array(
                    $db->quoteName('id') . ' = ' . $db->quote($value)
                );
                $conditions1 = array(
                    $db->quoteName('userid') . ' = ' . $db->quote($value)
                );
                $query = $db->getQuery(true);
                $query->delete($db->quoteName('#__users'));
                $query->where($conditions);
                $db->setQuery($query);
                $db->execute();
                $query = $db->getQuery(true);
                $query->delete($db->quoteName('#__loginradius_users'));
                $query->where($conditions);
                $db->setQuery($query);
                $db->execute();
                $query = $db->getQuery(true);
                $query->delete($db->quoteName('#__session'));
                $query->where($conditions1);
                $db->setQuery($query);
                $db->execute();

          $messagedisplay++;
          $message = JText::_('COM_SOCIALLOGIN_USERS_DELETED');
          $megtype = '';   

      endif;
    endforeach;
    $mainframe->redirect('index.php?option=com_userregistrationandmanagement&view=usermanager', $message, $megtype);
  }

  /**
   * unblock user
   */
  function enable() {
    $settings = self::getSettings();
    $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
    $mainframe = JFactory::getApplication();
    $cid = JRequest::getVar('cid');
    $loggeduser = JFactory::getUser();
    $messagedisplay = 0;
    $db = JFactory::getDBO();
    foreach ($cid as $key => $value):
      $self = $loggeduser->id == $value;
      if (!$self):
        $raas_uid = self::lrRaasGetUid($value);
         if (isset($raas_uid) && !empty($raas_uid)) {  
          try {
            $response = $raas_account->setStatus($raas_uid, false);  
          }
          catch (LoginRadiusException $e) {
          }
         }    
              $query = $db->getQuery(true);
                $fields = array(                    
                    $db->quoteName('block') . ' = 0'
                );
                $conditions = array(                   
                    $db->quoteName('id') . ' = ' . $db->quote($value)
                );

                $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $db->execute();
                $messagedisplay++;  
            endif;
    endforeach;
    if ($messagedisplay > 0):
      $mainframe->enqueueMessage($messagedisplay . ' ' . JText::_('COM_SOCIALLOGIN_USERS_ENABLE'));
    endif;
    $mainframe->redirect('index.php?option=com_userregistrationandmanagement&view=usermanager');
  }

  /**
   * block user
   */
  function disable() {
    $settings = self::getSettings();
    $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
    $mainframe = JFactory::getApplication();
    $cid = JRequest::getVar('cid');
    $loggeduser = JFactory::getUser();
    $messagedisplay = 0;
    $db = JFactory::getDBO();
    foreach ($cid as $key => $value):
      $self = $loggeduser->id == $value;
      if (!$self):
        $raas_uid = self::lrRaasGetUid($value);    
         if (isset($raas_uid) && !empty($raas_uid)) {  
          try {
            $response = $raas_account->setStatus($raas_uid, true);       
          }
          catch (LoginRadiusException $e) {
           }         
        }        
        
        $query = $db->getQuery(true);
                $fields = array(                    
                    $db->quoteName('block') . ' = 1'
                );
                $conditions = array(                   
                    $db->quoteName('id') . ' = ' . $db->quote($value)
                );

                $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $db->execute();
                $messagedisplay++;
      endif;
    endforeach;
    if ($messagedisplay > 0):
      $mainframe->enqueueMessage($messagedisplay . ' ' . JText::_('COM_SOCIALLOGIN_USERS_DISABLED'),'error');
    endif;
    $mainframe->redirect('index.php?option=com_userregistrationandmanagement&view=usermanager');
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

    /**
   * Activet user
   */
  function activate() {
    $mainframe = JFactory::getApplication();
    $cid = JRequest::getVar('cid');
    JArrayHelper::toInteger($cid);
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('activation') . ' = '. $db->quote('')
        );  
        $query->update($db->quoteName('#__users'))->set($fields) ->where('id IN (' . implode(',', $cid) . ')');
                $db->setQuery($query);        
    $db->setQuery($query);
    $db->query();
    $mainframe->redirect('index.php?option=com_userregistrationandmanagement&view=usermanager', JText::_('COM_SOCIALLOGIN_USERS_ACTIVETED'));
  }
}
