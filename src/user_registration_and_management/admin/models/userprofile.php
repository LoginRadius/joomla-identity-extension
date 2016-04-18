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
class UserRegistrationAndManagementModelUserProfile extends JModelList
{

    protected static $actions;

    /**
     * get user profile data from db
     * 
     * @param type $table
     * @param type $data
     * @return type
     */
    public function getUserProfile($table, $data)
    {
        $db = $this->getDbo();

        $sql = "SELECT * FROM #__loginradius_" . $table . " WHERE user_id = " . (int) $data;
        $db->setQuery($sql);

        return $db->LoadAssocList();
    }

    /**
     * get companies data from db
     * 
     * @param type $data
     * @return type
     */
    public static function getCompanies($data)
    {
        $db = JFactory::getDbo();
        $sql = "SELECT * FROM #__loginradius_companies WHERE id = " . $data;
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();
        return $rows;
    }

    /**
     * get user profile data for mapping from db
     * 
     * @param type $id
     * @return type
     */
    public function getSocialUserData($id)
    {
        $socialdata = array();
        $db = JFactory::getDbo();
        $sql = "SELECT * FROM #__loginradius_users WHERE id=" . (int) $id;
        $db->setQuery($sql);
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
     * Display all user profile data on user Profile page
     * 
     * @param type $array
     * @param type $subTable
     */
    public static function displayProfile($array, $subTable = false)
    {
        if ($subTable)
        {
            ?>
            <tfoot>
                <?php
                $count = 1;               
                foreach ($array as $temp)
                {
                    ?>
                    <tr <?php
                    if (($count % 2) == 0)
                    {
                        echo 'style="background-color:#fcfcfc"';
                    }
                    ?>>
                            <?php
                            foreach ($temp as $key => $val)
                            {
                                if ($key == 'user_id')
                                {
                                    continue;
                                } elseif (in_array($key, array('image', 'logo', 'image_url', 'picture')))
                                {
                                    if (isset($temp['provider']) && $temp['provider'] == 'facebook' && isset($temp['social_id']))
                                    {
                                        ?>
                                    <th scope="col" class="manage-colum"><img src ="https://graph.facebook.com/<?php echo $temp['social_id']; ?>/picture?type=square"/></th><?php
                            continue;
                        }
                                    ?>
                                <th scope="col" class="manage-colum"><?php if (!empty($val))
                        {
                                        ?><img src ="<?php echo $val; ?>" /><?php } ?></th>
                                    <?php
                                } else
                                {
                                    ?>
                                <th scope="col" class="manage-colum">
                                <?php echo ucfirst($val); ?>
                                </th>
                            <?php
                        }
                    }
                    ?>
                    </tr>
                <?php
                $count++;
            }
            ?>
            </tfoot>
            <?php
        } else
        {
            ?>
            <table class="form-table sociallogin_table" cellspacing="0">
                <tfoot>
                    <?php
                    $count = 1;
                    foreach ($array as $key => $value)
                    {
                        if ($value != '')
                        {
                            if ($key == 'user_id')
                            {
                                continue;
                            }
                            ?>
                            <tr <?php
                                if (($count % 2) == 0)
                                {
                                    echo 'style="background-color:#fcfcfc"';
                                }
                                ?>>
                                    <?php
                                    $keyParts = explode('_', $key);
                                    $keyParts = array_map(function($elem)
                                    {
                                        return ucfirst($elem);
                                    }, $keyParts);
                                    ?>
                                    <th scope="col" class="manage-colum"><?php echo count($keyParts) > 1 ? implode(' ', $keyParts) : ucfirst($key) ?></th> 
                                    <th scope="col" class="manage-colum"><?php
                                    if (is_string($value))
                                    {
                                        echo ucfirst($value);
                                    } else
                                    {
                                        $company['name'] = $value->company_name;
                                        $company['type'] = $value->company_type;
                                        $company['industry'] = $value->industry;
                                        UserRegistrationAndManagementModelUserProfile::displayProfile($company);
                                    }
                                    ?></th>
                            </tr>
                            <?php
                            $count++;
                        }
                    }
                    ?>
                </tfoot>
            </table>
            <?php
        }
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
     * Gets a list of the actions that can be performed.
     *
     * @return  JObject
     *
     * @since   1.6
     * @todo    Refactor to work with notes
     */
    public static function getActions()
    {
        if (empty(self::$actions))
        {
            $user = JFactory::getUser();
            self::$actions = new JObject;

            $actions = JAccess::getActions('com_users');

            foreach ($actions as $action)
            {
                self::$actions->set($action->name, $user->authorise($action->name, 'com_users'));
            }
        }

        return self::$actions;
    }

    /**
     * Get a list of filter options for the blocked state of a user.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    public static function getStateOptions()
    {
        // Build the filter options.
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('JENABLED'));
        $options[] = JHtml::_('select.option', '1', JText::_('JDISABLED'));

        return $options;
    }

    /**
     * Get a list of filter options for the activated state of a user.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    public static function getActiveOptions()
    {
        // Build the filter options.
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('COM_USERS_ACTIVATED'));
        $options[] = JHtml::_('select.option', '1', JText::_('COM_USERS_UNACTIVATED'));

        return $options;
    }

    /**
     * Get a list of the user groups for filtering.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    public static function getGroups()
    {
        $db = JFactory::getDbo();
        $db->setQuery(
                'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
                ' FROM #__usergroups AS a' .
                ' LEFT JOIN ' . $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
                ' GROUP BY a.id, a.title, a.lft, a.rgt' .
                ' ORDER BY a.lft ASC'
        );

        try
        {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e)
        {
            JError::raiseNotice(500, $e->getMessage());
            return null;
        }

        foreach ($options as &$option)
        {
            $option->text = str_repeat('- ', $option->level) . $option->text;
        }

        return $options;
    }

    /**
     * Creates a list of range options used in filter select list
     * used in com_users on users view
     *
     * @return  array
     *
     * @since   2.5
     */
    public static function getRangeOptions()
    {
        $options = array(
            JHtml::_('select.option', 'today', JText::_('COM_USERS_OPTION_RANGE_TODAY')),
            JHtml::_('select.option', 'past_week', JText::_('COM_USERS_OPTION_RANGE_PAST_WEEK')),
            JHtml::_('select.option', 'past_1month', JText::_('COM_USERS_OPTION_RANGE_PAST_1MONTH')),
            JHtml::_('select.option', 'past_3month', JText::_('COM_USERS_OPTION_RANGE_PAST_3MONTH')),
            JHtml::_('select.option', 'past_6month', JText::_('COM_USERS_OPTION_RANGE_PAST_6MONTH')),
            JHtml::_('select.option', 'past_year', JText::_('COM_USERS_OPTION_RANGE_PAST_YEAR')),
            JHtml::_('select.option', 'post_year', JText::_('COM_USERS_OPTION_RANGE_POST_YEAR')),
        );
        return $options;
    }

}
