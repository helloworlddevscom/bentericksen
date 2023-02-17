$( '#help_section' ).on('change', function() {
  key = $(this).val();
  
  $( '#help_sub_section option[value!=""]' ).addClass( 'hidden' )
  $( '#help_sub_section option[value=""]' ).prop( 'selected', true)
  $( '#help_sub_section option.'+ key ).removeClass( 'hidden' )
});

const sectionSelection = $('[name=hydrate_help_section]').val()
const subSectionSelection = $('[name=hydrate_help_sub_section]').val()

$( '#help_sub_section option[value!=""]' ).addClass( 'hidden' )

$( `#help_section option[value="${sectionSelection}"]` ).prop( 'selected', true)
$( `#help_sub_section option[value="${subSectionSelection}"]` ).prop( 'selected', true)

let key = $( '#help_section' ).val()

$( '#help_sub_section option.'+ key ).removeClass( 'hidden' )

CKEDITOR.replace('answer', {
  customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
});