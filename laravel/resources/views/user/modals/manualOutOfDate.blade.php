<div class='modal fade' id='modalManualOutOfDate' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class='modal-title text-center' id='modalLabel'>Policy Manual Out of Date</h4>
            </div>
            <div class='modal-body'>
                @if (!empty($viewUser) && ($viewUser->hasRole('manager') == 1 && !$viewUser->permissions('m121')))
                    <p>The manual is not available currently as a new manual is being created, please contact your Employer.</p>
                @else
                    <p>The policy manual is now out of date, please create a new one.</p>
                @endif
            </div>
            <div class='modal-footer text-center'>
                @if (!empty($viewUser) && ($viewUser->hasRole('manager') == 1 && !$viewUser->permissions('m121')))
                    <button type='button' class='btn btn-default btn-primary policy-ignore' data-dismiss='modal'>Close</button>
                @else
                    <a href='/user/policies/createManual' data-dismiss="modal" class='btn btn-primary createModal'>Create and Print Policy Manual</a>
                @endif

            </div>
        </div>
    </div>
</div>
