import '@/lib/jquery'
import '@/lib/bootstrap/bootstrap-3-3-1.min'

$(document).ready(function () {
  /**
   * Trigger create modal process. Generating token.
   **/
  $('.createModal, .policiesReset').on('click', function (e) {
      var laravel_token = document.querySelector('[name=csrf-token]').getAttribute('content')
      var href = $(this).prop('href');


      // if the policy manual creation was triggered from an update process, close the modal.
      if ($(this).parents('#modalPolicyComplete')) {
          $('#modalPolicyComplete').modal('hide');
      }

      // if the policy manual creation was triggered from out of date modal, close the modal.
      if ($(this).parents('#modalManualOutOfDate')) {
          $('#modalManualOutOfDate').modal('hide');
      }

      if ($(this).hasClass('policiesReset')) {
          $.post('/user/policies/createManualToken', {"_token": laravel_token}, function (data) {
              if (data.success === true) {
                  $('.reset-policies').attr('href', href + '?token=' + data.token);
                  $('#policiesResetModal').modal('show');
              }
          });
      } else {
          $.post('/user/policies/createManualToken', {"_token": laravel_token}, function (data) {
              if (data.success === true) {
                  $('.create-manual').attr('href', href + '?token=' + data.token);
                  $('#createManualModal').modal('show');
              }
          });
      }
      return false;
  });

  /**
   * Resetting create policy manual button after the action is triggered.
   **/
  $('.create-manual').on('click', function () {
      $('#createManualModal').modal('hide');
      $('.createModal').parent().toggleClass('hidden');
      $('.viewManual').parent().toggleClass('hidden');
      $('.viewBenefitsSummary').parent().toggleClass('hidden');
  });
  
  const { EMPLOYEE_COUNT_WARNING_SHOW_WARNING, EMPLOYEE_COUNT_WARNING_SHOW_REMINDER} = window
  
  if (EMPLOYEE_COUNT_WARNING_SHOW_WARNING) {
    $(document).ready(function () {
      $('#modalEmployeeCount').modal({
          backdrop: 'static',
          keyboard: false
      }, 'show');
    })
  }

  if (EMPLOYEE_COUNT_WARNING_SHOW_REMINDER) {
    $(document).ready(function () {
      let employeeCountModal = $('#modalEmployeeCount')
      employeeCountModal.attr('type', 'reminder')
      employeeCountModal.modal({
          backdrop: 'static',
          keyboard: false
      }, 'show')
    })
  }
  

  if( !SESSION_SET_IGNORED) {
    if( SESSION_SET_ACCEPTED == 1 ) {
      $('#modalPolicyCompare0').modal('show')
    } else if( SESSION_SET_ACCEPTED == 'complete') {
      $('#modalPolicyComplete').modal('show');
    } else {
      $('#modalPolicyUpdates').modal('show');
    }

    $('.policy-ignore').on('click', function () {
        $('.policy-update-notification').show();
    });
  } else {
    $('.policy-update-button').on('click', function () {
      $('#modalPolicyCompare0').modal('show');
    })
  }

  $('.cancel-confirm').on('click', function () {
      $('#modalPolicyCompare0').modal('hide');
      $('#modalPolicyCompare1').modal('hide'); // if policy is currently disabled, there are 2 modals.
  });

  $('.policy-update-button').on('click', function () {
      $('#modalPolicyCompare0').modal('show');
  });

  $(document).on('submit', '#time-off-requests-form', function (e) {
      e.preventDefault();

      var start_date = $('#request_start_date').val() || 0;
      var end_date = $('#request_end_date').val() || 0;
      var pto_type = $('#request_pto_type').val();
      var errors = [];

      $('.js-errors').remove();

      if (start_date === 0) {
          errors.push({
              elementName: 'start_date',
              message: 'The Start Date field is required.'
          });
      }

      if (end_date === 0) {
          errors.push({
              elementName: 'end_date',
              message: 'The End Date field is required.'
          });
      }

      if (Date.parse(end_date) < Date.parse(start_date)) {
          errors.push({
              elementName: 'end_date',
              message: 'Please enter an end date that is after the start date'
          });
      }

      if (pto_type === '') {
          errors.push({
              elementName: 'pto_type',
              message: 'The Field Type is required.'
          });
      }

      if ($('#request_employee_member_id').length > 0) {
          if ($('#request_employee_member_id').find(':selected').val() === '') {
              errors.push({
                  elementName: 'employee_member_id',
                  message: 'The Employee field is required.'
              });
          }
      }

      if (errors.length > 0) {
          displayErrors(errors);
          return;
      }

      this.submit();
  });

  var certCount = 0;
 

  $(document).on('change', '.certType', function (e) {
      var option = $(this).find('option:selected').val();
      var field_name = this.name.replace('licensure_certifications_id', 'name');
      var field;

      if (option !== 'new') {
          return;
      }

      field = $('<div class="form-group padding-top"><div class="col-md-12"><label>Name: </label>&nbsp;<input type="text" name="' + field_name + '" /></div></div>');
      $(this).after(field);
  });

  $('.add-cert').on('click', function () {
      $(".date-picker").datepicker("destroy");
      $('.empLicenseCertification').append("<tr class='cert-row new'><td><div class='form-group'><div class='col-md-12'><select class='form-control certType' name='licensure[new][" + certCount + "][licensure_certifications_id]'>" + CERT_OPTIONS + "</select></div></div></td><td><div class='input-group'><input type='text' id='licensure[new][" + certCount + "][expiration]' class='form-control date-picker date-box' name='licensure[new][" + certCount + "][expiration]' value='' placeholder='mm/dd/yyyy'><span class='input-group-addon'><label for='licensure[new][" + certCount + "][expiration]'><i class='fa fa-calendar'></i></label></span></div></td><td class='text-right'><button type='button' class='btn btn-default btn-primary btn-xs remove-cert'>DELETE</button></td></tr>");
      certCount++;
      $(".date-picker").datepicker({
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          yearRange: "-100:+10",
      }).mask("99/99/9999");
  });


  $('.empLicenseCertification').on('click', '.remove-cert', function () {
      var id;
      if ($(this).parents('.cert-row').hasClass('new')) {
          $(this).parents('.cert-row').remove();
      } else {
          id = $(this).parents('.cert-row').attr('data-id');
          $(this).parents('.cert-row').empty().html("<input type='hidden' name='licensure[remove][]' value='" + id + "'>");
      }
  });

  $('.empLicenseCertification').on('click', '.edit-cert', function () {
      $(this).parents('.cert-row').find('.date-box').attr('disabled', false);
  });

  var displayErrors = function (errors) {
      var warning;
      $(errors).each(function (i, data) {
          warning = $('<div class="js-errors danger" style="color: red" />');
          warning.text(data.message);
          $('#request_' + data.elementName).parents('.input-group').before(warning);
      });
  };

  // enabling spell checker plugin
  window.nanospell && window.nanospell.ckeditor('all', {
      dictionary: "en",
      server: "php"
  });

  window.CKEDITOR && CKEDITOR.on('instanceReady',() => {
      if (!CKEDITOR.instances.content_raw) {
          return
      }
      CKEDITOR.instances.content_raw.on( 'change', function(e) {
          $('[name=modified]').val(1)
      })
  })

  // JS to enable/close benefits Summary Create Module
  $('.benefitsSummaryCreate').on('click', function (e) {
      $('#modalBenefitsSummaryCreate').modal('show');
  });

  $('.benefit-summary-create-button').on('click', function () {
      $('#modalBenefitsSummaryCreate').modal('hide');
  });


});