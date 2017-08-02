<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>
<h1>Logout Success</h1>
<p>You will be redirect on Login page with in 5 seconds...</p>
<script>
    setTimeout(function () {
        window.location.href = "<?php echo JRoute::_('index.php?option=com_ciamloginradius&view=login'); ?>";
    }, 5000);
</script>