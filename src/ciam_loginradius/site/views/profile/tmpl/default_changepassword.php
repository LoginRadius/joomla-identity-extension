<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (JFactory::getUser()->id) {
    echo $this->loadTemplate('message');?>
    <div id="page-title" style="font-size: xx-large; margin-top: 8%;"></div><br>
    <div class="my-form-wrapper">      
       <div id="changepassword-container"></div>  
    </div>
    <script>
        jQuery(document).ready(function () {
            initializeChangePasswordCiamForms();
        });
    </script>
<?php
}?>