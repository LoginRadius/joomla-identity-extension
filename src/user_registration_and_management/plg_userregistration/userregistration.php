<?php

/**
 * @package     UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
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
use \LoginRadiusSDK\LoginRadius;
use \LoginRadiusSDK\LoginRadiusException;
use \LoginRadiusSDK\CustomerRegistration\UserAPI;
use \LoginRadiusSDK\CustomerRegistration\AccountAPI;
use \LoginRadiusSDK\SocialLogin\SocialLoginAPI;

require_once(dirname(__FILE__) . DS . 'helper' . DS . 'helper.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'functions.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'userProfileData.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'postMessage.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'sendMessage.php');
require_once(dirname(__FILE__) . DS . 'customhttpclient.php');

global $apiClient_class;
$apiClient_class = 'CustomHttpClient';
/*
 * Class that indicates the plugin.
 * 
 */

class plgSystemUserRegistration extends JPlugin {

    /**
     * Plugin class function that calls on intialise plugin
     * 
     * @param $subject
     * @param $config
     */
    function plgSystemUserRegistration(&$subject, $config) {

        parent::__construct($subject, $config);
    }
       
    /**
     * Plugin class function that get raas option
     *
     */
    public static function getRaasOptions($settings) {
        if (isset($settings['apikey']) && !empty($settings['apikey'])) {
            $document = JFactory::getDocument();
            $script = '';
            if (isset($settings['LoginRadius_termsAndCondition']) && $settings['LoginRadius_termsAndCondition'] != '') {
                $termsCondition = preg_replace('/\n+/', '', $settings['LoginRadius_termsAndCondition']);
                $termsCondition = preg_replace('/\r+/', '', $termsCondition);
                $script .= 'raasoption.termsAndConditionHtml = "' . str_replace(array('<script>', '</script>'), '', $termsCondition) . '";';
            }
            if (isset($settings['LoginRadius_formRenderDelay']) && is_numeric($settings['LoginRadius_formRenderDelay']) != '0') {
                $script .= 'raasoption.formRenderDelay =  ' . $settings['LoginRadius_formRenderDelay'] . ';';
            }
            $min_length = isset($settings['LoginRadius_passwordMinLength']) ? $settings['LoginRadius_passwordMinLength'] : '';
            $max_length = isset($settings['LoginRadius_passwordMaxLength']) ? $settings['LoginRadius_passwordMaxLength'] : '';

            if (!empty($min_length) && !empty($max_length)) {
                $password_length = '{min:' . $min_length . ',max:' . $max_length . '}';
                $script .= 'raasoption.passwordlength = ' . $password_length . ';';
            }
            if (isset($settings['LoginRadius_GoogleRecapthaPublicKey']) && $settings['LoginRadius_GoogleRecapthaPublicKey'] != '') {
                $script .= 'raasoption.V2RecaptchaSiteKey = "' . $settings['LoginRadius_GoogleRecapthaPublicKey'] . '";';
            }
            if (isset($settings['LoginRadius_enableFormValidationMsg']) && $settings['LoginRadius_enableFormValidationMsg'] != '' && $settings['LoginRadius_enableFormValidationMsg'] != 'false') {
                $script .= 'raasoption.inFormvalidationMessage = ' . $settings['LoginRadius_enableFormValidationMsg'] . ';';
            }
            if (isset($settings['LoginRadius_forgotEmailTemplate']) && $settings['LoginRadius_forgotEmailTemplate'] != '') {
                $script .= 'raasoption.forgotPasswordTemplate = "' . $settings['LoginRadius_forgotEmailTemplate'] . '";';
            }

            $emailVerifyOpt = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';
            if (isset($emailVerifyOpt) && $emailVerifyOpt != '') {
                if ($emailVerifyOpt == '0') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'raasoption.enableLoginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_enablePromptPassword'] != '' && $settings['LoginRadius_enablePromptPassword'] != 'false') {
                        $script .= 'raasoption.promptPasswordOnSocialLogin = ' . $settings['LoginRadius_enablePromptPassword'] . ';';
                    } if ($settings['LoginRadius_enableLoginWithUsername'] != '' && $settings['LoginRadius_enableLoginWithUsername'] != 'false') {
                        $script .= 'raasoption.enableUserName = ' . $settings['LoginRadius_enableLoginWithUsername'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'raasoption.askEmailAlwaysForUnverified = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                }
                elseif ($emailVerifyOpt == '1') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'raasoption.enableLoginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'raasoption.askEmailAlwaysForUnverified = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                    $script .= 'raasoption.OptionalEmailVerification = true;';
                }
                elseif ($emailVerifyOpt == '2') {
                    $script .= 'raasoption.DisabledEmailVerification = true;';
                }
            }

            if (isset($settings['LoginRadius_emailVerificationTemplate']) && $settings['LoginRadius_emailVerificationTemplate'] != '') {
                $script .= 'raasoption.emailVerificationTemplate = "' . $settings['LoginRadius_emailVerificationTemplate'] . '";';
            }

            if (isset($settings['LoginRadius_customOption']) && $settings['LoginRadius_customOption'] != '') {
                $jsondata = self::lrRaasJsonValidate($settings['LoginRadius_customOption']);
                if (is_object($jsondata)) {
                    foreach ($jsondata as $key => $value) {
                        $script .= "raasoption." . $key . "=";
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

            if (JVERSION < 3) {
                $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.js');
                $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery-noconflict.js');
                $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.min.js');
            }
            else {
                $document->addScript(JUri::root(true) . '/media/jui/js/jquery.js');
                $document->addScript(JUri::root(true) . '/media/jui/js/jquery-noconflict.js');
                $document->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
            }

            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
            $document->addScript('//cdn.loginradius.com/hub/prod/js/LoginRadiusRaaS.js');
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.ui.core.min.js');
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.ui.datepicker.min.js');

            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.core.css');
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.theme.css');
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.datepicker.css');
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/LoginRadiusFrontEnd.min.js');
            $version = '3';
            if (JVERSION < 3) {
                $version = '2';
            }
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/lr_userregistration' . $version . '.min.css');
            $path = parse_url(JURI::base());
            $domain = $path['scheme'] . '://' . $path['host'];
            $emailVerificationUrl = $path['scheme'] . '://' . $path['host'] . $path['path'] . 'index.php';

            $loginFunction = 'var raasoption = {};
                var homeDomain = "' . JURI::base() . '";            
                var profileUrl = "' . JURI::getInstance()->toString() . '";            
                raasoption.appName = "' . $settings['sitename'] . '";
                raasoption.apikey = "' . $settings['apikey'] . '";
                raasoption.V2Recaptcha = true;
                raasoption.emailVerificationUrl = "' . $emailVerificationUrl . '";
                raasoption.forgotPasswordUrl = "' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=login') . '";
                raasoption.templatename = "loginradiuscustom_tmpl";
                raasoption.hashTemplate = true; 
                ' . $script . '
                jQuery(document).ready(function () {
                initializeResetPasswordRaasForm(raasoption); 
                });';
            $document->addScriptDeclaration($loginFunction);
        }
    }

    /**
     * Check String is json or not.
     *
     * @param $string 
     * @return json|string
     */
    function lrRaasJsonValidate($string) {
        $result = json_decode($string);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        }
        else {
            return $string;
        }
    }

    /**
     * Plugin class function that calls on after plugin intialise
     * 
     * Manage Authantication Process
     */
    function onAfterInitialise() {
        if (JRequest::getVar('loginRadiusReferralSkip') == 'Skip') {
            $mainframe = JFactory::getApplication();
            $settings = plgSystemUserRegistrationTools::getSettings();
            $raas_user = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            $accessToken = trim(JRequest::getVar('loginRadiusIdentifier'));
            try {
                $userProfile = $raas_user->getUserProfiledata($accessToken);
            }
            catch (LoginRadiusException $e) {

                if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                }
            }
            if (isset($userProfile->ID)) {
                if (JVERSION < 3) {
                    $dispatcher = JDispatcher::getInstance();
                }
                else {
                    $dispatcher = JEventDispatcher::getInstance();
                }
                $userId = $dispatcher->trigger('checkProviderId', array($userProfile));
                if (!empty($userId[0])) {
                    $redirect = plgSystemUserRegistrationTools::userLoginViaAjax($userId[0], $userProfile, false);
                    die(json_encode($redirect));
                }
            }
            else {
                $redirect = plgSystemUserRegistrationTools::getReturnURL();
                die(json_encode($redirect));
            }
        }
        elseif (JRequest::getVar('loginRadiusReferralSubmit') == 'Send Message') {
            $mainframe = JFactory::getApplication();
            $settings = plgSystemUserRegistrationTools::getSettings();
            $raas_user = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            $accessToken = trim(JRequest::getVar('loginRadiusIdentifier'));
            $provider = trim(JRequest::getVar('loginRadiusProvider'));

            if (!JRequest::getVar('loginRadiusContacts') || count(JRequest::getVar('loginRadiusContacts')) <= 0) {
                plgSystemUserRegistrationSendMessage::friendInvitePopup($provider, $accessToken, 'error', 'Please select contacts to send referral to.', $settings);
                return;
            }
            $contacts = JRequest::getVar('loginRadiusContacts');
            plgSystemUserRegistrationSendMessage::sendMessageToSelctedContacts($contacts, $provider, $accessToken);

            try {
                $userProfile = $raas_user->getUserProfiledata($accessToken);
            }
            catch (LoginRadiusException $e) {
                if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                }
            }

            if (isset($userProfile->ID)) {
                if (JVERSION < 3) {
                    $dispatcher = JDispatcher::getInstance();
                }
                else {
                    $dispatcher = JEventDispatcher::getInstance();
                }
                $userId = $dispatcher->trigger('checkProviderId', array($userProfile));
                if (!empty($userId[0])) {
                    $redirect = plgSystemUserRegistrationTools::userLoginViaAjax($userId[0], $userProfile, false);
                }
            }
            die(json_encode($redirect));
        }

