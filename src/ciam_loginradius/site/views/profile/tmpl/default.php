<?php

//echo '<pre>';
//print_r($_SERVER);
//echo '<pre>';
//print_r($_REQUEST);
//echo '<pre>';
//print_r($_POST);
//echo '<pre>';
//print_r($_GET);
//die('ankitsfgf');
/**
 * @package      CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$showPassword = CiamLoginRadiusHelperRoute::change_password_custom_access();
?>
<div class="profile <?php echo $this->pageclass_sfx ?>">
    <?php
    if (JFactory::getUser()->id == $this->data->id) {
        global $lr_message;
        $lrmessage = JRequest::getVar('lr-message');
        if ($lr_message !== false && isset($lrmessage) && $lrmessage == 'linkingsuccess') {
            $lr_message = false;
            $mainframe = JFactory::getApplication();
            $mainframe->enqueueMessage(JText::_('COM_CIAM_ADD_ID'), 'message');
            $mainframe->redirect(JRoute::_('index.php?option=com_ciamloginradius&view=profile'));
            exit();
        }
        ?>
        <ul class="btn-toolbar pull-right"> 
            <?php if ($showPassword) { ?>
                <li class="btn-group">
                    <a class="btn" href="<?php echo JRoute::_('index.php?option=com_ciamloginradius&view=changepassword'); ?>">
                        <i class="icon-user"></i> <?php echo JText::_('COM_USERS_PASSWORD'); ?></a>
                </li>
            <?php } ?>
            <li class="btn-group">
                <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_ciamloginradius&view=logout'); ?>">
                    <i class="icon-exit"></i> <?php echo JText::_('JLOGOUT'); ?></a>
            </li> 
        </ul>
        <div style="clear:both;"></div>
        <?php
    }
    if ($this->params->get('show_page_heading')) {
        ?>
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
        <?php
    }

    echo $this->loadTemplate('mapping');
    echo $this->loadTemplate('core');
    echo $this->loadTemplate('params');
    echo $this->loadTemplate('custom');
    ?>
</div>
