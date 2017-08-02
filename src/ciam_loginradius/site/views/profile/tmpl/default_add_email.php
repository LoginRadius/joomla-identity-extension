<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if (JFactory::getUser()->id) {?>

    <div id="addemail-form" class="LoginRadius_overlay LoginRadius_content_IE" style="display: none;">
    <div class="popupmain">
        <div class="lr-popupheading"><?php echo 'Add Email'; ?><div onclick="lrCloseAddEmailPopup();" class="closePopup">x</div></div>
        <div class="popupinner" id="addemail-container">
            <div class="lr-noerror">
            </div>
        </div>
        <div class="lr-popup-footer">
        </div>
    </div>
</div>
    <script>
        jQuery(document).ready(function () {
            initializeAddEmailCiamForms();
        });
    </script>
<?php
}?>