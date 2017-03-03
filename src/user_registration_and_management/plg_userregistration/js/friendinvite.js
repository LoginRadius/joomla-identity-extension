var loginRadiusReferralSubmit = null;
function loginRadiusCheckAll(lrform, check) {
    var lrCheckList = lrform.elements['loginRadiusContacts[]'];
    if (typeof lrCheckList.length == "undefined") {
        lrCheckList.checked = (check ? 'checked' : '');
    } else {
        for (var i = 0; i < lrCheckList.length; i++) {
            lrCheckList[i].checked = (check ? 'checked' : '');
        }
    }
}
function LoginRadiusReferralValidate(lrForm) {
    if (loginRadiusReferralSubmit === null || loginRadiusReferralSubmit == "Cancel") {
        return true;
    }
    var lrCheckList = lrForm.elements['loginRadiusContacts[]'];
    var lrValid = false;
    if (typeof lrCheckList.length != "undefined") {
        for (var i = 0; i < lrCheckList.length; i++) {
            if (lrCheckList[i].checked === true) {
                lrValid = true;
                break;
            }
        }
    } else {
        if (lrCheckList.checked === true) {
            lrValid = true;
        }
    }
    if (!lrValid) {
        var loginRadiusErrorDiv = document.getElementById('loginRadiusError');
        loginRadiusErrorDiv.innerHTML = "Please select the contacts to send referral to.";
        loginRadiusErrorDiv.style.display = "block";
        document.getElementById('loginRadiusMiddiv').scrollTop = 0;
        return false;
    }
    return true;
}
