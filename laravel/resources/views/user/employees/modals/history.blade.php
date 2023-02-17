<div class="modal fade" id="modalHistoryView" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit history_modal">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">History</h4>
                </div>
                @foreach( $employee->history as $history )
                    <div class="modal-body hidden" data-history-id="{{ $history->id }}">
                        <div class="row">
                            <label class="col-md-4">Type</label>
                            <div class="col-md-8">{{ ucfirst($history->type) }}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Status</label>
                            <div class="col-md-8">{{ ucfirst( $history->status ) }}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Note</label>
                            <div class="col-md-8">{{ ucwords($history->note) }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
</div>