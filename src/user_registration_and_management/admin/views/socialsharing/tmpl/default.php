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

$enableVerticalArticalType = $disableVerticalArticalType = $enableHorizontalArticalType = $disableHorizontalArticalType = $enableEmailReadOnly = $disableEmailReadOnly = $enableCustomPopup = $disableCustomPopup = $enableSingleWindow = $disableSingleWindow = $enableShareCount = $disableShareCount = $enableShortUrl = $disableShortUrl = $enableMobileFriendly = $disableMobileFriendly = $enableHorizontalShare = $disableHorizontalShare = $horizontalTheme32 = $horizontalTheme16 = $horizontalThemeLarge = $horizontalThemeSmall = $horizontalCounter32 = $horizontalCounter16 = $responcive = $enableVerticalShare = $disableVerticalShare = $topLeft = $topRight = $bottomLeft = $bottomRight = $verticalTheme32 = $verticalTheme16 = $verticalCounterTheme32 = $verticalCounterTheme16 = "";
if (isset($this->settings['emailreadonly']) && $this->settings['emailreadonly'] == '0')
    $disableEmailReadOnly = "checked='checked'";
else
    $enableEmailReadOnly = "checked='checked'";

if (isset($this->settings['horizontalarticaltype']) && $this->settings['horizontalarticaltype'] == '0')
    $disableHorizontalArticalType = "checked='checked'";
else
    $enableHorizontalArticalType = "checked='checked'";

if (isset($this->settings['verticalarticaltype']) && $this->settings['verticalarticaltype'] == '0')
    $disableVerticalArticalType = "checked='checked'";
else
    $enableVerticalArticalType = "checked='checked'";
        
if (isset($this->settings['custompopup']) && $this->settings['custompopup'] == '1')
    $enableCustomPopup = "checked='checked'";
else
   $disableCustomPopup  = "checked='checked'";

if (isset($this->settings['sharehorizontal']) && $this->settings['sharehorizontal'] == '1')
   $enableHorizontalShare  = "checked='checked'";
else
    $disableHorizontalShare = "checked='checked'";

if (isset($this->settings['shorturl']) && $this->settings['shorturl'] == '0')
    $disableShortUrl = "checked='checked'";
else
    $enableShortUrl = "checked='checked'";

if (isset($this->settings['sharecount']) && $this->settings['sharecount'] == '0')
    $disableShareCount = "checked='checked'";
else
    $enableShareCount = "checked='checked'";

if (isset($this->settings['singlewindow']) && $this->settings['singlewindow'] == '0')
    $disableSingleWindow = "checked='checked'";
else
    $enableSingleWindow = "checked='checked'";

if (isset($this->settings['mobilefriendly']) && $this->settings['mobilefriendly'] == '0')
    $disableMobileFriendly = "checked='checked'";
else
    $enableMobileFriendly = "checked='checked'";

switch ($this->settings['choosehorizontalshare']) {
    case 1:
        $horizontalTheme16 = "checked='checked'";
        break;
    case 2:
        $horizontalThemeLarge = "checked='checked'";
        break;
    case 3:
        $horizontalThemeSmall = "checked='checked'";
        break;
    case 4:
        $horizontalCounter16 = "checked='checked'";
        break;
    case 5:
        $horizontalCounter32 = "checked='checked'";
        break;
    case 6:
        $responcive = "checked='checked'";
        break;
    case 0:
    default :
        $horizontalTheme32 = "checked='checked'";
        break;
}

switch ($this->settings['chooseverticalshare']) {
    case 1:
        $verticalTheme16 = "checked='checked'";
        break;
    case 2:
        $verticalCounterTheme32 = "checked='checked'";
        break;
    case 3:
        $verticalCounterTheme16 = "checked='checked'";
        break;
    case 0:
    default :
        $verticalTheme32 = "checked='checked'";
        break;
}

if (isset($this->settings['sharevertical']) && $this->settings['sharevertical'] == '1')
    $enableVerticalShare = "checked='checked'";
else
    $disableVerticalShare = "checked='checked'";

