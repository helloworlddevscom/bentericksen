<div class="modal fade" id="modalAttendanceView" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit attendance_modal">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">Attendance</h4>
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
                    <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
</div>