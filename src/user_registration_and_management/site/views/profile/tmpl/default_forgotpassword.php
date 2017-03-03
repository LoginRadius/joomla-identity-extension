<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if(!JFactory::getUser()->id){
?>
  <div class="messages" style="display:none">    
      <ul>
          <li class="messageinfo">

          </li>
          <div class="clear"></div>
      </ul>
  </div>
  <script>
    jQuery(document).ready(function () {
      initializeForgotPasswordRaasForms();
    });
  </script>
  <div class="raas-lr-form my-form-wrapper">
    <div id="forgotpassword-container"></div>
  </div>
<?php }?>