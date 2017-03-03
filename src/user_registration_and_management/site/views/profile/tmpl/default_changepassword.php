<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (JFactory::getUser()->id) {
    ?>
    <div class="messages" style="display:none">      
        <ul>
            <li id="messageinfo">

            </li>
            <div class="clear"></div>
        </ul>
    </div>
    <div id="page-title" style="font-size: xx-large; margin-top: 8%;"></div><br>
    <div class="my-form-wrapper">      
        <div id="changepasswordbox"></div>
        <div id="setpasswordbox"></div>
    </div>
    <script>
        jQuery(document).ready(function () {
            initializeChangePasswordRaasForms();
        });
    </script>
<?php
}?>