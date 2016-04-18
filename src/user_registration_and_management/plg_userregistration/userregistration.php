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
use LoginRadiusSDK\LoginRadiusException;

require_once(dirname(__FILE__) . DS . 'helper' . DS . 'helper.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'functions.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'userProfileData.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'postMessage.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'sendMessage.php');
/*
 * Class that indicates the plugin.
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
     * Plugin class function that calls on after plugin intialise
     * 
     * Manage Authantication Process
     */
    function onAfterInitialise() {
        // Get module configration option value    
        $language = JFactory::getLanguage();
        $mainframe = JFactory::getApplication();
        $app = JFactory::getApplication();       
            
        if (JFactory::getApplication()->isSite()) {
           $uri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));          
           if ($uri == 'log-out') {
              $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=login'));
            }
            $view = $app->input->get('view');        
            if (!JFactory::getUser()->id){
                if(in_array($view, array('reset','remind'))) {
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=forgotpassword'));
                }elseif(in_array($view, array('registration','login'))){
                    if($view == 'registration'){
                        $view = 'register';
                    }
                    $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view='.$view));
                }
                
            } else {
                $option = $app->input->get('option');            
                switch ($option) {
                    case 'com_users':
                        $mainframe = JFactory::getApplication();
                        if ($view == 'login') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=login'));
                        } elseif ($view == 'registration') {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=register'));
                        } elseif (in_array($view, array('remind', 'reset'))) {
                            $redirect = $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=password'));
                        } elseif ($view == 'profile') {
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
                } catch (LoginRadiusException $e) {
                    $dispatcher = JEventDispatcher::getInstance();
                    $dispatcher->trigger('onLoginRadiusSSOLogout', array($e));
                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                    }
                }

                if (isset($accessToken) && $accessToken !== false) {
                    try {
                        $userProfileObject = $loginRadiusObject->getUserProfiledata($accessToken);
                    } catch (LoginRadiusException $e) {

                        if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                        }
                    }
                    
                    $userProfile = plgSystemUserRegistrationTools::manageUserProfileData($userProfileObject, $accessToken);

                    if (JFactory::getUser()->id) {
                        $checkId = self::checkProviderId($userProfile);
                        if (empty($checkId)) {
                            plgSystemUserRegistrationTools::accountMapping($userProfile);
                        } else {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_ID'), 'error');
                        }
                    } else {                       
                            $userid = self::checkUid($userProfile);
                            if (!empty($userid)) {
                                plgSystemUserRegistrationTools:: insertSocialData($userid, $userProfile);
                                plgSystemUserRegistrationTools:: loginExistUser($userid, $userProfile, $accessToken, false);
                            } else {
                                if (isset($userProfileObject->ID)) {
                                    $userId = self::checkProviderId($userProfile);
                                    if (!empty($userId)) {
                                        if (!plgSystemUserRegistrationTools::checkActivateUser($userProfile->ID) && !plgSystemUserRegistrationTools::checkBlockUser($userProfile->ID)) {
                                            plgSystemUserRegistrationTools::loginExistUser($userId, $userProfile, $accessToken, false);
                                        }
                                    } else {
                                        plgSystemUserRegistrationTools::accountLogginProcess($userProfile, $accessToken);
                                    }
                                }
                            }                      
                    }
                }
            } else {
                $post_value = $_POST;
                if (JFactory::getApplication()->isSite()) {
                    $raas_account = new LoginRadiusSDK\CustomerRegistration\AccountAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));

                    if (isset($post_value['emailid']) && !empty($post_value['emailid']) && isset($post_value['password']) && !empty($post_value['password'])) {
                        //set password
                        $raas_data = self::lr_raas_get_raas_data(JFactory::getUser()->id);
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
                                foreach ($response as $k => $val) {
                                    if (isset($val->Provider) && strtolower($val->Provider) == 'raas') {
                                        $raasId = $val->ID;
                                        break;
                                    }
                                }
                                self::lr_social_login_insert_into_mapping_table($raas_data[0]->id, $raasId, 'RAAS', $raas_data[0]->Uid, $raas_data[0]->lr_picture);
                                if (isset($result->isPosted) && $result->isPosted) {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_SET'), 'message');
                                } else {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_NOT_SET'), 'error');
                                }
                            } catch (LoginRadiusException $e) {
                                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                                    $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->description, 'error');
                                }
                            }
                        } catch (LoginRadiusException $e) {
                            if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                                $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->description, 'error');
                            }
                        }
                    } else if (isset($post_value['newpassword']) && !empty($post_value['newpassword'])) {
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
                                    } else {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_PASSWORD_NOT_CHANGE'), 'error');
                                    }
                                } catch (LoginRadiusException $e) {
                                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), $e->getErrorResponse()->description, 'error');
                                    }
                                }
                            }
                        } else {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=changepassword'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                        }
                    } else if (isset($post_value['value']) && $post_value['value'] == 'accountUnLink') {
                        $provider = isset($post_value['provider']) ? trim($post_value['provider']) : '';
                        $providerId = isset($post_value['providerId']) ? trim($post_value['providerId']) : '';
                        if (!empty($provider) && !empty($providerId)) {
                            $Uid = $this->getUidbySocialId(JFactory::getUser()->id, $providerId);
                            if ($Uid) {
                                try {
                                    $result = $raas_account->accountUnlink($Uid, $providerId, $provider);
                                    if (isset($result->isPosted) && $result->isPosted) {
                                        $db = JFactory::getDBO();
                                        $query = "DELETE FROM #__loginradius_users WHERE id = " . JFactory::getUser()->id . " AND LoginRadius_id=" . $db->Quote($providerId);
                                        $db->setQuery($query);
                                        $db->query();
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_UNLINK_ID'), 'message');
                                    } else {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                                    }
                                } catch (LoginRadiusException $e) {
                                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                                        $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), $e->getErrorResponse()->description, 'error');
                                    }
                                }
                            }
                        } else {
                            $mainframe->redirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'), JText::_('COM_SOCIALLOGIN_NOT_UNLINK_ID'), 'error');
                        }
                    }  else {                
                        plgSystemUserRegistrationTools::popupHandler();
                    }
                }
            }
        }
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
                if (isset($response->isPosted) && $response->isPosted) {
                    $db = JFactory::getDbo();
                    $db->setQuery('DELETE FROM #__loginradius_users WHERE id = ' . $db->Quote($userId));
                    $db->execute();
                } else {
                    $this->_subject->setError(JText::_('COM_SOCIALLOGIN_USERS_NOTDELETED'));
                    return false;
                }
            } catch (LoginRadiusException $e) {
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $this->_subject->setError($e->getErrorResponse()->description);
                } else {
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
        $params = array(
            'fullname' => $data['name']
        );
        try {
            $response = $raas_user->edit($raasUserId, $params);
            $output['status'] = 'message';
            $output['message'] = 'Account has been updated successfully.';
            // Remove special char if have.
            $user->name = $data['name'];
            $user->save(true);
        } catch (LoginRadiusException $e) {
            if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                $output['message'] = $e->getErrorResponse()->description;
            } else {
                $output['message'] = 'Account can\'t updated. please try again';
            }
        }

        return $output;
    }

    function lr_raas_get_raas_data($acid) {
        $db = JFactory::getDbo();
        $query = "SELECT lu.id,lu.Uid,lu.lr_picture FROM #__loginradius_users AS lu INNER JOIN #__users AS u ON lu.id = u.id WHERE u.id = " . $db->Quote($acid);
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
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
    function lr_social_login_insert_into_mapping_table($acid, $responseId, $provider, $uid, $lrpic) {
        $db = JFactory::getDbo();
        $k2query = "INSERT INTO #__loginradius_users(`id`,`LoginRadius_id`,`provider`,`Uid`,`lr_picture`) VALUES (" . $db->Quote($acid) . "," . $db->Quote($responseId) . "," . $db->Quote($provider) . "," . $db->Quote($uid) . "," . $db->Quote($lrpic) . ")";
        $db->setQuery($k2query);
        $db->query();
    }

    /**
     * Check Uid exist.
     *
     * @param userProfile
     */
    function checkUid($userProfile) {
        $db = JFactory::getDbo();
        $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.Uid = " . $db->Quote($userProfile->Uid);
        $db->setQuery($query);
        $userId = $db->loadResult();
        return $userId;
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
    function checkProviderId($userProfile) {
        $db = JFactory::getDbo();
        $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($userProfile->ID);
        $db->setQuery($query);
        $userId = $db->loadResult();
        return $userId;
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
            $raas_user = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            if ($isnew == true) {
                if (isset($new['password2']) && !empty($new['password2'])) {
                    $params = array(
                        'emailid' => $new['email'],
                        'fullname' => $new['name'],
                        'username' => $new['username'],
                        'password' => $new['password2'],
                    );
                    try {
                        $response = $raas_user->create($params);
                    } catch (LoginRadiusException $e) {
                        if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                        }
                    }
                }
            } else {
                $params = array(
                    'FirstName' => $new['name']
                );
                try {
                    $response = $raas_user->getProfileByEmail($new['email']);

                    try {
                        $response = $raas_user->edit($response[0]->ID, $params);
                    } catch (LoginRadiusException $e) {

                        if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                        }
                    }
                } catch (LoginRadiusException $e) {

                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                    }
                }
            }
        }
    }

    public function onUserAfterSave($old, $isnew, $new) {
        if (JFactory::getApplication()->isAdmin()) {
            $mainframe = JFactory::getApplication();        
            $settings = plgSystemUserRegistrationTools::getSettings();
            $raas_user = new LoginRadiusSDK\CustomerRegistration\UserAPI($settings['apikey'], $settings['apisecret'], array('output_format' => 'json'));
            try {
                $response = $raas_user->getProfileByEmail($old['email']);
            } catch (LoginRadiusException $e) {

                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }

            if (isset($response[0]->Uid)) {
                $profile = array(
                    'Uid' => $response[0]->Uid,
                    'ID' => $response[0]->ID,
                    'Provider' => $response[0]->Provider,
                    'thumbnail' => $response[0]->ThumbnailImageUrl,
                );
                $userProfile = (object) $profile;
                plgSystemUserRegistrationTools::insertSocialData($old['id'], $userProfile);
            }
        }
    }

}
