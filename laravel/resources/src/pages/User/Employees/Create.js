import '@/lib/managerAuthModal'

$('.modal-button').on('click', function (e) {
  e.preventDefault();
});

$(".date-picker").datepicker({
  changeMonth: true,
  changeYear: true,
  showButtonPanel: true,
  yearRange: "-100:+10",
});

$('#employee_classification').on('change', function () {
  if ($(this).find('option:selected').val() === "other") {
      $('#employee_classification_name').removeClass('hidden').attr('disabled', false);
  } else {
      $('#employee_classification_name').addClass('hidden').attr('disabled', true);
  }
});

$('.emergency_relationship').on('change', function () {
  var valueSelected = this.value;
  var otherInput = $(this).attr('data-target');
  var name;

  if (valueSelected == 'other') {
      name = $(this).attr('name');

      $(otherInput).removeClass('hidden');
      $(otherInput).attr('name', name);

      $(this).attr('name', '');
  } else {
      name = $(otherInput).attr('name');

      $(otherInput).addClass('hidden');
      $(otherInput).attr('name', '');

      $(this).attr('name', name);
  }
});