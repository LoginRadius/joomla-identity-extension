<?php

/**
 * @package     SocialLoginandSocialShare.Plugin
 * @subpackage  com_socialloginandsocialshare
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
     */
    public function __construct(&$subject) {
        parent::__construct($subject);

        // Loading plugin parameters
        $settings = $this->getSettings();

        //Properties holding plugin settings
        $this->horizontalarticaltype = (isset($settings['horizontalarticaltype']) && $settings['horizontalarticaltype'] != '1') ? $settings['horizontalarticaltype'] : "1";
        $this->verticalarticaltype = (isset($settings['verticalarticaltype']) && $settings['verticalarticaltype'] != '1') ? $settings['verticalarticaltype'] : "1";
        $this->horizontalArticles = (!empty($settings['horizontalArticles']) ? @unserialize($settings['horizontalArticles']) : "");
        $this->verticalArticles = (!empty($settings['verticalArticles']) ? @unserialize($settings['verticalArticles']) : "");
        $this->sharehorizontal = (!empty($settings['sharehorizontal']) ? $settings['sharehorizontal'] : "");
        $this->sharevertical = (!empty($settings['sharevertical']) ? $settings['sharevertical'] : "");
        $this->shareontoppos = (!empty($settings['shareontoppos']) ? $settings['shareontoppos'] : "");
        $this->shareonbottompos = (!empty($settings['shareonbottompos']) ? $settings['shareonbottompos'] : "");
        $this->choosesharepos = isset($settings['verticalsharepos']) ? $settings['verticalsharepos'] : '';
        $this->horizontalScript = isset($settings['horizontalScript']) ? json_decode($settings['horizontalScript']) : '';
        $this->verticalScript = isset($settings['verticalScript']) ? json_decode($settings['verticalScript']) : '';
        $this->loadLanguage('plg_content_socialshare');
    }
    
    
