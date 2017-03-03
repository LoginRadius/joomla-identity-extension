 /**
 * @param elem
 */
function ossHorizontalRearrangeProviderList(elem) {
    if (elem.checked) {
        var ul = '<li title="'+elem.value+'" id="osshorizontal_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" class="ossshare_iconsprite32 ossshare_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="hidden" name="horizontal_rearrange[]" value="'+elem.value+'"></li>';
        jQuery('#horsortable').append(ul);
    }
    else {
    if (jQuery('#osshorizontal_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,''))) {
        jQuery('#osshorizontal_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).remove();
        }
    }
}

/**
 * @param elem
 */
function ossVerticalRearrangeProviderList(elem) {
    if (elem.checked) {
        var ul = '<li title="'+elem.value+'" id="ossvertical_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" class="ossshare_iconsprite32 ossshare_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="hidden" name="vertical_rearrange[]" value="'+elem.value+'"></li>';
        jQuery('#versortable').append(ul);
    }
    else {
        if (jQuery('#ossvertical_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,''))) {
            jQuery('#ossvertical_' + elem.value.toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).remove();
        }
    }
}

/**
 * @param elem
 */
function ossVerticalSharingLimit(elem) {
    var provider = $("#sharevprovider").find(":checkbox");
    var checkCount = 0;
    for (var i = 0; i < provider.length; i++) {
        if (provider[i].checked) {
            // count checked providers
            checkCount++;
            if (checkCount >= 10) {
                elem.checked = false;
                $("#ossVerticalSharingLimit").show('slow');
                setTimeout(function () {
                    $("#ossVerticalSharingLimit").hide('slow');
                }, 5000);
                return;
            }
        }
    }
}

/**
 * @param elem
 */
function ossHorizontalSharingLimit(elem) {
    var provider = $("#sharehprovider").find(":checkbox");
    var checkCount = 0;
    for (var i = 0; i < provider.length; i++) {
        if (provider[i].checked) {
            // count checked providers
            checkCount++;
            if (checkCount >= 10) {
                elem.checked = false;
                $("#ossHorizontalSharingLimit").show('slow');
                setTimeout(function () {
                    $("#ossHorizontalSharingLimit").hide('slow');
                }, 5000);
                return;
            }
        }
    }
}

/**
 * select counter in checkbox and rearrange
 */
function createHorzontalShareProvider() {
    jQuery('#osshorizontalshareprovider,#osshorizontalsharerearange').show();
    jQuery('#osshorizontalcounterprovider').hide();
}

/**
 * single image in provider
 */
function singleImgShareProvider() {
    jQuery('#osshorizontalshareprovider,#osshorizontalsharerearange,#osshorizontalcounterprovider').hide();
}

/**
 * select counter in checkbox
 */
function createHorizontalCounterProvider() {
    jQuery('#osshorizontalcounterprovider').show();
    jQuery('#osshorizontalsharerearange,#osshorizontalshareprovider').hide();
}

/**
 * select vertical sharing provider in checkbox
 */
function createVerticalShareProvider() {
    jQuery('#ossverticalshareprovider,#ossverticalsharerearange').show();
    jQuery('#ossverticalcounterprovider').hide();
}

/**
 * select counter in checkbox
 */
function createVerticalCounterProvider() {
    jQuery('#ossverticalcounterprovider').show();
    jQuery('#ossverticalsharerearange,#ossverticalshareprovider').hide();
}

/**
 * select vertical interface in sharing
 */
function makeVerticalVisible() {
    jQuery('#sharevertical').show();
    jQuery('#sharehorizontal,#shareadvance').hide();
    jQuery('#arrow').addClass("vertical");
    jQuery('#arrow').removeClass("advance");
    jQuery('#arrow').removeClass("horizontal");
    jQuery('#mymodal2').css("color", "#00CCFF");
    jQuery('#mymodal1, #mymodal3').css("color", "#000000");
}

/**
 * select horizontal interface in sharing
 */
function makeHorizontalVisible() {
    jQuery('#sharehorizontal').show();
    jQuery('#sharevertical,#shareadvance').hide();
    jQuery('#arrow').removeClass("vertical");
    jQuery('#arrow').removeClass("advance");
    jQuery('#arrow').addClass("horizontal");
    jQuery('#mymodal1').css("color", "#00CCFF");
    jQuery('#mymodal2,#mymodal3').css("color", "#000000");
}
/**
 * select advance interface in sharing
 */
function makeAdvanceVisible() {
    jQuery('#shareadvance').show();
    jQuery('#sharevertical,#sharehorizontal').hide();
    jQuery('#arrow').removeClass("vertical");
    jQuery('#arrow').removeClass("horizontal");
    jQuery('#arrow').addClass("advance");
    jQuery('#mymodal3').css("color", "#00CCFF");
    jQuery('#mymodal2,#mymodal1').css("color", "#000000");
}

jQuery(document).ready(function(){
    jQuery('input[name=\'settings[custompopup]\']').click(function(){
        if(jQuery(this).val() == '1'){
            jQuery('.custompopup').show();
        }else{
            jQuery('.custompopup').hide();
        }
    });
    var shareProvider = ['Facebook','GooglePlus','LinkedIn','Twitter','Pinterest','Email','Google','Digg','Reddit','Vkontakte','Tumblr','MySpace','Delicious','Print'];
    var counterProvider = ['Facebook Like','Facebook Recommend','Facebook Send','Twitter Tweet','Pinterest Pin it','LinkedIn Share','StumbleUpon Badge','Reddit','Google+ +1','Google+ Share'];
    
    for (var i = 0; i < shareProvider.length; i++) {
        var sharehdiv = '<div class="ossProviders" style="padding:5px !important;"><label class="socialTitle" for="horizontalsharingid'+shareProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="checkbox" onchange="ossHorizontalSharingLimit(this);ossHorizontalRearrangeProviderList(this);" id="horizontalsharingid'+shareProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" value="'+shareProvider[i]+'" style="float: left !important;">'+shareProvider[i]+'</label> </div>';
        jQuery("#sharehprovider").append(sharehdiv);

        var sharevdiv = '<div class="ossProviders"><label class="socialTitle" for="verticalsharingid'+shareProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="checkbox" onchange="ossVerticalSharingLimit(this);ossVerticalRearrangeProviderList(this);" id="verticalsharingid'+shareProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" value="'+shareProvider[i]+'" style="float: left !important;">'+shareProvider[i]+'</label> </div>';
        jQuery("#sharevprovider").append(sharevdiv);

    }
    for (var i = 0; i < counterProvider.length; i++) {
        var counterhdiv = '<div class="ossCounterProviders" style="padding:5px !important;"><label class="socialTitle" for="horizontalcounterid'+counterProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="checkbox" id="horizontalcounterid'+counterProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" value="'+counterProvider[i]+'" name="horizontalcounter[]" style="float: left !important;">'+counterProvider[i]+'</label> </div>';
        jQuery("#counterhprovider").append(counterhdiv);

        var countervdiv = '<div class="ossProviders"><label class="socialTitle" for="verticalcounterid'+counterProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'"><input type="checkbox" id="verticalcounterid'+counterProvider[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')+'" value="'+counterProvider[i]+'" name="verticalcounter[]" style="float: left !important;">'+counterProvider[i]+'</label> </div>';
        jQuery("#countervprovider").append(countervdiv);

    }
    for (var i = 0; i < horshareChecked.length; i++) {
        jQuery('#horizontalsharingid' + horshareChecked[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).attr("checked","checked");
    }
    for (var i = 0; i < vershareChecked.length; i++) {
        jQuery('#verticalsharingid' + vershareChecked[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).attr("checked","checked");
    }
    for (var i = 0; i < horcounterChecked.length; i++) {
        jQuery('#horizontalcounterid' + horcounterChecked[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).attr("checked","checked");
    }
    for (var i = 0; i < vercounterChecked.length; i++) {
        jQuery('#verticalcounterid' + vercounterChecked[i].toLowerCase().replace(/\ /gi,'').replace(/\+/gi,'')).attr("checked","checked");
    }
    articalType('horizontal');
    articalType('vertical');
    jQuery('input[name="settings[horizontalarticaltype]"]').click(function(){
        articalType('horizontal');
    });
    jQuery('input[name="settings[verticalarticaltype]"]').click(function(){
        articalType('vertical');
    });
});

function articalType(shareTheme){
    if(jQuery('input[name="settings['+shareTheme+'articaltype]"]:checked').val() == '0'){
        jQuery('#'+shareTheme+'Articles').show();
    }else{
        jQuery('#'+shareTheme+'Articles').hide();
    }
}