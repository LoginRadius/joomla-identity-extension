<?php
/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.application.component.controller');

/**
 * Controller Class LoginRadiusSocialLoginAndSocialShareController
 */
class LoginRadiusSocialLoginAndSocialShareController extends LRController
{

    /**
     * display view pat of extension
     * 
     * @param type $cachable
     * @param type $urlparams
     */
    public function display($cachable = false, $urlparams = false)
    {
        $this->input->setVar('view', $this->input->getCmd('view', 'LoginRadiusSocialLoginAndSocialShare'));
        parent::display($cachable);
    }

    /**
     * Save settings on click on save button from ui
     */
    public function apply()
    {
        $mainframe = JFactory::getApplication();
        $model = $this->getModel();
        $result = $model->saveSettings();
        $mainframe->enqueueMessage($result['message'], $result['status']);
        $this->setRedirect(JRoute::_('index.php?option=com_loginradiussocialloginandsocialshare&view=loginradiussocialloginandsocialshare&layout=default', false));
    }

    /**
     * Save and close settings  on click on save and close button from ui
     */
    public function save()
    {
        $mainframe = JFactory::getApplication();
        $model = &$this->getModel();
        $result = $model->saveSettings();
        $mainframe->enqueueMessage($result['message'], $result['status']);
        $this->setRedirect(JRoute::_('index.php', false));
    }

    /**
     * cancel settings  on click on cancel button from ui
     */
    public function cancel()
    {
        $this->setRedirect(JRoute::_('index.php', false));
    }

}
