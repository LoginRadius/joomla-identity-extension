<?php

/**
 * @package   	SocialLogin
 * @copyright 	Copyright 2012 http://www.loginradius.com - All rights reserved.
 * @license   	GNU/GPL 2 or later
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.controller');

/**
 * General Controller of LoginRadiusSocialLoginAndSocialShare component
 */
class LoginRadiusSocialLoginAndSocialShareController extends LRController
{

    /**
     * 
     * @param type $cachable
     * @param type $urlparams
     * @return type
     */
    public function display($cachable = false, $urlparams = false)
    {
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the default view name and format from the Request.
        $vName = $this->input->getVar('view', 'login');
        $vFormat = $document->getType();
        $lName = $this->input->getVar('layout', 'default');

        if ($view = $this->getView($vName, $vFormat))
        {
            // Do any specific processing by view.
            switch ($vName)
            {
                // Handle view specific models.
                case 'profile':

                    // If the user is a guest, redirect to the login page.
                    $user = JFactory::getUser();
                    if ($user->get('guest') == 1)
                    {
                        // Redirect to login page.
                        $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
                        return;
                    }
                    $model = $this->getModel($vName);
                    break;

                default:
                    $model = $this->getModel('Login');
                    break;
            }

            // Push the model into the view (as default).
            $view->setModel($model, true);
            $view->setLayout($lName);

            // Push document object into the view.
            $view->document = $document;

            $view->display();
        }
    }

}
