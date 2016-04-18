<?php

/**
 * @package      UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.user.helper');
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
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
 * User Registration plugin helper class.
 */
class plgSystemUserRegistrationTools {

    /**
     * 
     * @param type $entry
     * @param type $categories
     * @param type $priorities
     */
    public static function debug($entry, $categories = 'CURL', $priorities = JLog::ALL) {
        $settings = self::getSettings();
        if (isset($settings['debugEnable']) && $settings['debugEnable'] == 1) {
            $options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
            $options['text_file'] = 'loginradius.error.php';
            JLog::addLogger($options, $priorities, array($categories));
            JLog::add("\r\n" . 'API URL = ' . $entry['url'] . "\r\n" . 'API Status = ' . $entry['statuscode'] . "\r\n" . 'API Responce = ' . $entry['responce'] . "\r\n", $priorities, $categories);
        }
    }

    /**
     * generat html for email popup
     * 
     * @param $msg
     * @param $msgTitle
     * @param $msgType
     */
    public static function emailPopup($msg, $msgTitle, $msgType) {
        $document = JFactory::getDocument();
        $session = JFactory::getSession();

        $profileData = $session->get('tmpuser');

        $msgTitle = str_replace('@provider', $profileData->Provider, $msgTitle);
        $msg = str_replace('@provider', $profileData->Provider, $msg);
        $document->addStyleSheet(JURI::root() . 'plugins/system/userregistration/css/popupstyle.css');

        $output = '<div class="socialoverlay">';
        $output .= '<div id="popupouter"><form method="post" action=""><div class="socialpopupheading"> ' . $msgTitle . '</div>';
        $output .= '<div id="popupinner"><div id="textmatter" class="social' . $msgType . '">';
        $output .= $msg;
        $output .= '</div>';
        $output .= '<div class="emailtext">' . JText::_('COM_SOCIALLOGIN_POPUP_DESC') . '</div>';
        $output .= '<input type="text" name="email" id="email" class="inputtxt"/></div><div class="footerbox">';
        $output .= '<input type="submit" name="sociallogin_emailclick" value="' . JText::_('JLOGIN') . '" class="inputbutton"/>';
        $output .= '<input type="submit" value="' . JText::_('JCANCEL') . '" name = "cancel" class="inputbutton"/>';
        $output .= '<input type="hidden" name ="session" value="' . $profileData->session . '"/>';
        $output .= '</div></form></div></div>';

        $document->addCustomTag($output);
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
            $thumbnail = JURI::root() . 'media' . DS . 'com_userregistrationandmanagement' . DS . 'images' . DS . 'noimage.png';
        }

        $userImage = $socialId . '.jpg';
        $find = strpos($userImage, 'http');

