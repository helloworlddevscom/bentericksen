<div class='modal fade' id='modalPolicyCompare{{ $key }}' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='edit'>
                <form id="disabledPolicyForm" action="/user/policies/acceptUpdate/@if(!is_null($old_policy)){{ $old_policy['template_id'] }}@else{{ $id }}@endif" method="POST">
                    {{ csrf_field() }}
                    <div class='modal-header'>
                        <h4 class='modal-title text-center' id='modalLabel'>@if(!is_null($old_policy)) {{ $old_policy['manual_name'] }} @else {{ $policy_update->manual_name }} @endif</h4>
                    </div>
                    <div class='modal-body'>
                        <p>This policy was previously disabled. Keep it disabled?</p>
                        <input type="hidden" name="content_raw" value="{{$policy_update->content }}">
                    </div>
                    <div class='modal-footer text-center'>
                        <a id="disablePolicy" href="#" class='btn btn-default btn-primary'>Yes</a>
                        <a id="enablePolicy" href="#" class='btn btn-default btn-primary'>No</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    (function ($) {
        if ($('#modalPolicyCompare1').find('#enableInput').length === 0) {
            $('#modalPolicyCompare1').find('form').prepend($('<input id="enableInput" type="hidden" name="enableInput" value="1">'));
        }

        $(document).on('click', '#enablePolicy', function (e) {
            e.preventDefault();
            $('#modalPolicyCompare1 #enableInput').val(1)
            $('#modalPolicyCompare0').modal('hide');
            $('#modalPolicyCompare1').modal('show');
        });

        $(document).on('click', '#disablePolicy', function (e) {
            e.preventDefault();
            $('#modalPolicyCompare1 #enableInput').val(0)
            $('#modalPolicyCompare0').modal('hide');
            $('#modalPolicyCompare1').modal('show');
        });
    })(jQuery)
</script>