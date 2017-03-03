<?php
/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Profile controller class for Users.
 *
 * @package        Joomla.Site
 * @subpackage    com_users
 * @since        1.6
 */
class UserRegistrationAndManagementControllerProfile extends UserRegistrationAndManagementController
{

    /**
     * Method to check out a user for editing and redirect to the edit form.
     *
     * @since    1.6
     */
    public function edit()
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $loginUserId = (int) $user->get('id');

        // Get the previous user id (if any) and the current user id.
        $previousId = (int) $app->getUserState('com_users.edit.profile.id');
        $userId = $this->input->getInt('user_id', null, 'array');

        // Check if the user is trying to edit another users profile.
        if ($userId != $loginUserId)
        {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_users.edit.profile.id', $userId);

        // Get the model.
        $model = $this->getModel('Profile', 'UsersModel');

        // Check out the user.
        if ($userId)
        {
            $model->checkout($userId);
        }

        // Check in the previous user.
        if ($previousId)
        {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return    void
     * @since    1.6
     */
    public function save()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        if ($user->get('guest') == 1)
        {            
            // Redirect to login page.
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
          
            return;
        }
        $userId = (int) $user->get('id');

        // Get the user data.
        $data = $app->input->post->get('jform', array(), 'array');
        
        // Force the ID to this user.
        $data['id'] = $userId;
        if (JVERSION < 3) {
            $dispatcher = JDispatcher::getInstance();
        } else {
            $dispatcher = JEventDispatcher::getInstance();
        }
        $results = $dispatcher->trigger( 'onloginRadiusUserSave', array( $data ) );
        $app->enqueueMessage($results[0]['message'], $results[0]['status']);
        $this->setRedirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=updateprofile', false));       
        return;
    }

    // Delete the account mapping id.

    public function removeSocialAccount()
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Profile', 'UserRegistrationAndManagementModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');
        if ($user->get('guest') == 1)
        {
            // Redirect to login page.
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
            return;
        }
        $db = JFactory::getDBO();
        $mapProvider = JFactory::getApplication()->input->get('mapid');
        $map_userid = JFactory::getApplication()->input->get('lruser_id');
        $deleted = $model->removeSocialAccount($mapProvider, $map_userid);

        // Redirect to the list screen.
        if ($deleted == true)
        {
            $this->setMessage(JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_DELETE'));
            $this->setRedirect(JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile', false));
        }
    }

}