        if ($find !== false) {
            $userImage = plgSystemUserRegistrationFunctions::removeUnescapedChar(substr($userImage, 8));
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
        } else {
            $thumbImage = @file_get_contents($profileImage);
            if (@$http_response_header == NULL) {
                $profileImage = str_replace('https', 'http', $profileImage);
                $thumbImage = @file_get_contents($profileImage);
            }
            if (empty($thumbImage)) {
                $thumbImage = @file_get_contents(JURI::root() . 'media' . DS . 'com_userregistrationandmanagement' . DS . 'images' . DS . 'noimage.png');
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

        $sql = "SELECT * FROM #__loginradius_settings";
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
     * Get the database settings.
     * 
     * @return type
     */
    public static function getAdvanceSettings() {
        $settings = array();

        $db = JFactory::getDBO();

        $sql = "SELECT * FROM #__loginradius_advanced_settings";
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
        plgSystemUserRegistrationPostMessage::socialPost($profileDataObject->Provider, $accessToken);
        if ($settings['updateuserdata'] == 1 || $newUser) {
            plgSystemUserRegistrationTools::updateUserProfile($userId, $profileDataObject, $accessToken);
        }
        if (plgSystemUserRegistrationSendMessage::friendInvatePopupController($profileDataObject->Provider, $accessToken)) {
            plgSystemUserRegistrationSendMessage::sendMessageToAllContacts($profileDataObject->Provider, $accessToken);
            self::userLogin($userId, $profileDataObject, $newUser);
        }
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
            return;
        }
        // Register session variables
        $session = JFactory::getSession();
        $query = "SELECT Uid,lr_picture from #__loginradius_users WHERE LoginRadius_id=" . $db->Quote($userProfile->ID) . " AND id = " . $user->get('id');
        $db->setQuery($query);
        $getUserData = $db->loadObjectList();

        $session->set('user_picture', $getUserData[0]->lr_picture);
        $session->set('Uid', $getUserData[0]->Uid);
        $session->set('user_lrid', $userProfile->ID);
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
        if (isset($settings['LoginRadius_' . strtolower($userProfile->Provider) . 'DMEnable']) &&
                $settings['LoginRadius_' . strtolower($userProfile->Provider) . 'DMEnable'] == '1' &&
                isset($settings[strtolower($userProfile->Provider) . 'MessageFriends']) &&
                $settings[strtolower($userProfile->Provider) . 'MessageFriends'] == '1') {
            $redirect = JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile', false);
        }
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
            if ($router->getMode() == JROUTER_MODE_SEF) {
                $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($redirection);
                $db->setQuery($query);
                $url = $db->loadResult();
                if ($checkRewrite == '0' AND ! empty($url)) {
                    $url = 'index.php/' . $url;
                }
            } else {
                $query = "SELECT link FROM #__menu WHERE id = " . $db->Quote($redirection);
                $db->setQuery($query);
                $url = $db->loadResult();
            }
        }
        if (!$url) {
            // stay on the same page
            $uri = clone JFactory::getURI();
            $vars = $router->parse($uri);
            unset($vars['lang']);
            if ($router->getMode() == JROUTER_MODE_SEF) {
                if (isset($vars['Itemid'])) {
                    $itemId = $vars['Itemid'];
                    $menu = $app->getMenu();
                    $item = $menu->getItem($itemId);
                    unset($vars['Itemid']);
                    if (isset($item) && $vars == $item->query) {
                        $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($itemId) . " AND home = 1";
                        $db->setQuery($query);
                        $homeUrl = $db->loadResult();
                        if ($homeUrl) {
                            $url = 'index.php';
                        } else {
                            $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($itemId);
                            $db->setQuery($query);
                            $url = $db->loadResult();
                            if ($checkRewrite == '0' AND ! empty($url)) {
                                $url = 'index.php/' . $url;
                            }
                        }
                    } else {
                        // get article url path
                        $articlePath = JFactory::getURI()->getPath();
                        $url = $articlePath;
                    }
                } else {
                    $articlePath = JFactory::getURI()->getPath();
                    $url = $articlePath;
                }
            } else {
                $fullUrl = urldecode($_SERVER['HTTP_REFERER']);
                if (strpos($fullUrl, "callback=") > 0) {
                    $urlData = explode("callback=", $fullUrl);
                    $tampPos = strpos($urlData[1], "&");
                    $endLimit = strlen($urlData[1]);
                    if ($tampPos > 0) {
                        if (strpos($_SERVER['QUERY_STRING'], '&') > 0) {
                            $url = 'index.php?' . $_SERVER['QUERY_STRING'];
                        } else {
                            $url = 'index.php?' . $_SERVER['QUERY_STRING'] . substr($urlData[1], $tampPos, $endLimit);
                        }
                    }
                }
            }
        }
        return $url;
    }

