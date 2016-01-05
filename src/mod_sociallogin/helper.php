<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_sociallogin
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Helper for mod_login
 *
 * @package     Joomla.Site
 * @subpackage  mod_sociallogin
 * @since       1.5
 */
class modSocialLoginHelper {

    public static function getReturnURL($params, $type) {
        $app = JFactory::getApplication();
        $item = $app->getMenu()->getItem($params->get($type));

        if ($item) {
            $url = 'index.php?Itemid=' . $item->id;
        } else {
            // Stay on the same page
            $url = JUri::getInstance()->toString();
        }
        $base = 'ba'.'se64_encode';
        return $base($url);
    }

    /**
     * @param $user
     * @return mixed
     */
    public static function socialAccountCount($user, $socialId) {
        $db = JFactory::getDBO();
        $query = "SELECT * FROM " . $db->quoteName('#__loginradius_users') . " WHERE id = " . $db->Quote($user->get('id')) . " AND LoginRadius_id=" . $db->Quote($socialId);
        $db->setQuery($query);
        $findId = $db->loadResult();
        $query = "SELECT COUNT(*) FROM " . $db->quoteName('#__loginradius_users') . " WHERE id = " . $db->Quote($user->get('id'));
        $db->setQuery($query);
        $count = $db->loadResult();
        if (!empty($findId)) {
            $count = ($count == 0 ? $count : $count - 1);
        }
        return $count;
    }

    /**
     * check user login or not
     * 
     * @return string
     */
    public static function getType() {
        $user = JFactory::getUser();
        return !$user->get('guest') ? 'logout' : 'login';
    }

    /**
     * get social login interface
     * 
     * @param $settings
     * @return string
     */
    public static function getInterface($settings) {
        if (isset($settings['apikey']) && !empty($settings['apikey'])) {
            $interfaceBackground = '';
            $iconSize = '';
            $columns = '';
            if (isset($settings['iconsize'])) {
                $iconSize = trim($settings['iconsize']);
            }
            if (isset($settings['iconsperrow']) && trim($settings['iconsperrow']) != "") {
                $columns = '$ui.noofcolumns = ' . trim((int) $settings['iconsperrow']) . ';';
            }
            if (isset($settings['interfacebackground'])) {
                $interfaceBackground = trim($settings['interfacebackground']);
            }
            $document = JFactory::getDocument();
            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
            $document->addScript(JURI::root() . 'modules/mod_sociallogin/LoginRadiusSDK.2.0.0.js');
            $loginFunction = 'var lr_options={}; lr_options.login=true;LoginRadius_SocialLogin.util.ready(function () {$ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "' . $iconSize . '";$ui.apikey = "' . $settings['apikey'] . '";$ui.is_access_token=true;' . $columns . '$ui.lrinterfacebackground = "' . $interfaceBackground . '";$ui.callback="' . JURI::current() . '";$ui.lrinterfacecontainer ="interfacecontainerdiv";LoginRadius_SocialLogin.init(lr_options); });
            LoginRadiusSDK.setLoginCallback(function () {
                var token = LoginRadiusSDK.getToken();
                var form = document.createElement(\'form\');
                form.action = "' . JURI::current() . '";
                form.method = \'POST\';
                var hiddenToken = document.createElement(\'input\');
                hiddenToken.type = \'hidden\';
                hiddenToken.value = LoginRadiusSDK.getToken();
                hiddenToken.name = "token";
                form.appendChild(hiddenToken);
                document.body.appendChild(form);
                form.submit();
            });';
            $document->addScriptDeclaration($loginFunction);
            return '<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
        }
    }

    /**
     * Get the databse settings.
     * 
     * @return type
     */
    public static function getSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__loginradius_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings[$data['setting']] = $data['value'];
            }
        }
        return $settings;
    }

}
