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
$mainframe = JFactory::getApplication();

require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'LoginRadius.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'LoginRadiusException.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'SocialLogin'. DS .'SocialLoginAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'SocialLogin'. DS .'GetProvidersAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'CustomerRegistration'. DS .'UserAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'CustomerRegistration'. DS .'CustomObjectAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'CustomerRegistration'. DS .'AccountAPI.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'Clients'. DS .'IHttpClient.php');
require_once (JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'userregistration'. DS .'LoginRadiusSDK'. DS .'Clients'. DS .'DefaultHttpClient.php');

use LoginRadiusSDK\LoginRadiusException;
use LoginRadiusSDK\SocialLogin\SocialLoginAPI;
/**
 * User Registration plugin helper class.
 */
class plgSystemUserRegistrationUserProfileData
{
    /**
     * Manage User Profile
     * 
     * @param type $data
     * @return string
     */
    public static function manageUserProfile($data = array())
    {
        $result = array();
        foreach ($data as $k => $v)
        {
            if (!is_array($v) && !is_object($v))
            {
                $result[$k] = trim($v);
            } else
            {
                $result[$k] = '';
            }
        }
        return $result;
    }

    /**
     * Remove extended user profile data on user id
     * 
     * @param type $userId
     * @param type $columname
     * @param type $tablename
     */
    public static function removeExtendedUserRow($userId, $columname, $tablename)
    {
        $db = JFactory::getDBO();
        $db->setQuery("DELETE FROM `#__loginradius_" . $tablename . "` WHERE " . $columname . " = " . $db->Quote($userId));
        $db->query();
    }

    /**
     * Insert extended user profile data on user id
     * 
     * @param type $userId
     * @param type $tablename
     * @param type $data
     */
    public static function insertExtendedUserRow($userId, $tablename, $data)
    {
        $db = JFactory::getDBO();
        $string = '';
        
        foreach ($data as $key => $value)
        {
            if (is_array($value) || is_object($value))
            {       
               foreach($value as $val){              
               $value = $val . ',';    
               }
               $value = substr($value, 0, -1);
            }
            $string .= ', ' . $db->Quote($value);
        }

        $db->setQuery("INSERT INTO `#__loginradius_" . $tablename . "` VALUES (" . $db->Quote($userId) . $string . ")");
        $db->query();
    }

