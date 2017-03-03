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
class UserRegistrationAndManagementViewUserProfile extends JViewLegacy {

    /**
     * SocialLogin - Display administration area
     * 
     * @param type $tpl
     */
    public function display($tpl = null) {
        $document = JFactory::getDocument();
        $version = '3';
        if (JVERSION < 3) {
            $version = '2';
        }
        $document->addStyleSheet('components/com_userregistrationandmanagement/assets/css/userprofile' . $version . '.min.css');
        
        $model = $this->getModel();
        $userid = JRequest::getVar('userid');
        $this->getbasicuserprofile = $model->getUserProfile('basic_profile_data', $userid);
        $this->getbasicuseremails = $model->getUserProfile('emails', $userid);
        $this->getextendedprofile = $model->getUserProfile('extended_profile_data', $userid);
        $this->getextendedlocation = $model->getUserProfile('extended_location_data', $userid);
        $this->getlinkedincompanies = $model->getUserProfile('linkedin_companies', $userid);
        $this->getfacebookevents = $model->getUserProfile('facebook_events', $userid);
        $this->getstatus = $model->getUserProfile('status', $userid);
        $this->getfacebookposts = $model->getUserProfile('facebook_posts', $userid);
        $this->getfacebooklikes = $model->getUserProfile('facebook_likes', $userid);
        $this->gettwittermentions = $model->getUserProfile('twitter_mentions', $userid);
        $this->getsocialgroups = $model->getUserProfile('groups', $userid);
        $this->getcontacts = $model->getUserProfile('contacts', $userid);
        $this->getpositions = $model->getUserProfile('positions', $userid);
        $this->geteducation = $model->getUserProfile('education', $userid);
        $this->getphonenumbers = $model->getUserProfile('phone_numbers', $userid);
        $this->getIMaccounts = $model->getUserProfile('imaccounts', $userid);
        $this->getaddresses = $model->getUserProfile('addresses', $userid);
        $this->getsports = $model->getUserProfile('sports', $userid);
        $this->getinspirationalpeople = $model->getUserProfile('inspirational_people', $userid);
        $this->getskills = $model->getUserProfile('skills', $userid);
        $this->getcurrentstatus = $model->getUserProfile('current_status', $userid);
        $this->getcertifications = $model->getUserProfile('certifications', $userid);
        $this->getcourses = $model->getUserProfile('courses', $userid);
        $this->getvolunteer = $model->getUserProfile('volunteer', $userid);
        $this->getrecommendationsreceived = $model->getUserProfile('recommendations_received', $userid);
        $this->getlanguages = $model->getUserProfile('languages', $userid);
        $this->getpatents = $model->getUserProfile('patents', $userid);
        $this->getfavorites = $model->getUserProfile('favorites', $userid);
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        $this->form = $this->get('Form');
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JToolbarHelper::title(JText::_('COM_SOCIALLOGIN_USER_PROFILE'), 'user');
        JToolbarHelper::back();
    }

}
