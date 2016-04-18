<?php
/**
 * @package     UserRegistrationAndManagement.Administrator
 * @subpackage  com_userregistrationandmanagement
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
<div>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_SETTING'); ?></th>
        </tr>
        <tr >
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_ENABLE'); ?></span>
                <br/><br />
                <label for="send-twitterDMEnable-yes">
                    <input id="send-twitterDMEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_twitterDMEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_twitterDMEnable']) && $this->settings['LoginRadius_twitterDMEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_ENABLE_YES'); ?>
                </label>
                <label for="send-twitterDMEnable-no">
                    <input id="send-twitterDMEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_twitterDMEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_twitterDMEnable']) || $this->settings['LoginRadius_twitterDMEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_ENABLE_NO'); ?>                    
                </label>

            </td>
        </tr>

        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_TWITTER'); ?></span>
                <br/><br />
                <label for="send-twitterMessageFriends-yes">
                    <input id="send-twitterMessageFriends-yes" style="margin:0" type="radio" name="settings[twitterMessageFriends]" value="1" <?php echo(isset($this->settings['twitterMessageFriends']) && $this->settings['twitterMessageFriends'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_TWITTER_PICK'); ?>
                </label>
                <label for="send-twitterMessageFriends-no">
                    <input id="send-twitterMessageFriends-no" style="margin:0" type="radio" name="settings[twitterMessageFriends]" value="0" <?php echo(!isset($this->settings['twitterMessageFriends']) || $this->settings['twitterMessageFriends'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_TWITTER_ALL'); ?>                    
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="send-twitterDMSubject"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_MESSAGE_SUBJECT'); ?></label>
                        </td>
                        <td>
                            <input id="send-twitterDMSubject" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_twitterDMSubject']) ? htmlspecialchars($this->settings ['LoginRadius_twitterDMSubject']) : ''); ?>" id="LoginRadius_twitterDMSubject" name="settings[LoginRadius_twitterDMSubject]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="send-twitterDMMessage"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_TWITTER_MESSAGE_MESSAGE'); ?></label>
                        </td>
                        <td>
                            <textarea id="send-twitterDMMessage" name="settings[LoginRadius_twitterDMMessage]" ><?php
                                if (isset($this->settings['LoginRadius_twitterDMMessage']))
                                {
                                    echo trim($this->settings['LoginRadius_twitterDMMessage']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_ENABLE'); ?></span>
                <br/><br />
                <label for="send-linkedinDMEnable-yes">
                    <input id="send-linkedinDMEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_linkedinDMEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_linkedinDMEnable']) && $this->settings['LoginRadius_linkedinDMEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_ENABLE_YES'); ?>
                </label>
                <label for="send-linkedinDMEnable-no">
                    <input id="send-linkedinDMEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_linkedinDMEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_linkedinDMEnable']) || $this->settings['LoginRadius_linkedinDMEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>

        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_LINKEDIN'); ?></span>
                <br/><br />
                <label for="send-linkedinMessageFriends-yes">
                    <input id="send-linkedinMessageFriends-yes" style="margin:0" type="radio" name="settings[linkedinMessageFriends]" value="1" <?php echo(isset($this->settings['linkedinMessageFriends']) && $this->settings['linkedinMessageFriends'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_LINKEDIN_PICK'); ?>
                </label>
                <label for="send-linkedinMessageFriends-no">
                    <input id="send-linkedinMessageFriends-no" style="margin:0" type="radio" name="settings[linkedinMessageFriends]" value="0" <?php echo(!isset($this->settings['linkedinMessageFriends']) || $this->settings['linkedinMessageFriends'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_LINKEDIN_ALL'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="send-linkedinDMSubject"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_MESSAGE_SUBJECT'); ?></label>
                        </td>
                        <td>
                            <input id="send-linkedinDMSubject" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_linkedinDMSubject']) ? htmlspecialchars($this->settings ['LoginRadius_linkedinDMSubject']) : ''); ?>" id="LoginRadius_linkedinDMSubject" name="settings[LoginRadius_linkedinDMSubject]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="send-linkedinDMMessage"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_LINKEDIN_MESSAGE_MESSAGE'); ?></label>
                        </td>
                        <td>
                            <textarea id="send-linkedinDMMessage" name="settings[LoginRadius_linkedinDMMessage]" ><?php
                                if (isset($this->settings['LoginRadius_linkedinDMMessage']))
                                {
                                    echo trim($this->settings['LoginRadius_linkedinDMMessage']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_ENABLE'); ?></span>
                <br/><br />
                <label for="send-googleEmailEnable-yes">
                    <input id="send-googleEmailEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_googleDMEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_googleDMEnable']) && $this->settings['LoginRadius_googleDMEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_ENABLE_YES'); ?>
                </label>
                <label for="send-googleEmailEnable-no">
                    <input id="send-googleEmailEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_googleDMEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_googleDMEnable']) || $this->settings['LoginRadius_googleDMEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_ENABLE_NO'); ?>
                </label>

            </td>
        </tr>

        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_GOOGLE'); ?></span>
                <br/><br />
                <label for="send-googleMessageFriends-yes">
                    <input id="send-googleMessageFriends-yes" style="margin:0" type="radio" name="settings[googleMessageFriends]" value="1" <?php echo(isset($this->settings['googleMessageFriends']) && $this->settings['googleMessageFriends'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_GOOGLE_PICK'); ?>
                </label>
                <label for="send-googleMessageFriends-no">
                    <input id="send-googleMessageFriends-no" style="margin:0" type="radio" name="settings[googleMessageFriends]" value="0" <?php echo(!isset($this->settings['googleMessageFriends']) || $this->settings['googleMessageFriends'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_GOOGLE_ALL'); ?>
                </label>

            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="send-googleDMSubject"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_MESSAGE_SUBJECT'); ?></label>
                        </td>
                        <td>
                            <input id="send-googleDMSubject" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_googleDMSubject']) ? htmlspecialchars($this->settings ['LoginRadius_googleDMSubject']) : ''); ?>" id="LoginRadius_googleDMSubject" name="settings[LoginRadius_googleDMSubject]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="send-googleDMMessage"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_GOOGLE_MESSAGE_MESSAGE'); ?></label>
                        </td>
                        <td>
                            <textarea id="send-googleDMMessage" name="settings[LoginRadius_googleDMMessage]" ><?php
                                if (isset($this->settings['LoginRadius_googleDMMessage']))
                                {
                                    echo trim($this->settings['LoginRadius_googleDMMessage']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_ENABLE'); ?></span>
                <br/><br />
                <label for="send-yahooEmailEnable-yes">
                    <input id="send-yahooEmailEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_yahooDMEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_yahooDMEnable']) && $this->settings['LoginRadius_yahooDMEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_ENABLE_YES'); ?>
                </label>
                <label for="send-yahooEmailEnable-no">
                    <input id="send-yahooEmailEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_yahooDMEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_yahooDMEnable']) || $this->settings['LoginRadius_yahooDMEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_YAHOO'); ?></span>
                <br/><br />
                <label for="send-yahooMessageFriends-yes">
                    <input id="send-yahooMessageFriends-yes" style="margin:0" type="radio" name="settings[yahooMessageFriends]" value="1" <?php echo(isset($this->settings['yahooMessageFriends']) && $this->settings['yahooMessageFriends'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_YAHOO_PICK'); ?>
                </label>
                <label for="send-yahooMessageFriends-no">
                    <input id="send-yahooMessageFriends-no" style="margin:0" type="radio" name="settings[yahooMessageFriends]" value="0" <?php echo(!isset($this->settings['yahooMessageFriends']) || $this->settings['yahooMessageFriends'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_RECIVEMES_YAHOO_ALL'); ?>                    
                </label>

            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="send-yahooDMSubject"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_MESSAGE_SUBJECT'); ?></label>
                        </td>
                        <td>
                            <input id="send-yahooDMSubject" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_yahooDMSubject']) ? htmlspecialchars($this->settings ['LoginRadius_yahooDMSubject']) : ''); ?>" id="LoginRadius_yahooDMSubject" name="settings[LoginRadius_yahooDMSubject]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="send-yahooDMMessage"><?php echo JText::_('COM_SOCIALLOGIN_SENDMES_YAHOO_MESSAGE_MESSAGE'); ?></label>
                        </td>
                        <td>
                            <textarea id="send-yahooDMMessage" name="settings[LoginRadius_yahooDMMessage]" ><?php
                                if (isset($this->settings['LoginRadius_yahooDMMessage']))
                                {
                                    echo trim($this->settings['LoginRadius_yahooDMMessage']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>