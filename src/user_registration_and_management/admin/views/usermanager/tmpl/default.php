<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Load the tooltip behavior.
$canDo = UserRegistrationAndManagementModelUserManager::getActions();
$loggeduser = JFactory::getUser();
$canEdit = $canDo->get('core.edit');
$canChange = $loggeduser->authorise('core.edit.state', 'com_userregistrationandmanagement');
?>

<form action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=usermanager'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <fieldset id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <?php if (JVERSION < 3) { ?>
                    <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_USERS_SEARCH_USERS'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" value="<?php echo $this->escape($this->lists['filter_search']); ?>" title="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" />
                    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button type="button" onclick="document.id('filter_search').value = ''; this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
                <?php } else {?>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" value="<?php echo $this->escape($this->lists['filter_search']); ?>" title="<?php echo JText::_('COM_USERS_SEARCH_USERS'); ?>" />
                </div>
                <div class="btn-group pull-left">
                    <button type="submit" class="btn tip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn tip" onclick="document.id('filter_search').value = ''; this.form.submit();" title="<?php echo JText::_('JSEARCH_RESET'); ?>"><i class="icon-remove"></i></button>
                <?php }?>
            </div>
        </fieldset>
        <div class="clearfix"> </div>
        <table class="table adminlist table-striped">
            <thead>
                <tr>
                    <th width="1%" class="nowrap center">
                        <?php if ($canEdit) : ?>
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        <?php endif; ?>
                    </th>
                    <th class="left">
                        <?php echo JText::_('COM_SOCIALLOGIN_USERS_PROFILE_AVATAR'); ?>
                    </th>
                    <th class="left">
                        <?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_NAME', 'juser.name', @$this->lists['filter_order'], @$this->lists['order']); ?>
                    </th>
                    <th width="10%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'juser.username', @$this->lists['filter_order'], @$this->lists['order']); ?>
                    </th>
                    <th width="5%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_ENABLED', 'juser.block', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="5%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_ACTIVATED', 'juser.activation', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="15%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JGLOBAL_EMAIL', 'juser.email', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="10%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_LAST_VISIT_DATE', 'juser.lastvisitDate', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="10%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_REGISTRATION_DATE', 'juser.registerDate', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="1%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'juser.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th width="1%" class="nowrap center">
                        <?php echo JText::_('COM_SOCIALLOGIN_USER_PROFILE'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="15"><?php echo $this->page->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                foreach ($this->rows as $i => $item) :
                    // If this group is super admin and this user is not super admin, $canEdit is false
                    if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin')) {
                        $canEdit = false;
                        $canChange = false;
                    }
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php if ($canEdit) : ?>
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $lrsocial = UserRegistrationAndManagementModelUserManager::getsocialuserdata((int) $item->id); ?>
                            <img src="<?php
                            if (!empty($lrsocial['lr_picture'])) {
                                echo JURI::root() . 'images/sociallogin/' . $lrsocial['lr_picture'];
                            } else {
                                echo JURI::root() . 'media/com_userregistrationandmanagement/images/noimage.png';
                            }
                            ?>" alt="<?php echo $this->escape($item->name); ?>" style="width:50px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; margin: 2px 4px 4px 0; padding: 2px;">
                        </td>
                        <td>
                            <?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=user&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
                                    <?php echo $this->escape($item->name); ?></a>
                            <?php else : ?>
                                <?php echo $this->escape($item->name); ?>
                            <?php endif; ?>
                            <?php if (JDEBUG) : ?>
                                <div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id); ?>">
                                        <?php echo JText::_('COM_USERS_DEBUG_USER'); ?></a></div>
                            <?php endif; ?>
                        </td>
                        <td class="center">
                            <?php echo $this->escape($item->username); ?>
                        </td>
                        <td class="center">
                            <?php if ($canChange) : ?>
                                <?php
                                $self = $loggeduser->id == $item->id;
                                echo JHtml::_('jgrid.state', UserRegistrationAndManagementController::blockStates(), $item->block, $i, '', !$self);
                                ?>
                            <?php else : ?>
                                <?php echo JText::_($item->block ? 'JNO' : 'JYES'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="center">
                            <?php
                            $activated = empty($item->activation) ? 0 : 1;
                            echo JHtml::_('jgrid.state', UserRegistrationAndManagementController::activateStates(), $activated, $i, '', (boolean) $activated);
                            ?>
                        </td>
                        <td class="center">
                            <?php echo $this->escape($item->email); ?>
                        </td>
                        <td class="center">
                            <?php if ($item->lastvisitDate != '0000-00-00 00:00:00'): ?>
                                <?php echo JHtml::_('date', $item->lastvisitDate, 'Y-m-d H:i:s'); ?>
                            <?php else: ?>
                                <?php echo JText::_('JNEVER'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('date', $item->registerDate, 'Y-m-d H:i:s'); ?>
                        </td>
                        <td class="center">
                            <?php echo (int) $item->id; ?>
                        </td>
                        <td class="center">
                            <a href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=userprofile&userid=' . (int) $item->id); ?>">
                                <?php echo JText::_('COM_SOCIALLOGIN_USER_PROFILE_VIEW'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <input type="hidden" name="option" value="com_userregistrationandmanagement" />
        <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
