jQuery(document).ready(function () {
    //handleResponse(true, "");
    jQuery("#fade, #lr-loading").click(function () {
        jQuery('#fade, #lr-loading').hide();
    });

    showAndHideUI();
    LRObject = new LoginRadiusV2(ciamoption);
    LRObject.$hooks.register('startProcess', function () {
        jQuery('#lr-loading').show();
    });

    LRObject.$hooks.register('endProcess', function () {
        if (ciamoption.formRenderDelay) {
            setTimeout(function () {
                jQuery('#lr-loading').hide();
            }, ciamoption.formRenderDelay - 1);
        }
        jQuery('#lr-loading').hide();
      }
    );
    
    LRObject.$hooks.call('setButtonsName', {
        removeemail: "Remove"    
    });

    LRObject.$hooks.register('socialLoginFormRender', function () {
        //on social login form render
        jQuery('#lr-loading').hide();
        jQuery('#social-registration-form').show();
        show_birthdate_date_block();
    });

    LRObject.$hooks.register('afterFormRender', function (name) {     
        if (name == "socialRegistration") {
            show_birthdate_date_block();
            jQuery('#login-container').find('form[name=loginradius-socialRegistration]').parent().addClass('socialRegistration');
        } else if (name == 'registration' || name == 'login' || name == 'profileeditor') {
            show_birthdate_date_block();
        }
    });
    jQuery('#lr-loading').hide();
    initializeResetPasswordCiamForm(ciamoption);
});

function showRemoveEmailPopup(divid) {
    jQuery('#removeemail-form').show();
    initializeRemoveEmailCiamForms(divid);
    jQuery('#loginradius-removeemail-emailid').val(jQuery('#jformemail_' + divid).val());
}

function showAddEmailPopup() {
    jQuery('#addemail-form').show();
    initializeAddEmailCiamForms();
}

function lrCloseRemoveEmailPopup() {
    jQuery('form[name="loginradius-removeemail"]').remove();
    jQuery('#removeemail-form').hide();
}

function lrCloseAddEmailPopup() {
    jQuery('#addemail-form').hide();
}

function showAndHideUI() {
    var options = jQuery('input[name=lr_ciam_email_verification_condition]:checked').val();
    if (options == 2) {
        jQuery('.form-item-lr-ciam-enable-login-on-email-verification,.form-item-lr-ciam-prompt-password-on-social-login,.form-item-lr-ciam-enable-user-name,.form-item-lr-ciam-ask-email-always-for-unverified').hide();
    } else if (options == 1) {
        jQuery('.form-item-lr-ciam-enable-login-on-email-verification,.form-item-lr-ciam-ask-email-always-for-unverified').show();
        jQuery('.form-item-lr-ciam-prompt-password-on-social-login,.form-item-lr-ciam-enable-user-name').hide();
    } else {
        jQuery('.form-item-lr-ciam-enable-login-on-email-verification,.form-item-lr-ciam-prompt-password-on-social-login,.form-item-lr-ciam-ask-email-always-for-unverified,.form-item-lr-ciam-enable-user-name').show();
    }
}

function lrCheckValidJson() {
    jQuery('#add_custom_options').change(function () {
        var profile = jQuery('#add_custom_options').val();
        var response = '';
        try
        {
            response = jQuery.parseJSON(profile);
            if (response != true && response != false) {
                var validjson = JSON.stringify(response, null, '\t').replace(/</g, '&lt;');
                if (validjson != 'null') {
                    jQuery('#add_custom_options').val(validjson);
                    jQuery('#add_custom_options').css("border", "1px solid green");
                } else {
                    jQuery('#add_custom_options').css("border", "1px solid red");
                }
            } else {
                jQuery('#add_custom_options').css("border", "1px solid green");
            }
        } catch (e)
        {
            jQuery('#add_custom_options').css("border", "1px solid green");
        }
    });
}

function show_birthdate_date_block() {
    var maxYear = new Date().getFullYear();
    var minYear = maxYear - 100;
    if (jQuery('body').on) {
        jQuery('body').on('focus', '.loginradius-birthdate', function () {
            jQuery('.loginradius-birthdate').datepicker({
                dateFormat: 'mm-dd-yy',
                maxDate: new Date(),
                minDate: "-100y",
                changeMonth: true,
                changeYear: true,
                yearRange: (minYear + ":" + maxYear)
            });
        });
    } else {
        jQuery(".loginradius-birthdate").live("focus", function () {
            jQuery('.loginradius-birthdate').datepicker({
                dateFormat: 'mm-dd-yy',
                maxDate: new Date(),
                minDate: "-100y",
                changeMonth: true,
                changeYear: true,
                yearRange: (minYear + ":" + maxYear)
            });
        });
    }
}

