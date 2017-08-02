<?php
/**
 * @package     CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
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
$settings = CiamLoginRadiusHelperRoute::getSettings();
$optionVal = isset($settings['LoginRadius_emailVerificationOption']) ? $settings['LoginRadius_emailVerificationOption'] : '';
$AccountMapRows = CiamLoginRadiusHelperRoute::getAccountMapRows();
$userPicture = $session->get('user_picture');
echo $this->loadTemplate('message');
?>
<fieldset id="users-profile-core">
    <?php if($settings['enableAccountLinking'] == 'true' && $session->get('emailVerified') && $optionVal != '2') {?>  
    <legend>
        <?php echo isset($settings['Loginradius_linkingIdentityString']) ? $settings['Loginradius_linkingIdentityString'] : JText::_('COM_CIAM_LINK_ACCOUNT_HEAD'); ?>
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
                    echo JURI::root() . 'media' . LRDS . 'com_ciamloginradius' . LRDS . 'images' . LRDS . 'noimage.png';
                }
                ?>" alt="<?php echo JFactory::getUser()->name ?>" class="socialAvatar">
            </div>
            <div class="socialName">
                <b><?php echo JFactory::getUser()->name ?></b>
            </div>
        </div>        
        <?php if($settings['enableAccountLinking'] == 'true' && $session->get('emailVerified') && $optionVal != '2') {?>  
        <div style="float:right;width: 45%">
            <script type="text/html" id="loginradiuscustom_tmpl_link">
                <# if(isLinked) { #>
                <div class="lr-linked">
                    <div style="width:100%;line-height: 30px;">
                        <span title="<#= Name #>" alt="Linked with <#=Name#>">
                            <img style="margin-right: 5px;"
                                 src="<?php echo JURI::root() . 'media' . LRDS . 'com_ciamloginradius' . LRDS . 'images' . LRDS . 'mapping' . LRDS?><#= Name.toLowerCase() #>.png">
                        </span>
                        <span class="lr-linked-label" style="margin-right:4px;"><#= Name #> is
                            <# if(<?php                            
                            $value = $session->get('user_lrid'); 
                            echo "'" . $value . "'"
                            ?> == providerId) {  #>
                        </span> <span style="color:green"> <?php echo  JText::_('COM_CIAM_LINK_ACCOUNT_MSGONE') ?>
                            <# } else { #>
                        </span> <span class="lr-linked-label" style="margin-right:4px;"> <?php echo JText::_('COM_CIAM_LINK_ACCOUNT_MSG') ?>
                            <ul class="btn-toolbar pull-right" style="margin: 0px; display: inline-block; list-style: none; cursor:pointer;">
                                  <li class="btn-group">
                                      <a class="btn" onclick='return <#=ObjectName#>.util.unLinkAccount(\"<#= Name.toLowerCase() #>\",\"<#= providerId #>\")'>
                                              <?php                                             
                                              if (JVERSION < 3) { ?>
                                          <span class="unlinkBtn"><?php echo JText::_('unlink') ?></span>                                      
                                              <?php } else { ?>
                                                     <i class="icon-trash"></i>
                                              <?php }?>                                           
                                         
                                      </a>
                                  </li>
                              </ul>
                            <# }  #>
                        </span>                        
                        <div style="clear:both"></div>
                    </div>
                </div>
            </div> 
                <# } else {#>
                <div class="lr-unlinked lr_singleglider_200">
                        <div class="lr_icons_box">
                            <div style="width:100%">
                                <span class="lr-provider-label lr_providericons lr_<#=Name.toLowerCase()#>"
                                      onclick="return <#=ObjectName#>.util.openWindow('<#= Endpoint #>');"
                                      title="<#= Name #>" alt="Link with <#=Name#>">
                                </span>
                            </div>
                        </div>
                    </div>
              
                <# } #>
            </script>           
            <script>
            jQuery(document).ready(function () {
            initializeAccountLinkingCiamForms();
            });
            </script>
            <div class="lr_account_linking">
                <div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>
                <div style="clear:both"></div>
            </div>
        </div>        
        <?php }?>
    </div>
    <div style="clear:both;"></div>
</fieldset>
