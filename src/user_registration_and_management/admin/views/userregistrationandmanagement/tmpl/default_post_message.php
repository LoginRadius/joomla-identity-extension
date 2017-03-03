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
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_SETTING'); ?></th>
        </tr>
        <tr>
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_TITLE'); ?></span><br/><br />
                <label for="post-fbStatusEnable-yes">
                    <input id="post-fbStatusEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_facebookStatusEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_facebookStatusEnable']) && $this->settings['LoginRadius_facebookStatusEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="post-fbStatusEnable-no">
                    <input id="post-fbStatusEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_facebookStatusEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_facebookStatusEnable']) || $this->settings['LoginRadius_facebookStatusEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_FACEBOOK_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_FACEBOOK_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="post-fbStatusUrl"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_FACEBOOK_MESSAGE_URL'); ?></label>
                        </td>
                        <td>
                            <input id="post-fbStatusUrl" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_facebookStatusUrl']) ? htmlspecialchars($this->settings ['LoginRadius_facebookStatusUrl']) : ''); ?>" id="post-LoginRadius_facebookStatusUrl" name="settings[LoginRadius_facebookStatusUrl]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="post-fbStatusTitle"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_FACEBOOK_MESSAGE_TITLE'); ?></label>
                        </td>
                        <td>
                            <input id="post-fbStatusTitle" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_facebookStatusTitle']) ? htmlspecialchars($this->settings ['LoginRadius_facebookStatusTitle']) : ''); ?>" id="post-LoginRadius_facebookStatusTitle" name="settings[LoginRadius_facebookStatusTitle]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="post-fbDescription"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_FACEBOOK_MESSAGE_DISCRIPTION'); ?></label>
                        </td>
                        <td>
                            <textarea id="post-fbDescription" name="settings[LoginRadius_facebookDescription]" ><?php
                                if (isset($this->settings['LoginRadius_facebookDescription']))
                                {
                                    echo trim($this->settings['LoginRadius_facebookDescription']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="post-fbstatus"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_FACEBOOK_MESSAGE_STATUS'); ?></label>
                        </td>
                        <td>
                            <textarea id="post-fbstatus" name="settings[LoginRadius_facebookStatus]"><?php
                                if (isset($this->settings['LoginRadius_facebookStatus']))
                                {
                                    echo trim($this->settings['LoginRadius_facebookStatus']);
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
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_TWEETTOTWITTER_SETTING'); ?></th>
        </tr>
        <tr >
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_TWEETTOTWITTER_ENABLE_TITLE'); ?></span><br/><br />
                <label for="post-twitterStatusEnable-yes">
                    <input id="post-twitterStatusEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_twitterStatusEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_twitterStatusEnable']) && $this->settings['LoginRadius_twitterStatusEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_TWEETTOTWITTER_ENABLE_YES'); ?>
                </label>
                <label for="post-twitterStatusEnable-no">
                    <input id="post-twitterStatusEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_twitterStatusEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_twitterStatusEnable']) || $this->settings['LoginRadius_twitterStatusEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_TWEETTOTWITTER_ENABLE_NO'); ?>                    
                </label>

            </td>
        </tr>

        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_TWEETTOTWITTER_MESSAGE_HEADING'); ?></span><br />
                <textarea id="post-twitterTweet" name="settings[LoginRadius_twitterTweet]" ><?php
                    if (isset($this->settings['LoginRadius_twitterTweet']))
                    {
                        echo trim($this->settings['LoginRadius_twitterTweet']);
                    }
                    ?></textarea>
            </td>
        </tr>
    </table>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_LINKEDIN_STATUS_SETTING'); ?></th>
        </tr>
        <tr >
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_LINKEDIN_STATUS_ENABLE_TITLE'); ?></span>
                <br/><br />
                <label for="post-linkedinPostEnable-yes">
                    <input id="post-linkedinPostEnable-yes" style="margin:0" type="radio" name="settings[LoginRadius_linkedinPostEnable]" value="1" <?php echo(isset($this->settings['LoginRadius_linkedinPostEnable']) && $this->settings['LoginRadius_linkedinPostEnable'] == 1) ? "checked" : ""; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_LINKEDIN_STATUS_ENABLE_YES'); ?>
                </label>
                <label for="post-linkedinPostEnable-no">
                    <input id="post-linkedinPostEnable-no" style="margin:0" type="radio" name="settings[LoginRadius_linkedinPostEnable]" value="0" <?php echo(!isset($this->settings['LoginRadius_linkedinPostEnable']) || $this->settings['LoginRadius_linkedinPostEnable'] == 0) ? "checked" : ""; ?>  /> <?php echo JText::_('COM_SOCIALLOGIN_LINKEDIN_STATUS_ENABLE_NO'); ?>                    
                </label>
            </td>
        </tr>

        <tr class="sociallogin_row_white">
            <td colspan="2"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_LINKEDIN_MESSAGE_HEADING'); ?></span><br /><br />
                <table>
                    <tr>
                        <td><label for="post-liPostTitle"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_LINKEDIN_MESSAGE_TITLE'); ?></label>
                        </td>
                        <td>
                            <input id="post-liPostTitle" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_linkedinPostTitle']) ? htmlspecialchars($this->settings ['LoginRadius_linkedinPostTitle']) : ''); ?>" id="post-LoginRadius_linkedinPostTitle" name="settings[LoginRadius_linkedinPostTitle]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="post-liPostUrl"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_LINKEDIN_MESSAGE_URL'); ?></label>
                        </td>
                        <td>
                            <input id="post-liPostUrl" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_linkedinPostUrl']) ? htmlspecialchars($this->settings ['LoginRadius_linkedinPostUrl']) : ''); ?>" id="post-LoginRadius_linkedinPostUrl" name="settings[LoginRadius_linkedinPostUrl]">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="post-liPostImageUrl"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_LINKEDIN_IMAGEURL'); ?></label>
                        </td>
                        <td><input id="post-liPostImageUrl" style="margin:0" type="text" value="<?php echo (isset($this->settings ['LoginRadius_linkedinPostImageUrl']) ? htmlspecialchars($this->settings ['LoginRadius_linkedinPostImageUrl']) : ''); ?>" id="post-LoginRadius_linkedinPostImageUrl" name="settings[LoginRadius_linkedinPostImageUrl]"></td>
                    </tr>
                    <tr>
                        <td><label for="post-liPostMessage"><?php echo JText::_('COM_SOCIALLOGIN_POSTON_LINKEDIN_MESSAGE_STATUS'); ?></label>
                        </td>
                        <td>
                            <textarea id="post-liPostMessage" name="settings[LoginRadius_linkedinPostMessage]" ><?php
                                if (isset($this->settings['LoginRadius_linkedinPostMessage']))
                                {
                                    echo trim($this->settings['LoginRadius_linkedinPostMessage']);
                                }
                                ?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
