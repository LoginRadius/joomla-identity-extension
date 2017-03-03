<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * Class generate view.
 */
class UserRegistrationAndManagementViewApiLog extends JViewLegacy
{

     public $settings;
    /**
     * SocialLogin - Display administration area
     * 
     * @param type $tpl
     */
    
    public function display($tpl = null)
    {        
        $document = JFactory::getDocument();
        $model = $this->getModel();
        $version = '3';
        if (JVERSION < 3) {
            $version = '2';
        }
        $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js');
        $document->addStyleSheet('components/com_userregistrationandmanagement/assets/css/userprofile' . $version . '.min.css');
        $document->addScript('components/com_userregistrationandmanagement/assets/js/userregistrationandmanagement.min.js');  
         
        $this->apilogdata = $model->getAPILog();
        $this->settings = $model->getSettings();
        $this->form = $this->get('Form');
        $this->addToolbar();
        parent::display($tpl);
    }
    
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', false);
        JToolBarHelper::title(JText::_('COM_SOCIALLOGIN_DEBUG_LOG'), 'configuration.gif');     
        JToolbarHelper::custom('clear', 'cancel.png', 'cancel.png', 'COM_USERS_TOOLBAR_CLEAR', false);
//        JToolBarHelper::apply('apply');
//        JToolBarHelper::save('save', 'JTOOLBAR_SAVE');
//        JToolBarHelper::cancel('cancel');
    }
}
