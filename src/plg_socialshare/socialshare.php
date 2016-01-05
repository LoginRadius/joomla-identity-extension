<?php

/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
if (!class_exists('ContentHelperRoute'))
    require_once(JPATH_SITE . '/components/com_content/helpers/route.php');

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * Class plgContentSocialShare
 */
class plgContentSocialShare extends JPlugin
{

    /**
     * Constructor Loads the plugin settings and assigns them to class variables
     * 
     * @param type $subject
     */
    public function __construct(&$subject)
    {
        parent::__construct($subject);

        $document = JFactory::getDocument();
        // Loading plugin parameters
        $settings = $this->getSettings();
        //Properties holding plugin settings
        $this->horizontalarticles = (!empty($settings['horizontalarticles']) ? @unserialize($settings['horizontalarticles']) : "");
        $this->verticalarticles = (!empty($settings['verticalarticles']) ? @unserialize($settings['verticalarticles']) : "");
        $this->sharehorizontal = (!empty($settings['sharehorizontal']) ? $settings['sharehorizontal'] : "");
        $this->sharevertical = (!empty($settings['sharevertical']) ? $settings['sharevertical'] : "");
        $this->shareontoppos = (!empty($settings['shareontoppos']) ? $settings['shareontoppos'] : "");
        $this->shareonbottompos = (!empty($settings['shareonbottompos']) ? $settings['shareonbottompos'] : "");

        if ($this->sharehorizontal == 1)
        {
            $document->addScriptDeclaration(json_decode($settings['horizontalscript']));
        }
        if ($this->sharevertical == 1)
        {
            $document->addScriptDeclaration(json_decode($settings['verticalscript']));
        }
    }

    /**
     * Before display content method
     * 
     * @param $context
     * @param $article
     * @param $params
     * @param int $limitstart
     * @return string
     */
    public function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0)
    {
        $before = '';

        if ($this->shareontoppos == '1' && $this->sharehorizontal == '1')
        {
            if (is_array($this->horizontalarticles) && isset($article->id))
            {
                foreach ($this->horizontalarticles as $key => $value)
                {
                    if ($article->id == $value)
                    {
                        $before .= $this->shareScript($article);
                    }
                }
            }
        }

        if ($this->sharevertical == '1')
        {
            if (is_array($this->verticalarticles) && isset($article->id))
            {
                foreach ($this->verticalarticles as $key => $value)
                {
                    if ($article->id == $value)
                    {
                        $document = JFactory::getDocument();
                        $document->addScript(JURI::root(true) . '/plugins/content/socialshare/socialshare.js');
                        $document->addScript('//cdn.loginradius.com/share/v1/LoginRadius.js');
                        $before .= "<div align='left' style='padding-bottom:10px;padding-top:10px;'><div class='lrverticalsharecontainer'></div></div>";
                    }
                }
            }
        }

        return $before;
    }

    /**
     * After display content method
     * 
     * @param $context
     * @param $article
     * @param $params
     * @param int $limitstart
     * @return string
     */
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0)
    {
        $after = '';

        if ($this->shareonbottompos == '1' && $this->sharehorizontal == '1')
        {
            if (is_array($this->horizontalarticles) && isset($article->id))
            {
                foreach ($this->horizontalarticles as $key => $value)
                {
                    if ($article->id == $value)
                    {
                        $after .= $this->shareScript($article);
                    }
                }
            }
        }

        return $after;
    }

    /**
     * LoginRadius Share Script call functionality
     * 
     * @param $article
     * @return string
     * 
     */
    private function shareScript($article)
    {

        $document = JFactory::getDocument();

        if (!isset($article->language) && empty($article->language))
        {
            $article->language = 0;
        }
        if (!isset($article->catid) && empty($article->catid))
        {
            $article->catid = 0;
        }

        $document->addScript(JURI::root(true) . '/plugins/content/socialshare/socialshare.js');
        $document->addScript('//cdn.loginradius.com/share/v1/LoginRadius.js');
        $articleLink = urlencode(JURI::root() . ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language));
        $content = "<div align='left' style='padding-bottom:10px;padding-top:10px;'><div class='lrsharecontainer' data-share-url='" . $articleLink . "'></div></div>";

        return $content;
    }

    /**
     * LoginRadius Extension get saved setting from db
     * 
     * @return array
     */
    private function getSettings()
    {
        $settings = array();

        $db = JFactory::getDBO();

        $sql = "SELECT * FROM #__loginradius_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();

        if (is_array($rows))
        {
            foreach ($rows AS $key => $data)
            {
                $settings [$data['setting']] = $data ['value'];
            }
        }

        return $settings;
    }

}
