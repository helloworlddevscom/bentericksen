<div class="col-md-12 text-center">
    <h4>Attendance</h4>
</div>
<div class="col-md-8 col-md-offset-2">
    <div class="row">
        <div class="form-group">
            <div class="col-md-9">
                <label for="start_date" class="col-md-6 control-label">Start Date:</label>
                <div class="col-md-6">
                    <div class="input-group">
                    @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        {!! Form::text('attendance[start_date]', null, ['id' => 'start_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled' => 'disabled']) !!}
                    @else
                        {!! Form::text('attendance[start_date]', null, ['id' => 'start_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                    @endif

                        <span class="input-group-addon"><label for="start_date"><i class="fa fa-calendar"></i></label></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label for="end_date" class="col-md-6 control-label">End Date:</label>
                <div class="col-md-6">
                    <div class="input-group">
                        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        {!! Form::text('attendance[end_date]', null, ['id' => 'end_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled' => 'disabled']) !!}
                        @else
                            {!! Form::text('attendance[end_date]', null, ['id' => 'end_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                        @endif
                        <span class="input-group-addon"><label for="end_date"><i class="fa fa-calendar"></i></label></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label for="attendance_status" class="col-md-6 control-label">Status:</label>
                <div class="col-md-6">
                    @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        <select class="form-control" id="attendance_status" name="attendance[status]" disabled>
                            <option value="blank">- Select One -</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="left_early">Left Early</option>
                            <option value="no_call_no_show">No Call No Show</option>
                        </select>
                    @else
                        <select class="form-control" id="attendance_status" name="attendance[status]">
                            <option value="blank">- Select One -</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="left_early">Left Early</option>
                            <option value="no_call_no_show">No Call No Show</option>
                        </select>
                    @endif

                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="form-group">
                <label for="" class="col-md-4 control-label">Note:</label>
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                <div class="col-md-8">
                    <textarea class="form-control" id="attndance_note" name="attendance[note]" rows="6" disabled></textarea>
                </div>
                @else
                <div class="col-md-8">
                    <textarea class="form-control" id="attndance_note" name="attendance[note]" rows="6"></textarea>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <div class="row">
            @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
            <div class="col-md-12 text-center">
                {!! Form::button('Update', ['type' => 'submit', 'name' => 'action', 'value' => 'attendance', 'class' => 'btn btn-default btn-primary btn-xs attendance-submit', 'disabled' => 'disabled']) !!}
            </div>
            @else
            <div class="col-md-12 text-center">
                {!! Form::button('Update', ['type' => 'submit', 'name' => 'action', 'value' => 'attendance', 'class' => 'btn btn-default btn-primary btn-xs attendance-submit']) !!}
            </div>
            @endif
        </div>
    </div>
</div>
@if($viewUser->hasRole('manager') && !$viewUser->permissions('m148'))

@else
    <div class="col-md-12 text-center emp-heading even">
        <h4>History</h4>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" id="table">
                <thead>
                <tr>
                    <th width="25%">
                        <label class="control-label">Date</label>
                    </th>
                    <th width="20%">
                        <label class="control-label">Status</label>
                    </th>
                    <th width="35%">
                        <label class="control-label">Note</label>
                    </th>
                    <th class="text-right" width="20%">
                        <label class="control-label">Actions</label>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach( $employee->attendance->sortBy('start_date') as $attendance )
                    <tr>
                        <td>{{ $attendance->start_date->format('m/d/Y') }} - {{ $attendance->end_date->format('m/d/Y') }}</td>                        
                        <td>{{ mb_convert_case(ucfirst(str_replace('_', ' ', $attendance->status)), MB_CASE_TITLE, 'UTF-8')}}</td>
                        <td>{{ $attendance->note }}</td>
                        <td class="text-right">
                            <a href="#" class="btn btn-default btn-primary btn-xs attendance" data-attendance-id="{{ $attendance->id }}" data-toggle="modal" data-target="#modalAttendanceView">View</a>
                            @if($viewUser->getRole() === "admin")
                            <a href="#" class="btn btn-danger btn-primary btn-xs attendance-delete" data-attendance-id="{{ $attendance->id }}" data-toggle="modal" data-target="#modalAttendanceDelete">Delete</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Attendance Tracking View Modal -->
@include('user.employees.modals.attendance')
@include('user.employees.modals.attendance_delete')
