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
class plgSystemUserRegistrationSendMessage {

    /**
     * send message on social profile or send email
     * 
     * @param type $tos
     * @param type $provider
     * @param type $accessToken
     */
    public static function socialSend($tos, $provider, $accessToken) {
        $settings = plgSystemUserRegistrationTools::getSettings();
        //Twitter and linkedin post
        if (in_array($provider, array('twitter', 'linkedin'))) {
            $subject = isset($settings['LoginRadius_' . $provider . 'DMSubject']) ? trim($settings['LoginRadius_' . $provider . 'DMSubject']) : '';
            $message = isset($settings['LoginRadius_' . $provider . 'DMMessage']) ? trim($settings['LoginRadius_' . $provider . 'DMMessage']) : '';
            self::postMessage($accessToken, $tos, $subject, $message, $settings);
        } elseif (in_array($provider, array('google', 'yahoo'))) {
            $subject = isset($settings['LoginRadius_' . $provider . 'DMSubject']) ? trim($settings['LoginRadius_' . $provider . 'DMSubject']) : '';
            $message = isset($settings['LoginRadius_' . $provider . 'DMMessage']) ? trim($settings['LoginRadius_' . $provider . 'DMMessage']) : '';
            self::sendEmail($tos, $subject, $message);
        }
    }

