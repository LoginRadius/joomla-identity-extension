<?php

/**
 * @package     SocialLoginandSocialShare.Plugin
 * @subpackage  com_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.modellist');

/**
 * SocialLoginAndSocialShare Model.
 */
class SocialLoginAndSocialShareModelSocialLoginAndSocialShare extends JModelList
{

    /**
     * hide and display sharing admin content on selected theme on page load
     * 
     * @param $variable
     * @param $data
     * @return string
     */
    public static function selectDisplaySection($variable, $data)
    {
        $result = 'none';
        if (in_array($variable, $data))
        {
            $result = 'block';
        }
        return $result;
    }

    /**
     * save all setting in local db
     * 
     * Save Settings.
     */
    public function saveSettings()
    {
        //Get database handle
        $db = $this->getDbo();
        //Read Settings
        $defaultShare = serialize(array('facebook', 'googleplus', 'twitter', 'linkedin', 'pinterest'));
        $defaultCounter = serialize(array("Facebook Like", "Twitter Tweet", "LinkedIn Share", "Google+ Share"));

        $settings = JRequest::getVar('settings');
        $settings['apikey'] = isset($settings['apikey']) ? trim($settings['apikey']) : "";
        $settings['apisecret'] = isset($settings['apisecret']) ? trim($settings['apisecret']) : "";
        $settings['useapi'] = $this->getApiMethod();
        $settings['horizontalarticles'] = (sizeof(JRequest::getVar('horizontalarticles')) > 0 ? serialize(JRequest::getVar('horizontalarticles')) : "");
        $settings['verticalarticles'] = (sizeof(JRequest::getVar('verticalarticles')) > 0 ? serialize(JRequest::getVar('verticalarticles')) : "");
        $settings['horizontalrearrange'] = (sizeof(JRequest::getVar('horizontalrearrange')) > 0 ? serialize(JRequest::getVar('horizontalrearrange')) : $defaultShare);
        $settings['verticalrearrange'] = (sizeof(JRequest::getVar('verticalrearrange')) > 0 ? serialize(JRequest::getVar('verticalrearrange')) : $defaultShare);
        $settings['horizontalcounter'] = (sizeof(JRequest::getVar('horizontalcounter')) > 0 ? serialize(JRequest::getVar('horizontalcounter')) : $defaultCounter);
        $settings['verticalcounter'] = (sizeof(JRequest::getVar('verticalcounter')) > 0 ? serialize(JRequest::getVar('verticalcounter')) : $defaultCounter);

        $result = $this->saveConfiguration($settings);

        if ($result['status'] == 'message')
        {
            $sql = "DELETE FROM #__loginradius_settings";
            $db->setQuery($sql);
            $db->query();

            $settings['horizontalscript'] = json_encode($this->horizontalShare($settings));
            $settings['verticalscript'] = json_encode($this->verticalShare($settings));

            //Insert new settings
            foreach ($settings as $k => $v)
            {
                $sql = "INSERT INTO #__loginradius_settings ( setting, value ) VALUES ( " . $db->Quote($k) . ", " . $db->Quote($v) . " )";
                $db->setQuery($sql);
                $db->query();
            }
        }

        return $result;
    }

