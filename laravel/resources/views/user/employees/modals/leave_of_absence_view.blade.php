<!-- Leave Of Absence View -->
<div class="modal fade" id="modalLeaveOfAbsenceView" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="modalLabel">Update Leave of Absence</h4>
            </div>
            <div class="modal-body leave-modal">
                @foreach($employee->timeOff as $leave)
                    <div class="hidden timeoff" data-leave-id="{{ $leave->id }}">
                        {!! Form::hidden('leave_of_absence_update[id]', $leave->id, ['class' => 'form-control', 'disabled']) !!}
                        <div class="col-md-12">
                            <div class="col-md-12 sub_content">
                                <div class="row">
                                    <label for="request_pto_time" class="col-md-12">Status:</label>
                                    <div class="col-md-5">
                                        <select class="form-control" name="leave_of_absence_update[status]" disabled>
                                            <option @if($leave->status === 'pending') selected @endif value="pending">Pending</option>
                                            <option @if($leave->status === 'approved') selected @endif value="approved">Approved</option>
                                            <option @if($leave->status === 'denied') selected @endif value="denied">Denied</option>
                                            <option @if($leave->status === 'completed') selected @endif value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="start_at" class="col-md-12">Start Date:</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            {!! Form::text('leave_of_absence_update[start_at]', date('m/d/Y', strtotime($leave->start_at)), ['id' => 'start_at_' . $leave->id, 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled']) !!}
                                            <span class="input-group-addon"><label for="request_start_date"><i class="fa fa-calendar"></i></label></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="end_at" class="col-md-12">End Date:</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            {!! Form::text('leave_of_absence_update[end_at]', date('m/d/Y', strtotime($leave->end_at)), ['id' => 'end_at_' . $leave->id, 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled']) !!}
                                            <span class="input-group-addon"><label for="request_end_date"><i class="fa fa-calendar"></i></label></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_pto_time" class="col-md-12">Type:</label>
                                    <div class="col-md-5">
                                        <select class="form-control" name="leave_of_absence_update[type]" disabled>
                                            @foreach( $leaveTypes as $key => $type )
                                                <option @if($leave->type == $type) selected @endif value="{{ $key }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_reason" class="col-md-12">Reason:</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control request_textarea" id="request_reason" name="leave_of_absence_update[reason]" disabled>{!! $leave->reason !!}</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_note" class="col-md-12">Note:</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control request_textarea" id="request_note" name="leave_of_absence_update[note]">{!! $leave->note !!}</textarea>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CLOSE</button>
                @if($viewUser->hasRole('manager') && !$viewUser->permissions('m145'))
                    <button type="submit" class="btn btn-default btn-primary disabled" disabled>SAVE</button>
                @else
                    <button type="submit" class="btn btn-default btn-primary" name="action" value="leave_of_absence_update">SAVE</button>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Leave Of Absence View -->