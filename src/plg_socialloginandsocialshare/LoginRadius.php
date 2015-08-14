<?php

/**
 * @package     SocialLoginandSocialShare.Plugin
 * @subpackage  com_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.helper');

// Define LoginRadius domain
define('LR_DOMAIN', 'api.loginradius.com');

require_once(dirname(__FILE__) . DS . 'helper' . DS . 'helper.php');
require_once(dirname(__FILE__) . DS . 'helper' . DS . 'functions.php');

/**
 * Class LoginRadius
 */
class LoginRadius
{

    public $isAuthenticated, $jsonResponse, $userProfile;

    /**
     * 
     * @return type
     */
    public function getUserProfile()
    {
        $isAuthenticated = false;
        if (isset($_REQUEST['token']))
        {
            $accessToken = $_REQUEST['token'];
            $validateUrl = "https://" . LR_DOMAIN . "/api/v2/userprofile?access_token=" . $accessToken;
            $userProfile = $this->apiClient($validateUrl);

            if (isset($userProfile->ID) && $userProfile->ID != '')
            {
                $this->isAuthenticated = true;
                return $userProfile;
            }
        }
    }

    /**
     * @param $validateUrl
     * @return mixed|string
     */
    private function apiClient($validateUrl)
    {
        $settings = plgSystemSocialLoginTools::getSettings();

        if ($settings['useapi'] == 1)
        {
            $results = $this->curlApiMethod($validateUrl);
        } else
        {
            $results = $this->fsockopenApiMethod($validateUrl);
        }

        return $results;
    }

    /**
     * 
     * @param type $validateUrl
     * @return type
     */
    private function fsockopenApiMethod($validateUrl)
    {
        $jsonResponse = @file_get_contents($validateUrl);
        if (strpos(@$http_response_header[0], "400") !== false ||
                strpos(@$http_response_header[0], "401") !== false ||
                strpos(@$http_response_header[0], "403") !== false ||
                strpos(@$http_response_header[0], "404") !== false ||
                strpos(@$http_response_header[0], "500") !== false ||
                strpos(@$http_response_header[0], "503") !== false)
        {
            return JTEXT::_('COM_LOGINRADIUS_SERVICE_AND_TIMEOUT_ERROR');
        } else
        {
            $this->isAuthenticated = true;
            return json_decode($jsonResponse);
        }
    }

    /**
     * @param $validateUrl
     * @return mixed|string
     */
    private function curlApiMethod($validateUrl)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $validateUrl);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 15);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or ! ini_get('safe_mode')))
        {
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        } else
        {
            curl_setopt($curlHandle, CURLOPT_HEADER, 1);
            $url = curl_getinfo($curlHandle, CURLINFO_EFFECTIVE_URL);
            curl_close($curlHandle);
            $curlHandle = curl_init();
            $url = str_replace('?', '/?', $url);
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        }
        $jsonResponse = curl_exec($curlHandle);
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        if (in_array($httpCode, array(400, 401, 403, 404, 500, 503, 0)) && $httpCode != 200)
        {
            return JTEXT::_('COM_LOGINRADIUS_SERVICE_AND_TIMEOUT_ERROR');
        } else
        {
            if (curl_errno($curlHandle) == 28)
            {
                return JTEXT::_('COM_LOGINRADIUS_SERVICE_AND_TIMEOUT_ERROR');
            }
        }
        curl_close($curlHandle);
        return json_decode($jsonResponse);
    }

}
