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
if (isset($settings['apikey']) && $settings['apikey']!='' && !JFactory::getUser()->id) {
     echo $this->loadTemplate('message');?>
        
    <script>
        jQuery(document).ready(function () {
            initializeRegisterCiamForm();
            initializeSocialRegisterCiamForm();
            var isClear = 1;
            var formIntval = setInterval(function () {
                jQuery('#lr-loading').hide();
                if (isClear > 0) {
                    clearInterval(formIntval);
                }
            }, 1000);
        });
    </script>
    <label><?php echo isset($settings['lr_social_login_label_string']) && !empty($settings['lr_social_login_label_string']) ? $settings['lr_social_login_label_string'] : ''; ?></label>
    <?php
        echo $this->loadTemplate('social_widget_container');
        ?>
    <div class="my-form-wrapper">
        <div id="registration-container"></div>
        <?php
        echo $this->loadTemplate('loading');
        echo $this->loadTemplate('ciam_popup');
        ?>
    </div>    
    <?php    
}?>