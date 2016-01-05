<?php

/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * Class LoginRadiusSocialLoginAndSocialShareViewSocialLoginAndSocialShare
 */
class LoginRadiusSocialLoginAndSocialShareViewLoginRadiusSocialLoginAndSocialShare extends JViewLegacy {

    public $settings;

    /**
     * assign global variable for display section 
     * 
     * @param type $tpl
     */
    public function display($tpl = null) {
        $model = $this->getModel();

        $this->settings = $this->initialSetting($model);
        $this->articles = $this->selectArticles();
        $this->loginRedirection = $this->selectRedirection($this->settings['loginredirection']);
        $this->registerRedirection = $this->selectRedirection($this->settings['registerredirection']);

        $this->form = $this->get('Form');
        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * initialize all variable to be display
     * @param $model
     * @return mixed
     */
    private function initialSetting($model) {
        $settings = $model->getSettings();

        $shareProvider = array("facebook", "twitter", "pinterest", "googleplus", "linkedin");
        $counterProvider = array("Facebook Like", "Twitter Tweet", "Google+ Share", "LinkedIn Share");

        //Basic setting
        $settings['apikey'] = isset($settings['apikey']) ? trim(htmlspecialchars($settings['apikey'])) : '';
        $settings['apisecret'] = isset($settings['apisecret']) ? trim(htmlspecialchars($settings['apisecret'])) : '';
        $settings['loginredirection'] = isset($settings['loginredirection']) ? trim($settings['loginredirection']) : '';
        $settings['registerredirection'] = isset($settings['registerredirection']) ? trim($settings['registerredirection']) : '';

        //Horizontal sharing setting
        $settings['sharehorizontal'] = isset($settings['sharehorizontal']) ? trim($settings['sharehorizontal']) : '';
        $settings['choosehorizontalshare'] = isset($settings['choosehorizontalshare']) ? trim($settings['choosehorizontalshare']) : '';
        if (!isset($settings['shareontoppos']) && !isset($settings['shareonbottompos'])) {
            $settings['shareontoppos'] = '1';
        }
        $settings['shareontoppos'] = isset($settings['shareontoppos']) == '1' ? 'checked="checked"' : "";
        $settings['shareonbottompos'] = isset($settings['shareonbottompos']) == '1' ? 'checked="checked"' : "";
        $settings['horizontalrearrange'] = (!empty($settings['horizontalrearrange']) ? (@unserialize($settings['horizontalrearrange'])) : $shareProvider);
        $settings['horizontalcounter'] = (!empty($settings['horizontalcounter']) ? (@unserialize($settings['horizontalcounter'])) : $counterProvider);
        $settings['horizontalarticles'] = (isset($settings['horizontalarticles']) ? @unserialize($settings['horizontalarticles']) : "");

        //vertical sharing setting
        $settings['sharevertical'] = isset($settings['sharevertical']) ? trim($settings['sharevertical']) : '';
        $settings['chooseverticalshare'] = isset($settings['chooseverticalshare']) ? trim($settings['chooseverticalshare']) : '';
        $settings['verticalsharepos'] = isset($settings['verticalsharepos']) ? trim($settings['verticalsharepos']) : '';
        $settings['verticalrearrange'] = (!empty($settings['verticalrearrange']) ? (@unserialize($settings['verticalrearrange'])) : $shareProvider);
        $settings['verticalcounter'] = (!empty($settings['verticalcounter']) ? (@unserialize($settings['verticalcounter'])) : $counterProvider);
        $settings['verticalarticles'] = (isset($settings['verticalarticles']) ? @unserialize($settings['verticalarticles']) : "");

        //Advance setting
        $settings['iconsize'] = isset($settings['iconsize']) ? trim(htmlspecialchars($settings['iconsize'])) : '';
        $settings['iconsperrow'] = isset($settings['iconsperrow']) ? trim(htmlspecialchars($settings['iconsperrow'])) : '';
        $settings['interfacebackground'] = isset($settings['interfacebackground']) ? trim(htmlspecialchars($settings['interfacebackground'])) : '';
        $settings['showlogout'] = isset($settings['showlogout']) ? trim($settings['showlogout']) : '';
        $settings['loginform'] = isset($settings['loginform']) ? trim($settings['loginform']) : '';
        $settings['sendemail'] = isset($settings['sendemail']) ? trim($settings['sendemail']) : '';
        $settings['dummyemail'] = isset($settings['dummyemail']) ? trim($settings['dummyemail']) : '';
        $settings['popupemailtitle'] = (isset($settings['popupemailtitle']) ? htmlspecialchars($settings['popupemailtitle']) : JText::_('COM_SOCIALLOGIN_POPUP_HEAD'));
        $settings['popupemailmessage'] = (isset($settings['popupemailmessage']) ? htmlspecialchars($settings['popupemailmessage']) : JText::_('COM_SOCIALLOGIN_POPUP_MSG') . " @provider " . JText::_('COM_SOCIALLOGIN_POPUP_MSGONE') . " " . JText::_('COM_SOCIALLOGIN_POPUP_MSGTWO'));
        $settings['popuperroremailmessage'] = (isset($settings['popuperroremailmessage']) ? htmlspecialchars($settings['popuperroremailmessage']) : JText::_('COM_SOCIALLOGIN_EMAIL_INVALID'));
        $settings['updateuserdata'] = isset($settings['updateuserdata']) ? trim($settings['updateuserdata']) : '';

        $document = JFactory::getDocument();
        $version = '3';
        if (JVERSION < 3) {
            $version = '2';
        }
        $document->addStyleSheet('components/com_loginradiussocialloginandsocialshare/assets/css/loginradiussocialloginandsocialshare' . $version . '.css');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js');
        $document->addScriptDeclaration('$(function(){$("#horsortable").sortable({revert: true});});');
        $document->addScriptDeclaration('$(function(){$("#versortable").sortable({revert: true});});');
        $document->addScriptDeclaration($this->socialShareScript($settings));
        $document->addScript('components/com_loginradiussocialloginandsocialshare/assets/socialshare.js');

        return $settings;
    }

    private function socialShareScript($settings) {
        return 'var horshareChecked = '.json_encode($settings['horizontalrearrange']).';
        var vershareChecked = ' . json_encode($settings['verticalrearrange']) . ';
        var horcounterChecked = ' . json_encode($settings['horizontalcounter']) . ';
        var vercounterChecked = ' . json_encode($settings['verticalcounter']) . ';';
    }

    /**
     * get list of all joomla CMS Articals from db
     * 
     * @return mixed
     */
    private function selectArticles() {
        $db = JFactory::getDBO();
        $query = "SELECT id, title FROM #__content WHERE state = '1' ORDER BY ordering";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * create redirection section of display
     * 
     * @return mixed
     */
    private function selectRedirection($setRedirect) {
        $db = JFactory::getDBO();
        $query = "SELECT m.id, m.title,m.level,mt.menutype FROM #__menu AS m INNER JOIN #__menu_types AS mt ON mt.menutype = m.menutype WHERE mt.menutype = m.menutype AND m.published = '1' ORDER BY mt.menutype,m.level";
        $db->setQuery($query);
        $redirection = $db->loadObjectList();

        $output = '';
        foreach ($redirection as $row) {
            $output .= '<option ';
            if ($row->id == $setRedirect) {
                $output .= " selected=\"selected\"";
            }
            $output .= 'value="' . $row->id . '">';
            $output .= '<b>' . $row->menutype . '</b>';
            $output .= $this->getSection($row->level);
            $output .= $row->title . '</option>';
        }

        return $output;
    }

    /**
     * create seprator in option to be display
     * 
     * @param $level
     * @return string
     */
    public static function getSection($level) {
        $output = '';

        for ($i = 0; $i <= $level; $i++) {
            $output .= '-';
        }

        return $output;
    }

    /**
     * SocialLogin - Add admin option on toolbar
     */
    protected function addToolbar() {
        $application = JFactory::getApplication();
        $application->input->setVar('hidemainmenu', false);
        JToolBarHelper::title(JText::_('COM_SOCIALLOGINANDSOCIALSHARE'), 'configuration.gif');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save('save', 'JTOOLBAR_SAVE');
        JToolBarHelper::cancel('cancel');
    }

}
