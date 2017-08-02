<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$settings = CiamLoginRadiusHelperRoute::getSettings();
if (!JFactory::getUser()->id) {
    ?>
    <div>
        <script type="text/html" id="loginradiuscustom_tmpl">

            <div class="lr_icons_box">
                <div style="width:100%">

                    <span class="lr_providericons lr_<#=Name.toLowerCase()#>"
                          onclick="return LRObject.util.openWindow('<#= Endpoint #>');"
                          title="<#= Name #>" alt="Sign in with <#=Name#>">

                    </span>
                </div>
            </div>

        </script>
        <div class="lr_singleglider_200 interfacecontainerdiv"></div>
        <div style="clear:both"></div>
        <script>
            jQuery(document).ready(function(){
            callSocialInterface();
            })
        </script>
    </div>    
    <?php
}?>