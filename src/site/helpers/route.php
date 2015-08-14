<?php

/**
 * @version		$Id: route.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class SocialLoginAndSocialShareHelperRoute
{

    /**
     * Method to get the menu items for the component.
     *
     * @return	array		An array of menu items.
     * @since	1.6
     */
    public static function &getItems()
    {
        static $items;

        // Get the menu items for this component.
        if (!isset($items))
        {
            // Include the site app in case we are loading this from the admin.
            require_once JPATH_SITE . '/includes/application.php';

            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $com = JComponentHelper::getComponent('com_users');
            $items = $menu->getItems('component_id', $com->id);

            // If no items found, set to empty array.
            if (!$items)
            {
                $items = array();
            }
        }

        return $items;
    }

    /**
     * Method to get a route configuration for the profile view.
     *
     * @return	mixed		Integer menu id on success, null on failure.
     * @since	1.6
     */
    public static function getProfileRoute()
    {
        // Get the items.
        $items = self::getItems();
        $itemid = null;

        // Search for a suitable menu id.
        //Menu link can only go to users own profile.

        foreach ($items as $item)
        {
            if (isset($item->query['view']) && $item->query['view'] === 'profile')
            {
                $itemid = $item->id;
                break;
            }
        }
        return $itemid;
    }

    /**
     * @param $settings
     * @return string
     */
    public static function getInterface($settings)
    {
        if (!empty($settings['apikey']))
        {
            $interfaceBackground = '';
            $iconSize = '';
            $columns = '';
            if (isset($settings['iconsize']))
            {
                $iconSize = trim($settings['iconsize']);
            }
            if (isset($settings['iconsperrow']) && trim($settings['iconsperrow']) != "")
            {
                $columns = '$ui.noofcolumns = ' . trim($settings['iconsperrow']) . ';';
            }
            if (isset($settings['interfacebackground']))
            {
                $interfaceBackground = trim($settings['interfacebackground']);
            }
            $document = JFactory::getDocument();
            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
            $document->addScript(JURI::root(true) . '/modules/mod_socialloginandsocialshare/LoginRadiusSDK.2.0.0.js');
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
     * @return array|mixed
     */
    public static function getAccountMapRows()
    {
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__loginradius_users WHERE id =" . JFactory::getUser()->id;
        $db->setQuery($sql);
        return $db->loadObjectList();
    }

    /**
     * @return array
     */
    public static function getSetting()
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
                $settings [$data ['setting']] = $data ['value'];
            }
        }
        return $settings;
    }

}
