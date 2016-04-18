<?php
/**
 * @package     UserRegistrationAndManagement.Component
 * @subpackage  com_userregistrationandmanagement
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
$session = JFactory::getSession();
$settings = UserRegistrationAndManagementHelperRoute::getSetting();
$AccountMapRows = UserRegistrationAndManagementHelperRoute::getAccountMapRows();
$userPicture = $session->get('user_picture');
?>
<fieldset id="users-profile-core">
      <?php if($settings['enableAccountLinking'] == 'true' && $session->get('emailVerified')) {?>  
    <legend>
        <?php echo isset($settings['Loginradius_linkingIdentityString']) ? $settings['Loginradius_linkingIdentityString'] : JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_HEAD'); ?>
    </legend>
      <?php }?>
    <div style="clear:both;"></div>
    <div style="width: 100%">
        <div style="float:left; width:50%;">
            <div style="float:left; padding:5px;">
                <img src="<?php
                if (!empty($userPicture))
                {
                    echo JURI::root() . 'images' . LRDS . 'sociallogin' . LRDS . $session->get('user_picture');
                } else
                {
                    echo JURI::root() . 'media' . LRDS . 'com_userregistrationandmanagement' . LRDS . 'images' . LRDS . 'noimage.png';
                }
                ?>" alt="<?php echo JFactory::getUser()->name ?>" class="socialAvatar">
            </div>
            <div class="socialName">
                <b><?php echo JFactory::getUser()->name ?></b>
            </div>
        </div>        
        <?php if($settings['enableAccountLinking'] == 'true' && $session->get('emailVerified')) {?>  
        <div style="float:right;width: 45%">
            <script type="text/html" id="loginradiuscustom_tmpl">
                <# if(isLinked) { #>
                <div class="lr-linked">
                    <div style="width:100%;line-height: 30px;">
                        <span title="<#= Name #>" alt="Linked with <#=Name#>">
                            <img style="margin-right: 5px;"
                                 src="<?php echo JURI::root() . 'media' . LRDS . 'com_userregistrationandmanagement' . LRDS . 'images' . LRDS . 'mapping' . LRDS?><#= Name.toLowerCase() #>.png">
                        </span>

                        <span class="lr-linked-label" style="margin-right:4px;"><#= Name #> is
                            <# if(<?php
                            $value = $session->get('user_lrid'); 
                            echo "'" . $value . "'"
                            ?> == providerId) {  #>
                        </span> <span style="color:green"> <?php echo  JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSGONE') ?>
                            <# } else { #>
                        </span> <span class="lr-linked-label" style="margin-right:4px;"> <?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG') ?>
                            <ul class="btn-toolbar pull-right" style="margin: 0px;">
                                  <li class="btn-group">
                                      <a class="btn" onclick='return unLinkAccount(\"<#= Name.toLowerCase() #>\",\"<#= providerId #>\")'>
                                          <i class="icon-trash"></i></a>
                                  </li>
                              </ul>
                            <# }  #>
                        </span>
                        
                        <div style="clear:both"></div>
                    </div>
                </div>
            </div> 
                <# }  else {#>
                <div class="lr-unlinked">
                    <div class="lr_icons_box">
                        <div style="width:100%">
                            <span class="lr_providericons lr_<#=Name.toLowerCase()#>"
                                  onclick="return $SL.util.openWindow('<#= Endpoint #>&ac_linking=true&is_access_token=true&callback=<#= window.location #>');"
                                  title="<#= Name #>" alt="Sign in with <#=Name#>">                        
                            </span>
                        </div> 
                    </div>  
                </div>
                <br>
                <# } #>
            </script>           
            <script>
            jQuery(document).ready(function () {
            initializeAccountLinkingRaasForms();
            });
            </script>
            <div class="lr_account_linking">
                <div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>
                <div style="clear:both"></div>
         
            <div class="lr-unlinked-data lr_singleglider_200"></div>
            <div style="clear:both"></div>
            <div class="lr-linked-data lr_singleglider_200"></div>
            <div style="clear:both"></div>
            </div>
        </div>        
        <?php }?>
    </div>
    <div style="clear:both;"></div>
</fieldset>
