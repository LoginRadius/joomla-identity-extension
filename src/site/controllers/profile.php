<?php

/**
 * @version         $Id: profile.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package         LoginRadiusSocialLoginandSocialShare.Site
 * @subpackage      com_loginradiussocialloginandsocialshare
 * @copyright       Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
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
class LoginRadiusSocialLoginAndSocialShareControllerProfile extends LoginRadiusSocialLoginAndSocialShareController
{

    /**
     * Method to check out a user for editing and redirect to the edit form.
     * 
     * @return boolean
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
            $app->enqueueMessage(JText::_('COM_SOCIALLOGIN_ALERTNOAUTHOR'), 'warning');
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
     * @return boolean
     */
    public function save()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $model = $this->getModel('Profile', 'UsersModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');

        // Get the user data.
        $data = $app->input->post->get('jform', array(), 'array');

        // Force the ID to this user.
        $data['id'] = $userId;

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form)
        {
            $app->enqueueMessage($model->getError(), 'error');
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false)
        {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else
                {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_users.edit.profile.data', $data);

            // Redirect back to the edit screen.
            $userId = (int) $app->getUserState('com_users.edit.profile.id');
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id=' . $userId, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false)
        {
            // Save the data in the session.
            $app->setUserState('com_users.edit.profile.data', $data);

            // Redirect back to the edit screen.
            $userId = (int) $app->getUserState('com_users.edit.profile.id');
            $this->setMessage(JText::sprintf('COM_USERS_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id=' . $userId, false));
            return false;
        }

        // Redirect the user and adjust session state based on the chosen task.
        switch ($this->getTask())
        {
            case 'apply':
                // Check out the profile.
                $app->setUserState('com_users.edit.profile.id', $return);
                $model->checkout($return);

                // Redirect back to the edit screen.
                $this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
                $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_users&view=profile&layout=edit&hidemainmenu=1', false));
                break;

            default:
                // Check in the profile.
                $userId = (int) $app->getUserState('com_users.edit.profile.id');
                if ($userId)
                {
                    $model->checkin($userId);
                }

                // Clear the profile id from the session.
                $app->setUserState('com_users.edit.profile.id', null);

                // Redirect to the list screen.
                $this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
                $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_users&view=profile&user_id=' . $return, false));
                break;
        }

        // Flush the data from the session.
        $app->setUserState('com_users.edit.profile.data', null);
    }

    /**
     * Delete the account mapping id.
     * 
     * @return type
     */
    public function removeSocialAccount()
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Profile', 'LoginRadiusSocialLoginAndSocialShareModel');
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
            $this->setRedirect(JRoute::_('index.php?option=com_loginradiussocialloginandsocialshare&view=profile', false));
        }
    }

}
