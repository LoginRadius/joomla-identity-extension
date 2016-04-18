<?php

/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
jimport('joomla.plugin.helper');

/**
 * Profile model class for Users.
 *
 * @package        Joomla.Site
 * @subpackage    com_users
 * @since        1.6
 */
class UserRegistrationAndManagementModelRegister
{

    /**
     * @var        object    The user profile data.
     * @since    1.6
     */
    protected $data;

    /**
     * Method to check in a user.
     *
     * @param    integer        The id of the row to check out.
     * @return    boolean        True on success, false on failure.
     * @since    1.6
     */
    public function checkin($userId = null)
    {
        // Get the user id.
        $userId = (!empty($userId)) ? $userId : (int) $this->getState('user.id');

        if ($userId)
        {
            // Initialise the table with JUser.
            $table = JTable::getInstance('User');

            // Attempt to check the row in.
            if (!$table->checkin($userId))
            {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }


}
