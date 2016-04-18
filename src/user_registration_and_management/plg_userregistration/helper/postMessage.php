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
class plgSystemUserRegistrationPostMessage {

    /**
     * post on login user social profile
     * 
     * @param type $provider
     * @param type $accessToken
     */
    public static function socialPost($provider, $accessToken) {
        //facebook post
        $settings = plgSystemUserRegistrationTools::getSettings();
        $socialLoginObject = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
        $mainframe = JFactory::getApplication();
        if (isset($settings['LoginRadius_facebookStatusEnable']) && $settings['LoginRadius_facebookStatusEnable'] == 1 && $provider == 'facebook') {
            $fbStatusTitle = isset($settings['LoginRadius_facebookStatusTitle']) ? trim($settings['LoginRadius_facebookStatusTitle']) : '';
            $fbStatusUrl = isset($settings['LoginRadius_facebookStatusUrl']) ? trim($settings['LoginRadius_facebookStatusUrl']) : '';
            $fbstatus = isset($settings['LoginRadius_facebookStatus']) ? trim($settings['LoginRadius_facebookStatus']) : '';
            $fbDescription = isset($settings['LoginRadius_facebookDescription']) ? trim($settings['LoginRadius_facebookDescription']) : '';
            try {
                $result = $socialLoginObject->postStatus($accessToken, $fbStatusTitle, $fbStatusUrl, '', $fbstatus, $fbStatusTitle, $fbDescription);
            } catch (LoginRadiusException $e) {
                $e->getMessage();
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }
            if (isset($result->isPosted) && $result->isPosted) {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_SUCCESS'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_ERROR'), 'error');
            }
        }

        //Twitter post
        elseif (isset($settings['LoginRadius_twitterStatusEnable']) && $settings['LoginRadius_twitterStatusEnable'] == 1 && $provider == 'twitter') {
            $twitterTweet = isset($settings['LoginRadius_twitterTweet']) ? trim($settings['LoginRadius_twitterTweet']) : '';
            try {
                $result = $socialLoginObject->postStatus($accessToken, '', '', '', $twitterTweet, '', '');
            } catch (LoginRadiusException $e) {
                $e->getMessage();
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }
            if (isset($result->isPosted) && $result->isPosted) {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_SUCCESS'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_ERROR'), 'error');
            }
        }

        //linkedin post
        elseif (isset($settings['LoginRadius_linkedinPostEnable']) && $settings['LoginRadius_linkedinPostEnable'] == 1 && $provider == 'linkedin') {
            $liPostTitle = isset($settings['LoginRadius_linkedinPostTitle']) ? trim($settings['LoginRadius_linkedinPostTitle']) : '';
            $liPostUrl = isset($settings['LoginRadius_linkedinPostUrl']) ? trim($settings['LoginRadius_linkedinPostUrl']) : '';
            $liPostImageUrl = isset($settings['LoginRadius_linkedinPostImageUrl']) ? trim($settings['LoginRadius_linkedinPostImageUrl']) : '';
            $liPostMessage = isset($settings['LoginRadius_linkedinPostMessage']) ? trim($settings['LoginRadius_linkedinPostMessage']) : '';
            try {
                $result = $socialLoginObject->postStatus($accessToken, $liPostTitle, $liPostUrl, $liPostImageUrl, $liPostMessage, '', '');
            } catch (LoginRadiusException $e) {
                if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                    $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                }
            }
            if (isset($result->isPosted) && $result->isPosted) {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_SUCCESS'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_POST_STATUS_ERROR'), 'error');
            }
        }
    }

}
