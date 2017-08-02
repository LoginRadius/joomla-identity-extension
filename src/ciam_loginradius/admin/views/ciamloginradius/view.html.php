<?php
/**
 * @package     CiamLoginRadius.Administrator
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * Class CiamLoginRadiusViewCiamLoginRadius
 */
class CiamLoginRadiusViewCiamLoginRadius extends JViewLegacy
{

    public $settings;

    /**
     * @param null $tpl
     * @return mixed|void
     */
    public function display($tpl = null)
    {
        $model = $this->getModel();
        $this->settings = $this->initialSetting($model);   
        $this->loginRedirection = $this->selectRedirection($this->settings['loginredirection']);
                       
        $document = JFactory::getDocument();
        $version = '3';
        if (JVERSION < 3) {
            $version = '2';
        }
        $document->addStyleSheet('components/com_ciamloginradius/assets/css/ciambackend' . $version . '.min.css');
        $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
        $document->addScript('components/com_ciamloginradius/assets/js/ciamloginradius.min.js');
        $this->form = $this->get('Form');
        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * @param $model
     * @return mixed
     */
    private function initialSetting($model)
    {
        $settings = $model->getSettings();

        //Basic setting
        $settings['apikey'] = isset($settings['apikey']) ? trim(htmlspecialchars($settings['apikey'])) : '';
        $settings['apisecret'] = isset($settings['apisecret']) ? trim(htmlspecialchars($settings['apisecret'])) : '';
        $settings['loginredirection'] = isset($settings['loginredirection']) ? trim($settings['loginredirection']) : '';
       
        //Advance setting
        $settings['interface'] = isset($settings['interface']) ? trim($settings['interface']) : '0';
        $settings['popupemailtitle'] = (isset($settings['popupemailtitle']) ? htmlspecialchars($settings['popupemailtitle']) : JText::_('COM_CIAM_POPUP_HEAD'));
        return $settings;
    }
    /**
     * @return mixed
     */
    private function selectRedirection($setRedirect)
    {
        $db = JFactory::getDBO();
        $query = "SELECT m.id, m.title,m.level,mt.menutype FROM #__menu AS m INNER JOIN #__menu_types AS mt ON mt.menutype = m.menutype WHERE mt.menutype = m.menutype AND m.published = '1' ORDER BY mt.menutype,m.level";
        $db->setQuery($query);
        $redirection = $db->loadObjectList();

        $output = '';
        foreach ($redirection as $row)
        {
            $output .= '<option ';
            if ($row->id == $setRedirect)
            {
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
     * @param $level
     * @return string
     */
    public static function getSection($level)
    {
        $output = '';

        for ($i = 0; $i <= $level; $i++)
        {
            $output .= '-';
        }

        return $output;
    }

    /**
     * SocialLogin - Add admin option on toolbar
     */
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', false);
        JToolBarHelper::title(JText::_('COM_CIAM_LOGINRADIUS'), 'configuration.gif');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save('save', 'JTOOLBAR_SAVE');
        JToolBarHelper::cancel('cancel');
    }

}
