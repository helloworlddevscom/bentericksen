<div class='modal fade' id='modalPolicyComplete' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='edit edit_drivers_license'>
                <div class='modal-header'>
                    <h4 class='modal-title text-center' id='modalLabel'>You are almost done!</h4>
                </div>
                <div class='modal-body text-center'>
                    <p>
                      You have accepted the policy updates and now you need to refresh your policy manual. If you are ready, click Update Policy Manual. Your custom changes will not be lost; only the new updated policies will be modified.
                    </p>
                    
                    <p>
                      If you are not ready to create a new manual, click Make Additional Policy Changes. You will need to refresh the manual in order to print or allow employee access to your manual going forward.
                    </p>
                    @if(isset($has_pending_policies) && $has_pending_policies)
                      <a href="#" class="btn btn-primary update-complete" data-toggle='modal' data-dismiss='modal' data-target='#modalPoliciesPending'>Update Policy Manual</a>
                    @else
                      <a href='/user/policies/createManual' target="_blank" class='btn btn-primary update-complete createModal'>Update Policy Manual</a>
                    @endif
                    <br>
                    <a href='/user/policies' class='btn btn-primary update-complete'@if (!empty($user) && $user->permissions('m120') == 'View Only') disabled @endif>Make Additional Policy Changes</a>
                </div>
            </div>
        </div>
    </div>
</div>
