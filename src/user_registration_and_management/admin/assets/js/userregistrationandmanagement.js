function showAndHideUI() {
    var options = jQuery('.emailVerificationOptions:checked').val();
    if (options == 2) {
        jQuery('.enableLoginOnEmailVerification').hide();
        jQuery('.enablePromptPassword').hide();
        jQuery('.enableLoginWithUsername').hide();
        jQuery('.askEmailForUnverified').hide();
    } else if (options == 1) {
        jQuery('.enableLoginOnEmailVerification').show();
        jQuery('.enablePromptPassword').hide();
        jQuery('.enableLoginWithUsername').hide();
        jQuery('.askEmailForUnverified').show();
    } else {
        jQuery('.enableLoginOnEmailVerification').show();
        jQuery('.enablePromptPassword').show();
        jQuery('.askEmailForUnverified').show();
        jQuery('.enableLoginWithUsername').show();
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
jQuery(document).ready(function () {
    showAndHideUI();
    lrCheckValidJson();
});