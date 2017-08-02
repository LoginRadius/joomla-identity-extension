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
       initializeForgotPasswordCiamForms();
    });
  </script>
  <div class="raas-lr-form my-form-wrapper">
     <div id="forgotpassword-container"></div>
  </div>
<?php }?>