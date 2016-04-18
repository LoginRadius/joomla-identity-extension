<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$settings = UserRegistrationAndManagementHelperRoute::getSetting();
if(!JFactory::getUser()->id){
?>
  <div class="messages" style="display:none">
      <h2 class="element-invisible">Error message</h2>
      <ul>
          <li class="messageinfo">

          </li>
          <div class="clear"></div>
      </ul>
  </div>
  <script>
      jQuery(document).ready(function () {
       initializeRegisterRaasForm();
        initializeSocialRegisterRaasForm();
          var isClear = 1;
          var formIntval;
        setTimeout(show_birthdate_date_block, 1000);
          formIntval = setInterval(function(){ jQuery('#lr-loading').hide();
             if (isClear > 0) {
                 clearInterval(formIntval);
             }
         }, 1000);
      });
    </script>
  <label><?php echo isset($settings['lr_social_login_label_string']) && !empty($settings['lr_social_login_label_string']) ? $settings['lr_social_login_label_string'] : 'Login with Social Id'; ?></label>
  <div>
      <script type="text/html" id="loginradiuscustom_tmpl">
          <div class="lr_icons_box">
              <div style="width:100%">
                  <span class="lr_providericons lr_<#=Name.toLowerCase()#>"
                          onclick="return $SL.util.openWindow('<#= Endpoint #>&is_access_token=true&callback=<#= window.location #>');"
                          title="<#= Name #>" alt="Sign in with <#=Name#>">

				        	</span>
              </div>
          </div>
      </script>
      <div class="lr_singleglider_200 interfacecontainerdiv"></div>
      <div style="clear:both"></div>
      <script>
        callSocialInterface();
      </script>  
  </div>
  <div class="my-form-wrapper">
      <div id="registeration-container"></div>
      <div class="overlay" id="lr-loading" style="display: none;">
        <div class="circle">
              <div id="imganimation">
                  <img src="<?php echo JURI::root() . 'components/com_userregistrationandmanagement/assets/images/loading.gif' ?>" alt="LoginRadius Processing"
                       style="margin-top: -66px;margin-left: -73px;width: 150px;">
              </div>
          </div>
              <div></div>
      </div>
      <div id="social-registration-form" class="LoginRadius_overlay LoginRadius_content_IE" style="display: none;">
          <div class="popupmain">
              <div class="lr-popupheading"> <?php echo isset($settings['popupemailtitle']) ? $settings['popupemailtitle'] : 'Please fill the following details to proceed'; ?></div>
              <div class="raas-lr-form popupinner" id="social-registration-container">
                  <div class="lr-noerror">
                  </div>
              </div>
              <div class="lr-popup-footer">
              </div>
          </div>
      </div>
</div>
<?php }?>