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
class UserRegistrationAndManagementViewUserManager extends JViewLegacy
{

    /**
     * SocialLogin - Display administration area
     * 
     * @param type $tpl
     */
    public function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . $view . '.limitstart', 'limitstart', 0, 'int');
        $filter_order = $mainframe->getUserStateFromRequest($option . $view . 'filter_order', 'filter_order', 'juser.name', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . $view . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter_status = $mainframe->getUserStateFromRequest($option . $view . 'filter_status', 'filter_status', -1, 'int');
        $search = JString::strtolower($mainframe->getUserStateFromRequest($option . $view . 'filter_search', 'filter_search', '', 'string'));

        $model = $this->getModel();

        $total = $model->getTotal();
        if ($limitstart > $total - $limit)
        {
            $limitstart = max(0, (int) (ceil($total / $limit) - 1) * $limit);
            JRequest::setVar('limitstart', $limitstart);
        }
        $users = $model->getuserdata();
        $this->assignRef('rows', $users);

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);
        $this->assignRef('page', $pageNav);

        $lists = array();
        $lists['filter_search'] = $search;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $filter_status_options[] = JHTML::_('select.option', -1, JText::_('LR_SELECT_STATE'));
        $filter_status_options[] = JHTML::_('select.option', 0, JText::_('LR_ENABLED'));
        $filter_status_options[] = JHTML::_('select.option', 1, JText::_('LR_BLOCKED'));
        $lists['status'] = JHTML::_('select.genericlist', $filter_status_options, 'filter_status', '', 'value', 'text', $filter_status);

        $this->assignRef('lists', $lists);

        $template = $mainframe->getTemplate();
        $this->assignRef('template', $template);

        if ($mainframe->isAdmin())
        {
            $this->addToolbar();
        }
        $isAdmin = $mainframe->isAdmin();
        $this->assignRef('isAdmin', $isAdmin);
        parent::display($tpl);
    }

    /**
     * SocialLogin - Add admin option on toolbar
     */
    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_SOCIALLOGIN_USER_MANAGER'), 'users');
        JToolbarHelper::addNew('add');
        JToolbarHelper::editList('edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('publish', 'COM_USERS_TOOLBAR_ACTIVATE', true);
        JToolbarHelper::unpublish('block', 'COM_USERS_TOOLBAR_BLOCK', true);
        JToolbarHelper::custom('unblock', 'unblock.png', 'unblock_f2.png', 'COM_USERS_TOOLBAR_UNBLOCK', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'remove');
        JToolBarHelper::cancel('cancel');
    }
}
