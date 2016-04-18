<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (!defined('LRDS')) {
    define('LRDS', '/');
}
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

if (!JFactory::getUser()->id) {
    ?>
    <div class="logout-button">
        <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=login'); ?>">
            <?php echo JText::_('JLOGIN'); ?>
        </a>
    </div>    
    <ul class="unstyled">
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=register'); ?>">
                <?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=forgotpassword'); ?>">
                <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
        </li>
    </ul>
<?php } else {
    ?>
    <ul class="unstyled">
        <li>
            <?php echo JText::sprintf('MOD_LOGINRADIUS_HINAME', htmlspecialchars(JFactory::getUser()->name)); ?>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=profile'); ?>">
                <span class="icon-user"></span> <?php echo JText::_('MOD_LOGINRADIUS_VALUE_ACCOUNT'); ?> </a>
        </li>
    </ul>
    <div class="logout-button">
        <a class="btn btn-primary"  href="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=logout'); ?>">
            <?php echo JText::_('JLOGOUT'); ?>
        </a>
    </div>

    <?php
}