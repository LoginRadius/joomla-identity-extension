<?php
/**
 * @version        $Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package        Joomla.Site
 * @subpackage    com_users
 * @copyright    Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @since        1.6
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
if (!defined('LRDS'))
{
    define('LRDS', '/');
}
?>
<div class="profile <?php echo $this->pageclass_sfx ?>">
    <?php if (JFactory::getUser()->id == $this->data->id)
    { ?>
        <ul class="btn-toolbar pull-right">
            <li class="btn-group">
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id); ?>">
                    <i class="icon-user"></i> <?php echo JText::_('COM_USERS_Edit_Profile'); ?></a>
            </li>
        </ul>
    <div style="clear: both;"></div>
    <?php } ?>
        <?php if ($this->params->get('show_page_heading'))
        { ?>
        <h1>
        <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php }
    ?>
    <?php echo $this->loadTemplate('mapping'); ?>
    <?php echo $this->loadTemplate('core'); ?>
<?php echo $this->loadTemplate('params'); ?>
<?php echo $this->loadTemplate('custom'); ?>
</div>
