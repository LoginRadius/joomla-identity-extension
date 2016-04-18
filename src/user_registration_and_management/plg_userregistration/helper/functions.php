<?php
/**
 * @package      UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * User Registration plugin helper class.
 */
class plgSystemUserRegistrationFunctions
{

    /**
     * @param $username
     * @return string
     */
    public static function getExistUserName($username)
    {
        $exists = true;
        $index = 0;
        $userName = $username;

        while ($exists == true)
        {
            if (JUserHelper::getUserId($userName) != 0)
            {
                $index++;
                $userName = $username . $index;
            } else
            {
                $exists = false;
            }
        }
        return plgSystemUserRegistrationFunctions::removeUnescapedChar($userName);
    }

    /**
     * @param $profileData
     * @return string
     */
    public static function generateEmail($socialId, $provider)
    {
        $emailName = substr(str_replace(array("-","/","."),"_",$socialId), -10);
        $email = $emailName.'@'.$provider.'.com';
        $userId = plgSystemUserRegistrationTools::getUserIdByEmail($email);
        if(!empty($userId)){
            $socialId = $emailName.rand();
            $email = self::generateEmail($socialId, $provider);
        }
        return $email;
    }

    /**
     * @param $profileData
     * @return mixed
     */
    public static function getFilterUserName($profileData)
    {
        if (!empty($profileData->FullName))
        {
            $username = $profileData->FullName;
        } elseif (!empty($profileData->ProfileName))
        {
            $username = $profileData->ProfileName;
        } elseif (!empty($profileData->NickName))
        {
            $username = $profileData->NickName;
        } elseif (!empty($profileData->email))
        {
            $temp = explode('@', $profileData->email);
            $username = $temp[0];
        } else
        {
            $username = $profileData->id;
        }

        $username = self::removeSpaceChar($username);

        return $username;
    }