    /**
     * link user account with login account
     * 
     * @param type $userProfile
     */
    public static function accountMapping($userProfile) {
        $db = JFactory::getDbo();
        $session = JFactory::getSession();
        $mainframe = JFactory::getApplication();
        $query = "SELECT provider from #__loginradius_users WHERE provider=" . $db->Quote($userProfile->Provider) . " AND id = " . JFactory::getUser()->id;
        $db->setQuery($query);
        $checkProvider = $db->loadResult();

        if (empty($checkProvider)) {
            $settings = self::getSettings();
            $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            try {
                $result = $raas_account->accountLink($session->get('Uid'), $userProfile->ID, $userProfile->Provider);
                if (isset($result->isPosted) && $result->isPosted) {
                    $userProfile->Uid = $session->get('Uid');
                    self::insertSocialData(JFactory::getUser()->id, $userProfile);
                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_ADD_ID'), 'message');
                } else {
                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_PROVIDER'), 'error');
                }
            } catch (LoginRadiusException $e) {                
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), $e->getErrorResponse()->description, 'error');
                }
            }
        } else {
            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_PROVIDER'), 'error');
        }
    }

    /**
     * check email exist in local db
     * 
     * @param type $email
     * @return type
     */
    public static function getUserIdByEmail($email) {
        $db = JFactory::getDbo();
        $query = "SELECT id FROM #__users WHERE email=" . $db->Quote($email);
        $db->setQuery($query);
        return $db->loadResult();
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
        $settings = plgSystemUserRegistrationTools::getSettings();
        // Remove the session if any.
        if ($session->get('tmpuser')) {
            $session->clear('tmpuser');
        }
        //check email
        if (!empty($userProfile->email)) {
            $userId = self::getUserIdByEmail($userProfile->email);
            if (!empty($userId)) {
                if (!self::checkActivateUser($userProfile->ID) && !self::checkBlockUser($userProfile->ID)) {
                    plgSystemUserRegistrationTools::insertSocialData($userId, $userProfile);
                    plgSystemUserRegistrationTools::loginExistUser($userId, $userProfile, $accessToken, false);
                }
            } else {
                $userId = plgSystemUserRegistrationTools::registrationProcess($userProfile, $accessToken);
                if ($userId) {
                    plgSystemUserRegistrationTools::insertSocialData($userId, $userProfile);
                    plgSystemUserRegistrationTools::loginExistUser($userId, $userProfile, $accessToken, true);
                }
            }
        } elseif (empty($userProfile->email)) {
            if ($settings ['dummyemail'] == 0) {
                $userProfile->email = plgSystemUserRegistrationFunctions::generateEmail($userProfile->ID, $userProfile->Provider);
                $userId = plgSystemUserRegistrationTools::registrationProcess($userProfile, $accessToken);
                if ($userId) {
                    plgSystemUserRegistrationTools::insertSocialData($userId, $userProfile);
                    plgSystemUserRegistrationTools::loginExistUser($userId, $userProfile, $accessToken, true);
                }
            } else {
                //popup show
                $session->set('tmpuser', $userProfile);
                plgSystemUserRegistrationTools::emailPopup($settings['popupemailmessage'], $settings['popupemailtitle'], 'noerror');
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
        $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($socialId) . " AND u.block = 1";
        $db->setQuery($query);
        $blockId = $db->loadResult();
        if (!empty($blockId) || $blockId) {
            JError::raiseWarning('', JText::_('COM_SOCIALLOGIN_USER_BLOCK'));
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
        $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($socialId) . " AND u.activation != '' AND u.activation != 0";
        $db->setQuery($query);
        $activate = $db->loadResult();
        if (!empty($activate) || $activate) {
            JError::raiseWarning('', JText::_('COM_SOCIALLOGIN_USER_NOTACTIVATE'));
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
        $query = "SELECT lu.id FROM #__loginradius_users AS lu WHERE lu.LoginRadius_id = " . $db->Quote($userProfile->ID) . " AND id = " . $db->Quote($userId);
        $db->setQuery($query);
        $lastId = $db->loadResult();
        if (!isset($lastId) && empty($lastId)) {
            $userImage = plgSystemUserRegistrationTools::addNewIdImage($userProfile->thumbnail, $userProfile->ID);
            $sql = "INSERT INTO #__loginradius_users SET id = " . $db->Quote($userId) . ", LoginRadius_id = " . $db->Quote($userProfile->ID) . ", provider = " . $db->Quote($userProfile->Provider) . ", Uid = " . $db->Quote($userProfile->Uid) . ", lr_picture = " . $db->Quote($userImage);
            $db->setQuery($sql);
            return $db->execute();
        }
    }

    /**
     * remove social account from social table
     * 
     * @param type $socialId
     * @return type
     */
    public static function removeSocialData($socialId) {
        $db = JFactory::getDbo();
        $sql = "DELETE FROM #__loginradius_users WHERE LoginRadius_id = " . $db->Quote($socialId);
        $db->setQuery($sql);
        return $db->execute();
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
        } else {
            $name = plgSystemUserRegistrationFunctions::getFilterUserName($userProfile);
        }
        $user->name = plgSystemUserRegistrationFunctions::removeUnescapedChar($name);
        //update the user
        if ($user->save(true)) {
            // third party community support.
            plgSystemUserRegistrationFunctions::communities($userId, $user->username, $userProfile);
            plgSystemUserRegistrationUserProfileData::saveProfile($userId, $userProfile, $accessToken);
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
        } else {
            $userName = plgSystemUserRegistrationFunctions::getFilterUserName($userProfile);
            $name = plgSystemUserRegistrationFunctions::getFilterUserName($userProfile);
        }

        // if username already exists
        $userName = plgSystemUserRegistrationFunctions::getExistUserName($userName);
        $name = plgSystemUserRegistrationFunctions::removeUnescapedChar($name);
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
            JError::raiseWarning('', JText::_('COM_SOCIALLOGIN_REGISTER_FAILED'));
            return false;
        }

        $userId = $user->get('id');
        // third party community support.
        plgSystemUserRegistrationFunctions::communities($userId, $userName, $userProfile);
        plgSystemUserRegistrationUserProfileData::saveProfile($userId, $userProfile, $accessToken);
        // Handle account activation/confirmation emails.
        if ($userActivation == '2' OR $needVerification == true) {
            if ($needVerification == true) {
                $userMessage = 3;
                $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
            } else {
                $userMessage = 1;
                $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
            }
            plgSystemUserRegistrationTools::sendMail($user, $userMessage);
            $session->clear('tmpuser');
        } else {
            $userMessage = 2;
            plgSystemUserRegistrationTools::sendMail($user, $userMessage);
        }
        return $userId;
    }

    /**
     * manage popup handling cases 
     * 
     * @return type
     */
    public static function popupHandler() {
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $session = JFactory::getSession();
        $settings = plgSystemUserRegistrationTools::getSettings();
        $raas_user = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
        if (JRequest::getVar('cancel')) {
            // Redirect after Cancel click.
            $session->clear('tmpuser');
            $mainframe->redirect(JURI::base());
        } elseif (JRequest::getVar('loginRadiusReferralSkip')) {
            $accessToken = trim(JRequest::getVar('loginRadiusIdentifier'));
            try {
                $userProfile = $raas_user->getUserProfiledata($accessToken);
            } catch (LoginRadiusException $e) {
                
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }
            if (isset($userProfile->ID)) {
                $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($userProfile->ID);
                $db->setQuery($query);
                $userId = $db->loadResult();
                if (!empty($userId)) {
                    self::userLogin($userId, $userProfile, false);
                }
            }
            $redirect = plgSystemUserRegistrationTools::getReturnURL();
            $mainframe->redirect($redirect);
        } elseif (JRequest::getVar('sociallogin_emailclick')) {
            $profileData = $session->get('tmpuser');
            if (JRequest::getVar('session') == $profileData->session && !empty($profileData->session)) {
                $email = JRequest::getVar('email');
                $msg = $settings['popuperroremailmessage'];
                $msgTitle = $settings['popupemailtitle'];
                $msgType = 'error';
                if (!JMailHelper::isEmailAddress($email)) {
                    plgSystemUserRegistrationTools::emailPopup($msg, $msgTitle, $msgType);
                } else {
                    $query = "SELECT id FROM #__users WHERE email=" . $db->Quote($email);
                    $db->setQuery($query);
                    $userExist = $db->loadResult();
                    if ($userExist != 0) {
                        plgSystemUserRegistrationTools::emailPopup($msg, $msgTitle, $msgType);
                    } else {
                        $profileData->email = $db->escape($email);
                        $userId = plgSystemUserRegistrationTools::registrationProcess($profileData, $profileData->accessToken);
                        if ($userId) {
                            plgSystemUserRegistrationTools::insertSocialData($userId, $profileData);
                        }
                    }
                }
            } else {
                $session->clear('tmpuser');
                $mainframe->redirect(JURI::base(), JText::_('COM_SOCIALLOGIN_SESSION_EXPIRED'), 'error');
            }
        } elseif (JRequest::getVar('loginRadiusReferralSubmit') && JRequest::getVar('loginRadiusIdentifier')) {
            $accessToken = trim(JRequest::getVar('loginRadiusIdentifier'));
            $provider = trim(JRequest::getVar('loginRadiusProvider'));
            if (!JRequest::getVar('loginRadiusContacts') || count(JRequest::getVar('loginRadiusContacts')) <= 0) {
                // get contacts' Social IDs
                plgSystemUserRegistrationSendMessage::friendInvatePopup($provider, $accessToken, 'error', 'Please select contacts to send referral to.', $settings);
                return;
            }
            $contacts = JRequest::getVar('loginRadiusContacts');
            plgSystemUserRegistrationSendMessage::sendMessageToSelctedContacts($contacts, $provider, $accessToken);

            try {
                $userProfile = $raas_user->getUserProfiledata($accessToken);
            } catch (LoginRadiusException $e) {
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }

            if (isset($userProfile->ID)) {
                $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($userProfile->ID);
                $db->setQuery($query);
                $userId = $db->loadResult();
                if (!empty($userId)) {
                    self::userLogin($userId, $userProfile, false);
                }
            }
        }
    }

}