        // Get module configration option value    
        $language = JFactory::getLanguage();
        $mainframe = JFactory::getApplication();
        $app = JFactory::getApplication();

        if (JFactory::getApplication()->isSite()) {
            $jinput = JFactory::getApplication()->input;
            $baseuri = $jinput->server->get('REQUEST_URI', '', '');
            $uri = basename(parse_url($baseuri, PHP_URL_PATH));

            if ($uri == 'log-out') {
                $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=login'));
            }
            $view = $app->input->get('view');
            if (!JFactory::getUser()->id) {
                if (in_array($view, array('reset', 'remind'))) {
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=forgotpassword'));
                }
                elseif (in_array($view, array('registration', 'login'))) {
                    if ($view == 'registration') {
                        $view = 'register';
                    }
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=' . $view));
                }
            }
            else {
                $option = $app->input->get('option');
                switch ($option) {
                    case 'com_users':
                        $mainframe = JFactory::getApplication();
                        if ($view == 'login') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=login'));
                        }
                        elseif ($view == 'registration') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=register'));
                        }
                        elseif (in_array($view, array('remind', 'reset'))) {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=password'));
                        }
                        elseif ($view == 'profile') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'));
                        }
                        break;
                }
            }
        }

        $language->load('com_users');
        $language->load('com_userregistrationandmanagement', JPATH_ADMINISTRATOR);
        $settings = plgSystemUserRegistrationTools::getSettings();
        // Retrieve data from LoginRadius.  
        if (isset($settings['apikey']) && isset($settings['apisecret'])) {

            $token = JRequest::getVar('token');
            if (!empty($token)) {

                $loginRadiusObject = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
                try {
                    $result_accesstoken = $loginRadiusObject->exchangeAccessToken($token);
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

                if (isset($accessToken) && $accessToken !== false) {
                    try {
                        $userProfileObject = $loginRadiusObject->getUserProfiledata($accessToken);
                    }
                    catch (LoginRadiusException $e) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                    }
                    $userProfile = plgSystemUserRegistrationTools::manageUserProfileData($userProfileObject, $accessToken);
                 
                    if (JFactory::getUser()->id) {
                        $checkId = self::checkProviderIdOnLogin($userProfile, JFactory::getUser()->id);

                        if (empty($checkId)) {
                            plgSystemUserRegistrationTools::accountMapping($userProfile);
                        }
//                        else {                      
//                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_ID'), 'error');
//                        }
                    }
                    else {
                        $userid = self::checkUid($userProfile);
                        if (!empty($userid)) {
                           
                            plgSystemUserRegistrationTools::insertSocialData($userid, $userProfile);
                            plgSystemUserRegistrationTools::loginExistUser($userid, $userProfile, $accessToken, false);
                        }
                        else {

                            if (isset($userProfileObject->ID)) {
                                $userId = self::checkProviderId($userProfile);
                             
                                if (!empty($userId)) {                                 
                                    if (!plgSystemUserRegistrationTools::checkActivateUser($userProfile->ID) && !plgSystemUserRegistrationTools::checkBlockUser($userProfile->ID)) {
                                        plgSystemUserRegistrationTools::loginExistUser($userId, $userProfile, $accessToken, false);
                                    }
                                }
                                else {
                                    plgSystemUserRegistrationTools::accountLogginProcess($userProfile, $accessToken);
                                }
                            }
                        }
                    }
                }
            }
            else {
                $post_value = JRequest::get('post');

                if (JFactory::getApplication()->isSite()) {
                    $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));

                    if (isset($post_value['emailid']) && !empty($post_value['emailid']) && isset($post_value['password']) && !empty($post_value['password'])) {
                        //set password
                        $raas_data = self::lrRaasGetRaasData(JFactory::getUser()->id);
                        $param = array(
                          'accountid' => (isset($raas_data[0]->Uid) ? trim($raas_data[0]->Uid) : ''),
                          'password' => (isset($post_value['password']) ? trim($post_value['password']) : ''),
                          'emailid' => (isset($post_value['emailid']) ? trim($post_value['emailid']) : '')
                        );
                        try {
                            $result = $raas_account->createUserRegistrationProfile($param);
                            try {
                                $response = $raas_account->getAccounts($raas_data[0]->Uid);
                                $raasId = '';
                                $raasUname = '';
                                foreach ($response as $k => $val) {
                                    if (isset($val->Provider) && strtolower($val->Provider) == 'raas') {
                                        $raasId = $val->ID;
                                        $raasUname = $val->UserName;
                                        break;
                                    }
                                }
                                self::lrSocialLoginInsertIntoMappingTable($raas_data[0]->id, $raasId, 'RAAS', $raas_data[0]->Uid, $raas_data[0]->lr_picture);
                                if (isset($result->isPosted) && $result->isPosted) {

                                    if (empty($raasUname)) {
                                        $db = JFactory::getDbo();
                                        $query = $db->getQuery(true);
                                        $query->select('username')
                                            ->from('#__users')
                                            ->where($db->quoteName('id') . " = " . $db->quote(JFactory::getUser()->id));
                                        $db->setQuery($query);
                                        $provider_uname = $db->loadResult();

                                        $params = array(
                                          'UserName' => $provider_uname
                                        );

                                        $query = $db->getQuery(true);
                                        $query->select('LoginRadius_id')
                                            ->from('#__loginradius_users')
                                            ->where($db->quoteName('id') . " = " . $db->quote(JFactory::getUser()->id) . " AND " . $db->quoteName('provider') . " = " . $db->quote('RAAS'));

                                        $db->setQuery($query);
                                        $provider_user_id = $db->loadResult();

                                        if (count($params) > 0) {
                                            try {
                                                $userObject = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
                                                $userObject->edit($provider_user_id, $params);
                                            }
                                            catch (LoginRadiusException $e) {
                                                $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                                            }
                                        }
                                    }
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_SET'), 'message');
                                }
                                else {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_NOT_SET'), 'error');
                                }
                            }
                            catch (LoginRadiusException $e) {
                                $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->message, 'error');
                            }
                        }
                        catch (LoginRadiusException $e) {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->message, 'error');
                        }
                    }
                    elseif (isset($post_value['newpassword']) && !empty($post_value['newpassword'])) {
                        $socialloginId = isset($post_value['raasid']) ? trim($post_value['raasid']) : '';
                        $oldpassword = isset($post_value['oldpassword']) ? trim($post_value['oldpassword']) : '';
                        $newpassword = isset($post_value['newpassword']) ? trim($post_value['newpassword']) : '';
                        if (!empty($socialloginId)) {
                            $Uid = $this->getUidbySocialId(JFactory::getUser()->id, $socialloginId);
                            if ($Uid) {
                                try {
                                    $result = $raas_account->changeAccountPassword($Uid, $oldpassword, $newpassword);
                                    if (isset($result->isPosted) && $result->isPosted) {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_CHANGE'), 'message');
                                    }
                                    else {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_NOT_CHANGE'), 'error');
                                    }
                                }
                                catch (LoginRadiusException $e) {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->message, 'error');
                                }
                            }
                        }
                        else {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                        }
                    }
                    elseif (isset($post_value['value']) && $post_value['value'] == 'accountUnLink') {
                        $provider = isset($post_value['provider']) ? trim($post_value['provider']) : '';
                        $providerId = isset($post_value['providerId']) ? trim($post_value['providerId']) : '';
                        if (!empty($provider) && !empty($providerId)) {
                            $Uid = $this->getUidbySocialId(JFactory::getUser()->id, $providerId);
                            if ($Uid) {
                                try {
                                    $result = $raas_account->accountUnlink($Uid, $providerId, $provider);
                                    if (isset($result->isPosted) && $result->isPosted) {
                                        $db = JFactory::getDBO();
                                        $query = $db->getQuery(true);
                                        $query->delete($db->quoteName('#__loginradius_users'));
                                        $query->where($db->quoteName('id') . " = " . $db->quote(JFactory::getUser()->id) . " AND " . $db->quoteName('LoginRadius_id') . " = " . $db->quote($providerId));
                                        $db->setQuery($query);
                                        $db->execute();
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_UNLINK_ID'), 'message');
                                    }
                                    else {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                                    }
                                }
                                catch (LoginRadiusException $e) {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), $e->getErrorResponse()->message, 'error');
                                }
                            }
                        }
                        else {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                        }
                    }
                    else {
                        plgSystemUserRegistrationTools::popupHandler();
                    }
                }


                if (isset($_GET['lrmessage']) && !empty($_GET['lrmessage'])) {                   
                    $mainframe = JFactory::getApplication();
                    $path = parse_url(JURI::base());
                    $message = $_COOKIE['lr_message'];
                    $response = $_GET['response'];
                    $returnUrl = $path['scheme'] . '://' . $path['host'] . $path['path'] . 'index.php';
                    setcookie("lr_message", "", time()-3600, "/");
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
                            $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
                            $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js');
                            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
                            $document->addScript('//cdn.loginradius.com/hub/prod/js/LoginRadiusRaaS.js');
                            //$document->addScript('components/com_userregistrationandmanagement/assets/js/LoginRadiusFrontEnd.min.js');
                            //  set message in cookie
                            self::getRaasOptions($settings);
                            ?><div id="resetpassword-container" style="display: none"></div>
                            <?php
                        }
                    }
                }
                $action_completed = JRequest::getVar('action_completed');
                if ($action_completed == 'register') {                   
                    $mainframe = JFactory::getApplication();
                    $path = parse_url(JURI::base());
                    $returnUrl = $path['scheme'] . '://' . $path['host'] . $path['path'] . 'index.php';
                    $mainframe->redirect($returnUrl, JText::_('COM_HOSTED_PAGE_REGISTER_MSG'), 'message');
                }
                elseif ($action_completed == 'forgotpassword') {                
                    $mainframe = JFactory::getApplication();
                    $path = parse_url(JURI::base());
                    $returnUrl = $path['scheme'] . '://' . $path['host'] . $path['path'] . 'index.php';
                    $mainframe->redirect($returnUrl, JText::_('COM_HOSTED_PAGE_FORGOT_MSG'), 'message');
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

    function getUidbySocialId($userId, $socialloginId) {
        $session = JFactory::getSession();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('Uid'))
            ->from($db->quoteName('#__loginradius_users'))
            ->where($db->quoteName('LoginRadius_id') . " = " . $db->quote($socialloginId) . " AND " . $db->quoteName('id') . " = " . $db->quote($userId));
        $db->setQuery($query);
        $db->execute();
        $Uid = $db->loadAssocList();
        return isset($Uid[0]['Uid']) ? trim($Uid[0]['Uid']) : $session->get('Uid');
    }

    function getUidbyId($userId) {
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

    function onUserAfterDelete($user, $success, $msg) {
        if (!$success) {
            return false;
        }
        $userId = JArrayHelper::getValue($user, 'id', 0, 'int');
        if ($userId) {
            $settings = plgSystemUserRegistrationTools::getSettings();
            $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            try {
                $Uid = $this->getUidbyId($userId);
                $response = $raas_account->deleteAccount($Uid);
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->delete($db->quoteName('#__loginradius_users'));
                $query->where($db->quoteName('id') . " = " . $db->quote($userId));
                $db->setQuery($query);
                $db->execute();
            }
            catch (LoginRadiusException $e) {
                if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {
                    $this->_subject->setError($e->getErrorResponse()->message);
                }
                else {
                    $this->_subject->setError(JText::_('COM_SOCIALLOGIN_USERS_NOTDELETED'));
                }
                return false;
            }
        }
        return true;
    }

    function onloginRadiusUserSave($data) {
        $output['status'] = 'error';
        $output['message'] = 'An error occurred';
        $user = JUser::getInstance($data['id']);
        $settings = plgSystemUserRegistrationTools::getSettings();

        $raas_user = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
        $socialProfile = $this->getSocialProfilebyUserId($user->id);

        $raasUserId = isset($socialProfile[0]['LoginRadius_id']) ? $socialProfile[0]['LoginRadius_id'] : '';
        $name = explode(' ', $data['name']);
        $params = array(
          'firstname' => isset($name[0]) ? $name[0] : '',
          'lastname' => isset($name[1]) ? $name[1] : ''
        );

        try {
            $response = $raas_user->edit($raasUserId, $params);
            $output['status'] = 'message';
            $output['message'] = JText::_('COM_USER_REGISTRATION_CHANGE_PROFILE_MSG_FRONT');
            // Remove special char if have.
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

    function lrRaasGetRaasData($acid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('lu.id', 'lu.Uid', 'lu.lr_picture', 'u.email'))
            ->from($db->quoteName('#__loginradius_users', 'lu'))
            ->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('u.id') . " = " . $db->quote($acid));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Insert loginradius mapping data into database.
     *
     * @param string $acid Account ID
     * @param string $responseId Provider ID
     * @param string $provider Social Network
     * @param int $uid User UID
     * @param int $lrpic User image
     */
    function lrSocialLoginInsertIntoMappingTable($acid, $responseId, $provider, $uid, $lrpic) {
        $db = JFactory::getDbo();
        $columns = array('id', 'LoginRadius_id', 'provider', 'Uid', 'lr_picture');
        $values = array($db->quote($acid), $db->quote($responseId), $db->quote($provider), $db->quote($uid), $db->quote($lrpic));
        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__loginradius_users'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Check Uid exist.
     *
     * @param userProfile
     */
    function checkUid($userProfile) {
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
    function checkProviderId($userProfile) {
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

    function getSocialProfilebyUserId($userId) {
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
     * Plugin class function that  Check social id exist
     *    
     */
    function checkProviderIdOnLogin($userProfile, $userid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.LoginRadius_id') . " = " . $db->quote($userProfile->ID) . " AND " . $db->quoteName('lu.id') . " = " . $db->quote($userid));

        $db->setQuery($query);
        return $db->loadResult();
    }


    /**
     * Plugin class function that create & update raas profile of users
     * 
     * Create & update users raas profile
     */
    public function onUserBeforeSave($old, $isnew, $new) {
        $mainframe = JFactory::getApplication();
        if (JFactory::getApplication()->isAdmin()) {
            $settings = plgSystemUserRegistrationTools::getSettings();
            $userObj = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            $accountObj = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            if ($isnew) {
                if (isset($new['password2']) && !empty($new['password2'])) {
                    $params = array(
                      'emailid' => $new['email'],
                      'FirstName' => $new['name'],
                      'UserName' => $new['username'],
                      'password' => $new['password2']
                    );
                    try {
                        $response = $userObj->create($params);
                    }
                    catch (LoginRadiusException $e) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                    }
                    if (isset($response) && $response != '') {
                        try {
                            $fulldomainname = JURI::root() . 'index.php/component/userregistrationandmanagement/login';
                            $validate_url = 'https://api.loginradius.com/raas/client/password/forgot?apikey=' . rawurlencode(trim($settings['apikey'])) . '&emailid=' . rawurlencode(trim($new['email'])) . '&resetpasswordurl=' . $fulldomainname;
                            $result = LoginRadius::apiClient($validate_url, FALSE, array('output_format' => 'json'));
                        }
                        catch (LoginRadiusException $e) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                        }
                    }
                }
            }
            else {
                $socialProfile = $this->getSocialProfilebyUserId($old['id']);
                $raasUserId = isset($socialProfile[0]['LoginRadius_id']) ? $socialProfile[0]['LoginRadius_id'] : '';
                $raasUid = isset($socialProfile[0]['Uid']) ? $socialProfile[0]['Uid'] : '';

                if ($new['email'] != $old['email']) {
                    $newEmail = array(
                      'emailid' => $new['email'],
                      'emailType' => 'Primary'
                    );

                    try {
                        $result = $accountObj->userAdditionalEmail($raasUid, 'add', $newEmail);
                        try {
                            $oldEmail = array(
                              'emailid' => $old['email'],
                              'emailType' => 'Primary'
                            );
                            $response = $accountObj->userAdditionalEmail($raasUid, 'remove', $oldEmail);
                        }
                        catch (LoginRadiusException $e) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                        }
                    }
                    catch (LoginRadiusException $e) {
                        if (isset($e->getErrorResponse()->message) && $e->getErrorResponse()->message) {

                            try {
                                $returndata = $accountObj->getAccounts($raasUid);
                                $status = '';
                                foreach ($returndata as $key => $value) {
                                    if ($value->Provider == 'RAAS') {
                                        $status = 'true';
                                    }
                                }
                                if (isset($status) && $status == 'true') {
                                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                                }
                                else {
                                    $mainframe->enqueueMessage(JText::_('COM_USER_REGISTRATION_CHANGE_EMAIL_MSG'), 'error'); //                               
                                    $mainframe->redirect(JURI::root() . 'administrator/index.php?option=com_userregistrationandmanagement&view=usermanager');
                                    exit();
                                }
                            }
                            catch (LoginRadiusException $e) {
                                $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                            }
                            $mainframe->redirect(JURI::root() . 'administrator/index.php?option=com_userregistrationandmanagement&view=usermanager');
                            exit();
                        }
                    }
                }
                if ($new['username'] != $old['username']) {
                    $returndata = $accountObj->getAccounts($raasUid);
                    $status = '';
                    $raas_provider_id = '';
                    foreach ($returndata as $k => $val) {
                        if (isset($val->Provider) && strtolower($val->Provider) == 'raas') {
                            $raas_provider_id = $val->ID;
                            $status = 'true';
                            break;
                        }
                    }

                    if ($raas_provider_id) {
                        $params = array(
                          'UserName' => $new['username']
                        );

                        try {
                            $userObj->edit($raas_provider_id, $params);
                        }
                        catch (LoginRadiusException $e) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                            $mainframe->redirect(JURI::root() . 'administrator/index.php?option=com_userregistrationandmanagement&view=usermanager');
                            exit();
                        }
                    }
                    else {
                        $mainframe->enqueueMessage(JText::_('COM_USER_REGISTRATION_CHANGE_USERNAME_MSG'), 'error');
                        $mainframe->redirect(JURI::root() . 'administrator/index.php?option=com_userregistrationandmanagement&view=usermanager');
                        exit();
                    }
                }
                if ($new['name'] != $old['name']) {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('LoginRadius_id')
                        ->from('#__loginradius_users')
                        ->where($db->quoteName('id') . " = " . $db->quote($new['id']) . " AND " . $db->quoteName('provider') . " = " . $db->quote('RAAS'));

                    $db->setQuery($query);
                    $provider_user_id = $db->loadResult();
                    $params = array(
                      'FirstName' => $new['name']
                    );
                    if ($provider_user_id) {
                        try {
                            $response = $userObj->edit($provider_user_id, $params);
                        }
                        catch (LoginRadiusException $e) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
                        }
                    }
                    else {
                        $mainframe->enqueueMessage(JText::_('COM_USER_REGISTRATION_CHANGE_PROFILE_MSG'), 'error');
                        $mainframe->redirect(JURI::root() . 'administrator/index.php?option=com_userregistrationandmanagement&view=usermanager');
                        exit();
                    }
                }
            }
        }
    }

    public function onContentPrepareForm($form, $data) {
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
        if ($app->isAdmin()) {
            if ($option == 'com_users' && $view == 'user') {
                $form->load('<form>
                        <fields name="attribs">
                            <fieldset name="simplifiedsocialshare" label="PLG_CONTENT_SOCIAL_SHARE_FIELDSET_LABEL">
                            <field name="share_enable" type="list" description="PLG_CONTENT_SOCIAL_SHARE_FIELD_ENABLE_DESC" translate_description="true" label="PLG_CONTENT_SOCIAL_SHARE_FIELD_ENABLE_LABEL" translate_label="true" size="7" filter="cmd">
                                <option value="">JGLOBAL_USE_GLOBAL</option>
                                <option value="0">JHIDE</option>
                            </field>
                            </fieldset>
                        </fields>
                    </form>');
                $document = JFactory::getDocument();
                $document->addScriptDeclaration("jQuery(document).ready(function(){jQuery('#jform_requireReset-lbl').parent().parent().hide();});");
                $data->requireReset = '0';
            }
        }
        return true;
    }

    public function onUserAfterSave($old, $isnew, $new) {
        if (JFactory::getApplication()->isAdmin()) {
            $mainframe = JFactory::getApplication();
            $settings = plgSystemUserRegistrationTools::getSettings();
            $raas_user = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            try {
                $response = $raas_user->getProfileByEmail($old['email']);
            }
            catch (LoginRadiusException $e) {
                $mainframe->enqueueMessage($e->getErrorResponse()->message, 'error');
            }
            if (isset($response[0]->Uid)) {
                $profile = array(
                  'Uid' => $response[0]->Uid,
                  'ID' => $response[0]->ID,
                  'Provider' => $response[0]->Provider,
                  'thumbnail' => $response[0]->ThumbnailImageUrl
                );
                $userProfile = (object) $profile;
                plgSystemUserRegistrationTools::insertSocialData($old['id'], $userProfile);
            }
        }
    }
}
