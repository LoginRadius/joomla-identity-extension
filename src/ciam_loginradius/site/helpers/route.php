<?php

/**
 * @package     CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class CiamLoginRadiusHelperRoute {

    /**
     * Method to get the menu items for the component.
     *
     * @return	array		An array of menu items.
     * @since	1.6
     */
    public static function &getItems() {
        static $items;

        // Get the menu items for this component.
        if (!isset($items)) {
            // Include the site app in case we are loading this from the admin.
            require_once JPATH_SITE . '/includes/application.php';

            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $com = JComponentHelper::getComponent('com_users');
            $items = $menu->getItems('component_id', $com->id);

            // If no items found, set to empty array.
            if (!$items) {
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
    public static function getProfileRoute() {
        // Get the items.
        $items = self::getItems();
        $itemid = null;

        // Search for a suitable menu id.
        //Menu link can only go to users own profile.

        foreach ($items as $item) {
            if (isset($item->query['view']) && $item->query['view'] === 'profile') {
                $itemid = $item->id;
                break;
            }
        }
        return $itemid;
    }

    /**
     * @return array|mixed
     */
    public static function getAccountMapRows() {
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__loginradius_users WHERE id =" . JFactory::getUser()->id;
        $db->setQuery($sql);
        return $db->loadObjectList();
    }

    /**
     * @return array
     */
    public static function getSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__loginradius_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data ['setting']] = $data ['value'];
            }
        }
        return $settings;
    }  
   
    public static function change_password_custom_access() {
        $session = JFactory::getSession();
        $settings = CiamLoginRadiusHelperRoute::getSettings();
        $optionVal = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';
        if ($optionVal == '1') {
            if ($session->get('provider') == 'Email' || $session->get('emailVerified')) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if ($optionVal == '2') {
            if ($session->get('provider') == 'Email') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return TRUE;
    }    

}