switch ($this->settings['verticalsharepos']) {
    case 1:
        $topRight = "checked='checked'";
        break;
    case 2:
        $bottomLeft = "checked='checked'";
        break;
    case 3:
        $bottomRight = "checked='checked'";
        break;
    case 0:
    default :
        $topLeft = "checked='checked'";
        break;
}
?>
<script type="text/javascript">
    var horshareChecked = <?php echo json_encode($this->settings['horizontal_rearrange']); ?>;
    var vershareChecked = <?php echo json_encode($this->settings['vertical_rearrange']); ?>;
    var horcounterChecked = <?php echo json_encode($this->settings['horizontalcounter']); ?>;
    var vercounterChecked = <?php echo json_encode($this->settings['verticalcounter']); ?>;
</script>
<form action="<?php echo JRoute::_('index.php?option=com_userregistrationandmanagement&view=socialsharing&layout=default'); ?>" method="post" name="adminForm" id="adminForm">
    <div>
    <div class="section70">
            <div>
                <fieldset class="sociallogin_form sociallogin_form_main">
                    <div class="welcome">
                        <h3><?php echo JText::_('COM_SOCIALLOGIN_THANK'); ?></h3>
                    </div>
                    <div class="sociallogin_row welcome_text">
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK'); ?>
                        <a href='http://www.loginradius.com' target='_blank'>
                            <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_HERE'); ?>
                        </a>
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_HERE_TWO'); ?>
                    </div>
                    <div class="sociallogin_row welcome_text">
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO'); ?>
                        <a href="http://ish.re/10E78" target="_blank">Wordpress</a>,
                        <a href="http://ish.re/TRXK" target="_blank">Drupal</a>,
                        <a href="http://ish.re/UF5L" target="_blank">Magento</a>,
