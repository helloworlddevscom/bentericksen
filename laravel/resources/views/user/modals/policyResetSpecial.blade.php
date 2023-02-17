<div class='modal fade in' id="policiesResetSpecialModal" tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reset Select</h4>
            </div>
            <div class="modal-body">
                <p class="text-warning">
                    This will permanently reset the policy, erasing all changes. This cannot be undone. Are you
                    sure?</p>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <a href="/user/policies/{{ $policy->id }}/resetSelect" class="btn btn-primary reset-policies pull-right">Reset Policy</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

