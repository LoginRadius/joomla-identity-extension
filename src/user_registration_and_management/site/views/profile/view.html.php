<?php

/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Profile view class for Users.
 *
 * @package        Joomla.Site
 * @subpackage    com_users
 * @since        1.6
 */
class UserRegistrationAndManagementViewProfile extends JViewLegacy {

    protected $data;
    protected $form;
    protected $params;
    protected $state;

    /**
     * Method to display the view.
     *
     * @param    string $tpl The template file to include
     * @since    1.6
     */
    public function display($tpl = null) {
        // Get the view data.
        $this->data = $this->get('Data');
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->params = $this->state->get('params');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Check if a user was found.
        $currenturl = JUri::current();
        preg_match("/[^\/]+$/", $currenturl, $matches);
        $pagename = $matches[0];
        if ($pagename == 'profile') {
            if (!$this->data->id) {
                JError::raiseError(404, JText::_('JERROR_USERS_PROFILE_NOT_FOUND'));
                return false;
            }
        }

        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();
        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }

        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

        $this->prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     *
     * @since    1.6
     */
    protected function prepareDocument() {
        $app = JFactory::getApplication();
        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_userregistrationandmanagement/assets/css/profile.css');
        $settings = $this->getSettings();
        $this->getInterface($settings);
        $menus = $app->getMenu();
        $user = JFactory::getUser();
        $login = $user->get('guest') ? true : false;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $user->name));
        } else {
            $this->params->def('page_heading', JText::_('COM_USERS_PROFILE'));
        }

        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
    
  
    /**
     * 
     * @param type $settings
     * @return type
     */
    public function getInterface($settings) {
        if (isset($settings['apikey']) && !empty($settings['apikey'])) {
            $document = JFactory::getDocument();
            $script = '';                

      if (isset($settings['LoginRadius_termsAndCondition']) && $settings['LoginRadius_termsAndCondition'] != '') {              
                 $termsCondition =  preg_replace('/\n+/', '', $settings['LoginRadius_termsAndCondition']);     
                 $termsCondition =  preg_replace('/\r+/', '', $termsCondition);   
                $script .= 'raasoption.termsAndConditionHtml = "' . str_replace(array('<script>','</script>'), '', $termsCondition) . '";';
            }  
            if (isset($settings['LoginRadius_formRenderDelay']) && is_numeric($settings['LoginRadius_formRenderDelay']) != '0') {
                $script .= 'raasoption.formRenderDelay =  ' . $settings['LoginRadius_formRenderDelay'] . ';';
            }

            $min_length = isset($settings['LoginRadius_passwordMinLength']) ? $settings['LoginRadius_passwordMinLength'] : '';
            $max_length = isset($settings['LoginRadius_passwordMaxLength']) ? $settings['LoginRadius_passwordMaxLength'] : '';
            if (!empty($min_length) && !empty($max_length)) {
                $password_length = '{min:' . $min_length . ',max:' . $max_length . '}';
                $script .= 'raasoption.passwordlength = ' . $password_length . ';';
            }
            if (isset($settings['LoginRadius_GoogleRecapthaPublicKey']) && $settings['LoginRadius_GoogleRecapthaPublicKey'] != '') {
                $script .= 'raasoption.V2RecaptchaSiteKey = "' . $settings['LoginRadius_GoogleRecapthaPublicKey'] . '";';
            }
            if (isset($settings['LoginRadius_enableFormValidationMsg']) && $settings['LoginRadius_enableFormValidationMsg'] != '' && $settings['LoginRadius_enableFormValidationMsg'] != 'false') {
                $script .= 'raasoption.inFormvalidationMessage = ' . $settings['LoginRadius_enableFormValidationMsg'] . ';';
            }
            if (isset($settings['LoginRadius_forgotEmailTemplate']) && $settings['LoginRadius_forgotEmailTemplate'] != '') {
                $script .= 'raasoption.forgotPasswordTemplate = "' . $settings['LoginRadius_forgotEmailTemplate'] . '";';
            }

            $emailVerifyOpt = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';
            if (isset($emailVerifyOpt) && $emailVerifyOpt != '') {
                if ($emailVerifyOpt == '0') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'raasoption.enableLoginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_enablePromptPassword'] != '' && $settings['LoginRadius_enablePromptPassword'] != 'false') {
                        $script .= 'raasoption.promptPasswordOnSocialLogin = ' . $settings['LoginRadius_enablePromptPassword'] . ';';
                    } if ($settings['LoginRadius_enableLoginWithUsername'] != '' && $settings['LoginRadius_enableLoginWithUsername'] != 'false') {
                        $script .= 'raasoption.enableUserName = ' . $settings['LoginRadius_enableLoginWithUsername'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'raasoption.askEmailAlwaysForUnverified = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                } elseif ($emailVerifyOpt == '1') {
                    if ($settings['LoginRadius_enableLoginOnEmailVerification'] != '' && $settings['LoginRadius_enableLoginOnEmailVerification'] != 'false') {
                        $script .= 'raasoption.enableLoginOnEmailVerification = ' . $settings['LoginRadius_enableLoginOnEmailVerification'] . ';';
                    } if ($settings['LoginRadius_askEmailForUnverified'] != '' && $settings['LoginRadius_askEmailForUnverified'] != 'false') {
                        $script .= 'raasoption.askEmailAlwaysForUnverified = ' . $settings['LoginRadius_askEmailForUnverified'] . ';';
                    }
                    $script .= 'raasoption.OptionalEmailVerification = true;';
                } elseif ($emailVerifyOpt == '2') {
                    $script .= 'raasoption.DisabledEmailVerification = true;';
                }
            }

            if (isset($settings['LoginRadius_emailVerificationTemplate']) && $settings['LoginRadius_emailVerificationTemplate'] != '') {
                $script .= 'raasoption.emailVerificationTemplate = "' . $settings['LoginRadius_emailVerificationTemplate'] . '";';
            }

            if (isset($settings['LoginRadius_customOption']) && $settings['LoginRadius_customOption'] != '') {
                $jsondata = self::lr_raas_json_validate($settings['LoginRadius_customOption']);
                if (is_object($jsondata)) {
                    foreach ($jsondata as $key => $value) {
                        $script .= "raasoption." . $key . "=";
                        if (is_object($value) || is_array($value)) {
                            $encodedStr = json_encode($value);
                            $script.= $encodedStr . ';';
                        } else {
                            $script .= $value . ';';
                        }
                    }
                } else {
                    if (is_string($jsondata)) {
                        $script .= $jsondata;
                    }
                }
            }
            $path = parse_url(JURI::base());
            $basepath = $path['path'];    
       
            $document->addScript($basepath .'media/jui/js/jquery.js');              
            $document->addScript($basepath .'media/jui/js/jquery-noconflict.js');    
            $document->addScript($basepath .'media/jui/js/jquery.min.js');  
  
            $document->addScript('//hub.loginradius.com/include/js/LoginRadius.js');
            $document->addScript('//cdn.loginradius.com/hub/prod/js/LoginRadiusRaaS.js');
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.ui.core.min.js');
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/jquery.ui.datepicker.min.js');
            
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.core.css');
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.theme.css');
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/jquery.ui.datepicker.css');      
            $document->addScript(JURI::root() . 'components/com_userregistrationandmanagement/assets/js/LoginRadiusFrontEnd.js');
            $document->addStyleSheet(JURI::root() . 'components/com_userregistrationandmanagement/assets/css/lrcss.css');
            $path = parse_url(JURI::base());
            $domain = $path['scheme'] . '://' . $path['host'];
          
            $loginFunction = 'var raasoption = {};
                var homeDomain = "' . JURI::base() . '";            
                raasoption.appName = "' . $settings['sitename'] . '";
                raasoption.apikey = "' . $settings['apikey'] . '";
                raasoption.V2Recaptcha = true;
                raasoption.emailVerificationUrl = "' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=login') . '";
                raasoption.forgotPasswordUrl = "' . $domain . JRoute::_('index.php?option=com_userregistrationandmanagement&view=login') . '";
                raasoption.templatename = "loginradiuscustom_tmpl";
                raasoption.hashTemplate = true; 
                ' . $script . '
                jQuery(document).ready(function () {
                initializeResetPasswordRaasForm(raasoption); 
                });';
            $document->addScriptDeclaration($loginFunction);
        }
    }

    public function getSettings() {
        $settings = array();
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__loginradius_settings";
        $db->setQuery($sql);
        $rows = $db->LoadAssocList();
        if (is_array($rows)) {
            foreach ($rows AS $key => $data) {
                $settings[$data['setting']] = $data['value'];
            }
        }
        return $settings;
    }

}
