<?php

/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * SocialLogin plugin helper class.
 */
class plgSystemSocialLoginTools
{

    /**
     * display email popup on registration time if not found
     * 
     * @param $msg
     * @param $msgTitle
     * @param $msgType
     */
    public static function emailPopup($msg, $msgTitle, $msgType)
    {
        $replace = 'str_'.'replace';
        $document = JFactory::getDocument();
        $session = JFactory::getSession();

        $profileData = $session->get('tmpuser');

        $msgTitle = $replace('@provider', $profileData['Provider'], $msgTitle);
        $msg = $replace('@provider', $profileData['Provider'], $msg);
        $message = '<div id="textmatter" class="socialnoerror">';
        if ($msgType == 'warning')
        {
            $message = '<div id="loginRadiusError" class="socialerror">';
        }
        $document->addStyleSheet(JURI::root() . 'plugins/system/sociallogin/assets/popupstyle.css');

        $output = '<div class="socialoverlay">';
        $output .= '<div id="popupouter"><form id="socialemailrequired" name="socialemailrequired" method="post" action=""><div class="socialpopupheading"> ' . $msgTitle . '</div>';
        $output .= '<div id="popupinner">' . $message . $msg . '</div>';
        $output .= '<div class="emailtext">' . JText::_('COM_SOCIALLOGIN_POPUP_DESC') . '</div>';
        $output .= '<input type="text" name="email" id="email" class="inputtxt"/></div><div class="footerbox">';
        $output .= '<input type="submit" name="sociallogin_emailclick" value="' . JText::_('JLOGIN') . '" class="inputbutton"/>';
        $output .= '<input type="submit" value="' . JText::_('JCANCEL') . '" name = "cancel" class="inputbutton"/>';
        $output .= '<input type="hidden" name ="session" value="' . $profileData['session'] . '"/>';
        $output .= '</div></form></div></div>';

        $document->addCustomTag($output);
    }

