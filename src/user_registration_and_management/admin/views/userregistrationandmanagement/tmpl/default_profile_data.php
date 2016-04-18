<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$LoginRadius_profileData0 = '';
$LoginRadius_profileData1 = '';
$LoginRadius_profileData2 = '';
$LoginRadius_profileData3 = '';
$LoginRadius_profileData4 = '';
$LoginRadius_profileData5 = '';
$LoginRadius_profileData6 = '';
$LoginRadius_profileData7 = '';
$LoginRadius_profileData8 = '';
$LoginRadius_profileData9 = '';
$LoginRadius_profileData10 = '';
$lrbasic = (isset($this->settings['basic']) ? $this->settings['basic'] : "");
if ($lrbasic == '1')
{
    $LoginRadius_profileData0 = "checked='checked'";
}
$lrexlocation = (isset($this->settings['exlocation']) ? $this->settings['exlocation'] : "");
if ($lrexlocation == '1')
{
    $LoginRadius_profileData1 = "checked='checked'";
}
$lrexprofile = (isset($this->settings['exprofile']) ? $this->settings['exprofile'] : "");
if ($lrexprofile == '1')
{
    $LoginRadius_profileData2 = "checked='checked'";
}
$lrfollowcompanies = (isset($this->settings['followcompanies']) ? $this->settings['followcompanies'] : "");
if ($lrfollowcompanies == '1')
{
    $LoginRadius_profileData3 = "checked='checked'";
}
$lrfbprofile = (isset($this->settings['fbprofile']) ? $this->settings['fbprofile'] : "");
if ($lrfbprofile == '1')
{
    $LoginRadius_profileData4 = "checked='checked'";
}
$lrstatusmessage = (isset($this->settings['statusmessage']) ? $this->settings['statusmessage'] : "");
if ($lrstatusmessage == '1')
{
    $LoginRadius_profileData5 = "checked='checked'";
}
$lrfbpost = (isset($this->settings['fbpost']) ? $this->settings['fbpost'] : "");
if ($lrfbpost == '1')
{
    $LoginRadius_profileData6 = "checked='checked'";
}
$lrtwittermentions = (isset($this->settings['twittermentions']) ? $this->settings['twittermentions'] : "");
if ($lrtwittermentions == '1')
{
    $LoginRadius_profileData7 = "checked='checked'";
}
$lrgroups = (isset($this->settings['groups']) ? $this->settings['groups'] : "");
if ($lrgroups == '1')
{
    $LoginRadius_profileData8 = "checked='checked'";
}
$lrsocialcontacts = (isset($this->settings['socialcontacts']) ? $this->settings['socialcontacts'] : "");
if ($lrsocialcontacts == '1')
{
    $LoginRadius_profileData9 = "checked='checked'";
}
$lrfblike = (isset($this->settings['fblike']) ? $this->settings['fblike'] : "");
if ($lrfblike == '1')
{
    $LoginRadius_profileData10 = "checked='checked'";
}
?>
<div>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_USERDATA_SELECT_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2" ><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_USERDATA_SELECT_TITLE'); ?></span>
                <br/><br />
                <label for="basic">
                    <input id="basic" style="margin:0" type="checkbox" name="settings[basic]" <?php echo $LoginRadius_profileData0; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_BASIC_USERPROFILE_DATA'); ?> 
                </label>
                <label for="exlocation">
                    <input id="exlocation" style="margin:0" type="checkbox" name="settings[exlocation]" <?php echo $LoginRadius_profileData1; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_LOCATION_DATA'); ?>
                </label>
                <label for="exprofile">
                    <input id="exprofile"  style="margin:0" type="checkbox" name="settings[exprofile]" <?php echo $LoginRadius_profileData2; ?> value='1' />  <?php echo JText::_('COM_SOCIALLOGIN_EXTENDED_PROFILE_DATA'); ?> 
                </label>
                <label for="followcompanies">
                    <input id="followcompanies"  style="margin:0" type="checkbox" name="settings[followcompanies]" <?php echo $LoginRadius_profileData3; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_FOLLOWED_COMPANIED_ONLINKEDIN_DATA'); ?>
                </label>
                <label for="fbprofile">
                    <input id="fbprofile"  style="margin:0" type="checkbox" name="settings[fbprofile]" <?php echo $LoginRadius_profileData4; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_PROFILE_EVENT_DATA'); ?>
                </label>
                <label for="statusmessage">
                    <input id="statusmessage"  style="margin:0" type="checkbox" name="settings[statusmessage]" <?php echo $LoginRadius_profileData5; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_STATUS_MESSAGES_DATA'); ?>
                </label>
                <label for="fbpost">
                    <input id="fbpost" style="margin:0" type="checkbox" name="settings[fbpost]" <?php echo $LoginRadius_profileData6; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_POST_DATA'); ?>
                </label>
                <label for="twittermentions">
                    <input id="twittermentions"  style="margin:0" type="checkbox" name="settings[twittermentions]" <?php echo $LoginRadius_profileData7; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_TWITTER_MENTIONS_DATA'); ?>
                </label>
                <label for="groups">
                    <input id="groups"  style="margin:0" type="checkbox" name="settings[groups]" <?php echo $LoginRadius_profileData8; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_GROUPS_DATA'); ?>
                </label>
                <label for="socialcontacts">
                    <input id="socialcontacts"  style="margin:0" type="checkbox" name="settings[socialcontacts]" <?php echo $LoginRadius_profileData9; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_CONTACTS_DATA'); ?>
                </label>
                <label for="fblike">
                    <input id="fblike" style="margin:0" type="checkbox" name="settings[fblike]" <?php echo $LoginRadius_profileData10; ?> value='1' /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_LIKES_DATA'); ?>
                </label>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo JText::_('COM_SOCIALLOGIN_LIST_OFALL_FILEDS'); ?>
                <a href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=usermanager'); ?>" target="_blank">User Manager</a>
            </td>
        </tr>
    </table>
</div>