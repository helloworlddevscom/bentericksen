import Cookies from 'js.cookie'

const { ASA_EXPIRATION } = window

let asaDate = new Date(ASA_EXPIRATION)
let today = new Date();
let timeDifference = asaDate.getTime() - today.getTime();
let dayDifference = timeDifference / (1000 * 3600 * 24);

if(dayDifference <= 30 && Cookies.get('asaNotice') != 'false') {
    Cookies.set('asaNotice', true);
    $("#modalRenewalNotice").show();
}

$("#renewalNoticeCancel").on('click', function() {
    Cookies.set('asaNotice', false, {expires: 1});
    $("#modalRenewalNotice").hide();
});