function handleResponse(isSuccess, message, show, status) {
    status = status ? status : "status";
    if (typeof show != 'undefined' && !show) {
        jQuery('#fade').show();
    }
    if (isSuccess) {
        jQuery('form').each(function () {
            this.reset();
        });
    }
    if (message != null && message != "") {
        jQuery('#lr-loading').hide();
        jQuery('.messageinfo').text(message);
        jQuery(".messages").show();
        jQuery('.messageinfo').show();
        jQuery(".messages").removeClass("error status");
        jQuery(".messages").addClass(status);

    } else {
        jQuery(".messages").hide();
        jQuery('.messageinfo').hide();
        jQuery('.messageinfo').text("");
    }
}

function callSocialInterface() {
    var custom_interface_option = {};
    custom_interface_option.templateName = 'loginradiuscustom_tmpl';
    LRObject.customInterface(".interfacecontainerdiv", custom_interface_option);
    jQuery('#lr-loading').hide();
}

function initializeLoginCiamForm() {
    //initialize Login form
    var login_options = {};
    login_options.onSuccess = function (response) {
        ciamRedirect(response.access_token);
    };
    login_options.onError = function (response) {
        handleResponse(false, response[0].Description, "", "error");
    };
    login_options.container = "login-container";

    LRObject.init("login", login_options);

    jQuery('#lr-loading').hide();
}

function initializeRegisterCiamForm() {
    var registration_options = {}
    registration_options.onSuccess = function (response) {
        if (response.access_token != null && response.access_token != "") {
            handleResponse(true, "");
            ciamRedirect(response.access_token);
        } else {
            handleResponse(true, "An email has been sent to " + jQuery("#loginradius-registration-emailid").val() + ".Please verify your email address");
            window.setTimeout(function () {
                window.location.replace(homeDomain);
            }, 7000);
        }
    };
    registration_options.onError = function (response) {
        if (response[0].Description != null) {
            handleResponse(false, response[0].Description, "", "error");
        }
    };
    registration_options.container = "registration-container";
    LRObject.init("registration", registration_options);

    jQuery('#lr-loading').hide();
}

function initializeResetPasswordCiamForm(ciamoption) {
    //initialize reset password form and handel email verifaction
    var vtype = LRObject.util.getQueryParameterByName("vtype");
    if (vtype != null && vtype != "") {
        if (vtype == "reset") {
            var resetpassword_options = {};
            resetpassword_options.container = "resetpassword-container";
            jQuery('#login-container').hide();
            jQuery('.interfacecontainerdiv').hide();
            jQuery('#interfaceLabel').hide();
            jQuery('#resetpassword-container').show();
            resetpassword_options.onSuccess = function (response) {
                handleResponse(true, "Password reset successfully");
                window.setTimeout(function () {
                    window.location.replace(ciamoption.verificationUrl);
                }, 5000);
            };
            resetpassword_options.onError = function (errors) {
                handleResponse(false, errors[0].Description, "", "error");
            }
            LRObject.util.ready(function () {
                LRObject.init("resetPassword", resetpassword_options);
            });
        } else if (vtype == "emailverification") {
            var verifyemail_options = {};
            verifyemail_options.onSuccess = function (response) {
                if (typeof response != 'undefined') {
                    if (!loggedIn && ciamoption.loginOnEmailVerification && typeof response.access_token != "undefined" && response.access_token != null && response.access_token != "") {
                        ciamRedirect(response.access_token);
                    } else if (!loggedIn && ciamoption.loginOnEmailVerification && response.Data != null && response.Data.access_token != null && response.Data.access_token != "") {
                        ciamRedirect(response.Data.access_token);
                    } else {
                        lrSetCookie('lr_message', 'Your email has been verified successfully');
                        window.location.href = window.location.href.split('?')[0] + '?lrmessage=true&response=success';
                    }
                }
            };
            verifyemail_options.onError = function (errors) {
                lrSetCookie('lr_message', errors[0].Description);
                window.location.href = window.location.href.split('?')[0] + '?lrmessage=true&response=error';
            }

            LRObject.util.ready(function () {
                LRObject.init("verifyEmail", verifyemail_options);
            });
        }
    }
}

function initializeSocialRegisterCiamForm() {
    var sl_options = {};
    sl_options.onSuccess = function (response) {
        if (response.access_token != null && response.access_token != "") {
            handleResponse(false, "");
            ciamRedirect(response.access_token);
            jQuery('#lr-loading').hide();
        } else if (response.IsPosted) {
            handleResponse(true, "An email has been sent to " + jQuery("#loginradius-socialRegistration-emailid").val() + ".Please verify your email address.");
            jQuery('#social-registration-form').hide();
            jQuery('#lr-loading').hide();
        }
    };
    sl_options.onError = function (response) {
        if (response[0].Description != null) {
            handleResponse(false, response[0].Description, "", "error");
            jQuery('#social-registration-form').hide();
            jQuery('#lr-loading').hide();
        }
    };
    sl_options.container = "social-registration-container";

    LRObject.util.ready(function () {
        LRObject.init('socialLogin', sl_options);
        jQuery('#lr-loading').show();
    });
    jQuery('#lr-loading').hide();
}

