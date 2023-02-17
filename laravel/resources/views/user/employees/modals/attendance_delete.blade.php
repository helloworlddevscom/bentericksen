<div class="modal fade" id="modalAttendanceDelete" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit attendance_delete_modal">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">Are you sure you want to delete this record?</h4>
                </div>
                @foreach( $employee->attendance as $attendance )
                    <div class="modal-body hidden" data-attendance-id="{{ $attendance->id }}">
                        <div class="row">
                            <label class="col-md-4">Start Date</label>
                            <div class="col-md-8">{{ $attendance->start_date->format('m/d/Y') }}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-4">End Date</label>
                            <div class="col-md-8">{{ $attendance->end_date->format('m/d/Y') }}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Status</label>
                            <div class="col-md-8">{{ ucfirst( $attendance->status ) }}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Note</label>
                            <div class="col-md-8">{{ $attendance->note }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-danger btn-primary delete">DELETE</button>
                    <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </div>
    </div>
</div>
