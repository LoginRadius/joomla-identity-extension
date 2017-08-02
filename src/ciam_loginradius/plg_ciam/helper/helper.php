<?php

/**
 * @package      CIAM.Plugin
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.user.helper');
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'LoginRadiusException.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Utility' . DS . 'Functions.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Utility' . DS . 'SOTT.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Social' . DS . 'SocialLoginAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Authentication' . DS . 'UserAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'CustomerRegistration' . DS . 'Management' . DS . 'AccountAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'IHttpClient.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'ciam' . DS . 'LoginRadiusSDK' . DS . 'Clients' . DS . 'DefaultHttpClient.php');

use \LoginRadiusSDK\Utility\Functions;
use \LoginRadiusSDK\LoginRadiusException;
use \LoginRadiusSDK\Clients\IHttpClient;
use \LoginRadiusSDK\Clients\DefaultHttpClient;
use \LoginRadiusSDK\Utility\SOTT;
use \LoginRadiusSDK\CustomerRegistration\Social\SocialLoginAPI;
use \LoginRadiusSDK\CustomerRegistration\Authentication\UserAPI;
use \LoginRadiusSDK\CustomerRegistration\Management\AccountAPI;

/**
 * User Registration plugin helper class.
 */
class plgSystemCiamTools {

    /**
     * 
     * @param type $entry
     * @param type $categories
     * @param type $priorities
     */
    public static function debug($entry, $categories = 'CURL', $priorities = JLog::ALL) {
        $options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
        $options['text_file'] = 'loginradius.log.php';
        JLog::addLogger($options, $priorities, array($categories));
        JLog::add("\r\n" . 'API URL = ' . $entry['url'] . "\r\n" . 'API Status = ' . $entry['statuscode'] . "\r\n" . 'API Responce = ' . $entry['response'] . "\r\n", $priorities, $categories);
    }

    
    /**
     * manage User Profile data
     * 
     * @param $userProfile
     * @return mixed
     */
    public static function manageUserProfileData($userProfile, $accessToken) {
        $userProfile->session = uniqid('LoginRadius_', true);
        $userProfile->ID = self::checkVariable($userProfile, 'ID');
        $userProfile->Provider = self::checkVariable($userProfile, 'Provider');
        $userProfile->FirstName = self::checkVariable($userProfile, 'FirstName');
        $userProfile->LastName = self::checkVariable($userProfile, 'LastName');
        $userProfile->FullName = self::checkVariable($userProfile, 'FullName');
        $userProfile->NickName = self::checkVariable($userProfile, 'NickName');
        $userProfile->ProfileName = self::checkVariable($userProfile, 'ProfileName');
        $userProfile->dob = self::checkVariable($userProfile, 'BirthDate');
        $userProfile->gender = self::checkVariable($userProfile, 'Gender');
        $userProfile->email = (sizeof($userProfile->Email) > 0 ? trim($userProfile->Email[0]->Value) : "");
        $userProfile->thumbnail = self::checkVariable($userProfile, 'ImageUrl');
        $userProfile->address1 = self::checkVariable($userProfile, 'Addresses');
        $userProfile->thumbnail = self::manageFacebookUserAvatar($userProfile->ID, $userProfile->thumbnail, $userProfile->Provider);
        $userProfile->accessToken = $accessToken;
        if (empty($userProfile->address1)) {
            $userProfile->address1 = self::checkVariable($userProfile, 'MainAddress');
        }

        $userProfile->address2 = $userProfile->address1;

        $userProfile->city = self::checkVariable($userProfile, 'City');
        if (empty($userProfile->city)) {
            $userProfile->city = self::checkVariable($userProfile, 'HomeTown');
        }

        $userProfile->country = self::checkVariable($userProfile, 'Country');
        if (!empty($userProfile->country)) {
            $userProfile->country = self::checkVariable($userProfile, 'Country->Name');
        }

        $userProfile->aboutme = self::checkVariable($userProfile, 'About');
        $userProfile->website = self::checkVariable($userProfile, 'ProfileUrl');
        $userProfile->dob = self::calculateBirthDate($userProfile->dob);
        return $userProfile;
    }

