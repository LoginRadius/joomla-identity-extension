jQuery(document).ready(function () {    
    var showChar = 200;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    jQuery('.more').each(function() {
        var content = jQuery(this).html();
 
        if(content.length > showChar) { 
            var char = content.substr(0, showChar);
            var length = content.substr(showChar-1, content.length - showChar); 
            var html = char + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + length + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
            jQuery(this).html(html);
        } 
    });
 
    jQuery(".morelink").click(function() {
        if(jQuery(this).hasClass("less")) {
            jQuery(this).removeClass("less");
            jQuery(this).html(moretext);
        } else {
            jQuery(this).addClass("less");
            jQuery(this).html(lesstext);
        }
        jQuery(this).parent().prev().toggle();
        jQuery(this).prev().toggle();
        return false;
    });
    
    showAndHideUI();
    lrCheckValidJson();
   
});
 

function showAndHideUI() {
    var options = jQuery('.emailVerificationOptions:checked').val();
    if (options == 2) {
        jQuery('.enableLoginOnEmailVerification, .enablePromptPassword, .enableLoginWithUsername, .askEmailForUnverified').hide();
    } else if (options == 1) {        
        jQuery('.enableLoginOnEmailVerification, .askEmailForUnverified').show();
        jQuery('.enablePromptPassword, .enableLoginWithUsername').hide();        
    } else {
        jQuery('.enableLoginOnEmailVerification, .enablePromptPassword, .enableLoginWithUsername, .askEmailForUnverified').show();
    }
}

function lrCheckValidJson() {
    jQuery('#LoginRadius_customOption').change(function () {
        var profile = jQuery('#LoginRadius_customOption').val();
        var response = '';
        try
        {
            response = jQuery.parseJSON(profile);
            if (response != true && response != false) {
                var validjson = JSON.stringify(response, null, '\t').replace(/</g, '&lt;');
                if (validjson != 'null') {
                    jQuery('#LoginRadius_customOption').val(validjson);
                    jQuery('#LoginRadius_customOption').css("border", "1px solid green");
                } else {
                    jQuery('#LoginRadius_customOption').css("border", "1px solid red");
                }
            }
            else {
                jQuery('#LoginRadius_customOption').css("border", "1px solid green");
            }
        } catch (e)
        {
            jQuery('#LoginRadius_customOption').css("border", "1px solid green");
        }
    });
}
