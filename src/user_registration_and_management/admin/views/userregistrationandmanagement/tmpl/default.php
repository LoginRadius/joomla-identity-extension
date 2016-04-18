<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 defined('_JEXEC') or die('Direct Access to this location is not allowed.');
JHtml::_('behavior.tooltip');
jimport('joomla.plugin.helper');
jimport('joomla.html.html.tabs');

$options = array('onActive' => 'function(title, description){
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
if (!JPluginHelper::isEnabled('system', 'userregistration'))
{
    JError::raiseNotice('sociallogin_plugin', JText::_('COM_SOCIALLOGIN_PLUGIN_ERROR'));
}
if (!JPluginHelper::isEnabled('content', 'socialshare'))
{
    JError::raiseNotice('sociallogin_plugin', JText::_('COM_SOCIALSHARE_PLUGIN_ERROR'));
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=userregistrationandmanagement&layout=default'); ?>" method="post" name="adminForm" id="adminForm">
    <div>
        <div class="section70">
            <div>
                <fieldset class="sociallogin_form sociallogin_form_main">
                    <div class="welcome">
                        <h3><?php echo JText::_('COM_SOCIALLOGIN_THANK'); ?></h3>
                    </div>
                    <div class="sociallogin_row welcome_text">
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK'); ?>
                        <a href='http://www.loginradius.com' target='_blank'>
                            <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_HERE'); ?>
                        </a>
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_HERE_TWO'); ?>
                    </div>
                    <div class="sociallogin_row welcome_text">
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO'); ?>
                        <a href="http://ish.re/10E78" target="_blank">Wordpress</a>,
                        <a href="http://ish.re/TRXK" target="_blank">Drupal</a>,
                        <a href="http://ish.re/UF5L" target="_blank">Magento</a>,
                        <a href="http://ish.re/TRXU" target="_blank">Prestashop</a>,
                        <a href="http://ish.re/TRXR" target="_blank">VanillaForum</a>,
                        <a href="http://ish.re/TRXM" target="_blank">vBulletin</a>,
                        <a href="http://ish.re/TRY3" target="_blank">phpBB</a>,
                        <a href="http://ish.re/TRY2" target="_blank">SMF</a>
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO_AND'); ?>
                        <a href="http://ish.re/TRY1" target="_blank">DotNetNuke</a> !
                    </div>
                    <div class="sociallogin_row sociallogin_row_button btn btn-small ">
                        <div class="button2-left">
                            <div class="blank">
                                <a class="modal" href="http://www.loginradius.com/"
                                   target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_FIVE'); ?></a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <?php echo JHtml::_('tabs.start', 'pane', $options); ?>
            <!-- User registration -->
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_LOGIN'), 'panel1'); ?>
            <?php echo $this->loadTemplate('login'); ?>
            <!-- End User registration -->
            <!-- User registration advance -->
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_ADVANCE'), 'panel2'); ?>
            <?php echo $this->loadTemplate('advance'); ?>
            <!-- End User registration advance -->
            <!-- User registration Profile Data -->            
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_PROFILEDATA'), 'panel3'); ?>
             <?php echo $this->loadTemplate('profile_data'); ?>
            <!-- End User registration Profile Data -->
            <!-- User registration post messages -->           
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_POSTSTATUS'), 'panel4'); ?>
             <?php echo $this->loadTemplate('post_message'); ?>
            <!-- End User registration post messages -->
            <!-- User User registration send email/messages -->           
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_SENDMESSAGE'), 'panel5'); ?>
            <?php echo $this->loadTemplate('send_message'); ?>  
            <!-- End User registration send email/messages -->
            <!-- Single sign on -->
            <?php echo JHtml::_('tabs.panel', JText::_('COM_SOCIALLOGIN_PANEL_SSO'), 'panel6'); ?>
            <?php echo $this->loadTemplate('sso'); ?>
            <!-- End Single sign on -->          
        </div>

        <div class="section30">
            <!-- Help Box -->
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP'); ?></h3>
                <ul class="help_ul">
                    <li><a href="http://ish.re/9WC5" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_ONE'); ?></a></li>
                    <li><a href="http://ish.re/96M9" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_THREE'); ?></a></li>
                    <li><a href="http://ish.re/AEGF" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_FOUR'); ?></a></li>
                    <li><a href="http://ish.re/O1W0" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_FIVE'); ?></a></li>
                </ul>
            </div>
            <div style="clear:both;"></div>
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_GET_UPDATE'); ?></h3>
                <p><?php echo JText::_('COM_SOCIALLOGIN_GET_UPDATE_TEXT'); ?> </p>
                <p>
                    <a href="https://www.facebook.com/loginradius" target="_blank"><img src="components/com_userregistrationandmanagement/assets/img/facebook.png"></a>
                    <a href="https://twitter.com/LoginRadius" target="_blank"><img src="components/com_userregistrationandmanagement/assets/img/twitter.png"></a>
                    <a href="https://plus.google.com/+Loginradius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/google.png"></a>
                    <a href="https://www.linkedin.com/company/loginradius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/linkedin.png"></a>
                    <a href="https://www.youtube.com/user/LoginRadius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/youtube.png"></a>
                </p>
            </div>
            <div style="clear:both;"></div>
            <!-- Upgrade Box -->
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT'); ?></h3>
                <p>
                    <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT'); ?> <a
                        href='mailto:feedback@loginradius.com'>feedback@loginradius.com</a> !</p>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value=""/>
</form>