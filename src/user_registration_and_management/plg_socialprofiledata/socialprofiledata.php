<?php

/**
 * @package     SocialProfileData.Plugin
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * Class plgContentSingleSignOn
 */
class plgSystemSocialProfileData extends JPlugin {

    /**
     * Constructor Loads the plugin settings and assigns them to class variables
     */
    public function __construct(&$subject) {
        parent::__construct($subject);
    }
    
    /**
     * SignOnScript Script call functionality
     * 
     * @param $islogout
     * @return string
     */
    
    public function onlrSocialProfileData($settings) { 
        
    $output = '<table class="form-table sociallogin_table">';
     $output .= '<tr>';
     $output .= '<th class="head" colspan="2">'.JText::_('COM_SOCIALLOGIN_USERDATA_SELECT_SETTING').'</th>';
     $output .= '</tr>';
     $output .= '<tr>';
     $output .= '<td colspan="2"><span class="sociallogin_subhead">'.JText::_('COM_SOCIALLOGIN_USERDATA_SELECT_TITLE').'</span><br/><br />';
     $output .= '<label for="basic">';          
     $output .= '<input id="basic" style="margin:0" type="checkbox" name="settings[basic]" value="1" '. ((isset($settings['basic']) && $settings['basic'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_BASIC_USERPROFILE_DATA');
     $output .= '</label>';
     $output .= '<label for="exlocation">';          
     $output .= '<input id="exlocation" style="margin:0" type="checkbox" name="settings[exlocation]" value="1" '. ((isset($settings['exlocation']) && $settings['exlocation'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_EXTENDED_LOCATION_DATA');
     $output .= '</label>';
     $output .= '<label for="exprofile">';          
     $output .= '<input id="exprofile" style="margin:0" type="checkbox" name="settings[exprofile]" value="1" '. ((isset($settings['exprofile']) && $settings['exprofile'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_EXTENDED_PROFILE_DATA');
     $output .= '</label>';
     $output .= '<label for="followcompanies">';          
     $output .= '<input id="followcompanies" style="margin:0" type="checkbox" name="settings[followcompanies]" value="1" '. ((isset($settings['followcompanies']) && $settings['followcompanies'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_FOLLOWED_COMPANIED_ONLINKEDIN_DATA');
     $output .= '</label>';
     $output .= '<label for="fbprofile">';          
     $output .= '<input id="fbprofile" style="margin:0" type="checkbox" name="settings[fbprofile]" value="1" '. ((isset($settings['fbprofile']) && $settings['fbprofile'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_FACEBOOK_PROFILE_EVENT_DATA');
     $output .= '</label>';
     $output .= '<label for="statusmessage">';          
     $output .= '<input id="statusmessage" style="margin:0" type="checkbox" name="settings[statusmessage]" value="1" '. ((isset($settings['statusmessage']) && $settings['statusmessage'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_STATUS_MESSAGES_DATA');
     $output .= '</label>';
     $output .= '<label for="fbpost">';          
     $output .= '<input id="fbpost" style="margin:0" type="checkbox" name="settings[fbpost]" value="1" '. ((isset($settings['fbpost']) && $settings['fbpost'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_FACEBOOK_POST_DATA');
     $output .= '</label>';
     $output .= '<label for="twittermentions">';          
     $output .= '<input id="twittermentions" style="margin:0" type="checkbox" name="settings[twittermentions]" value="1" '. ((isset($settings['twittermentions']) && $settings['twittermentions'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_TWITTER_MENTIONS_DATA');
     $output .= '</label>';
     $output .= '<label for="groups">';          
     $output .= '<input id="groups" style="margin:0" type="checkbox" name="settings[groups]" value="1" '. ((isset($settings['groups']) && $settings['groups'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_GROUPS_DATA');
     $output .= '</label>';
     $output .= '<label for="socialcontacts">';          
     $output .= '<input id="socialcontacts" style="margin:0" type="checkbox" name="settings[socialcontacts]" value="1" '. ((isset($settings['socialcontacts']) && $settings['socialcontacts'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_CONTACTS_DATA');
     $output .= '</label>';
     $output .= '<label for="fblike">';          
     $output .= '<input id="fblike" style="margin:0" type="checkbox" name="settings[fblike]" value="1" '. ((isset($settings['fblike']) && $settings['fblike'] == '1') ? "checked" : "") .'/> '.JText::_('COM_SOCIALLOGIN_FACEBOOK_LIKES_DATA');
     $output .= '</label>';    
     $output .= '</td>';
     $output .= '</tr>';
     $output .= '<tr>';
     $output .= '<td colspan="2">'. JText::_('COM_SOCIALLOGIN_LIST_OFALL_FILEDS');
     $output .= '<a href="'. JRoute::_('index.php?option=com_userregistrationandmanagement&view=usermanager').'" target="_blank">User Manager</a>';
     $output .= '</td>';
     $output .= '</tr>';
     $output .= '</table>';
        
     return $output;        
    }
    
    
    public function onAfterInitialise() {
        $db = JFactory::getDBO();
        $columns = $db->getTableColumns('#__loginradius_extended_profile_data');
        if (!isset($columns['no_of_logins'])) {
            $querycol = "ALTER TABLE #__loginradius_extended_profile_data ADD no_of_logins varchar(20) NULL";
            $db->setQuery($querycol);
            $db->query();
        }
    }    
}
