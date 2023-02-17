$('.modal-button').on('click', function() {
  var licenseId = $(this).attr('data-license-id');
  var url = "/user/licensure-certifications/" + licenseId;
  
  $('#deletionModal form').attr('action', url);
});