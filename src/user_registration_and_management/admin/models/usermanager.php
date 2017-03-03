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

class UserRegistrationAndManagementModelUserManager extends JModelList
{
    protected static $actions;

    /**
     * 
     * @param type $ordering
     * @param type $direction
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout', 'default', 'cmd'))
        {
            $this->context .= '.' . $layout;
        }

        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $active = $this->getUserStateFromRequest($this->context . '.filter.active', 'filter_active');
        $this->setState('filter.active', $active);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state');
        $this->setState('filter.state', $state);

        $groupId = $this->getUserStateFromRequest($this->context . '.filter.group', 'filter_group_id', null, 'int');
        $this->setState('filter.group_id', $groupId);

        $range = $this->getUserStateFromRequest($this->context . '.filter.range', 'filter_range');
        $this->setState('filter.range', $range);

        $groups = json_decode(base64_decode($app->input->get('groups', '', 'BASE64')));
        if (isset($groups))
        {
            JArrayHelper::toInteger($groups);
        }
        $this->setState('filter.groups', $groups);

        $excluded = json_decode(base64_decode($app->input->get('excluded', '', 'BASE64')));
        if (isset($excluded))
        {
            JArrayHelper::toInteger($excluded);
        }
        $this->setState('filter.excluded', $excluded);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_userregistrationandmanagement');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
    }

    /**
     * 
     * @return type
     */
    public function getuserdata()
    {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $db = $this->getDbo();
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . $view . '.limitstart', 'limitstart', 0, 'int');
        $filter_order = $mainframe->getUserStateFromRequest($option . $view . 'filter_order', 'filter_order', 'juser.name', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . $view . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
        $filter_status = $mainframe->getUserStateFromRequest($option . $view . 'filter_status', 'filter_status', -1, 'int');
        $search = $mainframe->getUserStateFromRequest($option . $view . 'filter_search', 'filter_search', '', 'string');
        $search = JString::strtolower($search);
        $query = "SELECT juser.* FROM #__users as juser WHERE juser.id>0";

        if ($filter_status > -1)
        {
            $query .= " AND juser.block = {$filter_status}";
        }


        if ($search)
        {
            $escaped = $db->escape($search, true);
            $query .= " AND (LOWER( juser.name ) LIKE " . $db->Quote('%' . $escaped . '%', false) . " OR LOWER( juser.email ) LIKE " . $db->Quote('%' . $escaped . '%', false) . ")";
        }

        if (!$filter_order)
        {
            $filter_order = "juser.name";
        }


        $query .= "  GROUP BY juser.id  ";


        $query .= " ORDER BY {$filter_order} {$filter_order_Dir}";
        $db->setQuery($query, $limitstart, $limit);
        $rows = $db->loadObjectList();

        return $rows;
    }

    /**
     * 
     * @param type $times
     * @param type $char
     * @param type $start_char
     * @param type $end_char
     * @return type
     */
    function indent($times, $char = '&nbsp;&nbsp;&nbsp;&nbsp;', $start_char = '', $end_char = '')
    {
        $return = $start_char;
        for ($i = 0; $i < $times; $i++)
            $return .= $char;
        $return .= $end_char;
        return $return;
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public static function getsocialuserdata($id)
    {
        $socialdata = array();
        $db = JFactory::getDbo();        
        $query = $db->getQuery(true);
        $query->select('*')
                ->from('#__loginradius_users')
                ->where('id = ' . (int) $id);     
        $db->setQuery($query);     
        $rows = $db->LoadAssocList();
        if (is_array($rows))
        {
            foreach ($rows as $key => $data)
            {
                $socialdata = $data;
            }
        }
        return $socialdata;
    }

    /**
     * Read Settings
     * 
     * @return boolean
     */
    public function getItems()
    {
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (empty($this->cache[$store]))
        {
            $groups = $this->getState('filter.groups');
            $groupId = $this->getState('filter.group_id');
            if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups)))
            {
                $items = array();
            } else
            {
                $items = parent::getItems();
            }

            // Bail out on an error or empty list.
            if (empty($items))
            {
                $this->cache[$store] = $items;

                return $items;
            }

            // Joining the groups with the main query is a performance hog.
            // Find the information only on the result set.
            // First pass: get list of the user id's and reset the counts.
            $userIds = array();
            foreach ($items as $item)
            {
                $userIds[] = (int) $item->id;
                $item->group_count = 0;
                $item->group_names = '';
                $item->note_count = 0;
            }

            // Get the counts from the database only for the users in the list.
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            // Join over the group mapping table.
            $query->select('map.user_id, COUNT(map.group_id) AS group_count')
                    ->from('#__user_usergroup_map AS map')
                    ->where('map.user_id IN (' . implode(',', $userIds) . ')')
                    ->group('map.user_id')
                    // Join over the user groups table.
                    ->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

            $db->setQuery($query);

            // Load the counts into an array indexed on the user id field.
            try
            {
                $userGroups = $db->loadObjectList('user_id');
            } catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }

            $query->clear()
                    ->select('n.user_id, COUNT(n.id) As note_count')
                    ->from('#__user_notes AS n')
                    ->where('n.user_id IN (' . implode(',', $userIds) . ')')
                    ->where('n.state >= 0')
                    ->group('n.user_id');

            $db->setQuery((string) $query);

            // Load the counts into an array indexed on the aro.value field (the user id).
            try
            {
                $userNotes = $db->loadObjectList('user_id');
            } catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }

            // Second pass: collect the group counts into the master items array.
            foreach ($items as &$item)
            {
                if (isset($userGroups[$item->id]))
                {
                    $item->group_count = $userGroups[$item->id]->group_count;
                    //Group_concat in other databases is not supported
                    $item->group_names = $this->_getUserDisplayedGroups($item->id);
                }

                if (isset($userNotes[$item->id]))
                {
                    $item->note_count = $userNotes[$item->id]->note_count;
                }
            }

            // Add the items to the internal cache.
            $this->cache[$store] = $items;
        }

        return $this->cache[$store];
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.active');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.group_id');
        $id .= ':' . $this->getState('filter.range');

        return parent::getStoreId($id);
    }

    /**
     * 
     * @return type
     */
    function getTotal()
    {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $db = JFactory::getDBO();
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');
        $filter_status = $mainframe->getUserStateFromRequest($option . $view . 'filter_status', 'filter_status', -1, 'int');
        $search = $mainframe->getUserStateFromRequest($option . $view . 'search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $query = "SELECT COUNT(DISTINCT juser.id) FROM #__users as juser ";

        $query .= " WHERE juser.id>0";

        if ($filter_status > -1)
        {
            $query .= " AND juser.block = {$filter_status}";
        }

        if ($search)
        {
            $escaped = $db->escape($search, true);
            $query .= " AND (LOWER( juser.name ) LIKE " . $db->Quote('%' . $escaped . '%', false) . " OR LOWER( juser.email ) LIKE " . $db->Quote('%' . $escaped . '%', false) . ")";
        }

        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    /**
     * 
     * @return type
     */
    public static function getActions()
    {
        if (empty(self::$actions))
        {
            $user = JFactory::getUser();
            self::$actions = new JObject;

            $actions = JAccess::getActions('com_userregistrationandmanagement');
            foreach ($actions as $action)
            {
                self::$actions->set($action->name, $user->authorise($action->name, 'com_userregistrationandmanagement'));
            }
        }
        return self::$actions;
    }

}
