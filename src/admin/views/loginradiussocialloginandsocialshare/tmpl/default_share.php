<?php
/**
 * @package     LoginRadiusSocialLoginandSocialShare.Plugin
 * @subpackage  com_loginradiussocialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//Social Share option
$topLeft = $topRight = $bottomLeft = $bottomRight = $shareHorizontalTheme32 = $shareHorizontalTheme16 = $shareHorizontalThemeLargeImg = $shareHorizontalThemeSmallImg = $shareHorizontalResponsive = $shareHorizontalThemeCounter32 = $shareHorizontalThemeCounter16 = $shareVerticalTheme32 = $shareVerticalTheme16 = $shareVerticalThemeCounter32 = $shareVerticalThemeCounter16 = $enableHorizontalShare = $disableHorizontalShare = $enableVerticalShare = $disableVerticalShare = "";
//Social Share option
switch ($this->settings['verticalsharepos'])
{
    case 1:
        $topRight = "checked='checked'";
    break;
    case 2:
        $bottomLeft = "checked='checked'";
    break;
    case 3:
        $bottomRight = "checked='checked'";
    break;
    default :
    case 0:
        $topLeft = "checked='checked'";
    break;
}

switch ($this->settings['choosehorizontalshare'])
{
    case 1:
        $shareHorizontalTheme16 = "checked='checked'";
    break;
    case 2:
        $shareHorizontalThemeLargeImg = "checked='checked'";
    break;
    case 3:
        $shareHorizontalThemeSmallImg = "checked='checked'";
    break;
    case 4:
        $shareHorizontalThemeCounter16 = "checked='checked'";
    break;
    case 5:
        $shareHorizontalThemeCounter32 = "checked='checked'";
    break;
    case 6:
        $shareHorizontalResponsive = "checked='checked'";
    break;
    default :
    case 0:
        $shareHorizontalTheme32 = "checked='checked'";
    break;
}

switch ($this->settings['chooseverticalshare'])
{
    case 1:
        $shareVerticalTheme16 = "checked='checked'";
    break;
    case 2:
        $shareVerticalThemeCounter32 = "checked='checked'";
    break;
    case 3:
        $shareVerticalThemeCounter16 = "checked='checked'";
    break;
    default :
    case 0:
        $shareVerticalTheme32 = "checked='checked'";
    break;
}

if ($this->settings['sharehorizontal'] == '1')
{
    $enableHorizontalShare = "checked='checked'";
} else if ($this->settings['sharehorizontal'] == '0')
{
    $disableHorizontalShare = "checked='checked'";
} else
{
    $enableHorizontalShare = "checked='checked'";
}

if ($this->settings['sharevertical'] == '1')
{
    $enableVerticalShare = "checked='checked'";
} else if ($this->settings['sharevertical'] == '0')
{
    $disableVerticalShare = "checked='checked'";
} else
{
    $enableVerticalShare = "checked='checked'";
}
?>
<div>
    <table class="form-table sociallogin_table">
        <tr>
            <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SOCIALSHARE_SETTING'); ?></th>
        </tr>
        <tr class="sociallogin_row_white">
            <td colspan="2">
                <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME'); ?></span><br/><br/>
                <a id="mymodal1" href="javascript:void(0);" onclick="makeHorizontalVisible();" style="color: #00CCFF;"><b><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORI'); ?></b></a> &nbsp;|&nbsp;
                <a id="mymodal2" href="javascript:void(0);" onclick="makeVerticalVisible();" style="color: #000000;"><b><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_VERTICAL'); ?></b></a>
                <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 0;">
                    <span id="arrow" class="horizontal"></span>
                    <div id="sharehorizontal" style="display:block;">

                        <div style="overflow:auto; padding:10px;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORIZONTAL'); ?></span><br><br/>
                            <input name="settings[sharehorizontal]" type="radio"  <?php echo $enableHorizontalShare; ?> value="1"/> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="settings[sharehorizontal]" type="radio" <?php echo $disableHorizontalShare; ?> value="0"/> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> </div>
                        <div style="overflow:auto; background:#FFFFFF; padding:10px;"><span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORIZONTAL_THEMES'); ?></span><br/><br/>

                            <!--socialsharing interface theme-->
                            <label for="hori32">
                                <input name="settings[choosehorizontalshare]" id="hori32" onclick="createHorzontalShareProvider();" type="radio" <?php echo $shareHorizontalTheme32; ?>value="0" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/horizonSharing32.png" ?>'/>
                            </label><br/>
                            <label for="hori16">
                                <input name="settings[choosehorizontalshare]" id="hori16" onclick="createHorzontalShareProvider();" type="radio" <?php echo $shareHorizontalTheme16; ?>value="1" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/horizonSharing16.png" ?>'/>
                            </label><br/>
                            <label for="responsive">
                                <input name="settings[choosehorizontalshare]" id="responsive" onclick="createHorzontalShareProvider();" type="radio" <?php echo $shareHorizontalResponsive; ?>value="6" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/responsive-icons.png" ?>'/>
                            </label><br/>
                            <label for="horithemelarge">
                                <input name="settings[choosehorizontalshare]" id="horithemelarge" onclick="singleImgShareProvider();" type="radio" <?php echo $shareHorizontalThemeLargeImg; ?>value="2" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/single-image-theme-large.png" ?>'/>
                            </label><br/>
                            <label for="horithemesmall">
                                <input name="settings[choosehorizontalshare]" id="horithemesmall" onclick="singleImgShareProvider();" type="radio" <?php echo $shareHorizontalThemeSmallImg; ?>value="3" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/single-image-theme-small.png" ?>'/>
                            </label><br/>
                            <label for="chori16">
                                <input name="settings[choosehorizontalshare]" id="chori16" onclick="createHorizontalCounterProvider();" type="radio" <?php echo $shareHorizontalThemeCounter16; ?>value="4" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/hybrid-horizontal-horizontal.png" ?>'/>
                            </label><br/>
                            <label for="chori32">
                                <input name="settings[choosehorizontalshare]" id="chori32" onclick="createHorizontalCounterProvider();" type="radio" <?php echo $shareHorizontalThemeCounter32; ?>value="5" style="vertical-align:middle;"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/hybrid-horizontal-vertical.png" ?>'/>
                            </label>
                        </div>

                        <!--socialshare position select-->
                        <div style="overflow:auto; padding:10px;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION'); ?></span><br/><br/>
                            <input name="settings[shareontoppos]" type="checkbox"  <?php echo $this->settings['shareontoppos']; ?> value="1"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_TOP'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="settings[shareonbottompos]" type="checkbox"  <?php echo $this->settings['shareonbottompos']; ?> value="1"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_BOTTOM'); ?></div>

                        <!--select counter provider checkboxes-->
                        <div style="overflow:auto; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['choosehorizontalshare'], array('4', '5')); ?>;" id="lrhorizontalcounterprovider">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                            <div id="counterhprovider" class="row_white"></div>
                        </div>

                        <!--select share provider checkboxes-->
                        <div style="overflow:auto; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['choosehorizontalshare'], array('', '0', '1', '6')); ?>;" id="lrhorizontalshareprovider">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                            <div id="loginRadiusHorizontalSharingLimit" style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIALLOGIN_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
                            <div id="sharehprovider" class="row_white"></div>
                        </div>
                        <div style="overflow:auto; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['choosehorizontalshare'], array('', '0', '1', '6')); ?>;" id="lrhorizontalsharerearange">
                            <!--select rearrange icon for social share-->
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_REARRANGE'); ?></span><br><br/>
                            <ul id="horsortable" style="float:left; padding-left:0;margin: 0;">
                                <?php foreach ($this->settings['horizontalrearrange'] as $horizontalprovider)
                                { ?>
                                    <li title="<?php echo $horizontalprovider ?>" id="lrhorizontal_<?php echo strtolower($horizontalprovider); ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($horizontalprovider); ?> dragcursor">
                                        <input type="hidden" name="horizontalrearrange[]" value="<?php echo strtolower($horizontalprovider); ?>"/>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <!--select page for socialsharing-->
                        <div style="overflow:auto; padding:10px;" id="horizontalPageSelect">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES'); ?></span><br/><br/>
                            <select id="horizontalarticles[]" name="horizontalarticles[]" multiple="multiple" style="width:400px;">
                            <?php foreach ($this->articles as $row)
                            { ?>
                                <option <?php
                                    if (!empty($this->settings['horizontalarticles']))
                                    {
                                        foreach ($this->settings['horizontalarticles'] as $key => $value)
                                        {
                                            if ($row->id == $value)
                                            {
                                                echo " selected=\"selected\"";
                                            }
                                        }
                                    }
                                    ?>value="<?php echo $row->id; ?>">
                                <?php echo $row->title; ?>
                                </option>
                            <?php } ?>
                            </select></div>
                    </div>
                    <div id="sharevertical" style="display:none;">
                        <div style="overflow:auto; padding:10px;">

                            <!--enable vertical sharing-->
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ENABLE_VERTICAL'); ?></span><br><br/>
                            <input name="settings[sharevertical]" type="radio"  <?php echo $enableVerticalShare; ?> value="1"/> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="settings[sharevertical]" type="radio" <?php echo $disableVerticalShare; ?> value="0"/> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> </div>
                        <!--vertical socialshare theme-->
                        <div id="verticalsharingtheme" style="overflow:auto; padding:10px;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_VERTICAL_THEMES'); ?></span><br/><br/>
                            <label for="vertibox32">
                                <input name="settings[chooseverticalshare]" id="vertibox32" onclick="createVerticalShareProvider();" type="radio"  <?php echo $shareVerticalTheme32; ?> value="0"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/32VerticlewithBox.png" ?>' style="vertical-align:top;"/>
                            </label>
                            <label for="vertibox16">
                                <input name="settings[chooseverticalshare]" id="vertibox16" onclick="createVerticalShareProvider();" type="radio" <?php echo $shareVerticalTheme16; ?> value="1"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/16VerticlewithBox.png" ?>' style="vertical-align:top;"/>
                            </label>
                            <label for="cvertibox32">
                                <input name="settings[chooseverticalshare]" id="cvertibox32" onclick="createVerticalCounterProvider();" type="radio"  <?php echo $shareVerticalThemeCounter32; ?> value="2"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/hybrid-verticle-horizontal.png" ?>' style="vertical-align:top;"/>
                            </label><label for="cvertibox16">
                                <input name="settings[chooseverticalshare]" id="cvertibox16" onclick="createVerticalCounterProvider();" type="radio" <?php echo $shareVerticalThemeCounter16; ?> value="3"/>
                                <img src='<?php echo "components/com_loginradiussocialloginandsocialshare/assets/img/hybrid-verticle-vertical.png" ?>' style="vertical-align:top;"/>
                            </label>
                        </div>
                        <!--position for social sharing for vertical inter face-->
                        <div style="overflow:auto; padding:10px;clear: both;">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION'); ?></span><br><br>
                            <input name="settings[verticalsharepos]" id="topleft" type="radio" <?php echo $topLeft; ?>value="0"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_TOPL'); ?>
                            <br/>
                            <input name="settings[verticalsharepos]" id="topright" type="radio" <?php echo $topRight; ?>value="1"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_TOPR'); ?>
                            <br/>
                            <input name="settings[verticalsharepos]" id="bottomleft" type="radio" <?php echo $bottomLeft; ?>value="2"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_BOTTOML'); ?>
                            <br/>
                            <input name="settings[verticalsharepos]" id="bottomright" type="radio" <?php echo $bottomRight; ?>value="3"/> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_BOTTOMR'); ?>
                            <br/></div>
                        <!--select socialsharing checkboxed for vertical interface-->
                        <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['chooseverticalshare'], array('2', '3')); ?>;" id="lrverticalcounterprovider">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                            <div id="countervprovider" class="row_white"></div>
                        </div>
                        <!--social sharing for vertical interface-->
                        <div style="overflow:auto; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['chooseverticalshare'], array('', '1', '0')); ?>;" id="lrverticalshareprovider">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br/><br/>
                            <div id="loginRadiusVerticalSharingLimit" style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIALLOGIN_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
                            <div id="sharevprovider" class="row_white"></div>
                        </div>
                        <!--socialsharing rearrange for vertical-->
                        <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php echo LoginRadiusSocialLoginAndSocialShareModelLoginRadiusSocialLoginAndSocialShare::selectDisplaySection($this->settings['chooseverticalshare'], array('', '1', '0')); ?>;" id="lrverticalsharerearange">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_REARRANGE'); ?></span><br><br/>
                            <ul id="versortable" style="float:left; padding-left:0;margin: 0;">
                            <?php
                            foreach ($this->settings['verticalrearrange'] as $verticalprovider)
                            {
                                ?>
                                    <li title="<?php echo $verticalprovider ?>"
                                        id="lrvertical_<?php echo strtolower($verticalprovider); ?>"
                                        class="lrshare_iconsprite32 lrshare_<?php echo strtolower($verticalprovider); ?> dragcursor">
                                        <input type="hidden" name="verticalrearrange[]" value="<?php echo strtolower($verticalprovider); ?>"/>
                                    </li>
                                <?php
                            }
                            ?>
                            </ul>
                        </div>

                        <!-- select page for vertical sharing interface-->
                        <div style="overflow:auto;padding:10px;" id="verticalPageSelect">
                            <span class="sociallogin_subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES'); ?></span><br/><br/>
                            <select id="verticalarticles[]" name="verticalarticles[]" multiple="multiple" style="width:400px;">
                            <?php foreach ($this->articles as $row)
                            { ?>
                                    <option <?php
                                    if (!empty($this->settings['verticalarticles']))
                                    {
                                        foreach ($this->settings['verticalarticles'] as $key => $value)
                                        {
                                            if ($row->id == $value)
                                            {
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
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>