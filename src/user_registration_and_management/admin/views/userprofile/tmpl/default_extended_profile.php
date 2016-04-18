<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
//extended user profile
foreach ($this->getextendedprofile as $key => $getextendedprofile):
    if (count($getextendedprofile) > 0):
        $this->pagenotfound = false;
        echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_EXTENDED_PROFILE_DATA'), 'panel3');
        UserRegistrationAndManagementModelUserProfile::displayProfile($getextendedprofile);
    endif;
    if (count($this->getpositions) > 0):?>
<h2><?php  echo JText::_('COM_SOCIALLOGIN_EXTENDED_POSITIONS_DATA');?></h2>
                <?php
        $count = 1;
        ?>
        <table class="form-table sociallogin_table" cellspacing="0">
            <thead>
                <tr>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_SUMMARY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_STARTDATE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_ENDDATE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_CURRENT'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_COMPANY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_COMPANY_TYPE'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_INDUSTRY'); ?></th>
                    <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_POSITIONS_LOCATION'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <?php
                foreach ($this->getpositions as $position) {
                    ?>
                    <tr <?php
                    if (($count % 2) == 0) {
                        echo 'style="background-color:#fcfcfc"';
                    }
                    ?>>
                            <?php
                            foreach ($position as $key => $val) {
                                if ($key == 'user_id') {
                                    continue;
                                } elseif ($key == 'company') {
                                    if ($val == "NULL" || $val == "") {
                                        ?>
                                    <th scope="col" class="manage-colum"></th>
                                    <th scope="col" class="manage-colum"></th>
                                    <th scope="col" class="manage-colum"></th>
                                    <?php
                                } else {
                                    // companies
                                    $companies = UserRegistrationAndManagementModelUserProfile::getCompanies($val);
                                    if (count($companies) > 0) {
                                        foreach ($companies[0] as $k => $v) {
                                            if ($k == 'id') {
                                                continue;
                                            }
                                            ?>
                                            <th scope="col" class="manage-colum"><?php echo ucfirst($v) ?></th>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <th scope="col" class="manage-colum"></th>
                                        <th scope="col" class="manage-colum"></th>
                                        <th scope="col" class="manage-colum"></th>
                                        <?php
                                    }
                                }
                                continue;
                            } else {
                                ?>
                                <th scope="col" class="manage-colum"><?php echo ucfirst($val) ?></th>
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
        </table>
        <?php
    endif;
    break;
endforeach;
if (count($this->geteducation) > 0):?>
            <h2><?php 
            echo JText::_('COM_SOCIALLOGIN_EXTENDED_EDUCATION_DATA');?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_SCHOOL'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_YEAR'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_TYPE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_NOTES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_ACTIVIES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_DEGREE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_FOS'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_STARTDATE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_EDUCATION_ENDDATE'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->geteducation, true); ?>
    </table><?php
endif;
//display phone number
if (count($this->getphonenumbers) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_PHONENUMBER_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_PHONENUMBER_TYPE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_PHONENUMBER_VALUE'); ?></th>
            </tr>
        </thead>
        <?php
        UserRegistrationAndManagementModelUserProfile::displayProfile($this->getphonenumbers, true);
        ?>
    </table><?php
endif;
//display bank account info
if (count($this->getIMaccounts) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_ACCOUNT_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ACCOUNT_TYPE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ACCOUNT_USERNAME'); ?></th>
            </tr>
        </thead>
        <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getIMaccounts, true); ?>
    </table>
    <?php
endif;

//display address
if (count($this->getaddresses) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_ADDRESS_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_TYPE'); ?></th>                                     
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_LINE1'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_LINE2'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_CITY'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_STATE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_POSTALCODE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_ADDRESS_REGION'); ?></th>
            </tr>
        </thead><?php
    UserRegistrationAndManagementModelUserProfile::displayProfile($this->getaddresses, true);
    ?></table><?php
    endif;

//display sports data
    if (count($this->getsports) > 0):
        ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_SPORTS_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SPORT_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SPORT'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getsports, true) ?>
    </table>
        <?php
    endif;

//display social people
    if (count($this->getinspirationalpeople) > 0):
        ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_PEOPLE_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_NAME'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getinspirationalpeople, true) ?>
    </table>
        <?php
    endif;

//Display social skills
    if (count($this->getskills) > 0):
        ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_SKILLS_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SKILL_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_SKILL'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getskills, true) ?>
    </table>
        <?php
    endif;

//Dispaly current status
    if (count($this->getcurrentstatus) > 0):
        ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_STATUS_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_SOURCE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_STATUS_CREATED_DATE'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getcurrentstatus, true); ?>
    </table>
    <?php
endif;

//Display Certificate
if (count($this->getcertifications) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_CERTIFICATE_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_NAME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_AUTHORITY'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_LICENSE_NUMBER'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_START_DATE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_CERTIFICATE_END_DATE'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getcertifications, true); ?>
    </table>
    <?php
endif;

//Dispaly Cources
if (count($this->getcourses) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_COURSES_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_COURSES_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_COURSES'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_COURSES_NUMBERS'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getcourses, true) ?>
    </table><?php
endif;

//Display volunteer
if (count($this->getvolunteer) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_VOLUNTEER_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_VOLUNTEER_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_VOLUNTEER_ROLE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_VOLUNTEER_ORGANIZATION'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_VOLUNTEER_CAUSE'); ?></th>													 
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getvolunteer, true); ?>
    </table>
    <?php
endif;

//Display RECOMMENDATION
if (count($this->getrecommendationsreceived) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_RECOMMENDATION_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_RECOMMENDATION_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_RECOMMENDATION_TYPE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_RECOMMENDATION_TEXT'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_RECOMMENDATION'); ?></th>													 
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getrecommendationsreceived, true); ?>
    </table>
    <?php
endif;
//Display Language
if (count($this->getlanguages) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_LANGUAGE_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LANGUAGE_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_LANGUAGE'); ?></th>
            </tr>
        </thead>
    <?php
    UserRegistrationAndManagementModelUserProfile::displayProfile($this->getlanguages, true);
    ?></table><?php
endif;

//Display patents
if (count($this->getpatents) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_PATENTS_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_PATENTS_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_PATENTS_TITLE'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_PATENTS_DATE'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getpatents, true); ?>
    </table>
    <?php
endif;

if (count($this->getfavorites) > 0):
    ?>
            <h2><?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_FAVORITES_DATA');
            ?></h2>
    <table class="form-table sociallogin_table" cellspacing="0">
        <thead>
            <tr>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_FAVORITES_ID'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_FAVORITES_NAME'); ?></th>
                <th class="head"><?php echo JText::_('COM_SOCIALLOGIN_FAVORITES_TYPE'); ?></th>
            </tr>
        </thead>
    <?php UserRegistrationAndManagementModelUserProfile::displayProfile($this->getfavorites, true); ?>
    </table>
    <?php
endif;