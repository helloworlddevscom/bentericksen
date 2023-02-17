console.log('create')
window.ck_info.enableLite = false;

CKEDITOR.replace('description', {
  customConfig: '/assets/js/plugins/ckeditor/admin_config.js',
  height: 400
});

window.nanospell.ckeditor('description', {
  dictionary: "en",
  server: "php"
});


$('#type').on('change', function () {
  var id = $(this).find('option:selected').val();
  $('.js-errors').remove();

  if (!id || id === 'default') {
      $('.outline').hide();
      return;
  }

  $('.outline').show();

  if (id == 'blank') {
      $('#job_name').val('');
      CKEDITOR.instances['description'].setData('');
  } else {
      $.get('/user/job-descriptions/' + id, function (data) {
          $('#job_name').val(data.name);
          CKEDITOR.instances['description'].setData(data.description);
      });
  }
});

$('#job-descriptions-form').on('submit', function (e) {
  e.preventDefault();
  var message;

  $('.js-errors').remove();

  if ($('#type').find(':selected').val() === 'default') {
      message = $('<div class="js-errors" style="color: red" />');
      message.text('Please select a Job Description template.');

      $('#type').parents('.input-field').after(message);

      return;
  }

  this.submit();
});