<!--                        <a href="http://ish.re/8PEG" target="_blank">osCommerce</a>,
                        <a href="http://ish.re/96IC" target="_blank">Zen-Cart</a>,
                        <a href="http://ish.re/8PFQ" target="_blank">X-Cart</a>,-->
                        <a href="http://ish.re/TRXU" target="_blank">Prestashop</a>,
                        <a href="http://ish.re/TRXR" target="_blank">VanillaForum</a>,
                        <a href="http://ish.re/TRXM" target="_blank">vBulletin</a>,
                        <a href="http://ish.re/TRY3" target="_blank">phpBB</a>,
                        <a href="http://ish.re/TRY2" target="_blank">SMF</a>
                        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO_AND'); ?>
                        <a href="http://ish.re/TRY1" target="_blank">DotNetNuke</a> !
                    </div>
                    <div class="sociallogin_row sociallogin_row_button btn btn-small ">
                        <div class="button2-left">
                            <div class="blank">
                                <a class="modal" href="http://www.loginradius.com/"
                                   target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_FIVE'); ?></a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
           <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIAL_SHARE_SOCIAL_SHARE'); ?></th>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="3">
                <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_THEME'); ?></span><br/><br/>
                  <a id="mymodal1" href="javascript:void(0);" onclick="makeHorizontalVisible();" style="color: #00CCFF;"><b><?php echo JText::_('COM_SOCIAL_SHARE_HORI'); ?></b></a> &nbsp;|&nbsp;
                  <a id="mymodal2" href="javascript:void(0);" onclick="makeVerticalVisible();" style="color: #000000;"><b><?php echo JText::_('COM_SOCIAL_SHARE_VERTICAL'); ?></b></a> &nbsp;|&nbsp;
                  <a id="mymodal3" href="javascript:void(0);" onclick="makeAdvanceVisible();" style="color: #000000;"><b><?php echo JText::_('COM_SOCIAL_SHARE_ADVANCE'); ?></b></a>
               <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 0;">
                    <span id="arrow" class="horizontal"></span>
                    <div id="sharehorizontal" style="display:block;">
                        <div style="overflow:auto; padding:10px;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_HORIZONTAL'); ?></span><br><br/>
                            <input name="settings[sharehorizontal]" type="radio"  <?php echo $enableHorizontalShare; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="settings[sharehorizontal]" type="radio" <?php echo $disableHorizontalShare; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>

                        <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_HORIZONTAL_THEMES'); ?></span><br/><br/>

                            <!--socialsharing interface theme-->
                        <label for="hori32">
                            <input name="settings[choosehorizontalshare]" id="hori32" onclick="createHorzontalShareProvider();" type="radio" <?php echo $horizontalTheme32; ?>value="0"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/horizonSharing32.png" ?>'/>
                        </label>
                        <label for="hori16" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="hori16" onclick="createHorzontalShareProvider();" type="radio" <?php echo $horizontalTheme16; ?>value="1"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/horizonSharing16.png" ?>'/>
                        </label>
                        <label for="responcive" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="responcive" onclick="createHorzontalShareProvider();" type="radio" <?php echo $responcive; ?>value="6"/>
                            <img style=" margin-top: -25px; margin-left: 26px;" src='<?php echo "components/com_userregistrationandmanagement/assets/img/responsive-icons.png" ?>'/>
                        </label>
                        <label for="horithemelarge" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="horithemelarge" onclick="singleImgShareProvider();" type="radio" <?php echo $horizontalThemeLarge; ?>value="2"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/single-image-theme-large.png" ?>'/>
                        </label>
                        <label for="horithemesmall" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="horithemesmall" onclick="singleImgShareProvider();" type="radio" <?php echo $horizontalThemeSmall; ?>value="3"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/single-image-theme-small.png" ?>'/>
                        </label>
                        <label for="chori16" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="chori16" onclick="createHorizontalCounterProvider();" type="radio" <?php echo $horizontalCounter16; ?>value="4"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/hybrid-horizontal-horizontal.png" ?>'/>
                        </label>
                        <label for="chori32" class="imagehori">
                            <input name="settings[choosehorizontalshare]" id="chori32" onclick="createHorizontalCounterProvider();" type="radio" <?php echo $horizontalCounter32; ?>value="5"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/hybrid-horizontal-vertical.png" ?>'/>
                        </label>
                        </div>

                        <!--socialshare position select-->
                        <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_POSITION'); ?></span><br/><br/>
                        <input name="settings[shareontoppos]" type="checkbox"  <?php echo $this->settings['shareontoppos']; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_POSITION_TOP'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[shareonbottompos]" type="checkbox"  <?php echo $this->settings['shareonbottompos']; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_POSITION_BOTTOM'); ?>
                    </div>

                    <!--select counter provider checkboxes-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                    if ($this->settings['choosehorizontalshare'] == '4' || $this->settings['choosehorizontalshare'] == '5') {
                        echo 'block';
                    } else {
                        echo 'none';
                    }
                    ?>;" id="osshorizontalcounterprovider">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                        <div id="counterhprovider" class="row_white"></div>
                    </div>

                    <!--select share provider checkboxes-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                    if (in_array($this->settings['choosehorizontalshare'], array('', '0', '1', '6'))) {
                        echo 'block';
                    } else {
                        echo 'none';
                    }
                    ?>;" id="osshorizontalshareprovider">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                        <div id="ossHorizontalSharingLimit"
                             style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
                        <div id="sharehprovider" class="row_white"></div>
                    </div>
                    <!--select rearrange icon for social share-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                    if (in_array($this->settings['choosehorizontalshare'], array('', '0', '1', '6'))) {
                        echo 'block';
                    } else {
                        echo 'none';
                    }
                    ?>;" id="osshorizontalsharerearange">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_REARRANGE'); ?></span><br/><br/>
                        <ul id="horsortable">
                    <?php                  
                    foreach ($this->settings['horizontal_rearrange'] as $horizontal_provider) {
                        ?>
                                <li title="<?php echo $horizontal_provider ?>"
                                    id="osshorizontal_<?php echo strtolower($horizontal_provider); ?>"
                                    class="ossshare_iconsprite32 ossshare_<?php echo strtolower($horizontal_provider); ?> dragcursor">
                                    <input type="hidden" name="horizontal_rearrange[]"
                                           value="<?php echo strtolower($horizontal_provider); ?>"/>
                                </li>
                    <?php } ?>
                        </ul>
                    </div>
                    <!--select page for socialshare-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;" id="horizontalPageSelect">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_ARTICLES'); ?></span><br/>
                        <label class="lrlabelselect"><input name="settings[horizontalarticaltype]" type="radio"  <?php echo $enableHorizontalArticalType; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_ARTICLE_ALL'); ?> </label>
                        <label class="lrlabelselect1"><input name="settings[horizontalarticaltype]" type="radio" <?php echo $disableHorizontalArticalType; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_ARTICLE_LIST'); ?></label> </div>
                    
                        <select id="horizontalArticles" name="horizontalArticles[]" multiple="multiple" style="width:400px;<?php if(empty($enableHorizontalArticalType)){echo 'display:none;';}?>">
                            <?php foreach ($this->articles as $row) {
                                ?>
                                <option <?php
                            if (!empty($this->settings['horizontalArticles'])) {
                                foreach ($this->settings['horizontalArticles'] as $key => $value) {
                                    if ($row->id == $value) {
                                        echo " selected=\"selected\"";
                                    }
                                }
                            }
                                ?>value="<?php echo $row->id; ?>">
                                <?php echo $row->title; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="sharevertical" style="display:none;">
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <!--enable vertical share-->
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_ENABLE_VERTICAL'); ?></span><br/><br/>
                        <input name="settings[sharevertical]" type="radio"  <?php echo $enableVerticalShare; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[sharevertical]" type="radio" <?php echo $disableVerticalShare; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <!--vertical socialshare theme-->
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_VERTICAL_THEMES'); ?></span><br/><br/>
                        <label for="vertibox32">
                            <input name="settings[chooseverticalshare]" id="vertibox32" onclick="createVerticalShareProvider();" type="radio"  <?php echo $verticalTheme32; ?> value="0"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/32VerticlewithBox.png" ?>' style="vertical-align:top;"/>
                        </label>
                        <label for="vertibox16">
                            <input name="settings[chooseverticalshare]" id="vertibox16" onclick="createVerticalShareProvider();" type="radio" <?php echo $verticalTheme16; ?>value="1"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/16VerticlewithBox.png" ?>' style="vertical-align:top;"/>
                        </label>
                        <label for="cvertibox32">
                            <input name="settings[chooseverticalshare]" id="cvertibox32" onclick="createVerticalCounterProvider();" type="radio"  <?php echo $verticalCounterTheme32; ?> value="2"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/hybrid-verticle-horizontal.png" ?>' style="vertical-align:top;"/>
                        </label>
                        <label for="cvertibox16">
                            <input name="settings[chooseverticalshare]" id="cvertibox16" onclick="createVerticalCounterProvider();" type="radio" <?php echo $verticalCounterTheme16; ?> value="3"/>
                            <img src='<?php echo "components/com_userregistrationandmanagement/assets/img/hybrid-verticle-vertical.png" ?>' style="vertical-align:top;"/>
                        </label>
                    </div>

                    <!--position for social share for vertical inter face-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <p style="margin:0 0 6px 0; padding:0px;"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_THEME_POSITION'); ?></span></p>
                        <input name="settings[verticalsharepos]" id="topleft" type="radio" <?php echo $topLeft; ?>value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_THEME_POSITION_TOPL'); ?><br/>
                        <input name="settings[verticalsharepos]" id="topright" type="radio" <?php echo $topRight; ?>value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_THEME_POSITION_TOPR'); ?> <br/>
                        <input name="settings[verticalsharepos]" id="bottomleft" type="radio" <?php echo $bottomLeft; ?>value="2"/> <?php echo JText::_('COM_SOCIAL_SHARE_THEME_POSITION_BOTTOML'); ?><br/>
                        <input name="settings[verticalsharepos]" id="bottomright" type="radio" <?php echo $bottomRight; ?>value="3"/> <?php echo JText::_('COM_SOCIAL_SHARE_THEME_POSITION_BOTTOMR'); ?><br/>
                    </div>
                    <!--select socialshare checkboxed for vertical interface-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                                    if ($this->settings['chooseverticalshare'] == '2' || $this->settings['chooseverticalshare'] == '3') {
                                        echo 'block';
                                    } else {
                                        echo 'none';
                                    }
                            ?>;" id="ossverticalcounterprovider">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                        <div id="countervprovider" class="row_white"></div>
                    </div>

                    <!--social share for vertical interface-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                    if ($this->settings['chooseverticalshare'] == '' || $this->settings['chooseverticalshare'] == '0' || $this->settings['chooseverticalshare'] == '1') {
                        echo 'block';
                    } else {
                        echo 'none';
                    }
                    ?>;" id="ossverticalshareprovider">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                        <div id="ossVerticalSharingLimit" style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
                        <div id="sharevprovider" class="row_white"></div>
                    </div>
                    <!--socialshare rearrange for vertical-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php
                    if ($this->settings['chooseverticalshare'] == '' || $this->settings['chooseverticalshare'] == '0' || $this->settings['chooseverticalshare'] == '1') {
                        echo 'block';
                    } else {
                        echo 'none';
                    }
                    ?>;" id="ossverticalsharerearange">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_REARRANGE'); ?></span><br/><br/>
                        <ul id="versortable">
                            <?php 
                            foreach ($this->settings['vertical_rearrange'] as $vertical_provider) {
                                ?>
                                <li title="<?php echo $vertical_provider ?>"
                                    id="ossvertical_<?php echo strtolower($vertical_provider); ?>"
                                    class="ossshare_iconsprite32 ossshare_<?php echo strtolower($vertical_provider); ?> dragcursor">
                                    <input type="hidden" name="vertical_rearrange[]" value="<?php echo strtolower($vertical_provider); ?>"/>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- select page for vertical share interface-->
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;" id="verticalPageSelect">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_ARTICLES'); ?></span><br/>
                        <label class="lrlabelselect"><input name="settings[verticalarticaltype]" type="radio"  <?php echo $enableVerticalArticalType; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_ARTICLE_ALL'); ?> </label>
                        <label class="lrlabelselect1"><input name="settings[verticalarticaltype]" type="radio" <?php echo $disableVerticalArticalType; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_ARTICLE_LIST'); ?> </label></div>
                    
                        <select id="verticalArticles" name="verticalArticles[]" multiple="multiple" style="width:400px;<?php if(empty($enableVerticalArticalType)){echo 'display:none;';}?>">
                            <?php
                            foreach ($this->articles as $row) {
                                ?>
                                <option <?php
                                if (!empty($this->settings['verticalArticles'])) {
                                    foreach ($this->settings['verticalArticles'] as $key => $value) {
                                        if ($row->id == $value) {
                                            echo " selected=\"selected\"";
                                        }
                                    }
                                }
                                ?>value="<?php echo $row->id; ?>">
                                    <?php echo $row->title; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="shareadvance" style="display:none;">
                   <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_MOBILE_FRIENDLY'); ?></span><br/><br/>
                        <input name="settings[mobilefriendly]" type="radio"  <?php echo $enableMobileFriendly; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[mobilefriendly]" type="radio" <?php echo $disableMobileFriendly; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_SHORT_URL'); ?></span><br/><br/>
                        <input name="settings[shorturl]" type="radio"  <?php echo $enableShortUrl; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[shorturl]" type="radio" <?php echo $disableShortUrl; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_SHARE_COUNT'); ?></span><br/><br/>
                        <input name="settings[sharecount]" type="radio"  <?php echo $enableShareCount; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[sharecount]" type="radio" <?php echo $disableShareCount; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_SINGLE_WINDOW'); ?></span><br/><br/>
                        <input name="settings[singlewindow]" type="radio"  <?php echo $enableSingleWindow; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[singlewindow]" type="radio" <?php echo $disableSingleWindow; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_CUSTOM_POPUP_SIZE'); ?></span><br/><br/>
                        <input name="settings[custompopup]" type="radio"  <?php echo $enableCustomPopup; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[custompopup]" type="radio" <?php echo $disableCustomPopup; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                        <div class="custompopup" style="display: <?php
                    if ($this->settings['custompopup'] == '1') {
                        echo 'block';
                    } else {
                        echo 'none';
                    }?>">
                            <br>
                            <input name="settings[popupheight]" placeholder="<?php echo JText::_('COM_SOCIAL_SHARE_HEIGHT'); ?>" type="text" value="<?php echo isset($this->settings['popupheight'])?$this->settings['popupheight']:'530';?>" style="width:20%;margin-left: 13px;"/>
                            <input name="settings[popupwidth]" placeholder="<?php echo JText::_('COM_SOCIAL_SHARE_WIDTH'); ?>" type="text" value="<?php echo isset($this->settings['popupwidth'])?$this->settings['popupwidth']:'530';?>" style="width:20%;margin-left: 3px;"/>
                        </div>
                    <div style="clear:both;"></div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_EMAIL_CONTENT_READ_ONLY'); ?></span><br/><br/>
                        <input name="settings[emailreadonly]" type="radio"  <?php echo $enableEmailReadOnly; ?> value="1"/> <?php echo JText::_('COM_SOCIAL_SHARE_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="settings[emailreadonly]" type="radio" <?php echo $disableEmailReadOnly; ?> value="0"/> <?php echo JText::_('COM_SOCIAL_SHARE_NO'); ?> </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_EMAIL_SUBJECT'); ?></span><br/><br/>
                        <input name="settings[emailsubject]" type="text" value="<?php echo isset($this->settings['emailsubject'])?$this->settings['emailsubject']:'';?>"/>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_EMAIL_MESSAGE'); ?></span><br/><br/>
                        <textarea name="settings[emailmessage]"><?php echo isset($this->settings['emailmessage'])?$this->settings['emailmessage']:'';?></textarea>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_TWITTER_MENTION'); ?></span><br/><br/>
                        <input name="settings[twittermention]" type="text" value="<?php echo isset($this->settings['twittermention'])?$this->settings['twittermention']:'';?>"/>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_TWITTER_HASH_TAG'); ?></span><br/><br/>
                        <input name="settings[twitterhashtag]" type="text" value="<?php echo isset($this->settings['twitterhashtag'])?$this->settings['twitterhashtag']:'';?>"/>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_FACEBOOK_APP_ID'); ?></span><br/><br/>
                        <input name="settings[facebookappid]" type="text" value="<?php echo isset($this->settings['facebookappid'])?$this->settings['facebookappid']:'';?>"/>
                    </div>
                    <div style="overflow:auto; background:#FFFFFF; padding:10px;">
                        <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIAL_SHARE_CUSTOM_OPTIONS'); ?></span><br/><br/>
                        <textarea name="settings[customoptions]"><?php echo isset($this->settings['customoptions'])?$this->settings['customoptions']:'';?></textarea>
                        <p><?php echo JText::_('COM_SOCIAL_SHARE_CUSTOM_OPTIONS_HINT'); ?></p>
                    </div>
                </div>
                </div>
            </td>
        </tr>
    </table>
        </div>

        <div class="section30">
            <!-- Help Box -->
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP'); ?></h3>
                <ul class="help_ul">
                    <li><a href="http://ish.re/9WC5" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_ONE'); ?></a></li>
                    <li><a href="http://ish.re/96M9" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_THREE'); ?></a></li>
                    <li><a href="http://ish.re/AEGF" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_FOUR'); ?></a></li>
                    <li><a href="http://ish.re/O1W0" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_DOCUMENTS_HELP_FIVE'); ?></a></li>
                </ul>
            </div>
            <div style="clear:both;"></div>
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_GET_UPDATE'); ?></h3>
                <p><?php echo JText::_('COM_SOCIALLOGIN_GET_UPDATE_TEXT'); ?> </p>
                 <p>
                    <a href="https://www.facebook.com/loginradius" target="_blank"><img src="components/com_userregistrationandmanagement/assets/img/facebook.png"></a>
                    <a href="https://twitter.com/LoginRadius" target="_blank"><img src="components/com_userregistrationandmanagement/assets/img/twitter.png"></a>
                    <a href="https://plus.google.com/+Loginradius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/google.png"></a>
                    <a href="https://www.linkedin.com/company/loginradius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/linkedin.png"></a>
                    <a href="https://www.youtube.com/user/LoginRadius" target="_blank"> <img src="components/com_userregistrationandmanagement/assets/img/youtube.png"></a>
                </p>
            </div>
            <div style="clear:both;"></div>
            <!-- Upgrade Box -->
            <div class="socialBox">
                <h3><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT'); ?></h3>
                <p>
                    <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT'); ?> <a
                        href='mailto:feedback@loginradius.com'>feedback@loginradius.com</a> !</p>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value=""/>
</form>