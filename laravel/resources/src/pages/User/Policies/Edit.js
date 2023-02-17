import savePolicy, { savePolicyAsync } from '@/lib/savePolicy'
import '@/lib/ckeditor/policyEditor'

$(document).on('click', '#js-reset-policy', function() {
  const form = $('#policyForm');
  var action = form.attr('action') + "/restore";
  form.attr('action', action);
  savePolicy();
})

$(document).ready(function () {
  CKEDITOR.instances.editor1.on( 'change', function(e) {
      $('#mod_counter').val(1);
  });

  $('#add').click(function () {
      return !$('.select1 option:selected').remove().appendTo('.select2');
  });

  $('#remove').click(function () {
      return !$('.select2 option:selected').remove().appendTo('.select1');
  });

  // accept & notify behaviour
  $('#accept').on('click', function () {

      var input1 = $('<input />', {
          type: 'hidden',
          name: 'modification_approval[decision]',
          value: 'approved'
      });

      $('#policyForm').append(input1);

      savePolicy();
  });

  // reject & notify behaviour
  $('#reject').on('click', function () {
      $('#policyRejectModifications').modal('show');
  });

  // intercept submit notify event (view: modals/policyReject)
  $('#reject_notify').on('click', function (e) {
      e.preventDefault();

      var justification = $('#rejection-justification').val();

      var input1 = $('<input />', {
          type: 'hidden',
          name: 'modification_approval[decision]',
          value: 'rejected'
      });

      var input2 = $('<input />', {
          type: 'hidden',
          name: 'modification_approval[justification]',
          value: $('#rejection-justification').val()
      });

      $('#policyForm').append(input1, input2);

      savePolicy();
  });

  CKEDITOR.instances.editor1.on('lite:tracking', function(event) {
    if (event.data.tracking == 1){
        $('#is_tracking').val(1);
    } else {
        $('#is_tracking').val(0);
    }
  });

  // if we ever accept all changes, set tracking to 0.
  CKEDITOR.instances.editor1.on('lite:accept', function(event) {
    $('#is_tracking').val( lite.countChanges() > 0  )
    $('#mod_counter').val( lite.countChanges() )
    savePolicyAsync()
  });
  //TODO - figure out why these events cant be attached to the same listener.
  CKEDITOR.instances.editor1.on('lite:reject', function(event) {
    $('#is_tracking').val( lite.countChanges() > 0 )
    $('#mod_counter').val( lite.countChanges() )
    savePolicyAsync()
  });
});

$(`#category option[value="${POLICY_CATEGORY_ID}"]`).prop('selected', true);