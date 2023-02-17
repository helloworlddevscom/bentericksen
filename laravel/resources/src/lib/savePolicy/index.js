function savePolicy() {
  const form = $('#policyForm')
  const editor = CKEDITOR.instances.editor1
  let currentContent = (editor.editable().$ || editor.document.$.body ).innerHTML
  form.find('#content_raw').html(currentContent)
  $('[name="submitter"]').click()
}

export function savePolicyAsync() {
  const form = $('#policyForm')
  const currentContent = CKEDITOR.instances.editor1.getBody().innerHTML
  form.find('#content_raw').html(currentContent)

  const formData = new FormData(form[0])

  fetch(form.attr('action'), {
    method: 'POST',
    body: formData
  })
}

function toggleStatus(id) {
  $.get('/user/policies/' + id + '/toggleStatus', function (data) {
      var label = data === "enabled" ? 'DISABLE' : 'ENABLE';
      $('.buttons button[name="toggleStatus"]').html(label).toggleClass('btn-danger btn-primary');
  });
}

$(document).ready(function () {
  $('.form-btn').on('click', function () {
      const action = $(this).attr('name');
      const actionMap = {
          'toggleStatus': function() {
              toggleStatus(window.POLICY_ID);    
          },
          'reset': function() {
              $('#policyResetModal').modal('show');
          },
          'resetSpecial': function() {
              $('#policiesResetSpecialModal').modal('show');
          },
          'submit': function() {
              savePolicy();
          },
          'delete': function() {
            $('#policyDeleteModal').modal('show')
          }
      };
      actionMap[action] && actionMap[action]();
  });
})


export default savePolicy