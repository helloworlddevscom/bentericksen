/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

/* global CKEDITOR */

CKEDITOR.editorConfig = function (config) {
  'use strict';

  var ckePlugins = ['list', 'table', 'div', 'tabletools', 'contextmenu', 'maximize', 'dialogui', 'dialog', 'dialogadvtab'];

  if (window.ck_info && window.ck_info.enableLite === true) {
      ckePlugins.push('lite');
  }

  // This is the default CKEditor button config for non-admins.
  // The admin pages (e.g., Policy Template editor) use a different config, stored in admin_config.js.
  config.toolbarGroups = [
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
    { name: 'insert', groups: [ 'insert' ] },
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    '/',
    // track changes buttons go here, only if admin Viewing As the business. See below.
  ];

  // Displaying LITE (track changes) buttons only if user has approval privileges.
  if (window.ck_info && window.ck_info.user && window.ck_info.user.type === 'master') {
      config.toolbarGroups.push({
          name: 'lite',
          groups: [ 'lite' ]
      });
  }

  // This came from the CKEditor Toolbar Configurator (run samples/index.html)
  config.removeButtons = 'Save,Strike,NewPage,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CreateDiv,Blockquote,BidiLtr,BidiRtl,Language,Image,Flash,Smiley,SpecialChar,PageBreak,Iframe,Font,Styles,FontSize,TextColor,BGColor,ShowBlocks';

  // Dialog windows are also simplified.
  config.removeDialogTabs = 'link:advanced';
  config.extraPlugins = ckePlugins.join();
  config.removePlugins = 'elementspath,pastefromword';
  if (window.ck_info && !window.ck_info.buttonVS) {
    config.removePlugins += ',sourcearea';
  }
  config.resize_enabled = true;
  config.disableNativeSpellChecker = true;
  config.fillEmptyBlocks = false;
  config.enterMode = CKEDITOR.ENTER_BR;

  // Advanced Content Filtering settings - see https://ckeditor.com/docs/ckeditor4/latest/guide/dev_acf.html
  config.disallowedContent = 'a[name]';
  // note: we're specifically not allowing the setting of most font size and family styles here, due to
  // previous issues with unclosed tags breaking font styles in other sections. (BENT-324)
  config.extraAllowedContent = 'div; div{border,padding,text-align}; p{margin,margin-top,margin-bottom};' +
    ' span{font-weight,font-style}; table{*}; td{*}';

  // custom CSS overrides
  config.contentsCss = [CKEDITOR.getUrl( 'contents.css' ), "/assets/styles/ckeditor.css"];

};
