
  var elem = $('#modalEmployeeCount');
  var json = {};
  json._token = LARAVEL_TOKEN

  // Set save to disabled until check complete
  $('#updateEmployeeCount').attr('disabled', true);

  // Validating input value
  $('#employeeCount').on('keyup', function(e){
      var count = $(this).val();

      $('.feedback').empty().hide();

      if (!count) {
          $('.feedback').empty().text('Required field').show();
          $('#updateEmployeeCount').attr('disabled', true);
      } else if (isNaN(count)) {
          $('.feedback').empty().text('Please enter a valid number').show();
      } else if (count < 1) {
          $('.feedback').empty().text('Please enter 1 or more.').show();
      } else {
          $('#updateEmployeeCount').attr('disabled', false);
      }
  });

  // Submitting the employee count
  $('#updateEmployeeCount').on('click', function (e) {
      e.preventDefault();

      $(this).button('loading');

      var number = $('#employeeCount').val();
      json.employees = number;
      json.type = elem.attr('type');
      json.isAjax = true; // to differentiate from the existing route.

      $.post('/user/employees/number', json, function(data){
          if (data.success !== true) {
              $('.feedback').empty().text('Something went wrong, try again.').show();
          } else {
              elem.modal('hide');
              $('.js-force-modal').removeClass('js-force-modal')
                  .attr('data-toggle', '')
                  .attr('data-target', '')
                  .attr('data-dismiss', '');
          }
      });
  })

  $('#ignoreEmployeeReminder').on('click', function() {
      $.post('/user/dashboard/ignore', json);
  });

  let _modal = {
      body: elem.find('.modal-body'),
      title: elem.find('.modal-title'),
      desc: elem.find('.modal-description'),
      label: elem.find('label'),
      contact: elem.find('.modal-contact'),
      close: elem.find('#ignoreEmployeeReminder')
  }
  _modal.title.addClass('invisible');
  _modal.body.addClass('invisible');
  _modal.close.addClass('hidden');
  elem.on('shown.bs.modal', function () {
      if (elem.attr('type') === "reminder") {
          _modal.title.text('ALERT: Please Update Your Employee Count');
          _modal.desc.html(
              '<p>Many labor-related requirements and laws are based on the number of employees you have. </p>' +
              '<p>To help protect you and support your ongoing compliance, we periodically require that you update your employee count for us. </p>'
          );
          _modal.label.text('Please enter the number of employees* you currently have and click save:');
          _modal.contact.html(
              '<p>If you have any questions, please contact us at <a href="tel:1-800-679-2760">(800) 679-2760</a> or <a href="mailto:hrsupport@bentericksen.com">hrsupport@bentericksen.com</a></p>' +
              '<p>Thank you for helping us help you.</p>' +
              '<p>*How to calculate: add up all employees (full-time, part-time, temporary, people who receive a W-2, etc.) across all of your locations, regardless of whether or not those locations have separate tax ID numbers.</p>'
          );
          _modal.close.removeClass('hidden');
      }
      _modal.title.removeClass('invisible');
      _modal.body.removeClass('invisible');
  });