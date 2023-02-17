<div class="modal fade" id="policyDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Policy</h4>
            </div>
            <div class="modal-body">
                <p class="text-warning">
                    This will permanently delete the policy. This cannot be undone. Are you
                    sure?</p>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    CANCEL
                </button>
                {!!Form::open(['route' => ['user.policies.destroy', $policy->id], 'method' => 'DELETE'])!!}
                    <button type="submit" class="btn btn-danger reset-policies pull-right">
                        DELETE
                    </a>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->