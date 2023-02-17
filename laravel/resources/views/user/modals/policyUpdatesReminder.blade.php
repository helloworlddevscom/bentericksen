<div class='modal fade' id='modalPolicyUpdatesReminder' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title text-center' id='modalLabel'>Policy Updates</h4>
            </div>
            <div class='modal-body'>
                @if (!empty($viewUser) && ($viewUser->hasRole('manager') == 1 && !$viewUser->permissions('m121')))
                    <p>The manual is not available currently as a new manual is being created, please contact your Employer.</p>
                @else
                    <p>New policy updates are available!  You must view and accept these updates in order to view / print the Policy Manual or Benefits Summary.</p>
                @endif
            </div>
            <div class='modal-footer text-center'>
                @if (!empty($viewUser) && ($viewUser->hasRole('manager') == 1 && !$viewUser->permissions('m121')))
                    <button type='button' class='btn btn-default btn-primary policy-ignore' data-dismiss='modal'>Close</button>
                @else
                    <button type='button' class='btn btn-default btn-primary policy-ignore' data-dismiss='modal'>Ignore</button>
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-dismiss='modal' data-target='#modalPolicyCompare0'>Update</button>
                @endif
            </div>
        </div>
    </div>
</div>
