<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
JHtml::_('behavior.tooltip');
jimport('joomla.plugin.helper');
?>

<!--<form action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=apilog&layout=default'); ?>" method="post" name="adminForm" id="adminForm">-->
<form action="" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
    
<!--        <table class="form-table sociallogin_table">
            <tr>
                <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_DEBUG_SETTING'); ?></th>
            </tr>
            <tr>
                <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE'); ?></span>
                    <br/><br />
                    <label for="debugEnable-yes">
                        <input id="debugEnable-yes" style="margin:0" type="radio" name="settings[debugEnable]" value="1" <?php echo(isset($this->settings['debugEnable']) && $this->settings['debugEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE_YES'); ?>
                    </label>
                    <label for="debugEnable-no">
                        <input id="debugEnable-no" style="margin:0" type="radio" name="settings[debugEnable]" value="0" <?php echo(!isset($this->settings['debugEnable']) || $this->settings['debugEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_DEBUG_ENABLE_NO'); ?>                    
                    </label>

                </td>
            </tr>
        </table>-->
        <div class="clearfix"> </div> 
        <table class="form-table sociallogin_table">
            <?php if(isset($this->apilogdata) && !empty($this->apilogdata)) {?>
            <thead>
                <tr>                       
                    <?php foreach($this->apilogdata[0] as $key => $val) {?>
                    <td class="head">
                        <?php echo str_replace('_', ' ', ucfirst($key)); ?>
                    </td>
                    <?php }?>
                </tr>
            </thead>
            <tbody>           
                    <?php
                      $count = 1; 
                     foreach ($this->apilogdata as $value) { ?>
                     <tr <?php
                    if (($count % 2) == 0)
                    {
                        echo 'style="background-color:#fcfcfc"';
                    }
                    ?>>
                    <?php foreach($value as $key=>$val) {
                         if($key == 'timestamp'){
                         ?>
                    <th scope="col" class="manage-colum">
                            <?php echo date('d/m/Y H:i:s', $val) ?>
                        </th>
                         <?php } else if($key == 'response') {?>
                     <th scope="col" width="30%" class="manage-colum comment more">
                            <?php echo json_decode($val) ?>
                        </th>
                     <?php } else { ?>
                         <th scope="col" class="manage-colum">
                            <?php echo $val ?>
                        </th>
                     <?php }}?>             
                </tr>
                <?php
                $count++;
            }
            ?>
            </tbody>   
            <?php } else {?>
              <thead>
                <tr>   
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_LOG'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_API_URL'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_REQUEST_TYPE'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_LOG_DATA'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_RESPONSE'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_RESPONSE_TYPE'); ?></td>              
                    <td class="head"><?php echo JText::_('COM_API_LOG_FIELD_TIMESTAMP'); ?></td>              
                </tr>
            </thead>
            <tbody>           
                <tr>   
                    <th colspan="7" scope="col" class="manage-colum"><?php echo JText::_('COM_API_NO_LOG_MESSAGE'); ?></th>        
                </tr>
            </tbody>
            <?php }?>
        </table>
        <input type="hidden" name="task" value="" />
    </div>
</form>