function initializeForgotPasswordCiamForms() {
    //initialize forgot password form
    var forgotpassword_options = {};
    forgotpassword_options.container = "forgotpassword-container";
    forgotpassword_options.onSuccess = function (response) {
        handleResponse(true, "An email has been sent to " + jQuery("#loginradius-forgotpassword-emailid").val() + " with reset password link");
        window.setTimeout(function () {
            window.location.replace(homeDomain);
        }, 7000);
    };
    forgotpassword_options.onError = function (response) {
        if (response[0].Description != null) {
            handleResponse(false, response[0].Description, "", "error");
        }
    }
    LRObject.util.ready(function () {
        LRObject.init("forgotPassword", forgotpassword_options);
    });
    jQuery('#lr-loading').hide();
}

function initializeProfileEditorCiamForm() {
    //initialize forgot password form
    var profileeditor_options = {};
    profileeditor_options.container = "profileeditor-container";
    profileeditor_options.onSuccess = function (response) {
        handleResponse(false, "Profile has been updated successfully.");  
        window.setTimeout(function () {
            window.location.reload();
        }, 1000);
    };
    profileeditor_options.onError = function (response) {     
        handleResponse(false, response[0].Description, "", "error");
    };
    LRObject.util.ready(function () {
        LRObject.init("profileEditor", profileeditor_options);
    })
    jQuery('#lr-loading').hide();
}

function initializeAccountLinkingCiamForms() {
    var la_options = {};
    la_options.container = "interfacecontainerdiv";
    la_options.templateName = 'loginradiuscustom_tmpl_link';
    la_options.onSuccess = function (response) {
        if (response.IsPosted != true) {
            handleResponse(true, "");
            ciamRedirect(response);
        } else {
            handleResponse(true, "Account linked successfully.");
            window.setTimeout(function () {
                window.location.reload();
            }, 3000);

        }
    };
    la_options.onError = function (errors) {
        if (errors[0].Description != null) {
            handleResponse(false, errors[0].Description, "", "error");
        }
    }

    var unlink_options = {};
    unlink_options.onSuccess = function (response) {
        if (response.IsDeleted == true) {
            handleResponse(true, "Account unlinked successfully.");
            window.setTimeout(function () {
                window.location.reload();
            }, 3000);
        }
    };
    unlink_options.onError = function (errors) {
        if (errors[0].Description != null) {
            handleResponse(false, errors[0].Description, "", "error");
        }
    }

    LRObject.util.ready(function () {
        LRObject.init("linkAccount", la_options);
        LRObject.init("unLinkAccount", unlink_options);
    });
    jQuery('#lr-loading').hide();
}

function initializeAddEmailCiamForms() {
    var addemail_options = {};
    addemail_options.container = "addemail-container";
    addemail_options.onSuccess = function (response) {
        jQuery('#addemail-form').hide();
        handleResponse(false, "Email added successfully, Please verify your email address.");
    };
    addemail_options.onError = function (response) {
        jQuery('#addemail-form').hide();
        handleResponse(false, response[0].Description, "", "error");
    };
    LRObject.util.ready(function () {
        LRObject.init("addEmail", addemail_options);
    });
    jQuery('#lr-loading').hide();
}

function initializeRemoveEmailCiamForms(divid) {
    var removeemail_options = {};
    removeemail_options.container = "removeemail-container";
    removeemail_options.onSuccess = function (response) {
        jQuery('#removeemail-form').hide();
        handleResponse(false, "Email removed successfully");
        var html = jQuery('#emaillist_' + divid);
        html.remove();
        window.setTimeout(function () {
            window.location.reload();
        }, 2000);
    };
    removeemail_options.onError = function (response) {
        jQuery('#removeemail-form').hide();
        handleResponse(false, response[0].Description, "", "error");
    };
    LRObject.util.ready(function () {
        LRObject.init("removeEmail", removeemail_options);
    });
    jQuery('#lr-loading').hide();
}

function initializeChangePasswordCiamForms() {
    var changepassword_options = {};
    changepassword_options.container = "changepassword-container";
    changepassword_options.onSuccess = function (response) {
        handleResponse(true, "Password has been updated successfully");
    };
    changepassword_options.onError = function (errors) {
        handleResponse(false, errors[0].Description, "", "error");
    };

    LRObject.util.ready(function () {
        LRObject.init("changePassword", changepassword_options);
    });
    jQuery('#lr-loading').hide();
}

function ciamRedirect(token, name) {    
    if (window.redirect) {
        redirect(token, name);
    } else {
        var token_name = name ? name : 'token';
        var source = typeof lr_source != 'undefined' && lr_source ? lr_source : '';

        var form = document.createElement('form');

        form.action = homeDomain;
        form.method = 'POST';

        var hiddenToken = document.createElement('input');
        hiddenToken.type = 'hidden';
        hiddenToken.value = token;
        hiddenToken.name = token_name;
        form.appendChild(hiddenToken);

        document.body.appendChild(form);
        form.submit();
    }
}

function lrSetCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}