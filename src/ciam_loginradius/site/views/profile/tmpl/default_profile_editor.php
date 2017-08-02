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
  <script>
    jQuery(document).ready(function () {
       initializeProfileEditorCiamForm();
    });
  </script>
  <div class="my-form-wrapper">
     <div id="profileeditor-container"></div>
  </div>
<?php
}?>