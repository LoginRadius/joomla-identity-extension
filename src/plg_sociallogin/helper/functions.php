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
class plgSystemSocialLoginFunctions {

    /**
     * @param $username
     * @return string
     */
    public static function getExistUserName($username) {
        $exists = true;
        $index = 0;
        $userName = $username;

        while ($exists == true) {
            if (JUserHelper::getUserId($userName) != 0) {
                $index++;
                $userName = $username . $index;
            } else {
                $exists = false;
            }
        }

        return $userName;
    }

    /**
     * generate email with social id and provider
     * 
     * @param $profileData
     * @return string
     */
    public static function generateEmail($socialId, $provider) {
        $replace = 'str_' . 'replace';
        $emailName = substr($replace(array("-", "/", "."), "_", $socialId), -10);
        $email = $emailName . '@' . $provider . '.com';
        $userId = plgSystemSocialLoginTools::getUserIdByEmail($email);
        if (!empty($userId)) {
            $socialId = $emailName . rand();
            $email = self::generateEmail($socialId, $provider);
        }
        return $email;
    }

    /**
     * filter user name form social profile data
     * 
     * @param $profileData
     * @return mixed
     */
    public static function getFilterUserName($profileData) {
        if (!empty($profileData['FullName'])) {
            $username = $profileData['FullName'];
        } elseif (!empty($profileData['ProfileName'])) {
            $username = $profileData['ProfileName'];
        } elseif (!empty($profileData['NickName'])) {
            $username = $profileData['NickName'];
        } elseif (!empty($profileData['email'])) {
            $temp = explode('@', $profileData['email']);
            $username = $temp[0];
        } else {
            $username = $profileData['id'];
        }

        $username = self::removeSpaceChar($username);

        return $username;
    }

