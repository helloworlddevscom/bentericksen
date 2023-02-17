<div class='modal fade modalPolicyCompare' id='modalPolicyCompare{{ $key }}' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog' style="overflow-y: scroll;">
        <div class='modal-content'>
            <div class='edit edit_drivers_license'>
                <form action="/user/policies/acceptUpdate/{{ $policy_update->id }}" method="POST">
                    {{ csrf_field() }}
                    <div class='modal-header'>
                        <h4 class='modal-title text-center' id='modalLabel'>@if(($policy_update->manual_name === '')) {{ $old_policy['manual_name'] }} @else {{ $policy_update->manual_name }} @endif</h4>
                    </div>
                    <div class='modal-body'>
                        <div class="col-xs-6 pull-left old-policy" style="display: flex; flex-direction: column; justify-content: start;">
                            <h3>Previous Policy Wording</h3>
                            @if(!empty($old_policy))
                                {!! $old_policy['content'] !!}
                            @else
                                This is a new policy.
                            @endif
                            @if(!empty($old_policy))
                            <input type="hidden" name="modified" value="{{ $old_policy->is_modified }}" />
                            @else
                                <input type="hidden" name="modified" value="0" />
                            @endif
                            <input type="hidden" name="content_raw" value="{{ $policy_update->content }}">
                            <input type="hidden" name="manual_name" value="{{ $policy_update->manual_name }}">
                            <input type="hidden" name="modal_accept" value="1">
                            <input id="enableInput" type="hidden" name="enableInput" value="1">
                        </div>
                        

                        @if ($policy_update->status == 'disabled')
                            <div class="col-xs-6 pull-right">
                                {{ __('policies.disabled') }}
                            </div>
                        @else
                            <div class="col-xs-6" style="display: flex; flex-direction: column; justify-content: start;">
                                <h3>New Policy Wording</h3>
                                <textarea id="content_raw" class="form-control ckeditor" name="content" @if(!empty(array_intersect(['required', 'drequired', 'vrequired'], $policy_update->requirement))) readonly @endif>
                                {{ $policy_update->content }}
                                </textarea>
                            </div>
                        @endif
                    </div>
                    <div class='modal-footer text-center'>
                        <div class='policies-out-of'>
                        {{ Session::get('updateCurrent') }} of {{ Session::get('updateTotal') }}
                        </div>
                        <button type='submit' class='btn btn-default btn-primary'>Accept</button>
                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalCancelConfirm'>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
