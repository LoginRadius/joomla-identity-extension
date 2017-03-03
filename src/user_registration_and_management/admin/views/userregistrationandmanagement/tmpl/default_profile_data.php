<?php

/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
jimport('joomla.version');
$settings = $this->settings;
if (JVERSION < 3) {
    $dispatcher = JDispatcher::getInstance();
} else {
    $dispatcher = JEventDispatcher::getInstance();
}
$results = $dispatcher->trigger('onlrSocialProfileData', array($settings));
?>

<?php

if (isset($results) && !empty($results)) {
    echo $results[0];
}
?>