    /**
     * post messages
     * @param type $accessToken
     * @param type $tos
     * @param type $subject
     * @param type $message
     */
    public static function postMessage($accessToken, $tos, $subject, $message, $settings) {
        $socialLoginObject = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
        if (is_array($tos) && count($tos) > 0) {
            $mainframe = JFactory::getApplication();
            foreach ($tos as $to) {
                try {
                    $sendMessage = $socialLoginObject->sendMessage($accessToken, $to, $subject, $message);
                } catch (LoginRadiusException $e) {
                    $e->getMessage();
                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                    }
                }
            }
            $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_SEND_MESSAGE_SUCCESS'));
        }
    }

    /**
     * send email to user contacts
     * 
     * @param type $tos
     * @param type $subject
     * @param type $message
     */
    public static function sendEmail($tos, $subject, $message) {
        if (is_array($tos) && count($tos) > 0) {
            $mainframe = JFactory::getApplication();
            $config = JFactory::getConfig();
            $fromName = $config->get('fromname');
            $from = $config->get('mailfrom');
            foreach ($tos as $to) {
                if (empty($to)) {
                    continue;
                }
                JFactory::getMailer()->sendMail($from, $fromName, $to, $subject, $message);
            }
            $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_SEND_MESSAGE_SUCCESS'));
        }
    }

    /**
     * send message to all contact of user
     * 
     * @param type $provider
     * @param type $accessToken
     */
    public static function sendMessageToAllContacts($provider, $accessToken) {
        $settings = plgSystemUserRegistrationTools::getSettings();
        $mainframe = JFactory::getApplication();
        $socialLoginObject = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
        if (isset($settings['LoginRadius_' . $provider . 'DMEnable']) && $settings['LoginRadius_' . $provider . 'DMEnable'] == 1) {
            if (isset($settings[$provider . 'MessageFriends']) && $settings[$provider . 'MessageFriends'] == 0) {
                try {
                    $contacts = $socialLoginObject->getContacts($accessToken, '0');
                } catch (LoginRadiusException $e) {
                    $e->getMessage();
                    if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                        $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
                    }
                }
                if (isset($contacts->Data) && count($contacts->Data) > 0) {
                    if (in_array($provider, array('twitter', 'linkedin'))) {
                        foreach ($contacts->Data as $contact) {
                            $tos[] = isset($contact->ID) ? $contact->ID : '';
                        }
                    } else {
                        foreach ($contacts->Data as $contact) {
                            $tos[] = isset($contact->EmailID) ? $contact->EmailID : '';
                        }
                    }
                    self::socialSend($tos, $provider, $accessToken);
                }
            }
        }
    }

    /**
     * send message on selected user
     * 
     * @param type $tos
     * @param type $provider
     * @param type $accessToken
     */
    public static function sendMessageToSelctedContacts($tos, $provider, $accessToken) {
        $settings = plgSystemUserRegistrationTools::getSettings();
        if (isset($settings['LoginRadius_' . $provider . 'DMEnable']) && $settings['LoginRadius_' . $provider . 'DMEnable'] == 1) {
            self::socialSend($tos, $provider, $accessToken);
        }
    }

    /**
     * Display friend invate popup
     * 
     * @param type $provider
     * @param type $accessToken
     * @param type $class
     * @param type $message
     * @return type
     */
    public static function friendInvatePopup($provider, $accessToken, $class, $message, $settings) {
        $document = JFactory::getDocument();
        $mainframe = JFactory::getApplication();
        $socialLoginObject = new LoginRadiusSDK\SocialLogin\SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication' => false, 'output_format' => 'json'));
        try {
            $contacts = $socialLoginObject->getContacts($accessToken);
        } catch (LoginRadiusException $e) {
            $e->getMessage();
            if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
                $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
            }
        }
        if (!isset($contacts) || is_string($contacts) || !isset($contacts->Data) || count($contacts->Data) == 0) {
            return true;
        }
        $document->addStyleSheet(JURI::root() . 'plugins/system/userregistration/css/advancepopupstyle.css');
        $document->addScript(JURI::root() . 'plugins/system/userregistration/js/friendinvate.js');

        $output = '<div class="socialoverlay"></div>';
        $output .= '<div id="popupouter">';
        $output .= '<form method="post" name="loginRadiusReferralForm" action="" class="form">';
        $output .= '<div class="socialpopupheading">Send Message</div>';
        $output .= '<div style="clear:both"></div>';
        $output .= '<div id="popupinner">';
        $output .= '<div class="social' . $class . '">' . $message . '</div>';
        for ($i = 0; $i < count($contacts->Data); $i++) {
            if (!JMailHelper::isEmailAddress($contacts->Data[$i]->EmailID) && !in_array($provider, array('twitter', 'linkedin'))) {
                continue;
            }
            $output .= '<div class="div">';
            $output .= '<input style="float:left; margin:5px 10px 0px 30px" type="checkbox" checked="checked" id="loginRadiusContact' . $i . '" name="loginRadiusContacts[]" value="';

            if (in_array($provider, array('twitter', 'linkedin'))) {
                $output .= $contacts->Data[$i]->ID;
            } else {
                $output .= $contacts->Data[$i]->EmailID;
            }

            $output .= '" />';

            if ($provider != "yahoo") {
                $output .= '<label for="loginRadiusContact' . $i . '">';
                if (!empty($contacts->Data[$i]->Name)) {
                    $emailname = explode('@', $contacts->Data[$i]->Name);
                    $output .= ucfirst($emailname[0]);
                } elseif (!empty($contacts->Data[$i]->EmailID)) {
                    $emailname = explode('@', $contacts->Data[$i]->EmailID);
                    $output .= ucfirst($emailname[0]);
                }
                $output .= '</label>';
            }

            if (isset($contacts->Data[$i]->EmailID) && trim($contacts->Data[$i]->EmailID) != ""):
                $output .= '<label for="loginRadiusContact' . $i . '">' . $contacts->Data[$i]->EmailID . '</label>';
            endif;

            $output .= '</div>';
            if ($provider != "yahoo"):
                $output .= '<input type="hidden" name="loginRadiusReferralNames[]" value="' . ucfirst($contacts->Data[$i]->Name) . '" />';
                $output .= '<input type="hidden" name="loginRadiusReferralIds[]" value="';
                if ($contacts->Data[$i]->ID != ""):
                    $output.= $contacts->Data[$i]->ID;
                else:
                    $output .= $contacts->Data[$i]->EmailID;
                endif;
                $output.= '" />';
            endif;

            if (isset($contacts->Data[$i]->Email) && $contacts->Data[$i]->Email != "") {
                $output .= '<input type="hidden" name="loginRadiusReferralEmails[]" value="' . $contacts->Data[$i]->Email . '" />';
            }// yahoo, msn
            if (in_array($provider, array("yahoo", "google")) && isset($contacts->Data[$i]->EmailID)) {
                $output .= '<input type="hidden" name="loginRadiusReferralEmails[]" value="' . $contacts->Data[$i]->EmailID . '" />';
            }
        }
        $output .= '<input type="hidden" name="loginRadiusIdentifier" value="' . $accessToken . '" />';
        $output .= '<input type="hidden" name="loginRadiusProvider" value="' . $provider . '" /></div>';
        $output .= '<div class="heading" style="border-bottom:none; border-top:1px solid #888888; border-top-left-radius: 0px !important; border-top-right-radius: 0px !important; border-bottom-left-radius: 8px !important; border-bottom-right-radius: 8px !important;">';
        $output .= '<div class="footerbox">';
        $output .= '<input type="button" onclick="loginRadiusCheckAll(document.loginRadiusReferralForm, true)" id="" name="" value="Select All" class="inputbutton">&nbsp;&nbsp;';
        $output .= '<input type="button" onclick="loginRadiusCheckAll(document.loginRadiusReferralForm, false)" name="" value="Deselect All" class="inputbutton" />&nbsp;&nbsp;';
        $output .= '<input type="submit" name="loginRadiusReferralSubmit" value="Send Message" onclick="loginRadiusReferralSubmit = \'Submit\'" class="inputbutton">&nbsp;&nbsp;';
        $output .= '<input type="submit" name="loginRadiusReferralSkip" value="Skip" onclick="loginRadiusReferralSubmit = \'Cancel\'" class="inputbutton" />';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</form>';
        $output .= '</div>';
        $document->addCustomTag($output);
        return false;
    }

    /**
     * Contaoller friend invate popup
     * 
     * @param type $provider
     * @param type $accessToken
     * @return boolean
     */
    public static function friendInvatePopupController($provider, $accessToken) {
        $settings = plgSystemUserRegistrationTools::getSettings();
        if (isset($settings['LoginRadius_' . $provider . 'DMEnable']) && in_array('1', array($settings['LoginRadius_' . $provider . 'DMEnable']))) {
            if (in_array('1', array($settings[$provider . 'MessageFriends']))) {
                return self::friendInvatePopup($provider, $accessToken, 'noerror', 'Please select your contacts from the list mentioned below to whom you want to send message', $settings);
            }
        }
        return true;
    }

}