    /**
     * get k2 user ID
     * 
     * @param $id
     * @return mixed
     */
    public static function getK2UserID($id) {
        $db = JFactory::getDBO();

        $query = "SELECT id FROM #__k2_users WHERE userID=" . $db->Quote($id);
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * save and contarol all community functions
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function communities($userId, $profileImage, $userImage, $profileData) {
        // check for the community builder works.
        self::makeComProfileUser($userId, $profileImage, $userImage, $profileData);
        // check for the k2 works.
        self::makeK2User($userId, $profileImage, $userImage, $profileData);
        // Check for kunena profile.
        self::makeKunenaUser($userId, $profileImage, $userImage, $profileData);
        // check for the jom social works.
        self::makeJomSocialUser($userId, $profileImage, $userImage);
    }

    /**
     * save user profile data in community builder
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeComProfileUser($userId, $profileImage, $userImage, $profileData) {
        $db = JFactory::getDBO();

        if (JPluginHelper::isEnabled('system', 'communitybuilder')) {
            $firstName = self::removeUnescapedChar($profileData['fname']);
            $lastName = self::removeUnescapedChar($profileData['lname']);
            $path = JPATH_ROOT . '/images/comprofiler/';
            plgSystemSocialLoginTools::insertUserPicture($path, $profileImage, $userImage);
            plgSystemSocialLoginTools::insertUserPicture($path, $profileImage, 'tn' . $userImage);
            $query = "SELECT id FROM #__comprofiler WHERE id=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId)) {
                $cbQuery = "UPDATE #__comprofiler SET firstname = " . $db->Quote($firstName) . ",lastname = " . $db->Quote($lastName) . ",avatar = " . $db->Quote($userImage) . " WHERE id=" . $db->Quote($userId);
            } else {
                $cbQuery = $db->getQuery(true);
                $cbQuery->insert($db->quoteName('#__comprofiler'))
                        ->columns(
                                array(
                                    $db->quoteName('id'),
                                    $db->quoteName('user_id'),
                                    $db->quoteName('firstname'),
                                    $db->quoteName('lastname'),
                                    $db->quoteName('avatar')
                                )
                );

                $cbQuery->values(
                        $db->quote($userId) . ', '
                        . $db->quote($userId) . ', '
                        . $db->quote($firstName) . ', '
                        . $db->quote($lastName) . ', '
                        . $db->quote($userImage)
                );
            }
            $db->setQuery($cbQuery);
            $db->query();
        }
    }

    /**
     * remove all spacial char from given string value
     * 
     * @param $str
     * @return mixed|string
     */
    public static function removeUnescapedChar($str) {
        $replace = 'str_' . 'replace';
        $string = $replace(array('<', '>', '&', '{', '}', '*', '/', '(', '[', ']', '@', '!', ')', '&', '*', '#', '$', '%', '^', '|', '?', '+', '=', '"', ','), array(''), $str);
        $encoding = mb_detect_encoding($string);

        if ($encoding == "UTF-8" && mb_check_encoding($string, "UTF-8")) {
            return $string;
        }

        return utf8_encode($string);
    }

    /**
     * remove spaces from string value
     * 
     * @param type $string
     * @return type
     */
    public static function removeSpaceChar($string) {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * save user profile data in K2
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeK2User($userId, $profileImage, $userImage, $profileData) {
        if (JPluginHelper::isEnabled('system', 'k2')) {
            $db = JFactory::getDBO();
            $username = self::removeUnescapedChar($profileData['FullName']);
            $settings = plgSystemSocialLoginTools::getSettings();

            $settings['k2group'] = isset($settings['k2group']) ? $settings['k2group'] : '';
            $path = JPATH_ROOT . '/media/k2/users/';
            plgSystemSocialLoginTools::insertUserPicture($path, $profileImage, $userImage);
            $gender = 'f';
            if ($profileData['gender'] == 'M') {
                $gender = 'm';
            }

            $query = "SELECT id FROM #__k2_users WHERE id=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId)) {
                $k2query = "UPDATE #__k2_users SET gender = " . $db->Quote($gender) . ", description = " . $db->Quote($profileData['aboutme']) . ", image = " . $db->Quote($userImage) . ", url = " . $db->Quote($profileData['website']) . " WHERE id=" . $db->Quote($userId);
            } else {
                $k2query = $db->getQuery(true);
                $k2query->insert($db->quoteName('#__k2_users'))
                        ->columns(
                                array(
                                    $db->quoteName('id'),
                                    $db->quoteName('userID'),
                                    $db->quoteName('userName'),
                                    $db->quoteName('gender'),
                                    $db->quoteName('description'),
                                    $db->quoteName('image'),
                                    $db->quoteName('url'),
                                    $db->quoteName('group'),
                                    $db->quoteName('ip'),
                                    $db->quoteName('hostname')
                                )
                );

                $k2query->values(
                        $db->Quote($userId) . ", "
                        . $db->Quote($userId) . ", "
                        . $db->Quote($username) . ", "
                        . $db->Quote($gender) . ", "
                        . $db->Quote($profileData['aboutme']) . ", "
                        . $db->Quote($userImage) . ", "
                        . $db->Quote($profileData['website']) . ", "
                        . $db->Quote(trim($settings['k2group'])) . ", "
                        . $db->Quote($_SERVER['REMOTE_ADDR']) . ", "
                        . $db->Quote(gethostbyaddr($_SERVER['REMOTE_ADDR']))
                );
            }

            $db->setQuery($k2query);
            $db->query();
        }
    }

    /**
     * save user profile data in kunena
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     * @param $profileData
     */
    public static function makeKunenaUser($userId, $profileImage, $userImage, $profileData) {
        if (JPluginHelper::isEnabled('system', 'kunena')) {
            $db = JFactory::getDBO();
            $userImage = 'avatar' . $userImage;

            if (in_array($profileData['gender'], array('M', 'm', 'Male', 'male'))) {
                $profileData['gender'] = '1';
            } else if (in_array($profileData['gender'], array('F', 'f', 'Female', 'female'))) {
                $profileData['gender'] = '2';
            }

            $path = JPATH_ROOT . '/media/kunena/avatars/users/';
            $dumpUserImage = 'users/' . $userImage;
            plgSystemSocialLoginTools::insertUserPicture($path, $profileImage, $userImage);

            $query = "SELECT userid FROM #__kunena_users WHERE userid=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId)) {
                $kunenaQuery = "UPDATE #__kunena_users SET avatar = " . $db->Quote($dumpUserImage) . ",gender = " . $db->Quote($profileData['gender']) . ",birthdate = " . $db->Quote($profileData['dob']) . ",location = " . $db->Quote($profileData['city']) . ",personalText = " . $db->Quote($profileData['aboutme']) . ",websiteurl = " . $db->Quote($profileData['website']) . " WHERE userid = " . $db->Quote($userId);
            } else {
                $kunenaQuery = $db->getQuery(true);
                $kunenaQuery->insert($db->quoteName('#__kunena_users'))
                        ->columns(
                                array(
                                    $db->quoteName('userid'),
                                    $db->quoteName('avatar'),
                                    $db->quoteName('gender'),
                                    $db->quoteName('birthdate'),
                                    $db->quoteName('location'),
                                    $db->quoteName('personalText'),
                                    $db->quoteName('websiteurl')
                                )
                );

                $kunenaQuery->values(
                        $db->Quote($userId) . ", "
                        . $db->Quote($dumpUserImage) . ", "
                        . $db->Quote($profileData['gender']) . ", "
                        . $db->Quote($profileData['dob']) . ", "
                        . $db->Quote($profileData['city']) . ", "
                        . $db->Quote($profileData['aboutme']) . ", "
                        . $db->Quote($profileData['website'])
                );
            }
            $db->setQuery($kunenaQuery);
            $db->query();
        }
    }

    /**
     * save user profile data in JomSocial
     * 
     * @param $userId
     * @param $profileImage
     * @param $userImage
     */
    public static function makeJomSocialUser($userId, $profileImage, $userImage) {
        if (JPluginHelper::isEnabled('system', 'jomsocialconnect')) {
            $db = JFactory::getDBO();
            // Check for jom social.
            $path = JPATH_ROOT . '/images/avatar/';
            $avatar = 'images/avatar/' . $userImage;
            plgSystemSocialLoginTools::insertUserPicture($path, $profileImage, $userImage);

            $query = "SELECT userid FROM #__community_users WHERE userid=" . $db->Quote($userId);
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId)) {
                $jsQuery = "UPDATE #__community_users SET avatar = " . $db->Quote($avatar) . ",thumb = " . $db->Quote($avatar) . " WHERE userid=" . $db->Quote($userId);
            } else {
                $jsQuery = $db->getQuery(true);
                $jsQuery->insert($db->quoteName('#__community_users'))
                        ->columns(
                                array(
                                    $db->quoteName('userid'),
                                    $db->quoteName('avatar'),
                                    $db->quoteName('thumb')
                                )
                );

                $jsQuery->values(
                        $db->Quote($userId) . ", "
                        . $db->Quote($avatar) . ", "
                        . $db->Quote($avatar)
                );
            }
            $db->setQuery($jsQuery);
            $db->query();
        }
    }

}
