<?php
/**
 * @package     Ciam.Plugin
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.user.helper');
jimport('joomla.mail.helper');
jimport('joomla.application.component.helper');
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.controller');
jimport('joomla.event.dispatcher');
jimport('joomla.plugin.helper');
jimport('joomla.utilities.date');

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Includes plugins required files.
 */
use \LoginRadiusSDK\Utility\Functions;
use \LoginRadiusSDK\LoginRadiusException;
use \LoginRadiusSDK\Clients\IHttpClient;
use \LoginRadiusSDK\Clients\DefaultHttpClient;
use \LoginRadiusSDK\Utility\SOTT;
use \LoginRadiusSDK\CustomerRegistration\Social\SocialLoginAPI;
use \LoginRadiusSDK\CustomerRegistration\Authentication\UserAPI;
use \LoginRadiusSDK\CustomerRegistration\Management\AccountAPI;

require_once(dirname(__FILE__) . DS . 'helper' . DS . 'helper.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'functions.php');
require_once(dirname(__FILE__) . DS . 'customhttpclient.php');

global $apiClient_class;
$apiClient_class = 'CustomHttpClient';
/*
 * Class that indicates the plugin.
 * 
 */

class plgSystemCiam extends JPlugin {

    /**
     * Plugin class function that calls on intialise plugin
     * 
     * @param $subject
     * @param $config
     */
    function plgSystemCiam(&$subject, $config) {

        parent::__construct($subject, $config);
    }

    /**
     * Plugin class function that get raas option
     *
     */
    public static function getCiamOptions($settings, $is_lr_front_script = false) {
        if (isset($settings['apikey']) && !empty($settings['apikey'])) {
            $document = JFactory::getDocument();
            $script = '';
            if (isset($settings['LoginRadius_termsAndCondition']) && $settings['LoginRadius_termsAndCondition'] != '') {
                $termsCondition = preg_replace('/\n+/', '', $settings['LoginRadius_termsAndCondition']);
                $termsCondition = preg_replace('/\r+/', '', $termsCondition);
                $script .= 'ciamoption.termsAndConditionHtml = "' . str_replace(array('<script>', '</script>'), '', $termsCondition) . '";';
            }
            if (isset($settings['LoginRadius_formRenderDelay']) && is_numeric($settings['LoginRadius_formRenderDelay']) != '0') {
                $script .= 'ciamoption.formRenderDelay =  ' . $settings['LoginRadius_formRenderDelay'] . ';';
            }
            $min_length = isset($settings['LoginRadius_passwordMinLength']) ? $settings['LoginRadius_passwordMinLength'] : '';
            $max_length = isset($settings['LoginRadius_passwordMaxLength']) ? $settings['LoginRadius_passwordMaxLength'] : '';

            if (!empty($min_length) && !empty($max_length)) {
                $password_length = '{min:' . $min_length . ',max:' . $max_length . '}';
                $script .= 'ciamoption.passwordLength = ' . $password_length . ';';
            }
            if (isset($settings['LoginRadius_enableFormValidationMsg']) && $settings['LoginRadius_enableFormValidationMsg'] != '' && $settings['LoginRadius_enableFormValidationMsg'] != 'false') {
                $script .= 'ciamoption.formValidationMessage = ' . $settings['LoginRadius_enableFormValidationMsg'] . ';';
            }
            if (isset($settings['LoginRadius_forgotEmailTemplate']) && $settings['LoginRadius_forgotEmailTemplate'] != '') {
                $script .= 'ciamoption.forgotPasswordTemplate = "' . $settings['LoginRadius_forgotEmailTemplate'] . '";';
            }
            if (isset($settings['LoginRadius_enableStayLogin']) && $settings['LoginRadius_enableStayLogin'] != '' && $settings['LoginRadius_enableStayLogin'] != 'false') {
                $script .= 'ciamoption.stayLogin = ' . $settings['LoginRadius_enableStayLogin'] . ';';
            }
            if (isset($settings['LoginRadius_askRequiredFieldForTraditionalLogin']) && $settings['LoginRadius_askRequiredFieldForTraditionalLogin'] != '' && $settings['LoginRadius_askRequiredFieldForTraditionalLogin'] != 'false') {
                $script .= 'ciamoption.askRequiredFieldForTraditionalLogin = ' . $settings['LoginRadius_askRequiredFieldForTraditionalLogin'] . ';';
            }
            if (isset($settings['LoginRadius_displayPasswordStrength']) && $settings['LoginRadius_displayPasswordStrength'] != '' && $settings['LoginRadius_displayPasswordStrength'] != 'false') {
                $script .= 'ciamoption.displayPasswordStrength = ' . $settings['LoginRadius_displayPasswordStrength'] . ';';
            }
            $emailVerifyOpt = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';
            if (isset($emailVerifyOpt) && $emailVerifyOpt != '') {
                if ($emailVerifyOpt == '0') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'ciamoption.loginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_enablePromptPassword'] != '' && $settings['LoginRadius_enablePromptPassword'] != 'false') {
                        $script .= 'ciamoption.promptPasswordOnSocialLogin = ' . $settings['LoginRadius_enablePromptPassword'] . ';';
                    } if ($settings['LoginRadius_enableLoginWithUsername'] != '' && $settings['LoginRadius_enableLoginWithUsername'] != 'false') {
                        $script .= 'ciamoption.usernameLogin = ' . $settings['LoginRadius_enableLoginWithUsername'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'ciamoption.askEmailForUnverifiedProfileAlways = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                }
                elseif ($emailVerifyOpt == '1') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'ciamoption.loginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'ciamoption.askEmailForUnverifiedProfileAlways = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                    $script .= 'ciamoption.optionalEmailVerification = true;';
                }
                elseif ($emailVerifyOpt == '2') {
                    $script .= 'ciamoption.disabledEmailVerification = true;';
                }
            }

            if (isset($settings['LoginRadius_emailVerificationTemplate']) && $settings['LoginRadius_emailVerificationTemplate'] != '') {
                $script .= 'ciamoption.verificationEmailTemplate = "' . $settings['LoginRadius_emailVerificationTemplate'] . '";';
            }

            if (isset($settings['LoginRadius_customOption']) && $settings['LoginRadius_customOption'] != '') {
                $jsondata = self::lrCiamJsonValidate($settings['LoginRadius_customOption']);
                if (is_object($jsondata)) {
                    foreach ($jsondata as $key => $value) {
                        $script .= "ciamoption." . $key . "=";
                        if (is_object($value) || is_array($value)) {
                            $encodedStr = json_encode($value);
                            $script .= $encodedStr . ';';
                        }
                        else {
                            $script .= $value . ';';
                        }
                    }
                }
                else {
                    if (is_string($jsondata)) {
                        $script .= $jsondata;
                    }
                }
            }
            if (JVERSION > 3) {
                $document->addScript("http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js");
                $document->addScript("http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js");
            }
          
            $document->addStyleSheet(JURI::root() . 'components/com_ciamloginradius/assets/css/jquery.ui.core.css');
            $document->addStyleSheet(JURI::root() . 'components/com_ciamloginradius/assets/css/jquery.ui.theme.css');
            $document->addStyleSheet(JURI::root() . 'components/com_ciamloginradius/assets/css/jquery.ui.datepicker.css');

            $version = '3';
            if (JVERSION < 3) {
                $version = '2';
            }
            $document->addStyleSheet(JURI::root() . 'components/com_ciamloginradius/assets/css/lr_ciam' . $version . '.min.css');
            $parseURL = parse_url(JURI::base());
            $domain = $parseURL['scheme'] . '://' . $parseURL['host'];
            $emailVerificationUrl = $parseURL['scheme'] . '://' . $parseURL['host'] . $parseURL['path'] . 'index.php';
            $callbackUrl = $parseURL['scheme'] . '://' . $parseURL['host'] . $parseURL['path'] . 'index.php';
            $request_uri = JRequest::getURI();
            if (strpos($request_uri, 'redirect_to') !== FALSE) {
                $rid = $_GET['redirect_to'];
                $callbackUrl .= "?redirect_to=" . $rid;
            }
            if (JFactory::getUser()->id) {
                $loggedIn = true;
            }
            else {
                $loggedIn = false;
            }
            
            $path = parse_url(JURI::base());
            $sso_path = $path['path'];

            $loginFunction = 'var ciamoption = {};
                var homeDomain = "' . JURI::base() . '";            
                var loggedIn = "' . $loggedIn . '";            
                var profileUrl = "' . JURI::getInstance()->toString() . '";            
                ciamoption.appName = "' . $settings['sitename'] . '";
                ciamoption.appPath = "' . $sso_path . '";                
                ciamoption.apiKey = "' . $settings['apikey'] . '";        
                ciamoption.verificationUrl = "' . $emailVerificationUrl . '";
                ciamoption.forgotPasswordUrl = "' . $domain . JRoute::_('index.php?option=com_ciamloginradius&view=login') . '";
                ciamoption.sott = "' . self::lrCiamGetSott($settings['apikey'], $settings['apisecret']) . '";
                ciamoption.callbackUrl = "' . $callbackUrl . '"; 
                ciamoption.hashTemplate = true; 
                ' . $script;
            $document->addScriptDeclaration($loginFunction);
            if (!$is_lr_front_script) {
                $document->addScript(JURI::root() . 'components/com_ciamloginradius/assets/js/LoginRadiusFrontEnd.min.js');
            }
            $_SESSION['redirect_to'] = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : '';
            $HTTP_REFERER = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
            if (isset($parseURL['host']) && !empty($parseURL['host']) && (strpos($HTTP_REFERER, $parseURL['host']) !== false)) {
                $_SESSION['referer_url'] = $_SERVER["HTTP_REFERER"];
            }
        }
    }

    public static function lrCiamGetSott($apiKey, $secret) {
        $sott = new \LoginRadiusSDK\Utility\SOTT($apiKey, $secret);
        return urlencode($sott->encrypt('10', true));
    }

    /**
     * Check String is json or not.
     *
     * @param $string 
     * @return json|string
     */
    public static function lrCiamJsonValidate($string) {
        $result = json_decode($string);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        }
        else {
            return $string;
        }
    }

    function onBeforeCompileHead() {

        if (JFactory::getApplication()->isSite()) {
            $document = JFactory::getDocument();
            if (JVERSION < 3) {
                $request_uri = JRequest::getURI();
                if (strpos($request_uri, 'ciamloginradius') !== FALSE) {
                    if (isset($document->_scripts[JUri::root(true) . '/media/system/js/mootools-more-uncompressed.js'])) {
                        unset($document->_scripts[JUri::root(true) . '/media/system/js/mootools-more-uncompressed.js']);
                    } if (isset($document->_scripts[JUri::root() . 'media/system/js/mootools-more-uncompressed.js'])) {
                        unset($document->_scripts[JUri::root() . 'media/system/js/mootools-more-uncompressed.js']);
                    }
                }
            }
            $settings = plgSystemCiamTools::getSettings();
            if (JVERSION < 3) {
                $document->addScript(JURI::root() . 'components/com_ciamloginradius/assets/js/jquery.js');
                $document->addScript(JURI::root() . 'components/com_ciamloginradius/assets/js/jquery-noconflict.js');
                $document->addScript(JURI::root() . 'components/com_ciamloginradius/assets/js/jquery.min.js');
            }
            if (isset($settings['apikey']) && isset($settings['apisecret'])) {
                $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
                $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js');
                $document->addScript('components/com_ciamloginradius/assets/js/LoginRadiusFrontEnd.min.js');
                $document->addScript('//auth.lrcontent.com/v2/js/LoginRadiusV2.js');           
            }

            self::getCiamOptions($settings, true);
        }
    }

    public function onBeforeRender() {
        $controller = JRequest::get("controller");
        $mainframe = JFactory::getApplication();
        if (JFactory::getApplication()->isAdmin()) {
            switch ($controller['option']) {
                case 'com_users':
                    $mainframe = JFactory::getApplication();
                    if ($controller['view'] == 'user' && $controller['layout'] == 'edit') {
                        $redirect = $mainframe->redirect('https://secure.loginradius.com/user-management/manage-users');
                    }
                    break;
            }
        }       
    }

    /**
     * Plugin class function that calls on after plugin initialise
     * 
     * Manage Authantication Process
     */
    function onAfterInitialise() {
        // Get module configration option value    
        $language = JFactory::getLanguage();
        $mainframe = JFactory::getApplication();
        $app = JFactory::getApplication();


        if (JFactory::getApplication()->isSite()) {
            $jinput = JFactory::getApplication()->input;
            $baseuri = $jinput->server->get('REQUEST_URI', '', '');
            $uri = basename(parse_url($baseuri, PHP_URL_PATH));

            if ($uri == 'log-out') {
                $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=login'));
            }
            $view = $app->input->get('view');
            if (!JFactory::getUser()->id) {
                if (in_array($view, array('reset', 'remind'))) {
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=forgotpassword'));
                }
                elseif (in_array($view, array('registration', 'login'))) {
                    if ($view == 'registration') {
                        $view = 'register';
                    }
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=' . $view));
                }
            }
            else {
                $option = $app->input->get('option');
                switch ($option) {
                    case 'com_users':
                        $mainframe = JFactory::getApplication();
                        if ($view == 'login') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=login'));
                        }
                        elseif ($view == 'registration') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=register'));
                        }
                        elseif (in_array($view, array('remind', 'reset'))) {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=password'));
                        }
                        elseif ($view == 'profile') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=profile'));
                        }
                        break;
                }
            }
        }

        $language->load('com_users');
        $language->load('com_ciamloginradius', JPATH_ADMINISTRATOR);
        $settings = plgSystemCiamTools::getSettings();
        // Retrieve data from LoginRadius.  
        if (isset($settings['apikey']) && isset($settings['apisecret'])) {

            $token = JRequest::getVar('token');

            if (!empty($token)) {
                $socialLoginObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
                $userObject = new UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
                try {
                    $result_accesstoken = $socialLoginObject->exchangeAccessToken($token);
                    $accessToken = $result_accesstoken->access_token;
                }
                catch (LoginRadiusException $e) {

                    if (JVERSION < 3) {
                        $dispatcher = JDispatcher::getInstance();
                    }
                    else {
                        $dispatcher = JEventDispatcher::getInstance();
                    }     
               
                    $dispatcher->trigger('onLoginRadiusSSOLogout', array($e));
                    if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                    }
                }

                if (isset($result_accesstoken) && $result_accesstoken !== false) {
                    
                    $_SESSION['result_accesstoken'] = $result_accesstoken->access_token;
                    try {
                        $userProfileObject = $userObject->getProfile($result_accesstoken->access_token);
                       }
                    catch (LoginRadiusException $e) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                    }

                    $userProfile = plgSystemCiamTools::manageUserProfileData($userProfileObject, $accessToken);

                    if (!JFactory::getUser()->id) {
                      
                        $userid = self::checkUid($userProfile);
                        if (!empty($userid)) {                           
                            plgSystemCiamTools::insertSocialData($userid, $userProfile);
                            plgSystemCiamTools::loginExistUser($userid, $userProfile, $accessToken, false);
                        }
                        else {                        
                            if (isset($userProfileObject->ID)) {
                                $userId = self::checkProviderId($userProfile);
                                if (!empty($userId)) {                                   
                                    if (!plgSystemCiamTools::checkActivateUser($userProfile->ID) && !plgSystemCiamTools::checkBlockUser($userProfile->ID)) {
                                        plgSystemCiamTools::loginExistUser($userId, $userProfile, $accessToken, false);
                                    }
                                }
                                else {                                         
                                    plgSystemCiamTools::accountLogginProcess($userProfile, $accessToken);
                                }
                            }
                        }
                    }
                }
            }
            else {  
                    if(isset($_SESSION['result_accesstoken']) && $_SESSION['result_accesstoken']!= ''){
                         $userObject = new UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
                    try {
                        $userProfile = $userObject->getProfile($_SESSION['result_accesstoken']);
                     }
                    catch (LoginRadiusException $e) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                    }            
             
                    if (isset($userProfile->UserName) && JFactory::getUser()->username != $userProfile->UserName) {
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $fields = array(
                          $db->quoteName('username') . ' = ' . $db->quote($userProfile->UserName)
                        );
                        $conditions = array(
                          $db->quoteName('username') . ' = ' . $db->quote(JFactory::getUser()->username)
                        );

                        $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
                        $db->setQuery($query);
                        $db->execute();
                    }
                  
                    $name = explode(" ",JFactory::getUser()->name);
                    $firstname = isset($name[0]) ? $name[0] : '';
                    $lastname = isset($name[1]) ? $name[1] : '';
                    
                    $userFirstName = isset($userProfile->FirstName) ? $userProfile->FirstName: '';     
                    $userLastName = isset($userProfile->LastName) ? $userProfile->LastName: '';     
                    if (isset($userProfile) && (isset($firstname) && $firstname != $userFirstName) || (isset($lastname) && $lastname != $userLastName)) {
                        $db = JFactory::getDBO();
                        $query = $db->getQuery(true);
                        $fields = array(
                          $db->quoteName('name') . ' = ' . $db->quote($userFirstName . ' '. $userLastName)
                        );
                        $conditions = array(
                          $db->quoteName('name') . ' = ' . $db->quote($firstname . ' '. $lastname)
                        );

                        $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
   
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
                
                if (isset($_GET['lrmessage']) && !empty($_GET['lrmessage'])) {                
                    $mainframe = JFactory::getApplication();
                    $parseURL = parse_url(JURI::base());
                    $message = $_COOKIE['lr_message'];
                    $response = $_GET['response'];
                    $returnUrl = $parseURL['scheme'] . '://' . $parseURL['host'] . $parseURL['path'] . 'index.php';
                    setcookie("lr_message", "", time() - 3600, "/");
                    $mainframe->redirect($returnUrl, $message, $response);
                }
                else {
                    $vtype = JRequest::getVar('vtype');
                    if (isset($vtype) && $vtype == 'emailverification') {
                        global $lr_message;
                        $jinput = JFactory::getApplication()->input;
                        $php_self = $jinput->server->get('PHP_SELF', '', '');
                        if ($lr_message !== false && preg_match('/index\.php$/', $php_self)) {
                            $lr_message = false;
                            $document = JFactory::getDocument();
                            ?><div id="resetpassword-container" style="display: none"></div>
                            <?php
                        }
                    }
                }
            }
        }
    }

    function onlrGetPluginDisabled($pluginName) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('enabled'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . " = " . $db->quote($pluginName));
        $db->setQuery($query);
        $db->execute();
        return $db->loadResult();
    }

    public static function getUidbyId($userId) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('Uid'))
            ->from($db->quoteName('#__loginradius_users'))
            ->where($db->quoteName('id') . " = " . $db->quote($userId));
        $db->setQuery($query);
        $db->execute();
        $Uid = $db->loadAssocList();
        return isset($Uid[0]['Uid']) ? trim($Uid[0]['Uid']) : false;
    }

    /**
     * Check Uid exist.
     *
     * @param userProfile
     */
    public static function checkUid($userProfile) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.Uid') . " = " . $db->quote($userProfile->Uid));
        $db->setQuery($query);
        return $db->loadResult();
    }

    /**
     * Plugin class function that  Check social id exist
     *    
     */
    public static function checkProviderId($userProfile) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.LoginRadius_id') . " = " . $db->quote($userProfile->ID));
        $db->setQuery($query);
        return $db->loadResult();
    }

    function onloginRadiusUserSave($data) {
        $output['status'] = 'error';
        $output['message'] = 'An error occurred';
        $user = JUser::getInstance($data['id']);
        $settings = plgSystemCiamTools::getSettings();

        $accountObj = new LoginRadiusSDK\CustomerRegistration\Management\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
        $socialProfile = $this->getSocialProfilebyUserId($user->id);

        $raasUserId = isset($socialProfile[0]['LoginRadius_id']) ? $socialProfile[0]['LoginRadius_id'] : '';
        $name = explode(' ', $data['name']);
        $params = array(
          'firstname' => isset($name[0]) ? $name[0] : '',
          'lastname' => isset($name[1]) ? $name[1] : ''
        );

        try {
            $response = $accountObj->update($raasUserId, $params);
            $output['status'] = 'message';
            $output['message'] = JText::_('COM_USER_REGISTRATION_CHANGE_PROFILE_MSG_FRONT');
            $user->name = $data['name'];
            $user->save(true);
        }
        catch (LoginRadiusException $e) {
            if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {
                $output['message'] = $e->getErrorResponse()->message;
            }
            else {
                $output['message'] = JText::_('COM_USER_REGISTRATION_CHANGE_PROFILE_ERRORMSG_FRONT');
            }
        }
        return $output;
    }    
    
    public static function getSocialProfilebyUserId($userId) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__loginradius_users'))
            ->where($db->quoteName('id') . " = " . $db->quote($userId));
        $db->setQuery($query);
        $db->execute();
        return $db->loadAssocList();
    }

    /**
     * Plugin class function that create & update ciam profile of users
     * 
     * Create & update users ciam profile
     */
    public function onUserAfterSave($old, $isnew, $new) {
        if (JFactory::getApplication()->isAdmin()) {
            $settings = plgSystemCiamTools::getSettings();
            $accountObj = new LoginRadiusSDK\CustomerRegistration\Management\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            $mainframe = JFactory::getApplication();
            $cid = JRequest::getVar('cid');
            $loggeduser = JFactory::getUser();
            $messagedisplay = 0;
            $db = JFactory::getDBO();
         
            if ($old['block']) {  
                foreach ($cid as $key => $value):
                    $self = $loggeduser->id == $value;
                    if (!$self):
                        $uid = self::getUidbyId($value);
                        if (isset($uid) && !empty($uid)) {
                            try {
                                $data = array(
                                  'IsActive' => false
                                );
                                $response = $accountObj->update($uid, $data);                                
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
                    $mainframe->enqueueMessage($messagedisplay . ' ' . JText::_('COM_CIAM_USERS_DISABLED'));
                endif;
                   $mainframe->redirect('index.php?option=com_users&view=users');
            } else {            
                foreach ($cid as $key => $value):
                    $self = $loggeduser->id == $value;
                    if (!$self):
                        $uid = self::getUidbyId($value);
                        if (isset($uid) && !empty($uid)) {
                            try {
                                $data = array(
                                  'IsActive' => true
                                );
                                $response = $accountObj->update($uid, $data);
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
                    $mainframe->enqueueMessage($messagedisplay . ' ' . JText::_('COM_CIAM_USERS_ENABLE'), 'success');
                endif;
                  $mainframe->redirect('index.php?option=com_users&view=users');
            }
        }
    }


    function onUserAfterDelete($user, $success, $msg) {
        if (!$success) {
            return false;
        }
        if (JFactory::getApplication()->isAdmin()) {            
            $settings = plgSystemCiamTools::getSettings();
            $accountObj = new LoginRadiusSDK\CustomerRegistration\Management\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            $mainframe = JFactory::getApplication();
            $cid = JRequest::getVar('cid');
            $loggeduser = JFactory::getUser();
            $db = JFactory::getDBO();
            $messagedisplay = 0;
            $message = JText::_('COM_CIAM_USERS_NOTDELETED');
            $megtype = 'error';            

            foreach ($cid as $key => $value):
                $self = $loggeduser->id == $value;
                if (!$self):
                    $uid = self::getUidbyId($value);
                    if (isset($uid) && !empty($uid)) {
                        try {
                            $response = $accountObj->delete($uid);
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
                    $message = JText::_('COM_CIAM_USERS_DELETED');
                    $megtype = '';

                endif;
            endforeach;
            $mainframe->redirect('index.php?option=com_users&view=users', $message, $megtype);
        }
        return true;
    }
}