    /**
     * get facebook user avatar if not found in profile data
     * 
     * @param $socialId
     * @param $thumbnail
     * @param $provider
     * @return string
     */
    private static function manageFacebookUserAvatar($socialId, $thumbnail, $provider) {

        if (empty($thumbnail) && $provider == 'facebook') {
            $thumbnail = "http://graph.facebook.com/" . $socialId . "/picture?type=square";
        }
        return $thumbnail;
    }

    /**
     * inisialize user profile variables 
     * 
     * @param type $userProfile
     * @param type $key
     * @param type $defaultValue
     * @return type
     */
    public static function checkVariable($userProfile, $key, $defaultValue = '') {
        $variable = isset($userProfile->$key) && $userProfile->$key != "unknown" ? $userProfile->$key : $defaultValue;

        if (!is_array($variable) && !is_object($variable)) {
            return trim($variable);
        }
        return $variable;
    }

    /**
     * get birthdate from user profile in a formate
     * 
     * @param type $birthDate
     * @return type
     */
    private static function calculateBirthDate($birthDate) {
        return date('Y-m-d', strtotime($birthDate));
    }

    /**
     * Function that inserting data in cb table.
     * 
     * @param string $thumbnail
     * @param type $socialId
     * @return type
     */
    public static function addNewIdImage($thumbnail, $socialId) {
        if (empty($thumbnail)) {
            $thumbnail = JURI::root() . 'media' . DS . 'com_ciamloginradius' . DS . 'images' . DS . 'noimage.png';
        }

        $userImage = $socialId . '.jpg';
        $find = strpos($userImage, 'http');

        if ($find !== false) {
            $userImage = plgSystemCiamFunctions::removeUnescapedChar(substr($userImage, 8));
        }

        $path = JPATH_ROOT . DS . 'images' . DS . 'sociallogin' . DS;
        self::insertUserPicture($path, $thumbnail, $userImage);

        return $userImage;
    }

    /**
     * Function getting redirection after user login.
     * 
     * @param type $path
     * @param type $profileImage
     * @param type $userImage
     */
    public static function insertUserPicture($path, $profileImage, $userImage) {
        $settings = self::getSettings();
        if (!JFolder::exists($path)) {
            JFolder::create($path);
        }
        if ($settings['useapi'] == 1) {
            $curlHandle = curl_init($profileImage);
            $fp = fopen($path . $userImage, 'wb');
            curl_setopt($curlHandle, CURLOPT_FILE, $fp);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($curlHandle);
            curl_close($curlHandle);
            fclose($fp);
        }
        else {
            $thumbImage = @file_get_contents($profileImage);
            if (@$http_response_header == NULL) {
                $profileImage = str_replace('https', 'http', $profileImage);
                $thumbImage = @file_get_contents($profileImage);
            }
            if (empty($thumbImage)) {
                $thumbImage = @file_get_contents(JURI::root() . 'media' . DS . 'com_ciamloginradius' . DS . 'images' . DS . 'noimage.png');
            }
            $thumbFile = $path . $userImage;
            @file_put_contents($thumbFile, $thumbImage);
        }
    }

