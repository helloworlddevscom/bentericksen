<div class='modal fade in' id='managerAuthConfirmModal' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title text-center' id='modalLabel'>Set Manager Access</h4>
            </div>
            <div class='modal-body'>
                <p class="text-warning">Warning: you are creating a Manager who will have access to
                    policies, job descriptions, forms, employee records, and other business information.
                    Are you sure you want to proceed?</p>
                <p>You can customize access under Settings, Permissions.</p>
            </div>
            <div class='modal-footer text-center'>
                <button type='button' class='btn btn-primary' data-toggle='modal' data-dismiss='modal'>Yes</button>
                <button type='button' id="js-reset-auth-permission"  class='btn btn-primary' data-toggle='modal' data-dismiss='modal'>No</button>
            </div>
        </div>
    </div>
</div>