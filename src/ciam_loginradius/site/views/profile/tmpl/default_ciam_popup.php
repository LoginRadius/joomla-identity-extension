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
?>
<div id="social-registration-form" class="LoginRadius_overlay LoginRadius_content_IE" style="display: none;">
    <div class="popupmain">
        <div class="lr-popupheading"> <?php echo isset($settings['popupemailtitle']) ? $settings['popupemailtitle'] : 'Please fill the following details to proceed'; ?></div>
        <div class="popupinner" id="social-registration-container">
            <div class="lr-noerror">
            </div>
        </div>
        <div class="lr-popup-footer">
        </div>
    </div>
</div>
