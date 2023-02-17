window.ck_info.enableLite = false;

CKEDITOR.replace('description', {
  customConfig: '/assets/js/plugins/ckeditor/admin_config.js',
  height: 400
})

window.nanospell.ckeditor('description', {
    dictionary: "en",
    server: "php"
})