$(function () {
    $(".date-picker").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "-100:+10",
    });
});
CKEDITOR.timestamp=(new Date()).toISOString().replace(/T(\d\d):(\d\d):(\d\d).\d\d\dZ/gi, '')
CKEDITOR.replace('content', {
    customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
});