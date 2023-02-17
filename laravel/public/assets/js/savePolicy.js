function savePolicy() {
    const form = $('#policyForm')
    let currentContent = CKEDITOR.instances.editor1.getBody().innerHTML
    form.find('#content_raw').html(currentContent)
    $('[name="submitter"]').click()
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
});