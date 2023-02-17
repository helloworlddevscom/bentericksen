<div class="col-md-12">
    <div class="col-md-12 text-center emp-heading even">
        <h3>STATUS</h3>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="employee_hire_date" class="col-md-5 no-padding-both control-label">Hired:</label>
        <div class="col-md-5">
            <div class="input-group">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                    {!! Form::text('employment_status[hired]', $employee->hired ? date('m/d/Y', strtotime($employee->hired)) : null, ['id' => 'employee_hire_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled' => 'disabled']) !!} 
                @else 
                    {!! Form::text('employment_status[hired]', $employee->hired ? date('m/d/Y', strtotime($employee->hired)) : null, ['id' => 'employee_hire_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                @endif
                <span class="input-group-addon"><label for="employee_hire_date"><i class="fa fa-calendar"></i></label></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_years_of_service" class="col-md-5 no-padding-both control-label">Years of Service:</label>
        <div class="col-md-4 padding-top">
            {{ ($employee->hired ? $employee->hired->age : null) }}
        </div>
    </div>
    @if($employee->rehired !== null)
        <div class="form-group">
            <label for="employee_rehire_date" class="col-md-5 no-padding-both control-label">Rehired:</label>
            <div class="col-md-4">
                <div class="input-group">
                    {!! Form::text('employment_status[rehired]', (!is_null($employee->rehired) ? date('m/d/Y', strtotime($employee->rehired)) : null), ['id' => 'employee_rehire_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                    <span class="input-group-addon"><label for="employee_rehire_date"><i class="fa fa-calendar"></i></label></span>
                </div>
            </div>
        </div>
    @endif
    @if($employee->on_leave !== null)
        <div class="form-group">
            <label for="employee_on_leave_date" class="col-md-5 no-padding-both control-label">On Leave:</label>
            <div class="col-md-4">
                <div class="input-group">
                    {!! Form::text('employment_status[on_leave]', (!is_null($employee->on_leave) ? date('m/d/Y', strtotime($employee->on_leave)) : null), ['id' => 'employee_on_leave_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                    <span class="input-group-addon"><label for="employee_on_leave_date"><i class="fa fa-calendar"></i></label></span>
                </div>
            </div>
        </div>
    @endif
    <div class="form-group">
        <label class="col-md-5 no-padding-both control-label">Terminated:</label>
        <div class="col-md-3 padding-top">
            @if(!is_null ($employee->terminated) )
                {{ date('m/d/Y', strtotime($employee->terminated)) }}
            @endif
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6 no-padding-both">
                    @if ($employee->status != 'enabled')
                        <a href="/user/employees/{{ $employee->id }}/re-hire" class="btn btn-primary btn-xs">Re-Hire</a>
                    @elseif ($employee->status == 'enabled')
                        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                            {!! Form::button('Terminate', ['class' => 'btn btn-danger btn-xs', 'data-toggle' => 'modal', 'data-target' => '#modalTerminateAccountWarningEnable', 'disabled' => 'disabled']) !!}
                        @else 
                            {!! Form::button('Terminate', ['class' => 'btn btn-danger btn-xs', 'data-toggle' => 'modal', 'data-target' => '#modalTerminateAccountWarningEnable']) !!}
                        @endif
                    @endif
                </div>
                <div class="col-md-6 padding-top-3">{!! $help->button("u3051") !!}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="position_title" class="col-md-5 no-padding-both control-label">Position Title:</label>
        <div class="col-md-5 no-padding-right">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                    {!! Form::text('employment_status[position_title]', $employee->position_title, ['id' => 'employee_rehire_date', 'class' => 'form-control', 'placeholder' => 'Position Title', 'disabled' => 'disabled']) !!}
                @else 
                    {!! Form::text('employment_status[position_title]', $employee->position_title, ['id' => 'employee_rehire_date', 'class' => 'form-control', 'placeholder' => 'Position Title']) !!}
                @endif
            
        </div>
    </div>

    <div class="form-group">
        <label for="employee_salary" class="col-md-5 no-padding-both control-label">Salary:</label>
        <div class="col-md-4 padding-top">
            @if($employee->salary)
                <span>${{ number_format($employee->salary->salary, 2) }} per {{ $employee->salary->rate }}</span>
            @else
                <span>$ 0.00</span>
            @endif
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-6 no-padding-both">
                    @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        {!! Form::button('Edit', ['data-toggle' => 'modal', 'data-target' => '#modalSalary', 'class' => 'btn btn-default btn-primary btn-xs modal-button', 'disabled' => 'disabled']) !!}
                    @else 
                        {!! Form::button('Edit', ['data-toggle' => 'modal', 'data-target' => '#modalSalary', 'class' => 'btn btn-default btn-primary btn-xs modal-button']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_classification" class="col-md-5 no-padding-both control-label">Emp. Classification:</label>
        <div class="col-md-4 padding-top capitalize">
            {{ $employee->classification ? $employee->classification->name : 'n/a' }}
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-6 no-padding-both">
                    @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        {!! Form::button('Edit', ['id' => 'edit_classification', 'data-toggle' => 'modal', 'data-target' => '#modalClassifications', 'class' => 'btn btn-default btn-primary btn-xs modal-button', 'disabled' => 'disabled']) !!}
                    @else 
                        {!! Form::button('Edit', ['id' => 'edit_classification', 'data-toggle' => 'modal', 'data-target' => '#modalClassifications', 'class' => 'btn btn-default btn-primary btn-xs modal-button']) !!}
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_status" class="col-md-5 no-padding-both control-label">Employee Status:</label>
        <div class="col-md-6">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                    {!! Form::select('employment_status[employee_status]', $employeeStatus, $employee->employee_status, ['id' => 'employee_status', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                @else 
                    {!! Form::select('employment_status[employee_status]', $employeeStatus, $employee->employee_status, ['id' => 'employee_status', 'class' => 'form-control']) !!}
                @endif
            
        </div>
    </div>
    <div class="col-md-12 text-center">
        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
            {!! Form::button('Save', ['type' => 'submit', 'class' => 'btn btn-default btn-xs btn-primary', 'name' => 'action', 'value' => 'employment_status', 'disabled' => 'disabled']) !!}
        @else 
            {!! Form::button('Save', ['type' => 'submit', 'class' => 'btn btn-default btn-xs btn-primary', 'name' => 'action', 'value' => 'employment_status']) !!}
        @endif
        
    </div>

    <!-- Terminate Account Modal -->
    @include('user.employees.modals.employee_termination')

<!-- Salary Modal-->
    @include('user.employees.modals.salary')

<!-- Employee Classification -->
    @include('user.employees.modals.employee_classification')
</div>
