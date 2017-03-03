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
class UserRegistrationAndManagementViewSocialSharing extends JViewLegacy {

    public $settings;

    /**
     * SocialLogin - Display administration area
     * 
     * @param type $tpl
     */
    public function display($tpl = null) {
        $this->settings = $this->initialSetting();
        $this->articles = $this->selectArticles();
        $document = JFactory::getDocument();
        $version = '3';
        if (JVERSION < 3) {
            $version = '2';
        }
        $document->addStyleSheet('components/com_userregistrationandmanagement/assets/css/socialloginandsocialshare' . $version . '.min.css');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js');
        $document->addScriptDeclaration('$(function(){$("#horsortable").sortable({revert: true});});');
        $document->addScriptDeclaration('$(function(){$("#versortable").sortable({revert: true});});');
        $document->addScript('components/com_userregistrationandmanagement/assets/js/simplifiedsocialshare.min.js');

        $this->form = $this->get('Form');
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * @param $model
     * @return mixed
     */
    private function initialSetting() {
        $settings = $this->getSettings();

        $shareProvider = array("facebook", "twitter", "pinterest", "googleplus", "linkedin");
        $counterProvider = array("Facebook Like", "Twitter Tweet", "Google+ Share", "LinkedIn Share");

        //Horizontal sharing setting
        $settings['horizontal_rearrange'] = (!empty($settings['horizontal_rearrange']) ? (@unserialize($settings['horizontal_rearrange'])) : $shareProvider);
        $settings['vertical_rearrange'] = (!empty($settings['vertical_rearrange']) ? (@unserialize($settings['vertical_rearrange'])) : $shareProvider);
        $settings['horizontalcounter'] = (!empty($settings['horizontalcounter']) ? (@unserialize($settings['horizontalcounter'])) : $counterProvider);
        $settings['verticalcounter'] = (!empty($settings['verticalcounter']) ? (@unserialize($settings['verticalcounter'])) : $counterProvider);
        $settings['sharehorizontal'] = (isset($settings['sharehorizontal']) ? $settings['sharehorizontal'] : "");
        $settings['horizontalarticaltype'] = (isset($settings['horizontalarticaltype']) && $settings['horizontalarticaltype'] != '1') ? $settings['horizontalarticaltype'] : "1";
        $settings['choosehorizontalshare'] = (isset($settings['choosehorizontalshare']) ? $settings['choosehorizontalshare'] : "");
        $settings['chooseverticalshare'] = (isset($settings['chooseverticalshare']) ? $settings['chooseverticalshare'] : "");
        $settings['shareontoppos'] = isset($settings['shareontoppos']) == '1' ? 'checked="checked"' : "";
        $settings['shareonbottompos'] = isset($settings['shareonbottompos']) == '1' ? 'checked="checked"' : "";
        $settings['sharevertical'] = (isset($settings['sharevertical']) ? $settings['sharevertical'] : "");
        $settings['verticalsharepos'] = (isset($settings['verticalsharepos']) ? $settings['verticalsharepos'] : "");
        $settings['verticalArticles'] = (isset($settings['verticalArticles']) ? @unserialize($settings['verticalArticles']) : "");
        $settings['horizontalArticles'] = (isset($settings['horizontalArticles']) ? @unserialize($settings['horizontalArticles']) : "");
        $settings['verticalarticaltype'] = (isset($settings['verticalarticaltype']) && $settings['verticalarticaltype'] != '1') ? $settings['verticalarticaltype'] : "1";
        $settings['mobilefriendly'] = (isset($settings['mobilefriendly']) ? $settings['mobilefriendly'] : "");
        $settings['emailreadonly'] = (isset($settings['emailreadonly']) ? $settings['emailreadonly'] : "");
        $settings['custompopup'] = (isset($settings['custompopup']) ? $settings['custompopup'] : "");
        $settings['mobilefriendly'] = (isset($settings['mobilefriendly']) ? $settings['mobilefriendly'] : "");
        $settings['shorturl'] = (isset($settings['shorturl']) ? $settings['shorturl'] : "");
        $settings['sharecount'] = (isset($settings['sharecount']) ? $settings['sharecount'] : "");
        $settings['singlewindow'] = (isset($settings['singlewindow']) ? $settings['singlewindow'] : "");
        $settings['custompopup'] = (isset($settings['custompopup']) ? $settings['custompopup'] : "");
        $settings['emailsubject'] = (isset($settings['emailsubject']) ? $settings['emailsubject'] : "");
        $settings['emailmessage'] = (isset($settings['emailmessage']) ? $settings['emailmessage'] : "");
        $settings['twittermention'] = (isset($settings['twittermention']) ? $settings['twittermention'] : "");
        $settings['twitterhashtag'] = (isset($settings['twitterhashtag']) ? $settings['twitterhashtag'] : "");
        $settings['facebookappid'] = (isset($settings['facebookappid']) ? $settings['facebookappid'] : "");
        $settings['customoptions'] = (isset($settings['customoptions']) ? $settings['customoptions'] : "");


        return $settings;
    }

    /**
     * @return mixed
     */
    private function selectArticles() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id, title')
                ->from('#__content')
                ->where('state = ' . $db->Quote('1'))
                ->order('ordering');
        $db->setQuery($query);       
        return $db->loadObjectList();      
    }     

    /**
     * SocialLogin - Add admin option on toolbar
     */
    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', false);
        JToolBarHelper::title(JText::_('COM_SOCIALLOGIN_SOCIAL_SHARING'), 'configuration.gif');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save('save', 'JTOOLBAR_SAVE');
        JToolBarHelper::cancel('cancel');
    }

    /**
     * Read Settings
     * 
     * @return type
     */
    public function getSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $db->setQuery("CREATE TABLE IF NOT EXISTS #__loginradius_advanced_settings (
						`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`setting` varchar(255) NOT NULL,
						`value` varchar(1000) NOT NULL,
						PRIMARY KEY (`id`),
						UNIQUE KEY `setting` (`setting`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
        $db->query();
        
        $query = $db->getQuery(true);
        $query->select('*')
                ->from('#__loginradius_advanced_settings');               
        $db->setQuery($query);     
        $rows = $db->LoadAssocList();
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data ['value'];
            }
        }
        return $settings;
    }

}