    /**
     * check email exist in local db
     * 
     * @param type $email
     * @return type
     */
    public static function getUserIdByEmail($email){
        $db = JFactory::getDbo();
        $query = "SELECT id FROM #__users WHERE email=" . $db->Quote($email);
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    /**
     * get userprofile data in array 
     * 
     * @param $userProfile
     * @return mixed
     */
    public static function getUserProfileData($userProfile)
    {
        $profileData['session'] = uniqid('LoginRadius_', true);
        $profileData['id'] = self::checkVariable($userProfile->ID);
        $profileData['Provider'] = self::checkVariable($userProfile->Provider);
        $profileData['fname'] = self::checkVariable($userProfile->FirstName);
        $profileData['lname'] = self::checkVariable($userProfile->LastName);
        $profileData['FullName'] = self::checkVariable($userProfile->FullName);
        $profileData['NickName'] = self::checkVariable($userProfile->NickName);
        $profileData['ProfileName'] = self::checkVariable($userProfile->ProfileName);
        $profileData['dob'] = self::checkVariable($userProfile->BirthDate);
        $profileData['gender'] = self::checkVariable($userProfile->Gender);
        $profileData['email'] = (sizeof($userProfile->Email) > 0 ? trim($userProfile->Email[0]->Value) : "");
        $profileData['thumbnail'] = self::checkVariable($userProfile->ImageUrl);
        $profileData['address1'] = self::checkVariable($userProfile->Addresses);
        $profileData['thumbnail'] = self::getFacebookUserImage($profileData['id'], $profileData['thumbnail'], $profileData['Provider']);

        if (empty($profileData['address1']))
        {
            $profileData['address1'] = self::checkVariable($userProfile->MainAddress);
        }

        $profileData['address2'] = $profileData['address1'];
        $profileData['city'] = self::checkVariable($userProfile->City);

        if (empty($profileData['city']))
        {
            $profileData['city'] = self::checkVariable($userProfile->HomeTown);
        }

        $profileData['country'] = isset($userProfile->Country) ? $userProfile->Country : '';

        if (!empty($profileData['country']))
        {
            $profileData['country'] = self::checkVariable($userProfile->Country->Name);
        }

        $profileData['aboutme'] = self::checkVariable($userProfile->About);
        $profileData['website'] = self::checkVariable($userProfile->ProfileUrl);
        $profileData['dob'] = self::calculateBirthDate($profileData['dob']);

        return $profileData;
    }

    /**
     * get facebook profile avatar if not get from facebook
     * @param $socialId
     * @param $thumbnail
     * @param $provider
     * @return string
     */
    private static function getFacebookUserImage($socialId, $thumbnail, $provider)
    {

        if (empty($thumbnail) && $provider == 'facebook')
        {
            $thumbnail = "http://graph.facebook.com/" . $socialId . "/picture?type=square";
        }
        return $thumbnail;
    }

    /**
     * check and trim given variable
     * 
     * @param $var
     * @return string
     */
    private static function checkVariable($var)
    {
        if (!empty($var) && is_string($var))
        {
            return trim($var);
        }
        return $var;
    }

    /**
     * format user DOB in Y-m-d format
     * 
     * @param $birthDate
     * @param $provider
     * @return bool|string
     */
    private static function calculateBirthDate($birthDate)
    {
        return date('Y-m-d', strtotime($birthDate));
    }

    /**
     * Function that inserting data in cb table.
     * 
     * @param string $thumbnail
     * @param type $socialId
     * @return type
     */
    public static function addNewIdImage($thumbnail, $socialId)
    {
        if (empty($thumbnail))
        {
            $thumbnail = JURI::root() . 'media/com_loginradiussocialloginandsocialshare/images/noimage.png';
        }

        $userImage = $socialId . '.jpg';
        $find = strpos($userImage, 'http');

        if ($find !== false)
        {
            $userImage = substr($userImage, 8);
            $userImage = plgSystemSocialLoginFunctions::removeUnescapedChar($userImage);
        }

        $path = JPATH_ROOT . '/images/sociallogin/';
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
    public static function insertUserPicture($path, $profileImage, $userImage)
    {
        $settings = self::getSettings();
        if (!JFolder::exists($path))
        {
            JFolder::create($path);
        }
        if($profileImage == JURI::root() . 'media/com_loginradiussocialloginandsocialshare/images/noimage.png'){
            copy(JPATH_ROOT . '/media/com_loginradiussocialloginandsocialshare/images/noimage.png', $path . $userImage);
        }else if ($settings['useapi'] == 1)
        {
            $curlHandle = curl_init($profileImage);
            $fp = fopen($path . $userImage, 'wb');
            curl_setopt($curlHandle, CURLOPT_FILE, $fp);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($curlHandle);
            curl_close($curlHandle);
            fclose($fp);
        } else
        {
            $thumbImage = @file_get_contents($profileImage);
            if (@$http_response_header == NULL)
            {
                $replace = 'str_'.'replace';
                $profileImage = $replace('https', 'http', $profileImage);
                $thumbImage = @file_get_contents($profileImage);
            }
            if (empty($thumbImage))
            {
                $thumbImage = @file_get_contents(JURI::root() . 'media/com_loginradiussocialloginandsocialshare/images/noimage.png');
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
    public static function getSettings()
    {
        $settings = array();

        $db = JFactory::getDBO();

        $sql = "SELECT * FROM #__loginradius_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();

        if (is_array($rows))
        {
            foreach ($rows AS $key => $data)
            {
                $settings [$data['setting']] = $data ['value'];
            }
        }

        return $settings;
    }

    /**
     * send emails  to user and admin by joomla default email functionality
     * 
     * @param $user
     * @param $userMessage
     * @return bool
     */
    public static function sendMail(&$user, $userMessage)
    {
        // Compile the notification mail values.
        $db = JFactory::getDBO();

        $settings = self::getSettings();
        $config = JFactory::getConfig();
        $data = $user->getProperties();

        $data['fromname'] = $config->get('fromname');
        $data['mailfrom'] = $config->get('mailfrom');
        $data['sitename'] = $config->get('sitename');
        $data['siteurl'] = JUri::base();
        $uri = JURI::getInstance();

        $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
        $data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation'], false);
        $emailSubject = JText::sprintf('COM_USERS_EMAIL_ACCOUNT_DETAILS', $data['name'], $data['sitename']);
        switch ($userMessage)
        {
            case 1:
                $emailBody = JText::sprintf('COM_SOCIALLOGIN_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY', $data['name'], $data['sitename'], $data['activate'], $data['siteurl'], $data['username'], $data['password_clear']);
                break;
            case 2:
                $emailBody = JText::sprintf('COM_SOCIALLOGIN_SEND_MSG', $data['name'], $data['sitename'], $data['siteurl'] . 'index.php', $data['username'], $data['password_clear']);
                break;
            case 3:
                $emailBody = JText::sprintf('COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY', $data['name'], $data['sitename'], $data['activate'], $data['siteurl'], $data['username'], $data['password_clear']);
                break;
        }

        // Send the registration email.
        $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

        // Check for an error.
        if ($return !== true)
        {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'), 'error');
            // Send a system message to administrators receiving system mails
            $q = "SELECT id FROM #__users WHERE block = 0 AND sendEmail = 1";
            $db->setQuery($q);
            $sendEmail = $db->loadColumn();

            if (count($sendEmail) > 0)
            {
                $jdate = new JDate();
                // Build the query to add the messages
                $q = "INSERT INTO #__messages (user_id_from, user_id_to, date_time, subject, message)	VALUES ";
                $messages = array();

                foreach ($sendEmail as $userid)
                {
                    $messages[] = "(" . $db->Quote($userid) . ", " . $db->Quote($userid) . ", " . $db->Quote($jdate->toSql()) . ", " . $db->Quote(JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')) . ", " . $db->Quote(JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])) . ")";
                }

                $q .= implode(',', $messages);
                $db->setQuery($q);
                $db->execute();
            }

            return false;
        } elseif ($settings['sendemail'] == 1 && $userMessage == 2)
        {
            // get all admin users
            $query = 'SELECT name, email, sendEmail FROM #__users WHERE sendEmail=1';
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            // Send mail to all super administrators id
            foreach ($rows as $row)
            {
                JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, JText::sprintf('COM_SOCIALLOGIN_SEND_MSG_ADMIN', $row->name, $data['sitename'], $data['siteurl'], $data['email'], $data['username'], $data['password_clear']));
            }
        }

        return false;
    }

    /**
     * Function that remove unescaped char from string.
     * 
     * @param $userId
     * @param $profileData
     * @param $newUser
     */
    public static function loginExistUser($userId, $profileData, $newUser)
    {
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();
        $user = JUser::getInstance((int) $userId);

        // Register session variables
        $session = JFactory::getSession();
        $query = "SELECT lr_picture from #__loginradius_users WHERE LoginRadius_id=" . $db->Quote($profileData['id']) . " AND id = " . $user->get('id');
        $db->setQuery($query);
        $getImage = $db->loadResult();

        $session->set('user_picture', $getImage);
        $session->set('user_lrid', $profileData['id']);
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
        $session->clear('tmpuser');

        //Redirect after Login
        $redirect = self::getReturnURL($newUser);
        $mainframe->redirect($redirect);
    }

    /**
     * get redirection url to redirect user after sucessfully login
     * 
     * @param bool $returnUser
     * @return mixed|null|string
     */
    public static function getReturnURL($returnUser = false)
    {
        $app = JFactory::getApplication();
        $router = $app->getRouter();
        $settings = self::getSettings();
        $checkRewrite = $app->getCfg('sef_rewrite');
        $db = JFactory::getDbo();
        $url = null;
        $redirection = $settings['loginredirection'];
        if ($returnUser)
        {
            $redirection = $settings['registerredirection'];
        }
        if ($redirection)
        {
            if ($router->getMode() == JROUTER_MODE_SEF)
            {
                $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($redirection);
                $db->setQuery($query);
                $url = $db->loadResult();
                if ($checkRewrite == '0' AND ! empty($url))
                {
                    $url = 'index.php/' . $url;
                }
            } else
            {
                $query = "SELECT link FROM #__menu WHERE id = " . $db->Quote($redirection);
                $db->setQuery($query);
                $url = $db->loadResult();
            }
        }
        if (!$url)
        {
            // stay on the same page
            $uri = clone JFactory::getURI();
            $vars = $router->parse($uri);
            unset($vars['lang']);
            if ($router->getMode() == JROUTER_MODE_SEF)
            {
                if (isset($vars['Itemid']))
                {
                    $itemId = $vars['Itemid'];
                    $menu = $app->getMenu();
                    $item = $menu->getItem($itemId);
                    unset($vars['Itemid']);
                    if (isset($item) && $vars == $item->query)
                    {
                        $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($itemId) . " AND home = 1";
                        $db->setQuery($query);
                        $homeUrl = $db->loadResult();
                        if ($homeUrl)
                        {
                            $url = 'index.php';
                        } else
                        {
                            $query = "SELECT path FROM #__menu WHERE id = " . $db->Quote($itemId);
                            $db->setQuery($query);
                            $url = $db->loadResult();
                            if ($checkRewrite == '0' AND ! empty($url))
                            {
                                $url = 'index.php/' . $url;
                            }
                        }
                    } else
                    {
                        // get article url path
                        $articlePath = JFactory::getURI()->getPath();
                        $url = $articlePath;
                    }
                } else
                {
                    $articlePath = JFactory::getURI()->getPath();
                    $url = $articlePath;
                }
            } else
            {
                $fullUrl = urldecode($_SERVER['HTTP_REFERER']);
                if (strpos($fullUrl, "callback=") > 0)
                {
                    $urlData = explode("callback=", $fullUrl);
                    $tampPos = strpos($urlData[1], "&");
                    $endLimit = strlen($urlData[1]);
                    if ($tampPos > 0)
                    {
                        if (strpos($_SERVER['QUERY_STRING'], '&') > 0)
                        {
                            $url = 'index.php?' . $_SERVER['QUERY_STRING'];
                        } else
                        {
                            $url = 'index.php?' . $_SERVER['QUERY_STRING'] . substr($urlData[1], $tampPos, $endLimit);
                        }
                    }
                }
            }
        }
        return $url;
    }

}
