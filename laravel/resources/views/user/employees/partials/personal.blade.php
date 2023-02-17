<div class="col-md-12 text-center">
    <div class="col-md-12 text-center emp-heading even">
        <h3>PERSONAL</h3>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="employee_dob" class="col-md-5 no-padding-both control-label">Date of Birth:</label>
        <div class="col-md-5">
            <div class="input-group">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                    {!! Form::text('personal[dob]', (!is_null($employee->dob) ? date('m/d/Y', strtotime($employee->dob)) : null), ['id' => 'employee_dob', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy', 'disabled' => 'disabled']) !!}
                @else 
                    {!! Form::text('personal[dob]', (!is_null($employee->dob) ? date('m/d/Y', strtotime($employee->dob)) : null), ['id' => 'employee_dob', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                @endif
                <span class="input-group-addon"><label for="employee_dob"><i class="fa fa-calendar"></i></label></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_age" class="col-md-5 no-padding-both control-label">Age:</label>
        <div class="col-md-4 padding-top">
            <span class="employee_age">{{ ($employee->dob ? $employee->dob->age : null) }}</span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12 text-center">
            @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                {!! Form::button('SAVE', ['class' => 'btn btn-default btn-xs btn-primary', 'type' => 'submit', 'name' => 'action', 'value' => 'personal', 'disabled' => 'disabled']) !!}
            @else 
            {!! Form::button('SAVE', ['class' => 'btn btn-default btn-xs btn-primary', 'type' => 'submit', 'name' => 'action', 'value' => 'personal']) !!}
            @endif
            
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12 text-center emp-buttons">
            <div class="row">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('EMERGENCY CONTACT', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalEmergencyContact', 'disabled' => 'disabled']) !!}
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('DRIVER\'S LICENSE', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalDriverInfo', 'disabled' => 'disabled']) !!}
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('LICENSURE / CERTIFICATION', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalLicensureCertifications', 'id' => 'edit_licensure_certification', 'disabled' => 'disabled']) !!}
                    </div>
                @else 
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('EMERGENCY CONTACT', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalEmergencyContact']) !!}
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('DRIVER\'S LICENSE', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalDriverInfo']) !!}
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        {!! Form::button('LICENSURE / CERTIFICATION', ['class' => 'btn btn-default btn-primary btn-xs modal-button', 'data-toggle' => 'modal', 'data-target' => '#modalLicensureCertifications', 'id' => 'edit_licensure_certification']) !!}
                    </div>
                @endif
                
            </div>
        </div>
    </div>
    <!-- Emergency Contacts Modal -->
    @include('user.employees.modals.emergency_contact')

    <!-- Drivers License Info -->
    @include('user.employees.modals.drivers_license')

    <!-- Licensure Certifications -->
    @include('user.employees.modals.licensure_certifications')
</div>