    /**
     * get all extension setting from local db
     * 
     * Read Settings
     */
    public function getSettings()
    {
        $settings = array();
        $db = $this->getDbo();

        $db->setQuery("CREATE TABLE IF NOT EXISTS #__loginradius_users (`id` int(11) DEFAULT NULL, `LoginRadius_id` varchar(255) DEFAULT NULL, `provider` varchar(255) DEFAULT NULL, `lr_picture` varchar(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $db->query();

        $db->setQuery("CREATE TABLE IF NOT EXISTS #__loginradius_settings (
						`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`setting` varchar(255) NOT NULL,
						`value` text NOT NULL,
						PRIMARY KEY (`id`),
						UNIQUE KEY `setting` (`setting`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
        $db->query();

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
     * generate horizontal sharing script
     * 
     * @param $settings
     * @return string
     */
    private function horizontalShare($settings)
    {
        switch ($settings['choosehorizontalshare'])
        {
            case 0:
                $size = '32';
                $interface = 'horizontal';
                break;
            case 1:
                $size = '16';
                $interface = 'horizontal';
                break;
            case 2:
                $size = '32';
                $interface = 'simpleimage';
                break;
            case 3:
                $size = '16';
                $interface = 'simpleimage';
                break;
            case 4:
                $ishorizontal = 'true';
                $interface = 'horizontal';
                break;
            case 5:
                $ishorizontal = 'true';
                $interface = 'vertical';
                break;
            case 6:
                $size = '32';
                $interface = 'responsive';
                break;
        }

        if (isset($size) && !empty($size))
        {
            $sharescript = 'LoginRadius.util.ready(function () {$i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ' . json_encode(@unserialize($settings['horizontalrearrange'])) . '; $u = LoginRadius.user_settings; $u.sharecounttype = \'url\'; $u.apikey = "' . $settings['apikey'] . '"; $i.size = ' . $size . ';$i.show("lrsharecontainer"); });';
        } else if (isset($ishorizontal) && !empty($ishorizontal))
        {
            $sharescript = 'LoginRadius.util.ready(function () { $SC.Providers.Selected = ' . json_encode(@unserialize($settings['horizontalcounter'])) . '; $S = $SC.Interface.simple; $S.isHorizontal = ' . $ishorizontal . '; $S.countertype = "' . $interface . '"; $S.show("lrsharecontainer"); });';
        }
        return 'if(typeof LoginRadius != "undefined"){' . $sharescript . '}';
    }

    /**
     * generate vertical sharing script
     * 
     * @param $settings
     * @return string
     */
    private function verticalShare($settings)
    {
        switch ($settings['chooseverticalshare'])
        {
            case 0:
                $size = '32';
                $vinterface = 'Simplefloat';
                break;
            case 1:
                $size = '16';
                $vinterface = 'Simplefloat';
                break;
            case 2:
                $isvertical = 'false';
                $vinterface = 'horizontal';
                break;
            case 3:
                $isvertical = 'false';
                $vinterface = 'vertical';
                break;
        }

        switch ($settings['verticalsharepos'])
        {
            case 0:
                $vershretop = '0px';
                $vershreright = '';
                $vershrebottom = '';
                $vershreleft = '0px';
                break;
            case 1:
                $vershretop = '0px';
                $vershreright = '0px';
                $vershrebottom = '';
                $vershreleft = '';
                break;
            case 2:
                $vershretop = '0px';
                $vershreright = '';
                $vershrebottom = '0px';
                $vershreleft = '0px';
                break;
            case 3:
                $vershretop = '0px';
                $vershreright = '0px';
                $vershrebottom = '0px';
                $vershreleft = '';
                break;
            default:
                $vershretop = '0px';
                $vershreright = '';
                $vershrebottom = '';
                $vershreleft = '';
                break;
        }
        if (isset($size) && !empty($size))
        {
            $vsharescript = 'LoginRadius.util.ready(function () {$i = $SS.Interface.' . $vinterface . '; $SS.Providers.Top = ' . json_encode(@unserialize($settings['verticalrearrange'])) . '; $u = LoginRadius.user_settings; $u.apikey = "' . $settings['apikey'] . '"; $i.size = ' . $size . ';$i.left = "' . $vershreleft . '"; $i.top = "' . $vershretop . '";$i.right = "' . $vershreright . '";$i.bottom = "' . $vershrebottom . '"; $i.show("lrverticalsharecontainer"); });';
        } else if (isset($isvertical) && !empty($isvertical))
        {
            $vsharescript = 'LoginRadius.util.ready(function () { $SC.Providers.Selected = ' . json_encode(@unserialize($settings['verticalcounter'])) . '; $S = $SC.Interface.simple; $S.isHorizontal = ' . $isvertical . '; $S.countertype = "' . $vinterface . '"; $S.left = "' . $vershreleft . '"; $S.top = "' . $vershretop . '";$S.right = "' . $vershreright . '";$S.bottom = "' . $vershrebottom . '"; $S.show("lrverticalsharecontainer"); });';
        }
        return 'if(typeof LoginRadius != "undefined"){' . $vsharescript . '}';
    }

    /**
     * check server connection method
     * 
     * @return string
     */
    private function getApiMethod()
    {
        if (function_exists('curl_version'))
        {
            return '1';
        }
        return '0';
    }

    /**
     * get error message from loginradius for api and secret key
     * 
     * @param $settings
     */
    public function saveConfiguration($settings)
    {
        if (empty($settings['apikey']))
        {
            $results['status'] = "error";
            $results['message'] = JText::_('COM_SOCIALLOGIN_ADVANCE_MESSAGE_APIKEY');
        } elseif (empty($settings['apisecret']))
        {
            $results['status'] = "error";
            $results['message'] = JText::_('COM_SOCIALLOGIN_ADVANCE_MESSAGE_SECRETKEY');
        } else
        {
            $result = $this->saveApiSettings($settings);
            $results = $this->loginRadiusApiClient($result['url'], JURI::buildQuery($result['data']));
        }
        return $results;
    }

    /**
     * manage and config dump api data for loginradius
     * 
     * @param $settings
     */
    private function saveApiSettings($settings)
    {
        $result['url'] = 'http://api.loginradius.com/api/v2/app/validate?apikey=' . rawurlencode($settings['apikey']) . '&apisecret=' . rawurlencode($settings['apisecret']);
        $string = "~1#";
        foreach ($settings as $k => $v)
        {
            if (in_array($k, array("apikey", "apisecret")))
            {
                
            } elseif (is_numeric($v))
            {
                $string .= '|' . $v;
            } elseif (@unserialize($v))
            {
                $string .= '|' . json_encode(@unserialize($v));
            } elseif (is_string($v))
            {
                $string .= '|"' . $v . '"';
            }
        }
        $result['data'] = array(
            'addon' => 'Joomla',
            'version' => '3.8',
            'agentstring' => $_SERVER["HTTP_USER_AGENT"],
            'clientip' => $_SERVER["REMOTE_ADDR"],
            'configuration' => $string
        );
        return $result;
    }

    /**
     * loginradius API call function with help of curl and fsockopen
     *  
     * @param type $ValidateUrl
     * @param type $data
     * @return type
     */
    private function loginRadiusApiClient($ValidateUrl, $data)
    {
        if ($this->getApiMethod())
        {
            $response = $this->curlApiMethod($ValidateUrl, $data);
        } else
        {
            $response = $this->fsockopenApiMethod($ValidateUrl, $data);
        }
        $message = isset($response->Messages[0]) ? trim($response->Messages[0]) : '';
        switch ($message)
        {
            case 'API_KEY_NOT_VALID':
                $results['status'] = "error";
                $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_ONE') . " <a href='http://www.loginradius.com' target='_blank'>LoginRadius</a>";
                break;
            case 'API_SECRET_NOT_VALID':
                $results['status'] = "error";
                $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_TWO') . " <a href='http://www.loginradius.com' target='_blank'>LoginRadius</a>";
                break;
            case 'API_KEY_NOT_FORMATED':
                $results['status'] = "error";
                $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_THREE');
                break;
            case 'API_SECRET_NOT_FORMATED':
                $results['status'] = "error";
                $results['message'] = JText::_('COM_SOCIALLOGIN_SAVE_SETTING_ERROR_FOUR');
                break;
            default:
                $results['status'] = "message";
                $results['message'] = JText::_('COM_SOCIALLOGIN_SETTING_SAVED');
                break;
        }
        return $results;
    }

    /**
     * loginradius API call function with help of fsockopen
     *  
     * @param type $ValidateUrl
     * @param type $data
     * @return type
     */
    private function fsockopenApiMethod($ValidateUrl, $data)
    {
        if (!empty($data))
        {
            $options = array('http' =>
                array(
                    'method' => 'POST',
                    'timeout' => 15,
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $data
                )
            );
            $context = stream_context_create($options);
        } else
        {
            $context = NULL;
        }
        $JsonResponse = @file_get_contents($ValidateUrl, false, $context);
        if (strpos(@$http_response_header[0], "400") !== false ||
                strpos(@$http_response_header[0], "401") !== false ||
                strpos(@$http_response_header[0], "403") !== false ||
                strpos(@$http_response_header[0], "404") !== false ||
                strpos(@$http_response_header[0], "500") !== false ||
                strpos(@$http_response_header[0], "503") !== false)
        {
            return JTEXT::_('COM_SOCIALLOGIN_SERVICE_AND_TIMEOUT_ERROR');
        } else
        {
            return json_decode($JsonResponse);
        }
    }

    /**
     * loginradius API call function with help of curl
     *  
     * @param type $validateUrl
     * @param type $data
     * @return type
     */
    private function curlApiMethod($validateUrl, $data)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $validateUrl);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 15);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($data))
        {
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        }
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
            return JTEXT::_('COM_SOCIALLOGIN_SERVICE_AND_TIMEOUT_ERROR');
        } else
        {
            if (curl_errno($curlHandle) == 28)
            {
                return JTEXT::_('COM_SOCIALLOGIN_SERVICE_AND_TIMEOUT_ERROR');
            }
        }
        $results = json_decode($jsonResponse);
        curl_close($curlHandle);
        return $results;
    }

}
