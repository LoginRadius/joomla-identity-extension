<?php
/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
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

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query
    ->select(array('u.name'))
    ->from($db->quoteName('#__users', 'u'))   
    ->where($db->quoteName('u.id') . " = " . $db->quote(JFactory::getUser()->id));

$db->setQuery($query);
$name = $db->loadResult();

?>
<div class="profile-edit<?php echo $this->pageclass_sfx ?>">
    <?php
    if ($this->params->get('show_page_heading')) {
        ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php } ?>

    <form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&task=profile.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        <fieldset>
            <legend>Edit Your Profile</legend>
            <div class="control-group">
                <div class="control-label">
                    <label id="jform_name-lbl" for="jform_name" class="hasTooltip required" title="" data-original-title="<strong>Name</strong><br />Enter your full name." aria-invalid="false">
                        Name<span class="star">&nbsp;*</span>
                    </label>                            
                </div>
                <div class="controls">
                    <input type="text" name="jform[name]" id="jform_name" value="<?php echo $name;?>" class="required" size="30" required="required" aria-required="true" aria-invalid="false">              
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label id="jform_username-lbl" for="jform_username" class="hasTooltip required" title="" data-original-title="<strong>Username</strong><br />Enter your desired username.">
                        Username<span class="star">&nbsp;*</span>
                    </label>                           
                </div>
                <div class="controls">
                    <input type="text" name="jform[username]" id="jform_username" value="<?php echo JFactory::getUser()->username;?>" class="validate-username required" size="30" readonly="" required="required" aria-required="true">   
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label id="jform_email-lbl" for="jform_email" class="hasTooltip required" title="" data-original-title="<strong>Email Address</strong><br />Enter your email address.">
                        Email Address<span class="star">&nbsp;*</span>
                    </label>                  
                </div>
                <div class="controls">
                    <input type="email" name="jform[email]" class="validate-email required" id="jform_email" value="<?php echo JFactory::getUser()->email;?>" size="30" readonly="" required="required" aria-required="true">               
                </div>
            </div>
        </fieldset>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <a class="btn" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>">
                <?php echo JText::_('JCANCEL'); ?>
            </a>
            <input type="hidden" name="option" value="com_userregistrationandmanagement" />
            <input type="hidden" name="task" value="profile.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
