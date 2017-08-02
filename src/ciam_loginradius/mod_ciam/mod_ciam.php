<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Include the login functions only once
$params->def('greeting', 1);
$usersConfig = JComponentHelper::getParams('com_users');
$document = JFactory::getDocument();
if (JVERSION < 3) {
    $path = parse_url(JURI::base());
    $path = $path['path'];
   
    if((JURI::current() != JURI::base().'index.php') || !isset($_GET['vtype'])){   
        unset($document->_scripts[$path . 'media/system/js/mootools-more.js']);
    } 
    if((JURI::current() == JURI::base().'index.php') && isset($_GET['vtype'])){
        unset($document->_scripts[$path . 'media/system/js/mootools-more.js']);          
    } elseif(JURI::current() == JURI::base().'index.php'){
         $document->addScript($path . 'media/system/js/mootools-more.js');
    }  
}
require JModuleHelper::getLayoutPath ('mod_ciam',$params->get('layout', 'default'));