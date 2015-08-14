<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$session = JFactory::getSession();
$settings = SocialLoginAndSocialShareHelperRoute::getSetting();
$AccountMapRows = SocialLoginAndSocialShareHelperRoute::getAccountMapRows();
?>
<style>
    .AccountSetting-addprovider li {
        list-style-type: none;
    }
    .AccountSetting-addprovider {
        margin: 0 0 9px 15px !important;
    }
    .AccountSetting-addprovider li form button{
        float:right;
    }
</style>
<fieldset id="users-profile-core">
    <legend>
        <?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_HEAD'); ?>
    </legend>
    <div style="clear:both;"></div>
    <div style="width: 100%">
        <div style="float:left; width:50%;">
            <div style="float:left; padding:5px;">
                <?php $userPicture = $session->get('user_picture'); ?>
                <img src="<?php
                if (!empty($userPicture))
                {
                    echo JURI::root() . 'images' . LRDS . 'sociallogin' . LRDS . $session->get('user_picture');
                } else
                {
                    echo JURI::root() . 'media' . LRDS . 'com_socialloginandsocialshare' . LRDS . 'images' . LRDS . 'noimage.png';
                }
                ?>" alt="<?php echo JFactory::getUser()->name ?>"
                     style="width:80px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;">
            </div>
            <div style="float:right;padding:5px;font-size: 20px;margin: 5px;">
                <b><?php echo JFactory::getUser()->name ?></b>
            </div>
        </div>
        <div style="float:right;width: 40%">
                    <?php echo SocialLoginAndSocialShareHelperRoute::getInterface($settings); ?>
            <div>
                <ul class="AccountSetting-addprovider">
                            <?php foreach ($AccountMapRows as $row)
                            { ?>
                        <li>
                            <form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_socialloginandsocialshare&task=profile.removeSocialAccount'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
                                <?php
                                $msg = JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG');
                                if ($row->LoginRadius_id == $session->get('user_lrid'))
                                {
                                    $msg = '<span style="color:green;">' . JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSGONE') . '</span>';
                                }
                                ?>
    <?php echo $msg; ?>
                                <span style="margin-right:5px;"> <img src="<?php echo JURI::root() . 'media' . LRDS . 'com_socialloginandsocialshare' . LRDS . 'images' . LRDS . $row->provider . '.png'; ?>"/></span>
                                <button type="submit" class="buttondelete">
                                    <span><?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_REMOVE'); ?></span>
                                </button>
                                <input type="hidden" name="option" value="com_socialloginandsocialshare"/>
                                <input type="hidden" name="task" value="profile.removeSocialAccount"/>
                                <input type="hidden" name="mapid" value="<?php echo $row->provider; ?>"/>
                                <input type="hidden" name="lruser_id" value="<?php echo $row->LoginRadius_id; ?>"/>
                            </form>
    <?php echo JHtml::_('form.token'); ?></li>
<?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
</fieldset>
