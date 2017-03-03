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
        $query = $db->getQuery(true)
                            ->select('id')
                            ->from($db->quoteName('#__k2_users'))
                            ->where($db->quoteName('userID') . " = " . $db->quote($id));
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
            $query = $db->getQuery(true)
                            ->select('id')
                            ->from($db->quoteName('#__comprofiler'))
                            ->where($db->quoteName('id') . " = " . $db->quote($userId));  
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId))
            {
                    $cbQuery = $db->getQuery(true);
		    $cbQuery->update($db->quoteName('#__comprofiler'))					
						->set($db->quoteName('firstname') . ' = ' . $db->quote($firstName))
						->set($db->quoteName('lastname') . ' = ' . $db->quote($lastName))
						->set($db->quoteName('avatar') . ' = ' . $db->quote($userImage))
						->where($db->quoteName('id') . ' = ' . $db->Quote($userId));                
              
            } else
            {
                $columns = array('id', 'user_id', 'firstname', 'lastname', 'avatar');
                $values = array($db->quote($userId), $db->quote($userId), $db->quote($firstName), $db->quote($lastName), $db->quote($userImage));
                $cbQuery = $db->getQuery(true)
                        ->insert($db->quoteName('#__comprofiler'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));                
                
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
            $query = $db->getQuery(true)                
                        ->select('id')
                        ->from($db->quoteName('#__k2_users'))
                        ->where($db->quoteName('id') . " = " . $db->quote($userId));           
            $db->setQuery($query);
            $updateId = $db->loadResult();
            $jinput = JFactory::getApplication()->input;
            $clientip = $jinput->server->get('REMOTE_ADDR', '', '');
            if (!empty($updateId))
            {
                $k2query = $db->getQuery(true);
		$k2query->update($db->quoteName('#__k2_users'))					
						->set($db->quoteName('gender') . ' = ' . $db->quote($gender))
						->set($db->quoteName('description') . ' = ' . $db->quote($profileData->aboutme))
						->set($db->quoteName('image') . ' = ' . $db->quote($userImage))
						->set($db->quoteName('url') . ' = ' . $db->quote($profileData->website))
						->where($db->quoteName('id') . ' = ' . $db->Quote($userId));                    
               
            } else
            {
                $columns = array('id', 'userID', 'userName', 'gender', 'description', 'image', 'url', 'group', 'ip', 'hostname', 'notes');
                $values = array($db->quote($userId), $db->quote($userId), $db->quote($username), $db->quote($gender), $db->quote($profileData->aboutme), $db->quote($userImage), $db->quote($profileData->website), $db->quote(trim($settings['k2group'])), $db->quote($clientip), $db->quote(gethostbyaddr($clientip)), $db->quote(''));
                $k2query = $db->getQuery(true)
                        ->insert($db->quoteName('#__k2_users'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));
               
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
            $query = $db->getQuery(true)                
                        ->select('userid')
                        ->from($db->quoteName('#__kunena_users'))
                        ->where($db->quoteName('userid') . " = " . $db->quote($userId));               
           
            $db->setQuery($query);
            $updateId = $db->loadResult();

            if (!empty($updateId))
            {
                $kunenaQuery = $db->getQuery(true);
		$kunenaQuery->update($db->quoteName('#__kunena_users'))					
						->set($db->quoteName('avatar') . ' = ' . $db->quote($dumpUserImage))
						->set($db->quoteName('gender') . ' = ' . $db->quote($profileData->gender))
						->set($db->quoteName('birthdate') . ' = ' . $db->quote($profileData->dob))
						->set($db->quoteName('location') . ' = ' . $db->quote($profileData->city))
						->set($db->quoteName('personalText') . ' = ' . $db->quote($profileData->aboutme))
						->set($db->quoteName('websiteurl') . ' = ' . $db->quote($profileData->website))
						->where($db->quoteName('userid') . ' = ' . $db->Quote($userId));                
            } else
            {
                    $columns = array('userid', 'avatar', 'gender', 'birthdate', 'location', 'personalText', 'websiteurl');
                    $values = array($db->quote($userId), $db->quote($dumpUserImage), $db->quote($profileData->gender), $db->quote($profileData->dob), $db->quote($profileData->city), $db->quote($profileData->aboutme), $db->quote($profileData->website));
                    $kunenaQuery = $db->getQuery(true)
                            ->insert($db->quoteName('#__kunena_users'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));                     
                
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
                $query = $db->getQuery(true)                
                        ->select('userid')
                        ->from($db->quoteName('#__community_users'))
                        ->where($db->quoteName('userid') . " = " . $db->quote($userId));
                $db->setQuery($query);
                $updateId = $db->loadResult();

                if (!empty($updateId))
                {
                    $jsQuery = $db->getQuery(true);
		    $jsQuery->update($db->quoteName('#__community_users'))					
						->set($db->quoteName('avatar') . ' = ' . $db->quote($avatar))
						->set($db->quoteName('thumb') . ' = ' . $db->quote($avatar))
						->where($db->quoteName('userid') . ' = ' . (int) $pk);                                        
                   
                } else
                {
                    $columns = array('userid', 'avatar', 'thumb');
                    $values = array($db->quote($userId), $db->quote($avatar), $db->quote($avatar));
                    $jsQuery = $db->getQuery(true)
                            ->insert($db->quoteName('#__community_users'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));                    
                }
                $db->setQuery($jsQuery);
                $db->query();
            }
        }
    }

}
