/* global CKEDITOR */

// This is the Admin version of the CKEditor button config. It is used on the admin pages, like the Policy Template editor.
// Business owners/managers, and admins Viewing As a business, will see a different set of buttons on the Business-specific
// pages. That config is stored in config.js.
CKEDITOR.editorConfig = function( config ) {
  config.toolbarGroups = [
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    { name: 'links', groups: [ 'links' ] },
    { name: 'insert', groups: [ 'insert' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    '/',
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    '/',
    { name: 'colors', groups: [ 'colors' ] },
    { name: 'others', groups: [ 'others' ] }
  ];
  config.removeButtons = 'Save,Strike,NewPage,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CreateDiv,Blockquote,BidiLtr,BidiRtl,Language,Image,Flash,Smiley,SpecialChar,PageBreak,Iframe,Font,Styles,TextColor,BGColor,ShowBlocks';

  config.fontSize_sizes = '8pt/8pt:9pt/9pt;10pt/10pt;11pt/11pt;12pt/12pt;14pt/14pt;16pt/16pt;18pt/18pt;20pt/20pt;24pt/24pt'

  config.fillEmptyBlocks = false;
  config.disableNativeSpellChecker = true;
  config.enterMode = CKEDITOR.ENTER_BR;
  config.removePlugins = 'elementspath,pastefromword';
  if (window.ck_info && !window.ck_info.buttonVS) {
    config.removePlugins += ',sourcearea';
  }

  // Advanced Content Filtering settings - see https://ckeditor.com/docs/ckeditor4/latest/guide/dev_acf.html
  config.disallowedContent = 'a[name]';
  // note: we're specifically not allowing the setting of most font size and family styles here, due to
  // previous issues with unclosed tags breaking font styles in other sections. (BENT-324)
  config.extraAllowedContent = 'div; div{border,padding,text-align}; p{margin,margin-top,margin-bottom};' +
    ' span{font-weight,font-style}; table{*}; td{*}';

  // custom CSS overrides
  config.contentsCss = [CKEDITOR.getUrl( 'contents.css' ), "/assets/styles/ckeditor.css"];

};
