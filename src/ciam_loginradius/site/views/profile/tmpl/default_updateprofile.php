<?php

/**
 * @package     CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.noframes');
//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);

use \LoginRadiusSDK\Utility\Functions;
use \LoginRadiusSDK\LoginRadiusException;
use \LoginRadiusSDK\Clients\IHttpClient;
use \LoginRadiusSDK\Clients\DefaultHttpClient;
use \LoginRadiusSDK\Utility\SOTT;
use \LoginRadiusSDK\CustomerRegistration\Authentication\UserAPI;
if (JFactory::getUser()->id) {
$settings = CiamLoginRadiusHelperRoute::getSettings();
$apiKey = $settings['apikey'];
$apiSecret = $settings['apisecret'];
$userObject = new UserAPI($apiKey, $apiSecret, array('output_format' => 'json'));
try {
    $userprofile = $userObject->getProfile($_SESSION['result_accesstoken']);
}
catch (LoginRadiusException $e) {
    
}
 if (JVERSION < 3) {
$db = JFactory::getDbo();
$query = $db->getQuery(true)
    ->select($db->quoteName('email'))
    ->from($db->quoteName('#__users'))
    ->where($db->quoteName('id') . " = " . JFactory::getUser()->id);
$db->setQuery($query);
$db->execute();
$emailval = $db->loadResult();
}else {
    $emailval = JFactory::getUser()->email;
}
if (isset($userprofile->Email) && $emailval != $userprofile->Email[0]->Value) {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $fields = array(
      $db->quoteName('email') . ' = ' . $db->quote($userprofile->Email[0]->Value)
    );
    $conditions = array(
      $db->quoteName('email') . ' = ' . $db->quote($emailval)
    );

    $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
    $db->setQuery($query);
    $db->execute();
}
echo $this->loadTemplate('remove_email');
echo $this->loadTemplate('add_email');
?>
<div class="profile-edit<?php echo $this->pageclass_sfx ?>">
    <?php
    if ($this->params->get('show_page_heading')) {
        ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php } ?>

    <form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_ciamloginradius&task=profile.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        <fieldset>
            <legend>Edit Your Profile</legend>
            <?php echo $this->loadTemplate('profile_editor')?>
        </fieldset>
        <fieldset>
            <legend>Email Settings</legend>
            <table class="form-table sociallogin_table">   
                <div class="addEmail btn btn-primary" onclick="showAddEmailPopup()"><i class="icon-plus"></i> <?php echo JText::_('Add Email'); ?></div>    
                <thead>
                    <tr>   
                        <td class="head"><?php echo JText::_('COM_CIAM_EMAIL_LIST'); ?></td>              
                        <td class="head"><?php echo JText::_('COM_CIAM_EMAIL_ACTION'); ?></td>      
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    $emailCount = isset($userprofile->Email) ? count($userprofile->Email) : '0';
                    for ($i = 0; $i < $emailCount; $i++) {
                        ?>
                        <tr id="emaillist_<?php echo $i ?>">   
                            <td scope="col" class="manage-colum">
                                <input type="email" name="jform[emailaddress]" class="validate-email required" id="jformemail_<?php echo $i ?>" value="<?php echo $userprofile->Email[$i]->Value; ?>" size="30" readonly="true" required="required" aria-required="true">
                            </td>                     
                            <td><div id="removeEmail_<?php echo $i ?>" onclick="showRemoveEmailPopup('<?php echo $i ?>')" class="removeEmail btn btn-primary"><?php echo JText::_('Remove');?></div></td>    
                        </tr> 
                    <?php } ?>
                </tbody>       
            </table>
        </fieldset>        
    </form>
</div>
<?php }?>