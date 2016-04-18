<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$options = array(
    'onActive' => 'function(title, description){
        description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
    'onBackground' => 'function(title, description){
        description.setStyle("display", "none");
        title.addClass("closed").removeClass("open");
    }',
    'startOffset' => 0, // 0 starts on the first tab, 1 starts the second, etc...
    'useCookie' => true, // this must not be a string. Don't use quotes.
);
$this->pagenotfound = true;
?>


<form action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=userprofile'); ?>" method="post" name="adminForm" id="adminForm">
    <?php    
    echo JHtml::_('tabs.start', 'pane', $options);
    echo $this->loadTemplate('basic_profile');
    echo $this->loadTemplate('extended_location');
    echo $this->loadTemplate('extended_profile');
    echo $this->loadTemplate('status');
    echo $this->loadTemplate('facebook_posts');
    echo $this->loadTemplate('groups');
    echo $this->loadTemplate('contacts');
    echo $this->loadTemplate('twitter_mentions');
    echo $this->loadTemplate('facebook_events');
    echo $this->loadTemplate('linkedin_companies');
    echo $this->loadTemplate('facebook_likes');
    echo $this->loadTemplate('not_found');
    ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>