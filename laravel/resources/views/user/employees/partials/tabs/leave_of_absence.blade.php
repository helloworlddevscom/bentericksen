<div class="col-md-12 text-center">
    <h4>Leave of Absence</h4>
</div>
<div class="col-md-12">
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 text-center">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                <button type="button" class="btn btn-default btn-primary btn-xs modal-button" data-toggle="modal" data-target="#modalLeaveOfAbsence" id="add_new_leave" disabled>ADD NEW LEAVE</button>
                {!! $help->button("u3057") !!}
                @else
                <button type="button" class="btn btn-default btn-primary btn-xs modal-button" data-toggle="modal" data-target="#modalLeaveOfAbsence" id="add_new_leave">ADD NEW LEAVE</button>
                {!! $help->button("u3057") !!}
                @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped" id="attendance_table">
            <style>
                #attendance_table th {
                    padding-left: 20px;
                }

                #attendance_table .input-group {
                    max-width: 200px;
                }
            </style>
            <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Leave Type</th>
                <th>Status</th>
                <th class="bg_none"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($employee->timeOff AS $leave)
                <tr>
                    <td>{{ $leave->start_at->format('m/d/Y') }}</td>
                    <td>{{ $leave->end_at->format('m/d/Y') }}</td>
                    <td>{{ $leave->type }}</td>
                    <td>{{ ucfirst($leave->status) }}</td>
                    <td class="text-right">
                        <a href="#" class="btn btn-default btn-primary btn-xs leave-button" data-leave-id="{{ $leave->id }}" data-toggle="modal" data-target="#modalLeaveOfAbsenceView">View / Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('user.employees.modals.leave_of_absence')
    @include('user.employees.modals.leave_of_absence_view')
</div>