    /**
     * Save profile data in database.
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function basicUserProfile($userId, $profileDataObject)
    {      
        $keysArray = array("ID", "Provider", "Prefix", "FirstName", "MiddleName", "LastName", "Suffix", "FullName", "NickName", "ProfileName", "BirthDate", "Gender", "country_code", "country_name", "ThumbnailImageUrl", "ImageUrl", "LocalCountry", "ProfileCountry");
        $profileDataObject->country_code = plgSystemUserRegistrationTools::checkVariable($profileDataObject->Country, 'Code');
        $profileDataObject->country_name = plgSystemUserRegistrationTools::checkVariable($profileDataObject->Country, 'Name');
        $data = self::manageArray($profileDataObject, $keysArray);
      
        self::removeExtendedUserRow($userId, 'user_id', 'basic_profile_data');
        self::insertExtendedUserRow($userId, 'basic_profile_data', $data);
    }

    /**
     * Insert extended Location data on user id
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function extendedLocation($userId, $profileDataObject)
    {
        $keysArray = array("MainAddress", "HomeTown", "State", "City", "LocalCity", "ProfileCity", "ProfileUrl", "LocalLanguage", "Language");
        $data = self::manageArray($profileDataObject, $keysArray);
        self::removeExtendedUserRow($userId, 'user_id', 'extended_location_data');
        self::insertExtendedUserRow($userId, 'extended_location_data', $data);
    }

    /**
     * Insert extended Profile data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function extendedProfile($userId, $profileDataObject)
    {
        $keysArray = array("Website", "Favicon", "Industry", "About", "TimeZone", "Verified", "UpdatedTime", "Created", "RelationshipStatus", "Quote", "InterestedIn",
            "Interests", "Religion", "Political", "HttpsImageUrl", "FollowersCount", "FriendsCount", "IsGeoEnabled", "TotalStatusesCount",
            "NumRecommenders", "Honors", "Associations", "Hireable", "RepositoryUrl", "Age", "ProfessionalHeadline", "AccessToken", "TokenSecret");
        $profileDataObject->AccessToken = plgSystemUserRegistrationTools::checkVariable($profileDataObject->ProviderAccessCredential, 'AccessToken');
        $profileDataObject->TokenSecret = plgSystemUserRegistrationTools::checkVariable($profileDataObject->ProviderAccessCredential, 'TokenSecret');
        $data = self::manageArray($profileDataObject, $keysArray);
        self::removeExtendedUserRow($userId, 'user_id', 'extended_profile_data');
        self::insertExtendedUserRow($userId, 'extended_profile_data', $data);
    }

    /**
     * Insert position in comapny data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function positionComapny($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Positions) && count($profileDataObject->Positions) > 0)
        {
            $db = JFactory::getDBO();
            $keysArray1 = array("Position", "Summary", "StartDate", "EndDate", "IsCurrent", "ComapnyId", "Location");
            $keysArray2 = array("Name", "Type", "Industry");
            self::removeExtendedUserRow($userId, 'user_id', 'positions');
            foreach ($profileDataObject->Positions as $position)
            {
                // companies
                if (isset($position->Comapny))
                {
                    $id = NULL;
                    $temp = self::manageArray($position->Comapny, $keysArray2);
                    self::insertExtendedUserRow($id, 'companies', $temp);
                    $tempId = $db->insertid();
                }
                // positions
                $position->ComapnyId = (isset($tempId) ? $tempId : NULL);
                $data = self::manageArray($position, $keysArray1);
                self::insertExtendedUserRow($userId, 'positions', $data);
            }
        }
    }

    /**
     * Insert education data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function education($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Educations) && count($profileDataObject->Educations) > 0)
        {
            $keysArray = array("School", "year", "type", "notes", "activities", "degree", "fieldofstudy", "StartDate", "EndDate");
            self::removeExtendedUserRow($userId, 'user_id', 'education');
            self::insertExtenedDataRows($userId, 'education', $keysArray, $profileDataObject->Educations);
        }
    }

    /**
     * Insert phone Number data on user id
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function phoneNumbers($userId, $profileDataObject)
    {
        if (isset($profileDataObject->PhoneNumbers) && count($profileDataObject->PhoneNumbers) > 0)
        {
            $keysArray = array("PhoneType", "PhoneNumber");
            self::removeExtendedUserRow($userId, 'user_id', 'phone_numbers');
            self::insertExtenedDataRows($userId, 'phone_numbers', $keysArray, $profileDataObject->PhoneNumbers);
        }
    }

    /**
     * Insert im Accounts data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function iMAccounts($userId, $profileDataObject)
    {
        if (isset($profileDataObject->IMAccounts) && count($profileDataObject->IMAccounts) > 0)
        {
            $keysArray = array("AccountType", "AccountName");
            self::removeExtendedUserRow($userId, 'user_id', 'imaccounts');
            self::insertExtenedDataRows($userId, 'imaccounts', $keysArray, $profileDataObject->IMAccounts);
        }
    }

    /**
     * Insert Addresses data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function addresses($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Addresses) && count($profileDataObject->Addresses) > 0)
        {
            $keysArray = array("Type", "Address1", "Address2", "City", "State", "PostalCode", "Region");
            self::removeExtendedUserRow($userId, 'user_id', 'addresses');
            self::insertExtenedDataRows($userId, 'addresses', $keysArray, $profileDataObject->Addresses);
        }
    }

    /**
     * Insert email address data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function userEmailAddress($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Email) && count($profileDataObject->Email) > 0)
        {
            $keysArray = array("Type", "Value");
            self::removeExtendedUserRow($userId, 'user_id', 'emails');
            self::insertExtenedDataRows($userId, 'emails', $keysArray, $profileDataObject->Email);
        }
    }

    /**
     * Insert sports data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function sports($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Sports) && count($profileDataObject->Sports) > 0)
        {
            $keysArray = array("Id", "Name");
            self::removeExtendedUserRow($userId, 'user_id', 'sports');
            self::insertExtenedDataRows($userId, 'sports', $keysArray, $profileDataObject->Sports);
        }
    }

    /**
     * Insert inspirational data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function inspirational($userId, $profileDataObject)
    {
        if (isset($profileDataObject->InspirationalPeople) && count($profileDataObject->InspirationalPeople) > 0)
        {
            $keysArray = array("Id", "Name");
            self::removeExtendedUserRow($userId, 'user_id', 'inspirational_people');
            self::insertExtenedDataRows($userId, 'inspirational_people', $keysArray, $profileDataObject->InspirationalPeople);
        }
    }

    /**
     * Insert Skill data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function Skill($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Skills) && count($profileDataObject->Skills) > 0)
        {
            $keysArray = array("Id", "Name");
            self::removeExtendedUserRow($userId, 'user_id', 'skills');
            self::insertExtenedDataRows($userId, 'skills', $keysArray, $profileDataObject->Skills);
        }
    }

    /**
     * Insert current Status data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function currentStatus($userId, $profileDataObject)
    {
        if (isset($profileDataObject->CurrentStatus) && count($profileDataObject->CurrentStatus) > 0)
        {
            $keysArray = array("Id", "Text", "Source", "CreatedDate");
            self::removeExtendedUserRow($userId, 'user_id', 'current_status');
            self::insertExtenedDataRows($userId, 'current_status', $keysArray, $profileDataObject->CurrentStatus);
        }
    }

    /**
     * Insert certifications data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function certifications($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Certifications) && count($profileDataObject->Certifications) > 0)
        {
            $keysArray = array("Id", "Name", "Authority", "Number", "StartDate", "EndDate");
            self::removeExtendedUserRow($userId, 'user_id', 'certifications');
            self::insertExtenedDataRows($userId, 'certifications', $keysArray, $profileDataObject->Certifications);
        }
    }

    /**
     * Insert courses data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function courses($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Courses) && count($profileDataObject->Courses) > 0)
        {
            $keysArray = array("Id", "Name", "Number");
            self::removeExtendedUserRow($userId, 'user_id', 'courses');
            foreach ($profileDataObject->Courses as $course)
            {
                $data = self::manageArray($course, $keysArray);
                self::insertExtendedUserRow($userId, 'courses', $data);
            }
        }
    }

    /**
     * Insert volunteer data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function volunteer($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Volunteer) && count($profileDataObject->Volunteer) > 0)
        {
            $keysArray = array("Id", "Role", "Organization", "Cause");
            self::removeExtendedUserRow($userId, 'user_id', 'volunteer');
            self::insertExtenedDataRows($userId, 'volunteer', $keysArray, $profileDataObject->Volunteer);
        }
    }

    /**
     * Insert recommendationsReceived data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function recommendationsReceived($userId, $profileDataObject)
    {
        if (isset($profileDataObject->RecommendationsReceived) && count($profileDataObject->RecommendationsReceived) > 0)
        {
            $keysArray = array("Id", "RecommendationType", "RecommendationText", "Recommender");
            self::removeExtendedUserRow($userId, 'user_id', 'recommendations_received');
            self::insertExtenedDataRows($userId, 'recommendations_received', $keysArray, $profileDataObject->RecommendationsReceived);
        }
    }

    /**
     * Insert languages data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function languages($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Languages) && count($profileDataObject->Languages) > 0)
        {
            $keysArray = array("Id", "Name");
            self::removeExtendedUserRow($userId, 'user_id', 'languages');
            self::insertExtenedDataRows($userId, 'languages', $keysArray, $profileDataObject->Languages);
        }
    }

    /**
     * Insert patents data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function patents($userId, $profileDataObject)
    {
        if (isset($profileDataObject->Patents) && count($profileDataObject->Patents) > 0)
        {
            $keysArray = array('Id', 'Title', 'Date');
            self::removeExtendedUserRow($userId, 'user_id', 'patents');
            self::insertExtenedDataRows($userId, 'patents', $keysArray, $profileDataObject->Patents);
        }
    }

    /**
     * Insert favorites data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     */
    public static function favorites($userId, $profileDataObject)
    {
        if (isset($profileDataObject->FavoriteThings) && count($profileDataObject->FavoriteThings) > 0)
        {
            $keysArray = array('Id', 'Name', 'Type');
            self::removeExtendedUserRow($userId, 'user_id', 'favorites');
            self::insertExtenedDataRows($userId, 'favorites', $keysArray, $profileDataObject->FavoriteThings);
        }
    }

