$('.jobs').on('click', '.modal-button', function () {
  var jobId = $(this).attr('data-job-id');
  var action = $('#deletionModal form').attr('action');
  action = action.substring(0, action.length - 1) + jobId;

  $('#deletionModal form').attr('action', action);
  $('#deletionModal').modal();
});