    /**
     * get k2 user id from db
     * 
     * @param $id
     * @return mixed
     */
    public static function getK2UserID($id)
    {
        $db = JFactory::getDBO();

        $query = "SELECT id FROM #__k2_users WHERE userID=" . $db->Quote($id);
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * insert and update user data in communitys
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function communities($userId, $userName, $userProfile)
    {
        $profileImage = !empty($userProfile->thumbnail) ? $userProfile->thumbnail : JURI::root() . 'media' . DS . 'com_userregistrationandmanagement' . DS . 'images' . DS . 'noimage.png';
        $userImage = $userName . $userId . '.jpg';
        // check for the community builder works.
        self::makeComProfileUser($userId, $profileImage, $userImage, $userProfile);
        // check for the k2 works.
        self::makeK2User($userId, $profileImage, $userImage, $userProfile);
        // Check for kunena profile.
        self::makeKunenaUser($userId, $profileImage, $userImage, $userProfile);
        // check for the jom social works.
        self::makeJomSocialUser($userId, $profileImage, $userImage);
    }

    /**
     * insert and update profile in community builder
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeComProfileUser($userId, $profileImage, $userImage, $profileData)
    {
        $db = JFactory::getDBO();        
        if (JPluginHelper::isEnabled('system', 'communitybuilder'))
        {
            $firstName = self::removeUnescapedChar($profileData->FirstName);
            $lastName = self::removeUnescapedChar($profileData->LastName);
            $path = JPATH_ROOT . DS . 'images' . DS . 'comprofiler' . DS;
            plgSystemUserRegistrationTools::insertUserPicture($path, $profileImage, $userImage);
            plgSystemUserRegistrationTools::insertUserPicture($path, $profileImage, 'tn'.$userImage);
            $query = "SELECT id FROM #__comprofiler WHERE id=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId))
            {
                $cbQuery = "UPDATE #__comprofiler SET `firstname` = " . $db->Quote($firstName) . ",`lastname` = " . $db->Quote($lastName) . ",`avatar` = " . $db->Quote($userImage) . " WHERE id=" . $db->Quote($userId);
            } else
            {
                $cbQuery = "INSERT INTO #__comprofiler (`id`,`user_id`,`firstname`,`lastname`,`avatar`) VALUES (" . $db->Quote($userId) . "," . $db->Quote($userId) . "," . $db->Quote($firstName) . "," . $db->Quote($lastName) . "," . $db->Quote($userImage) . ")";
            }
            $db->setQuery($cbQuery);
            $db->query();
        }
    }

    /**
     * remove spacial char from string
     * 
     * @param $str
     * @return mixed|string
     */
    public static function removeUnescapedChar($str)
    {
        $string = str_replace(array('<', '>', '&', '{', '}', '*', '/', '(', '[', ']', '@', '!', ')', '&', '*', '#', '$', '%', '^', '|', '?', '+', '=', '"', ','), array(''), $str);
        $encoding = mb_detect_encoding($string);

        if ($encoding == "UTF-8" && mb_check_encoding($string, "UTF-8"))
        {
            return $string;
        }

        return utf8_encode($string);
    }

    /**
     * remove space char from string
     * 
     * @param type $string
     * @return type
     */
    public static function removeSpaceChar($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * insert and update profile in k2
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeK2User($userId, $profileImage, $userImage, $profileData)
    {
        if (JPluginHelper::isEnabled('system', 'k2'))
        {
            $db = JFactory::getDBO();
            $username = self::removeUnescapedChar($profileData->FullName);
            $settings = plgSystemUserRegistrationTools::getSettings();

            $settings['k2group'] = isset($settings['k2group']) ? $settings['k2group'] : '';
            $path = JPATH_ROOT . DS . 'media' . DS . 'k2' . DS . 'users' . DS;
            plgSystemUserRegistrationTools::insertUserPicture($path, $profileImage, $userImage);
            $gender = 'f';
            if ($profileData->gender == 'M')
            {
                $gender = 'm';
            }

            $query = "SELECT id FROM #__k2_users WHERE id=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId))
            {
                $k2query = "UPDATE #__k2_users SET `gender` = " . $db->Quote($gender) . ", `description` = " . $db->Quote($profileData->aboutme) . ", `image` = " . $db->Quote($userImage) . ", `url` = " . $db->Quote($profileData->website) . " WHERE id=" . $db->Quote($userId);
            } else
            {
                $k2query = "INSERT INTO #__k2_users(`id`,`userID`,`userName`,`gender`,`description`,`image`,`url`,`group`,`ip`,`hostname`,`notes`) VALUES (" . $db->Quote($userId) . "," . $db->Quote($userId) . "," . $db->Quote($username) . "," . $db->Quote($gender) . "," . $db->Quote($profileData->aboutme) . "," . $db->Quote($userImage) . "," . $db->Quote($profileData->website) . "," . $db->Quote(trim($settings['k2group'])) . "," . $db->Quote($_SERVER['REMOTE_ADDR']) . "," . $db->Quote(gethostbyaddr($_SERVER['REMOTE_ADDR'])) . ",'')";
            }

            $db->setQuery($k2query);
            $db->query();
        }
    }

    /**
     * insert and update profile in Kunena fourm
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeKunenaUser($userId, $profileImage, $userImage, $profileData)
    {
        if (JPluginHelper::isEnabled('system', 'kunena'))
        {
            $db = JFactory::getDBO();
            $userImage = 'avatar' . $userImage;

            if (in_array($profileData->gender, array('M', 'm', 'Male', 'male')))
            {
                $profileData->gender = '1';
            } else if (in_array($profileData->gender, array('F', 'f', 'Female', 'female')))
            {
                $profileData->gender = '2';
            }

            $path = JPATH_ROOT . DS . 'media' . DS . 'kunena' . DS . 'avatars' . DS . 'users' . DS;
            $dumpUserImage = 'users/' . $userImage;
            plgSystemUserRegistrationTools::insertUserPicture($path, $profileImage, $userImage);

            $query = "SELECT userid FROM #__kunena_users WHERE userid=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId))
            {
                $kunenaQuery = "UPDATE #__kunena_users SET `avatar` = " . $db->Quote($dumpUserImage) . ",`gender` = " . $db->Quote($profileData->gender) . ",`birthdate` = " . $db->Quote($profileData->dob) . ",`location` = " . $db->Quote($profileData->city) . ",`personalText` = " . $db->Quote($profileData->aboutme) . ",`websiteurl` = " . $db->Quote($profileData->website) . " WHERE `userid` = " . $db->Quote($userId);
            } else
            {
                $kunenaQuery = "INSERT INTO #__kunena_users (`userid`,`avatar`,`gender`,`birthdate`,`location`,`personalText`,`websiteurl`) VALUES(" . $db->Quote($userId) . "," . $db->Quote($dumpUserImage) . "," . $db->Quote($profileData->gender) . "," . $db->Quote($profileData->dob) . "," . $db->Quote($profileData->city) . "," . $db->Quote($profileData->aboutme) . "," . $db->Quote($profileData->website) . ")";
            }
            $db->setQuery($kunenaQuery);
            $db->query();
        }
    }

    /**
     * insert and update profile in jom social
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     */
    public static function makeJomSocialUser($userId, $profileImage, $userImage)
    {
        if (JPluginHelper::isEnabled('system', 'jomsocialconnect'))
        {
            $db = JFactory::getDBO();

            $query = "SHOW TABLES LIKE '#__community_users'";
            $db->setQuery($query);
            $jomTableExists = $db->loadResult();

            if (isset($jomTableExists))
            {
                // Check for jom social.
                $path = JPATH_ROOT . DS . 'images' . DS . 'avatar' . DS;
                $avatar = 'images/avatar/' . $userImage;
                plgSystemUserRegistrationTools::insertUserPicture($path, $profileImage, $userImage);

                $query = "SELECT userid FROM #__community_users WHERE userid=" . $db->Quote($userId);
                $db->setQuery($query);
                $updateId = $db->loadResult();

                if (!empty($updateId))
                {
                    $jsQuery = "UPDATE #__community_users SET `avatar` = " . $db->Quote($avatar) . ",`thumb` = " . $db->Quote($avatar) . " WHERE userid=" . $db->Quote($userId);
                } else
                {
                    $jsQuery = "INSERT INTO #__community_users(`userid`,`avatar`,`thumb`) VALUES(" . $db->Quote($userId) . "," . $db->Quote($avatar) . "," . $db->Quote($avatar) . ")";
                }
                $db->setQuery($jsQuery);
                $db->query();
            }
        }
    }

}