    /**
     * Get the database settings.
     * 
     * @return type
     */
    public static function getSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__loginradius_settings');
        $db->setQuery($query);
        $rows = $db->LoadAssocList();

        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data ['value'];
            }
        }

        return $settings;
    }

    /**
     * Get the database settings.
     * 
     * @return type
     */
    public static function getAdvanceSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__loginradius_advanced_settings');
        $db->setQuery($query);
        $rows = $db->LoadAssocList();
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data ['value'];
            }
        }

        return $settings;
    }

    /**
     * send mail from joomla 
     * 
     * @param type $user
     * @param type $userMessage
     * @return boolean
     */
    public static function sendMail(&$user, $userMessage) {
        return false;
    }

    /**
     * Function that remove unescaped char from string.
     * 
     * @param $userId
     * @param $profileDataObject
     * @param $newUser
     */
    public static function loginExistUser($userId, $profileDataObject, $accessToken, $newUser) {
        $settings = self::getSettings();
        $username = self::lrCiamUsernameOption($settings, $profileDataObject);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.username', 'u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('LEFT', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.Uid') . " = " . $db->quote($profileDataObject->Uid));
        $db->setQuery($query);
        $userdata = $db->loadObjectList();
        if (isset($username) && $username != '') {
            if (isset($userdata) && !empty($userdata)) {
                if ($userdata[0]->username != $username) {
                    $query = $db->getQuery(true);
                    $fields = array(
                      $db->quoteName('username') . ' = ' . $db->quote($username)
                    );
                    $conditions = array(
                      $db->quoteName('id') . ' = ' . $db->quote($userdata[0]->id)
                    );

                    $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }

        if (!$newUser) {
            plgSystemCiamTools::updateUserProfile($userId, $profileDataObject, $accessToken);
        }

        self::userLogin($userId, $profileDataObject, $newUser);
    }

    public static function lrCiamUsernameOption($settings, $userprofile) {
        $enableUname = isset($settings['LoginRadius_enableLoginWithUsername']) ? $settings['LoginRadius_enableLoginWithUsername'] : '';
        $emailVerifyOption = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';

        if (isset($emailVerifyOption) && $emailVerifyOption == '1') {
            $enableUname = 'false';
        }
        if (isset($enableUname) && $enableUname == 'true') {
                $username = '';          
                if ($userprofile->Provider == 'Email' && $userprofile->UserName != '') {
                    $username = $userprofile->UserName;
                }      
        }
        return $username;
    }

    /**
     * provide session to user
     * 
     * @param type $userId
     * @param type $userProfile
     * @param type $newUser
     * @return type
     */
    public static function userLogin($userId, $userProfile, $newUser) {
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();
        $user = JUser::getInstance((int) $userId);
        if ($user->get('block') == '1') {            
            $mainframe->redirect(JURI::base(), JText::_('user is block'), 'error');          
        }
        // Register session variables
        $session = JFactory::getSession();
        $query = $db->getQuery(true);
        $query->select('Uid, lr_picture')
            ->from('#__loginradius_users')
            ->where($db->quoteName('LoginRadius_id') . " = " . $db->quote($userProfile->ID) . " AND " . $db->quoteName('id') . " = " . $db->quote($user->get('id')));

        $db->setQuery($query);
        $getUserData = $db->loadObjectList();
        
        $settings = self::getSettings();
        $socialLoginObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
        try {
            $socialProfileData = $socialLoginObject->getUserProfiledata($_SESSION['result_accesstoken']);
        }
        catch (LoginRadiusException $e) {
   
        }      
        

        $session->set('user_picture', $getUserData[0]->lr_picture);
        $session->set('Uid', $getUserData[0]->Uid);
        $session->set('user_lrid', $socialProfileData->ID);
        $session->set('emailVerified', $userProfile->EmailVerified);
        $session->set('provider', $userProfile->Provider);
        $session->set('user', $user);
        // Getting the session object
        $table = JTable::getInstance('session');
        $table->load($session->getId());
        $table->guest = '0';
        $table->username = $user->get('username');
        $table->userid = intval($user->get('id'));
        $table->usertype = $user->get('usertype');
        $table->gid = $user->get('gid');
        $table->update();
        $user->setLastVisit();
        //Redirect after Login
        $redirect = self::getReturnURL($newUser);
        $settings = self::getSettings();
        $mainframe->redirect($redirect);
    }

    /**
     * redirect user on give url
     * 
     * @param bool $returnUser
     * @return mixed|null|string
     */
    public static function getReturnURL($returnUser = false) {
        $app = JFactory::getApplication();
        $router = $app->getRouter();
        $settings = self::getSettings();
        $checkRewrite = $app->getCfg('sef_rewrite');
        $db = JFactory::getDbo();
        $url = null;
        $redirection = $settings['loginredirection'];
        if ($returnUser) {
            $redirection = $settings['loginredirection'];
        }
        if ($redirection) {
          
            if (!empty($_SESSION['redirect_to'])) {
                $url = $_SESSION['redirect_to'];
            }
            else if ($redirection == "referer") {
                if (!empty($_SESSION['referer_url'])) {
                    if (strpos($_SESSION['referer_url'], 'logout') === FALSE) {
                        $url = $_SESSION['referer_url'];
                    }
                }
            }
            else if ($router->getMode() == JROUTER_MODE_SEF) {
                $query = $db->getQuery(true);
                $query->select('path')
                    ->from('#__menu')
                    ->where($db->quoteName('id') . " = " . $db->quote($redirection));
                $db->setQuery($query);
                $url = $db->loadResult();
                if ($checkRewrite == '0' AND ! empty($url)) {
                    $url = 'index.php/' . $url;
                }
            }
            else {
                $query = $db->getQuery(true);
                $query->select('link')
                    ->from('#__menu')
                    ->where($db->quoteName('id') . " = " . $db->quote($redirection));
                $db->setQuery($query);
                $url = $db->loadResult();
            }
        }
        if (!$url) {
            // stay on the same page
            $uri = clone JFactory::getURI();
            $vars = $router->parse($uri);
            unset($vars['lang']);
            if (!empty($_SESSION['redirect_to'])) {
                $url = $_SESSION['redirect_to'];
            }elseif ($router->getMode() == JROUTER_MODE_SEF) {
                if (isset($vars['Itemid'])) {
                    $itemId = $vars['Itemid'];
                    $menu = $app->getMenu();
                    $item = $menu->getItem($itemId);
                    unset($vars['Itemid']);
                    if (isset($item) && $vars == $item->query) {

                        $query = $db->getQuery(true);
                        $query->select('path')
                            ->from('#__menu')
                            ->where($db->quoteName('id') . " = " . $db->quote($itemId) . " AND " . $db->quoteName('home') . " = " . $db->quote('1'));


                        $db->setQuery($query);
                        $homeUrl = $db->loadResult();
                        if ($homeUrl) {
                            $url = 'index.php';
                        }
                        else {
                            $query = $db->getQuery(true);
                            $query->select('path')
                                ->from('#__menu')
                                ->where($db->quoteName('id') . " = " . $db->quote($itemId));

                            $db->setQuery($query);
                            $url = $db->loadResult();
                            if ($checkRewrite == '0' AND ! empty($url)) {
                                $url = 'index.php/' . $url;
                            }
                        }
                    }
                    else {
                        // get article url path
                        $articlePath = JFactory::getURI()->getPath();
                        $url = $articlePath;
                    }
                }
                else {
                    $articlePath = JFactory::getURI()->getPath();
                    $url = $articlePath;
                }
            }
            else {
                $jinput = JFactory::getApplication()->input;
                $http_referer = $jinput->server->get('HTTP_REFERER', '', '');
                $query_string = $jinput->server->get('QUERY_STRING', '', '');

                $fullUrl = urldecode($http_referer);
                if (strpos($fullUrl, "callback=") > 0) {
                    $urlData = explode("callback=", $fullUrl);
                    $tampPos = strpos($urlData[1], "&");
                    $endLimit = strlen($urlData[1]);
                    if ($tampPos > 0) {
                        if (strpos($query_string, '&') > 0) {
                            $url = 'index.php?' . $query_string;
                        }
                        else {
                            $url = 'index.php?' . $query_string . substr($urlData[1], $tampPos, $endLimit);
                        }
                    }
                }
            }
        }
        return $url;
    }

    /**
     * check email exist in local db
     * 
     * @param type $email
     * @return type
     */
    public static function getUserIdByEmail($email) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__users')
            ->where($db->quoteName('email') . " = " . $db->quote($email));
        $db->setQuery($query);
        return $db->loadResult();
    }

    /**
     * get raas data
     * 
     * @param type $id
     * @return type
     */
    public static function getRaasData($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.name', 'u.username', 'u.email', 'lu.Uid'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('u.id') . " = " . $db->quote($id));
        $db->setQuery($query);
        return $db->LoadAssocList();
    }

    /**
     * manage login process after get user profile data
     * 
     * @param type $userProfile
     * @param type $accessToken
     */
    public static function accountLogginProcess($userProfile, $accessToken) {
        $db = JFactory::getDbo();
        $session = JFactory::getSession();
        $settings = plgSystemCiamTools::getSettings();
        // Remove the session if any.
        if ($session->get('tmpuser')) {
            $session->clear('tmpuser');
        }
        //check email
        if (!empty($userProfile->email)) {
            $userId = self::getUserIdByEmail($userProfile->email);
            if (!empty($userId)) {
                if (!self::checkActivateUser($userProfile->ID) && !self::checkBlockUser($userProfile->ID)) {
                    plgSystemCiamTools::insertSocialData($userId, $userProfile);
                    plgSystemCiamTools::loginExistUser($userId, $userProfile, $accessToken, false);
                }
            }
            else {
                $userId = plgSystemCiamTools::registrationProcess($userProfile, $accessToken);
                if ($userId) {
                    $returnId = plgSystemCiamTools::insertSocialData($userId, $userProfile);
                    plgSystemCiamTools::loginExistUser($userId, $userProfile, $accessToken, true);
                }
            }
        }
    }

    /**
     * check blocked user
     * 
     * @param type $socialId
     * @return boolean
     */
    public static function checkBlockUser($socialId) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.LoginRadius_id') . " = " . $db->quote($socialId) . " AND " . $db->quoteName('u.block') . " = 1");

        $db->setQuery($query);

        $blockId = $db->loadResult();
        if (!empty($blockId) || $blockId) {
            JError::raiseWarning('', JText::_('COM_CIAM_USER_BLOCK'));
            return true;
        }
        return false;
    }

    /**
     * check avtivate user
     * 
     * @param type $socialId
     * @return boolean
     */
    public static function checkActivateUser($socialId) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select(array('u.id'))
            ->from($db->quoteName('#__users', 'u'))
            ->join('INNER', $db->quoteName('#__loginradius_users', 'lu') . ' ON (' . $db->quoteName('lu.id') . ' = ' . $db->quoteName('u.id') . ')')
            ->where($db->quoteName('lu.LoginRadius_id') . " = " . $db->quote($socialId) . " AND " . $db->quoteName('u.activation') . " !=  ''" . " AND " . $db->quoteName('u.activation') . " != 0");

        $db->setQuery($query);
        $activate = $db->loadResult();
        if (!empty($activate) || $activate) {
            JError::raiseWarning('', JText::_('COM_CIAM_USER_NOTACTIVATE'));
            return true;
        }
        return false;
    }

    /**
     * link account in loginradius user table
     * 
     * @param type $userId
     * @param type $userProfile
     * @return type
     */
    public static function insertSocialData($userId, $userProfile) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__loginradius_users')
            ->where($db->quoteName('LoginRadius_id') . " = " . $db->quote($userProfile->ID) . " AND " . $db->quoteName('id') . " = " . $db->quote($userId));

        $db->setQuery($query);

        $lastId = $db->loadResult();
        if (!isset($lastId) && empty($lastId)) {
            $userImage = plgSystemCiamTools::addNewIdImage($userProfile->thumbnail, $userProfile->ID);
            $columns = array('id', 'LoginRadius_id', 'provider', 'Uid', 'lr_picture');
            $values = array($db->quote($userId), $db->quote($userProfile->ID), $db->quote($userProfile->Provider), $db->quote($userProfile->Uid), $db->quote($userImage));
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__loginradius_users'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);

            return $db->execute();
        }
    }
    /**
     * update user profile data on login
     * 
     * @param type $userId
     * @param type $userProfile
     * @param type $accessToken
     */
    public static function updateUserProfile($userId, $userProfile, $accessToken) {
        // Remove special char if have.
        $user = JUser::getInstance($userId);
        if (!empty($userProfile->FirstName) && !empty($userProfile->LastName)) {
            $name = $userProfile->FirstName . ' ' . $userProfile->LastName;
        }
        else {
            $name = plgSystemCiamFunctions::getFilterUserName($userProfile);
        }
        $user->name = plgSystemCiamFunctions::removeUnescapedChar($name);
        //update the user
        if ($user->save(true)) {
            // third party community support.
            plgSystemCiamFunctions::communities($userId, $user->username, $userProfile);
        }
    }

    /**
     * 
     * @param type $userProfile
     * @param type $accessToken
     * @return boolean
     */
    public static function registrationProcess($userProfile, $accessToken) {
        $user = new JUser;
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $session = JFactory::getSession();

        $needVerification = false;
        $userdata ['activation'] = '';
        $userdata ['block'] = 0;
        // If user registration is not allowed, show 403 not authorized.
        $usersConfig = JComponentHelper::getParams('com_users');
        // Default to Registered.
        $defaultUserGroups = $usersConfig->get('new_usertype', 2);
        if (empty($defaultUserGroups)) {
            $defaultUserGroups = 'Registered';
        }

        if (!empty($userProfile->FirstName) && !empty($userProfile->LastName)) {
            $userName = $userProfile->FirstName . $userProfile->LastName;
            $name = $userProfile->FirstName . ' ' . $userProfile->LastName;
        }
        else {
            $userName = plgSystemCiamFunctions::getFilterUserName($userProfile);
            $name = plgSystemCiamFunctions::getFilterUserName($userProfile);
        }

        // if username already exists
        $userName = plgSystemCiamFunctions::getExistUserName($userName);
        $name = plgSystemCiamFunctions::removeUnescapedChar($name);
        //Insert data
        jimport('joomla.user.helper');

        $userdata ['name'] = $db->escape($name);
        $userdata ['username'] = $db->escape($userName);
        $userdata ['email'] = $db->escape($userProfile->email);
        $userdata ['usertype'] = 'deprecated';
        $userdata ['groups'] = array($defaultUserGroups);
        $userdata ['registerDate'] = JFactory::getDate()->toSql();
        $userdata ['password'] = JUserHelper::genRandomPassword();
        $userdata ['password2'] = $userdata ['password'];
        $userActivation = $usersConfig->get('useractivation');

        if (JRequest::getVar('sociallogin_emailclick') && $userActivation != '2') {
            $needVerification = true;
        }

        if ($userActivation == '2' || $needVerification == true) {
            $userdata ['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
            $userdata ['block'] = 1;
        }


        if (!$user->bind($userdata)) {
            JError::raiseWarning('', JText::_('COM_USERS_REGISTRATION_BIND_FAILED'));
            return false;
        }
        //Save the user 
        if (!$user->save()) {
            JError::raiseWarning('', JText::_('COM_CIAM_REGISTER_FAILED'));
            return false;
        }

        $userId = $user->get('id');
        // third party community support.
        plgSystemCiamFunctions::communities($userId, $userName, $userProfile);
        // Handle account activation/confirmation emails.
        if ($userActivation == '2' OR $needVerification == true) {
            if ($needVerification == true) {
                $userMessage = 3;
                $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
            }
            else {
                $userMessage = 1;
                $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
            }
            plgSystemCiamTools::sendMail($user, $userMessage);
            $session->clear('tmpuser');
        }
        else {
            $userMessage = 2;
            plgSystemCiamTools::sendMail($user, $userMessage);
        }
        return $userId;
    }
}