    public static function insertExtenedDataRows($userId, $tableName, $keysArray, $bulkData, $provider=''){
        $db = JFactory::getDBO();
        $sql = "INSERT INTO `#__loginradius_".$tableName."` VALUES ";
            foreach ($bulkData as $rowData)
            {
                $sql .= "(" .$db->Quote($userId);
                $data = self::manageArray($rowData, $keysArray);
                if(!empty($provider)){
                    $data['provider'] = $provider;
                }
                foreach ($data as $key => $value)
                {
                    if (is_array($value) || is_object($value))
                    {
                        $value = '';
                    }
                    $sql .= ', ' . $db->Quote($value);
                }
                $sql .= "),";
          }
        $query = substr($sql, 0, -1);
        $db->setQuery($query);
        $db->query();
        }
    /**
     * Insert contacts By Nextcursor data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $nextCursor
     */
    public static function contactsByNextcursor($userId, $provider, $accessToken, $settings, $nextCursor = '')
    {          
        $mainframe = JFactory::getApplication();
        $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
        try {
        $contacts = $loginRadiusObject->getContacts($accessToken, $nextCursor);   
        }
        catch (LoginRadiusException $e) {
          $e->getMessage();
          if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
          }
        }
    if (isset($contacts) && !is_string($contacts) && isset($contacts->Data) && count($contacts->Data) > 0)
        {
            $keysArray = array("ID", "Name", "EmailID", "ProfileUrl", "ImageUrl", "Status", "Industry", "Country", "Gender", "PhoneNumber", "DateOfBirth", "Location");
            self::insertExtenedDataRows($userId, 'contacts', $keysArray, $contacts->Data, $provider);        
            if (isset($contacts->NextCursor) && !empty($contacts->NextCursor))
            {
                self::contactsByNextcursor($userId, $provider, $accessToken, $settings, $contacts->NextCursor);
            }
        }
    }

    /** 
     * Insert contacts data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function contacts($userId, $provider, $accessToken, $settings)
    {
        if (in_array($provider, array('twitter', 'facebook', 'linkedin', 'google', 'yahoo')) &&
                isset($settings['socialcontacts']) && $settings['socialcontacts'] == '1')
        {
            self::removeExtendedUserRow($userId, 'user_id', 'contacts');
            self::contactsByNextcursor($userId, $provider, $accessToken, $settings);
        }
    }

    /**
     * Insert events data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function events($userId, $provider, $accessToken, $settings)
    {       
        $mainframe = JFactory::getApplication();
        if ($provider == 'facebook' && isset($settings['fbprofile']) && $settings['fbprofile'] == '1')
        {
          $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $events = $loginRadiusObject->getEvents($accessToken);
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
              if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
           } 
          }
          if (!is_string($events) && count($events) > 0)
            {
                $keysArray = array("ID", "Name", "StartTime", "EndTime", "Location", "RsvpStatus", "Description", "UpdatedDate", "Privacy", "OwnerId", "OwnerName");
                self::removeExtendedUserRow($userId, 'user_id', 'facebook_events');
                self::insertExtenedDataRows($userId, 'facebook_events', $keysArray, $events);
            }
        }
    }

    /**
     * Insert post data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function posts($userId, $provider, $accessToken, $settings)
    {
        $mainframe = JFactory::getApplication();
        if ($provider == 'facebook' && isset($settings['fbpost']) && $settings['fbpost'] == '1')
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $posts = $loginRadiusObject->getPosts($accessToken);
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
              if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
            }
            }        
            if (!is_string($posts) && count($posts) > 0)
            {
                $keysArray = array("ID", "Name", "Title", "StartTime", "UpdateTime", "Message", "Place", "Picture", "Likes", "Share", "Post");
                self::removeExtendedUserRow($userId, 'user_id', 'facebook_posts');
                self::insertExtenedDataRows($userId, 'facebook_posts', $keysArray, $posts);
            }
        }
    }

    /**
     * Insert linkedin companies data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function linkedinCompanies($userId, $provider, $accessToken, $settings)
    {
       $mainframe = JFactory::getApplication();
        if ($provider == 'linkedin' && isset($settings['followcompanies']) && $settings['followcompanies'] == '1')
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $linkedInCompanies = $loginRadiusObject->getFollowedCompanies($accessToken);                  
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
              if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
        }
            }          
            if (isset($linkedInCompanies) && !empty($linkedInCompanies) && !is_string($linkedInCompanies) && count($linkedInCompanies) > 0)
            {
                $keysArray = array("ID", "Name");
                self::removeExtendedUserRow($userId, 'user_id', 'linkedin_companies');
                self::insertExtenedDataRows($userId, 'linkedin_companies', $keysArray, $linkedInCompanies);
            }
        }
    }

    /**
     * Insert status data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function status($userId, $provider, $accessToken, $settings)
    {
        $mainframe = JFactory::getApplication();
        if (in_array($provider, array('twitter', 'facebook', 'linkedin')) && (isset($settings['statusmessage']) && $settings['statusmessage'] == '1'))
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $statusReport = $loginRadiusObject->getStatus($accessToken);
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
              if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
        }
            }      
            if (isset($statusReport) && !is_string($statusReport) && count($statusReport) > 0)
            {
                $keysArray = array('Id', 'Text', 'DateTime', 'Likes', 'Place', 'Source', 'ImageUrl', 'LinkUrl');
                self::removeExtendedUserRow($userId, 'user_id', 'status');
                self::insertExtenedDataRows($userId, 'status', $keysArray, $statusReport, $provider);
            }
        }
    }

    /**
     * Insert mentions data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function mentions($userId, $provider, $accessToken, $settings)
    {
        $mainframe = JFactory::getApplication();
        if ($provider == 'twitter' && isset($settings['twittermentions']) && $settings['twittermentions'] == '1')
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $mentions = $loginRadiusObject->getMentions($accessToken);
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
              if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
        }
            }         
            if (!is_string($mentions) && count($mentions) > 0)
            {
                $keysArray = array('Id', 'Text', 'DateTime', 'Likes', 'Place', 'Source', 'ImageUrl', 'LinkURL', 'Name');
                self::removeExtendedUserRow($userId, 'user_id', 'twitter_mentions');
                self::insertExtenedDataRows($userId, 'twitter_mentions', $keysArray, $mentions);
            }
        }
    }

    /**
     * Insert groups data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function groups($userId, $provider, $accessToken, $settings)
    {
        $mainframe = JFactory::getApplication();
        if (in_array($provider, array('facebook', 'linkedin')) && isset($settings['groups']) && $settings['groups'] == '1')
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $groups = $loginRadiusObject->getGroups($accessToken);             
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
             if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
        }
            }
           
            if (isset($groups) && !is_string($groups) && count($groups) > 0)
            {
                $keysArray = array('ID', 'Name', 'Type', 'Description', 'Email', 'Country', 'PostalCode', 'Logo', 'Image', 'MemberCount');
                self::removeExtendedUserRow($userId, 'user_id', 'groups');
                self::insertExtenedDataRows($userId, 'groups', $keysArray, $groups, $provider);
            }
        }
    }

    /**
     * Insert facebook likes data on user id
     * 
     * @param type $userId
     * @param type $provider
     * @param type $accessToken
     * @param type $settings
     */
    public static function facebookLikes($userId, $provider, $accessToken, $settings)
    {
        $mainframe = JFactory::getApplication();
        if ($provider == 'facebook' && isset($settings['fblike']) && $settings['fblike'] == '1')
        {
           $loginRadiusObject = new SocialLoginAPI($settings['apikey'], $settings['apisecret'], array('authentication'=>false, 'output_format' => 'json'));
            try {
               $facebookLikes = $loginRadiusObject->getLikes($accessToken);              
            }
            catch (LoginRadiusException $e) {
              $e->getMessage();
             if (isset($e->getErrorResponse()->description) && $e->getErrorResponse()->description) {
            $mainframe->enqueueMessage($e->getErrorResponse()->description, 'error');
        }
            }
            
           if (!is_string($facebookLikes) && isset($facebookLikes) && !empty($facebookLikes))
            {
                $keysArray = array('ID', 'Name', 'Category', 'CreatedDate', 'Website', 'Description');
                self::removeExtendedUserRow($userId, 'user_id', 'facebook_likes');
                self::insertExtenedDataRows($userId, 'facebook_likes', $keysArray, $facebookLikes);
            }
        }
    }

    /**
     * Insert manage Array data on user id
     * 
     * @param type $profileObject
     * @param type $keysArray
     * @return type
     */
    public static function manageArray($profileObject, $keysArray)
    {
        $data = array();
        foreach ($keysArray as $key)
        {
            $data[$key] = plgSystemUserRegistrationTools::checkVariable($profileObject, $key);
        }
        return $data;
    }

    /**
     * Insert All Advance data on user id
     * 
     * @param type $userId
     * @param type $profileDataObject
     * @param type $accessToken
     */
    public static function saveProfile($userId, $profileDataObject, $accessToken)
    {
        $settings = plgSystemUserRegistrationTools::getSettings();
        // insert basic profile data if option is selected
        if (isset($settings['basic']) && $settings['basic'] == '1')
        {
            self::basicUserProfile($userId, $profileDataObject);
            self::userEmailAddress($userId, $profileDataObject);
        }
        // insert extended location data if option is selected
        if (isset($settings['exlocation']) && $settings['exlocation'] == '1')
        {
            self::extendedLocation($userId, $profileDataObject);
        }

        // insert extended profile data if option is selected
        if (isset($settings['exprofile']) && $settings['exprofile'] == '1')
        {
            self::extendedProfile($userId, $profileDataObject);
            self::positionComapny($userId, $profileDataObject);
            self::education($userId, $profileDataObject);
            self::phoneNumbers($userId, $profileDataObject);
            self::iMAccounts($userId, $profileDataObject);
            self::addresses($userId, $profileDataObject);
            self::sports($userId, $profileDataObject);
            self::inspirational($userId, $profileDataObject);
            self::Skill($userId, $profileDataObject);
            self::currentStatus($userId, $profileDataObject);
            self::certifications($userId, $profileDataObject);
            self::courses($userId, $profileDataObject);
            self::volunteer($userId, $profileDataObject);
            self::recommendationsReceived($userId, $profileDataObject);
            self::languages($userId, $profileDataObject);
            self::patents($userId, $profileDataObject);
            self::favorites($userId, $profileDataObject);
        }
        self::contacts($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::events($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::posts($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::linkedinCompanies($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::status($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::mentions($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::groups($userId, $profileDataObject->Provider, $accessToken, $settings);
        self::facebookLikes($userId, $profileDataObject->Provider, $accessToken, $settings);
    }

}
