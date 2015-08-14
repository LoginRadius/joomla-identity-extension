<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (!defined('LRDS'))
{
    define('LRDS', '/');
}
JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

if ($type == 'logout')
{
    $session = JFactory::getSession(); ?>
    <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
        <div>
            <?php
            $socialId = $session->get('user_lrid');
            $userPicture = $session->get('user_picture');
            $avatar = JURI::root() . 'media' . LRDS . 'com_socialloginandsocialshare' . LRDS . 'images' . LRDS . 'noimage.png';
            if (!empty($userPicture))
            {
                $avatar = JURI::root() . 'images' . LRDS . 'sociallogin' . LRDS . $userPicture;
            }
            ?>
            <div style="float:left;"><a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=profile'; ?>" title="My Profile">
                    <img src="<?php echo $avatar; ?>" alt="<?php echo $user->get('name'); ?>"
                         style="width:50px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;"></a>
            </div>
            <div>
                <div class="login-greeting">
                    <div style=" font-weight:bold;">
                        <?php echo JText::sprintf('MOD_LOGINRADIUS_HINAME', htmlspecialchars($user->get('name'))); ?></div>
                    <?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAP'); ?>
                    <b><?php echo modSocialLoginAndSocialShareHelper::socialAccountCount($user, $socialId); ?></b><br/> <?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAPONE'); ?>
                </div>
                <br/>
                <a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=profile'; ?>"><?php echo JText::_('MOD_LOGINRADIUS_VALUE_ACCOUNT'); ?></a>
                <div class="logout-button">
                    <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>"/>
                    <input type="hidden" name="option" value="com_users"/>
                    <input type="hidden" name="task" value="user.logout"/>
                    <input type="hidden" name="return" value="<?php echo $return; ?>"/>
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </div>
        </div>
    </form>
<?php
} else
{
    ?>
        <div class="pretext">
            <p><?php echo $params->get('pretext'); ?></p>
        </div>
        <?php echo modSocialLoginAndSocialShareHelper::getInterface($settings);
        if(!isset($settings['loginform']) || $settings['loginform'] != 0){
            jimport( 'joomla.application.module.helper' );
            $lModule = JModuleHelper::getModule( 'mod_login');
            $atts['style'] = 'xhtml';
            $lModule->title='';
            echo JModuleHelper::renderModule( $lModule, $atts );
        }
}