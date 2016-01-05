<?php

/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_sociallogin
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

// Includes plugins required files.
require_once(dirname(__FILE__) . '/LoginRadius.php');
/*
 * Class that indicates the plugin.
 */

class plgSystemSocialLogin extends JPlugin
{

    /**
     * @param $subject
     * @param $config
     */
    function plgSystemSocialLogin(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /*
     * Plugin class function that calls on after plugin intialise.
     */

    function onAfterInitialise()
    {
                
                
        $settings = plgSystemSocialLoginTools::getSettings();
        // Get module configration option value
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $language = JFactory::getLanguage();
        $session = JFactory::getSession();
        $language->load('com_users');
        $language->load('com_loginradiussocialloginandsocialshare', JPATH_ADMINISTRATOR);
        // Retrieve data from LoginRadius.
        $obj = new LoginRadius();
        $userProfile = $obj->getUserProfile();

        // Checking user is logged in.
        if ($obj->isAuthenticated == true && JFactory::getUser()->id)
        {
            $profileData = plgSystemSocialLoginTools::getUserProfileData($userProfile);
            // Check lr id exist.
            $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($profileData['id']);
            $db->setQuery($query);
            $checkId = $db->loadResult();

            // Try to map another account.
            if (empty($checkId))
            {
                $query = "SELECT provider from #__loginradius_users WHERE provider=" . $db->Quote($profileData['Provider']) . " AND id = " . JFactory::getUser()->id;
                $db->setQuery($query);
                $checkProvider = $db->loadResult();

                if (empty($checkProvider))
                {
                    $userImage = plgSystemSocialLoginTools::addNewIdImage($profileData['thumbnail'], $profileData['id']);

                    // Remove.
                    $sql = "DELETE FROM #__loginradius_users WHERE LoginRadius_id = " . $db->Quote($profileData['id']);
                    $db->setQuery($sql);
                    if ($db->execute())
                    {
                        // Add new id to db.
                        $sql = "INSERT INTO #__loginradius_users SET id = " . JFactory::getUser()->id . ", LoginRadius_id = " . $db->Quote($profileData['id']) . ", provider = " . $db->Quote($profileData['Provider']) . ", lr_picture = " . $db->Quote($userImage);
                        $db->setQuery($sql);
                        $db->execute();
                    }
                    $mainframe->redirect(JRoute::_('index.php?option=com_loginradiussocialloginandsocialshare&view=profile'), JText::_('COM_SOCIALLOGIN_ADD_ID'), 'message');
                } else
                {
                    $mainframe->redirect(JRoute::_('index.php?option=com_loginradiussocialloginandsocialshare&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_PROVIDER'), 'error');
                    return false;
                }
            } else
            {
                $mainframe->redirect(JRoute::_('index.php?option=com_loginradiussocialloginandsocialshare&view=profile'), JText::_('COM_SOCIALLOGIN_EXIST_ID'), 'error');
                return false;
            }
        }
        // User is not logged in trying to make log in user.
        else if ($obj->isAuthenticated == true && !JFactory::getUser()->id)
        {
            // Remove the session if any.
            if ($session->get('tmpuser'))
            {
                $session->clear('tmpuser');
            }
            // Getting all user data.
            $profileData = plgSystemSocialLoginTools::getUserProfileData($userProfile);
            if ($settings ['dummyemail'] == 0 && $profileData['email'] == "")
            {
                // Random email if not true required email.
                $profileData['email'] = plgSystemSocialLoginFunctions::generateEmail($profileData['id'], $profileData['Provider']);
            }

            // Find the not activate user.
            $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($profileData['id']) . " AND u.activation != '' AND u.activation != '0'";
            $db->setQuery($query);
            $blockId = $db->loadResult();
            if (!empty($blockId) || $blockId)
            {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_USER_NOTACTIVATE'), 'error');
                return false;
            }

            // Find the block user.
            $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($profileData['id']) . " AND u.block = 1";
            $db->setQuery($query);
            $blockId = $db->loadResult();
            if (!empty($blockId) || $blockId)
            {
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_USER_BLOCK'), 'error');
                return false;
            }

            // Checking user admin mail setting.
            if ($settings ['dummyemail'] == 1 && $profileData['email'] == '')
            {
                $usersConfig = JComponentHelper::getParams('com_users');
                $userActivation = $usersConfig->get('useractivation');
                $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($profileData['id']);
                $db->setQuery($query);
                $userId = $db->loadResult();
                $newUser = true;
                if (isset($userId))
                {
                    $user = JFactory::getUser($userId);
                    if ($user->id == $userId)
                    {
                        $newUser = false;
                    }
                } else
                {
                    if ($userActivation == '0')
                    {
                        $profileData['email'] = plgSystemSocialLoginFunctions::generateEmail($profileData['id'], $profileData['Provider']);
                    } else
                    {
                        // Register session variables.
                        $session->set('tmpuser', $profileData);
                        plgSystemSocialLoginTools::emailPopup($settings['popupemailmessage'], $settings['popupemailtitle'], 'msg');
                    }
                }
            }
        }
        $postData = $mainframe->input->getArray();
        // Check user click on enter mail popup submit button.
        if (isset($postData['sociallogin_emailclick']) && !empty($postData['sociallogin_emailclick']))
        {
            $profileData = $session->get('tmpuser');
            if (!empty($postData['session']) && $postData['session'] == $profileData['session'] && !empty($profileData['session']))
            {
                $msg = $settings['popuperroremailmessage'];
                $msgTitle = $settings['popupemailtitle'];
                $msgType = 'warning';
                if (!JMailHelper::isEmailAddress($postData['email']))
                {
                    plgSystemSocialLoginTools::emailPopup($msg, $msgTitle, $msgType);
                    return false;
                } else
                {
                    $email = $db->escape($postData['email']);
                    $query = "SELECT id FROM #__users WHERE email=" . $db->Quote($email);
                    $db->setQuery($query);
                    $userExist = $db->loadResult();
                    if ($userExist != 0)
                    {
                        plgSystemSocialLoginTools::emailPopup($msg, $msgTitle, $msgType);
                        return false;
                    } else
                    {
                        $profileData = $session->get('tmpuser');
                        $profileData['email'] = $email;
                    }
                }
            } else
            {
                $session->clear('tmpuser');
                $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_SESSION_EXPIRED'), 'error');
                return false;
            }
        }
        // Checking user click on popup cancel button.
        else if (isset($postData['cancel']) && !empty($postData['cancel']))
        {
            // Redirect after Cancel click.
            $session->clear('tmpuser');
            $redirect = JURI::base();
            $mainframe->redirect($redirect);
        }
        if (isset($profileData['id']) && !empty($profileData['id']) && !empty($profileData['email']))
        {
            // Filter username form data.
            if (!empty($profileData['fname']) && !empty($profileData['lname']))
            {
                $userName = $profileData['fname'] . $profileData['lname'];
                $name = $profileData['fname'] . ' ' . $profileData['lname'];
            } else
            {
                $userName = plgSystemSocialLoginFunctions::getFilterUserName($profileData);
                $name = plgSystemSocialLoginFunctions::getFilterUserName($profileData);
            }
            $query = "SELECT u.id FROM #__users AS u INNER JOIN #__loginradius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = " . $db->Quote($profileData['id']);
            $db->setQuery($query);
            $userId = $db->loadResult();

            // If not then check for email exist.
            if (empty($userId))
            {
                $query = "SELECT id FROM #__users WHERE email=" . $db->Quote($profileData['email']);
                $db->setQuery($query);
                $userId = $db->loadResult();
                if (!empty($userId))
                {
                    $query = "SELECT LoginRadius_id from #__loginradius_users WHERE LoginRadius_id=" . $db->Quote($profileData['id']) . " AND id = " . $db->Quote($userId);
                    $db->setQuery($query);
                    $checkId = $db->loadResult();
                    if (empty($checkId))
                    {

                        // Add new id to db.
                        $userImage = plgSystemSocialLoginTools::addNewIdImage($profileData['thumbnail'], $profileData['id']);
                        $sql = "INSERT INTO #__loginradius_users SET id = " . $db->Quote($userId) . ", LoginRadius_id = " . $db->Quote($profileData['id']) . ", provider = " . $db->Quote($profileData['Provider']) . ", lr_picture = " . $db->Quote($userImage);
                        $db->setQuery($sql);
                        $db->execute();
                    }
                }
            }
            $newUser = true;
            if (isset($userId))
            {
                $user = JFactory::getUser($userId);
                if ($user->id == $userId)
                {
                    $newUser = false;
                }
            }
            if ($newUser == true)
            {
                $user = new JUser;
                $needVerification = false;

                // If user registration is not allowed, show 403 not authorized.
                $usersConfig = JComponentHelper::getParams('com_users');
                if ($usersConfig->get('allowUserRegistration') == '0')
                {
                    $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_REGISTER_DISABLED'), 'error');
                    return false;
                }

                // Default to Registered.
                $defaultUserGroups = $usersConfig->get('new_usertype', 2);
                if (empty($defaultUserGroups))
                {
                    $defaultUserGroups = 'Registered';
                }

                // if username already exists
                $userName = plgSystemSocialLoginFunctions::getExistUserName($userName);
                // Remove special char if have.
                $userName = plgSystemSocialLoginFunctions::removeUnescapedChar($userName);
                $name = plgSystemSocialLoginFunctions::removeUnescapedChar($name);
                //Insert data
                jimport('joomla.user.helper');
                $userdata = array();
                $userdata ['name'] = $db->escape($name);
                $userdata ['username'] = $db->escape($userName);
                $userdata ['email'] = $profileData['email'];
                $userdata ['usertype'] = 'deprecated';
                $userdata ['groups'] = array($defaultUserGroups);
                /*$userdata ['registerDate'] = $date->toSql();*/
                $userdata ['password'] = JUserHelper::genRandomPassword();
                $userdata ['password2'] = $userdata ['password'];
                $userActivation = $usersConfig->get('useractivation');

                if (isset($postData['sociallogin_emailclick']) && !empty($postData['sociallogin_emailclick']) AND $userActivation != '2')
                {
                    $needVerification = true;
                }

                if ($userActivation == '2' OR $needVerification == true)
                {
                    $userdata ['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
                    $userdata ['block'] = 1;
                } else
                {
                    $userdata ['activation'] = '';
                    $userdata ['block'] = 0;
                }

                if (!$user->bind($userdata))
                {
                    $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_BIND_FAILED'), 'error');
                    return false;
                }

                //Save the user
                if (!$user->save())
                {
                    $mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_REGISTER_FAILED'), 'error');
                    return false;
                }

                $userId = $user->get('id');
                // Trying to insert image.
                $profileImage = $profileData['thumbnail'];
                if (empty($profileImage) || $profileImage == null)
                {
                    $profileImage = JURI::root() . 'media/com_loginradiussocialloginandsocialshare/images/noimage.png';
                }
                $userImage = $userName . $userId . '.jpg';
                $imagePath = JPATH_ROOT . '/images/sociallogin/';
                plgSystemSocialLoginTools::insertUserPicture($imagePath, $profileImage, $userImage);

                // Remove.
                $sql = "DELETE FROM #__loginradius_users WHERE LoginRadius_id = " . $db->Quote($profileData['id']);
                $db->setQuery($sql);
                if ($db->execute())
                {
                    //Add new id to db
                    $sql = "INSERT INTO #__loginradius_users SET id = " . $db->quote($userId) . ",  LoginRadius_id = " . $db->Quote($profileData['id']) . ", provider = " . $db->Quote($profileData['Provider']) . ", lr_picture = " . $db->Quote($userImage);
                    $db->setQuery($sql);
                    $db->execute();
                }
                // third party community support.
                plgSystemSocialLoginFunctions::communities($userId, $profileImage, $userImage, $profileData);

                // Handle account activation/confirmation emails.
                if ($userActivation == '2' OR $needVerification == true)
                {
                    if ($needVerification == true)
                    {
                        $userMessage = 3;
                        plgSystemSocialLoginTools::sendMail($user, $userMessage);
                        $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
                        $session->clear('tmpuser');
                        return false;
                    } else
                    {
                        $userMessage = 1;
                        plgSystemSocialLoginTools::sendMail($user, $userMessage);
                        $mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
                        $session->clear('tmpuser');
                        return false;
                    }
                } else
                {
                    $userMessage = 2;
                    plgSystemSocialLoginTools::sendMail($user, $userMessage);
                }
            }

            //updata user profile data on login the user
            else if ($newUser == false && $settings['updateuserdata'] == 1)
            {
                // Remove special char if have.
                $userName = plgSystemSocialLoginFunctions::removeUnescapedChar($userName);
                $name = plgSystemSocialLoginFunctions::removeUnescapedChar($name);
                $user = JUser::getInstance($userId);

                $user->name = $name;
                //update the user
                $userUpdate = false;
                if (!$user->save(true))
                {
                    $userUpdate = true;
                }
                if ($userUpdate == false)
                {
                    $userId = $user->get('id');
                    // Saving user extra profile.
                    // Trying to insert image.
                    $profileImage = $profileData['thumbnail'];
                    if (empty($profileImage) || $profileImage == null)
                    {
                        $profileImage = JURI::root() . 'media/com_loginradiussocialloginandsocialshare/images/noimage.png';
                    }
                    $userImage = $userName . $userId . '.jpg';
                    $imagePath = JPATH_ROOT . '/images/sociallogin/';
                    plgSystemSocialLoginTools::insertUserPicture($imagePath, $profileImage, $userImage);
                    // Remove.
                    $sql = "DELETE FROM #__loginradius_users WHERE LoginRadius_id = " . $db->Quote($profileData['id']);
                    $db->setQuery($sql);
                    if ($db->query())
                    {
                        //Add new id to db
                        $sql = "INSERT INTO #__loginradius_users SET id = " . $db->quote($userId) . ",  LoginRadius_id = " . $db->Quote($profileData['id']) . ", provider = " . $db->Quote($profileData['Provider']) . ", lr_picture = " . $db->Quote($userImage);
                        $db->setQuery($sql);
                        $db->query();
                    }
                    // third party community support.
                    plgSystemSocialLoginFunctions::communities($userId, $profileImage, $userImage, $profileData);
                }
            }
        }
        if (isset($userId) && $userId)
        {
            plgSystemSocialLoginTools::loginExistUser($userId, $profileData, $newUser);
        }
    }

}