/**
     * 
     * @param type $form
     * @param type $data
     * @return boolean
     */
    public function onContentPrepareForm($form, $data) {
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        if ($app->isAdmin()) {
        switch ($option) {
            case 'com_content':                
                    $form->load('<form>
                        <fields name="attribs">
                            <fieldset name="simplifiedsocialshare" label="PLG_CONTENT_SOCIAL_SHARE_FIELDSET_LABEL">
                            <field name="share_enable" type="list" description="PLG_CONTENT_SOCIAL_SHARE_FIELD_ENABLE_DESC" translate_description="true" label="PLG_CONTENT_SOCIAL_SHARE_FIELD_ENABLE_LABEL" translate_label="true" size="7" filter="cmd">
                                <option value="">JGLOBAL_USE_GLOBAL</option>
                                <option value="0">JHIDE</option>
                            </field>
                            </fieldset>
                        </fields>
                    </form>');
                break;
            }
        }
        return true;
    }
    /**
     * social9 get saved setting from db
     * 
     * @return array
     */
    private function getSettings() {
        $db = JFactory:: getDBO();
        $sql = "SELECT * FROM #__loginradius_advanced_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();
        $settings = array();
        
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings [$data['setting']] = $data['value'];
            }
        }
        
        return $settings;
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
    public function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0) {
        $beforediv = '';
        $attribs = isset($article->attribs) && !empty($article->attribs) ? json_decode($article->attribs) : '';
        $share_enable = isset($attribs->share_enable) && $attribs->share_enable == '0' ? false : true;
        if ($share_enable && $this->shareontoppos == '1' && $this->sharehorizontal == '1') {
            if ($this->horizontalarticaltype == '1') {
                $beforediv .= $this->horizontalShareScript($article);
            } else if (is_array($this->horizontalArticles)) {
                if (isset($article->id) && in_array($article->id, $this->horizontalArticles)) {
                    $beforediv .= $this->horizontalShareScript($article);
                }
            }
        }

        if ($share_enable && $this->sharevertical == '1') {
            $document = JFactory::getDocument();
            $document->addScript(JURI::root(true) . '/plugins/content/socialshare/socialshare.min.js');
            if ($this->verticalarticaltype == '1') {
                $beforediv .= $this->verticalShareScript($article);
            } else if (is_array($this->verticalArticles)) {
                if (isset($article->id) && in_array($article->id, $this->verticalArticles)) {
                    $beforediv .= $this->verticalShareScript($article);
                }
            }
        }
        return $beforediv;
    }

    /**
     * 
     * @param type $article
     * @return type
     */
    private function verticalShareScript($article) {
        $articleSummary = '';
        if (isset($article->introtext) && !empty($article->introtext)) {
            $articleSummary = strip_tags($article->introtext);
            $articleSummary = preg_replace('/[\t]+/', '', preg_replace('/[\r\n]+/', " ", $articleSummary));
            $articleSummary = ' data-share-description="' . htmlentities($articleSummary) . '"';
        }
        $pictures = isset($article->images) ? json_decode($article->images) : '';
        $articleImage = isset($pictures->image_intro) && !empty($pictures->image_intro) ? (" data-share-imageurl='" . JURI::base() . $pictures->image_intro . "'") : '';
        return "<div align='left' style='padding-bottom:10px;padding-top:10px;'>"
                . "<div class='openSocialShareVerticalSharing' "
                . $articleSummary
                . $articleImage
                . "></div></div>";
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
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0) {
        $afterdiv = '';
        $attribs = isset($article->attribs) && !empty($article->attribs) ? json_decode($article->attribs) : '';
        $share_enable = isset($attribs->share_enable) && $attribs->share_enable == '0' ? false : true;
        if ($share_enable && $this->shareonbottompos == '1' && $this->sharehorizontal == '1') {
            if ($this->horizontalarticaltype == '1') {
                $afterdiv .= $this->horizontalShareScript($article);
            } else if (is_array($this->horizontalArticles)) {
                if (isset($article->id) && in_array($article->id, $this->horizontalArticles)) {
                    $afterdiv .= $this->horizontalShareScript($article);
                }
            }
        }

        if ($share_enable && $this->sharehorizontal == 1) {
            $afterdiv .= '<script>' . $this->horizontalScript . '</script>';
        }
        if ($share_enable && $this->sharevertical == 1) {
            $afterdiv .= '<script>' . $this->verticalScript . '</script>';
            switch ($this->choosesharepos) {
                case 1:
                    $style = 'top:0px;right:0px;';
                    break;
                case 3:
                    $style = 'bottom:0px;right:0px;';
                    break;
                case 2:
                    $style = 'bottom:0px;left:0px;';
                    break;
                default:
                    $style = 'top:0px;left:0px;';
            }

            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.openSocialShareVerticalSharing {position: fixed;' . $style . '}');
        }
        return $afterdiv;
    }

    /**
     * social9 Share Script call functionality
     * 
     * @param $article
     * @return string
     */
    private function horizontalShareScript($article) {
        $document = JFactory::getDocument();
        $document->addScript(JURI::root(true) . '/plugins/content/socialshare/socialshare.min.js');

        $pageTitle = isset($article->title) && !empty($article->title) ? (" data-share-title='" . $article->title . "'") : '';
        $articleSummary = '';
        if (isset($article->introtext) && !empty($article->introtext)) {
            $articleSummary = strip_tags($article->introtext);
            $articleSummary = preg_replace('/[\t]+/', '', preg_replace('/[\r\n]+/', " ", $articleSummary));
            $articleSummary = ' data-share-description="' . htmlentities($articleSummary) . '"';
        }

        $pictures = isset($article->images) ? json_decode($article->images) : '';
        $articleImage = isset($pictures->image_intro) && !empty($pictures->image_intro) ? (" data-share-imageurl='" . JURI::base() . $pictures->image_intro . "'") : '';
        return "<div align='left' style='padding-bottom:10px;padding-top:10px;'>"
                . "<div class='openSocialShareHorizontalSharing' "
                . "data-share-url='" .JURI::current() . "' "
                . $pageTitle
                . $articleSummary
                . $articleImage . "></div></div>";
    }

}
