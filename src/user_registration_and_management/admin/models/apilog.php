<?php

/**
 * @package     UserRegistrationAndManagement.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.modellist');

/**
 * UserRegistrationAndManagement Model.
 */
JTable::addIncludePath(JPATH_COMPONENT . DS . 'tables');

class UserRegistrationAndManagementModelApiLog extends JModelList {

    /**
     * 
     * @param type $id
     * @return type
     */
    public static function getAPILog() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
                ->from('#__loginradius_log')
                ->order('log_id DESC LIMIT 20');
        $db->setQuery($query);
        return $db->LoadAssocList();       
    }
    
    public static function getSettings() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
                ->from('#__loginradius_settings');              
        $db->setQuery($query);
        $rows = $db->LoadAssocList();
        $settings = '';
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data ['value'];
            }
        } 
        return $settings;
    }

}
