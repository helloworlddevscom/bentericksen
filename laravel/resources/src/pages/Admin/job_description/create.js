$(function () {
  $('#job_industry').on('change', function() {
    elem = $('#job_industry option:selected').val();
    
    $('#job_sub_type option[value!=""]').addClass('hidden');
    $('#job_sub_type option[value=""]').prop('selected', true);
    $('#job_sub_type option.'+elem).each(function() {
      $(this).removeClass('hidden');
    });
  });
});

CKEDITOR.replace('description', {
  customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
})