<?php
/**
 * @package     CiamLoginRadius.Plugin
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.controller');
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}
require_once (JPATH_ROOT.DS. 'plugins'. DS . 'system'. DS .'ciam'. DS .'ciam.php');

/**
 * Controller Class CiamLoginRadiusController
 */
class CiamLoginRadiusController extends LRController
{

    /**
     * @param bool $cachable
     * @param bool $urlparams
     * @return JController|JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = false)
    {
        JRequest::setVar('view', JRequest::getCmd('view', 'CiamLoginRadius'));    
        parent::display($cachable);
    }

    /**
     * Save settings
     */
    public function apply()
    {     
        $mainframe = JFactory::getApplication();
        $model = $this->getModel();       
        $view = JRequest::getVar('view', 'ciamloginradius');         
        $option = JRequest::getVar('option', 'ciamloginradius');   
        $result = $model->saveSettings($view);
        $mainframe->enqueueMessage($result['message'], $result['status']);
        $this->setRedirect(JRoute::_('index.php?option=' . $option . '&view=' . $view . '&layout=default', false));
    }
    /**
     * Save and close settings
     */
    public function save()
    {      
        $mainframe = JFactory::getApplication();
        $model = &$this->getModel();
        $view = JRequest::getVar('view', 'ciamloginradius');
        $result = $model->saveSettings($view);        
        $mainframe->enqueueMessage($result['message'], $result['status']);
        $this->setRedirect(JRoute::_('index.php', false));
    }
  
    /**
     * block/Unblock user account from user manager 
     * 
     * @param type $self
     * @return array
     */
//    public static function blockStates($self = false)
//    {
//        if ($self)
//        {
//            $states = array(
//                1 => array(
//                    'task' => 'unblock',
//                    'text' => '',
//                    'active_title' => 'COM_USERS_USER_FIELD_BLOCK_DESC',
//                    'inactive_title' => '',
//                    'tip' => true,
//                    'active_class' => 'unpublish',
//                    'inactive_class' => 'unpublish'
//                ),
//                0 => array(
//                    'task' => 'block',
//                    'text' => '',
//                    'active_title' => '',
//                    'inactive_title' => 'COM_USERS_USERS_ERROR_CANNOT_BLOCK_SELF',
//                    'tip' => true,
//                    'active_class' => 'publish',
//                    'inactive_class' => 'publish'
//                )
//            );
//        } else
//        {
//            $states = array(
//                1 => array(
//                    'task' => 'unblock',
//                    'text' => '',
//                    'active_title' => 'COM_USERS_TOOLBAR_UNBLOCK',
//                    'inactive_title' => '',
//                    'tip' => true,
//                    'active_class' => 'unpublish',
//                    'inactive_class' => 'unpublish'
//                ),
//                0 => array(
//                    'task' => 'block',
//                    'text' => '',
//                    'active_title' => 'COM_USERS_USER_FIELD_BLOCK_DESC',
//                    'inactive_title' => '',
//                    'tip' => true,
//                    'active_class' => 'publish',
//                    'inactive_class' => 'publish'
//                )
//            );
//        }
//
//        return $states;
//    }

    /**
     * Build an array of activate states to be used by jgrid.state,
     *
     * @return  array  a list of possible states to display
     *
     * @since  3.0
     */
    public static function activateStates()
    {
        $states = array(
            1 => array(
                'task' => 'activate',
                'text' => '',
                'active_title' => 'COM_USERS_TOOLBAR_ACTIVATE',
                'inactive_title' => '',
                'tip' => true,
                'active_class' => 'unpublish',
                'inactive_class' => 'unpublish'
            ),
            0 => array(
                'task' => '',
                'text' => '',
                'active_title' => '',
                'inactive_title' => 'COM_USERS_ACTIVATED',
                'tip' => true,
                'active_class' => 'publish',
                'inactive_class' => 'publish'
            )
        );
        return $states;
    }

    /**
     * Method to change the block status on a record.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function changeBlock()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $ids = JRequest::getVar('cid', array(), 'array');
        $values = array('block' => 1, 'unblock' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($ids))
        {
            JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
        } else
        {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->block($ids, $value))
            {
                JError::raiseWarning(500, $model->getError());
            } else
            {
                if ($value == 1)
                {
                    $this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
                } elseif ($value == 0)
                {
                    $this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED', count($ids)));
                }
            }
        }

        $this->setRedirect('index.php?option=com_users&view=users');
    }

    /**
     * Method to activate a record.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function activate()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $ids = JRequest::getVar('cid', array(), 'array');

        if (empty($ids))
        {
            JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
        } else
        {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->activate($ids))
            {
                JError::raiseWarning(500, $model->getError());
            } else
            {
                $this->setMessage(JText::plural('COM_USERS_N_USERS_ACTIVATED', count($ids)));
            }
        }

        $this->setRedirect('index.php?option=com_users&view=users');
    }

}
