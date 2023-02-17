@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                {!! Form::open(['route' => 'user.employees.store', 'class' => 'form-horizontal']) !!}
                <div class="col-md-12 text-center">
                    <h3>New Employee</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="form-group">
                        <label for="employee_name" class="col-md-4 control-label"><span class="text-danger">*</span>Name:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5 no-padding-right">
                                    {!! $errors->first('contact.first_name', '<span style="color:red;">:message</span>') !!}
                                    {!! Form::text('contact[first_name]', Request::old('contact[first_name]'), ['class' => 'form-control', 'placeholder' => 'First']) !!}
                                </div>
                                <div class="col-md-2 no-padding-both">
                                    {!! $errors->first('contact.middle_name', '<span style="color:red;">:message</span>') !!}
                                    {!! Form::text('contact[middle_name]', Request::old('contact[middle_name]'), ['class' => 'form-control', 'placeholder' => 'Middle']) !!}
                                </div>
                                <div class="col-md-5 no-padding-left">
                                    {!! $errors->first('contact.last_name', '<span style="color:red;">:message</span>') !!}
                                    {!! Form::text('contact[last_name]', Request::old('contact[last_name]'), ['class' => 'form-control', 'placeholder' => 'Last']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prefix" class="col-md-4 control-label">Prefix:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::text('contact[prefix]', Request::old('contact[prefix]'), ['class' => 'form-control', 'placeholder' => 'Prefix']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="suffix" class="col-md-4 control-label">Suffix:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::text('contact[suffix]', Request::old('contact[suffix]'), ['class' => 'form-control', 'placeholder' => 'Suffix']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address1" class="col-md-4 control-label"><span class="text-danger">*</span> Address
                            1:</label>
                        <div class="col-md-6">
                            {!! $errors->first('contact.address1', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('contact[address1]', Request::old('contact[address1]'), ['id' => 'address1', 'class' => 'form-control', 'placeholder' => 'Address 1']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address2" class="col-md-4 control-label">Address 2:</label>
                        <div class="col-md-6">
                            {!! Form::text('contact[address2]', Request::old('contact[address2]'), ['id' => 'address2', 'class' => 'form-control', 'placeholder' => 'Address 2']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="city" class="col-md-4 control-label"><span class="text-danger">*</span>
                            City/State/ZIP:</label>
                        <div class="col-md-2">
                            {!! $errors->first('contact.city', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('contact[city]', Request::old('contact[city]'), ['id' => 'city', 'class' => 'form-control', 'placeholder' => 'City']) !!}
                        </div>

                        <div class="col-md-2">
                            {!! $errors->first('contact.state', '<span style="color:red;">:message</span>') !!}
                            {!! Form::select('contact[state]', $states, Request::old('contact[state]'), ['id' => 'state', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-2">
                            {!! $errors->first('contact.postal_code', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('contact[postal_code]', Request::old('contact[postal_code]'), ['id' => 'postal_code', 'class' => 'form-control zip_code', 'placeholder' => 'ZIP']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone1" class="col-md-4 control-label"><span class="text-danger">*</span> Phone
                            1:</label>
                        <div class="col-md-2">
                            {!! Form::select('contact[phone1_type]', ['home' => 'Home', 'cell' => 'Cell'], Request::old('contact[phone1_type]'), ['id' => 'phone1_type', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! $errors->first('contact.phone1', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('contact[phone1]', Request::old('contact[phone1]'), ['id' => 'phone1', 'class' => 'form-control phone_number', 'placeholder' => 'Phone']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone2" class="col-md-4 control-label">Phone 2:</label>
                        <div class="col-md-2">
                            {!! Form::select('contact[phone2_type]', ['' => 'Select One', 'home' => 'Home', 'cell' => 'Cell'], Request::old('contact[phone2_type]'), ['id' => 'phone2_type', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::text('contact[phone2]', Request::old('contact[phone2]'), ['id' => 'phone2', 'class' => 'form-control phone_number', 'placeholder' => 'Phone']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label"><span class="text-danger">*</span>
                            Email:</label>
                        <div class="col-md-6">
                            {!! $errors->first('contact.email', '<span style="color:red;">:message</span>') !!}
                            {!! Form::email('contact[email]', Request::old('contact[email]'), ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hired" class="col-md-4 control-label"><span class="text-danger">*</span> Hired:</label>
                        <div class="col-md-2">
                            <div class="input-group">
                                {!! $errors->first('contact.hired', '<span style="color:red;">:message</span>') !!}
                                {!! Form::text('contact[hired]', Request::old('contact[hired]'), ['id' => 'hired', 'class' => 'form-control date-box date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                                <label for="hired" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="position_title" class="col-md-4 control-label">Position Title:</label>
                        <div class="col-md-6">
                            {!! Form::text('contact[position_title]', Request::old('contact[position_title]'), ['id' => 'position_title', 'class' => 'form-control', 'placeholder' => 'Position Title']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dob" class="col-md-4 control-label"><span class="text-danger">*</span> DOB:</label>
                        <div class="col-md-2">
                            <div class="input-group">
                                {!! $errors->first('contact.dob', '<span style="color:red;">:message</span>') !!}
                                {!! Form::text('contact[dob]', Request::old('contact[dob]'), ['id' => 'dob', 'class' => 'form-control date-box date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                                <label for="dob" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employee_status" class="col-md-4 no-padding-both control-label">Employee Status:</label>
                        <div class="col-md-2">
                            {!! Form::select('contact[employee_status]', $employeeStatus, Request::old('contact[employee_status]'), ['id' => 'employee_status', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="user_role" class="col-md-4 no-padding-both control-label"><span class="text-danger">*</span> Permissions Level:</label>
                        <div class="col-md-2">
                            <div class="input-group">
                                {!! $errors->first('contact.user_role', '<span style="color:red;">:message</span>') !!}
                                {!! Form::select('contact[user_role]', $roles->pluck('display_name','id'), Request::old('contact[user_role]') ? Request::old('user_role') : 5, ['id' => 'user_role', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="salary" class="col-md-4 control-label">Salary:</label>
                        <div class="col-md-6">
                            <button class="btn btn-default btn-sm btn-primary modal-button" id="salary" data-toggle="modal" data-target="#modalSalary">SALARY</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employee_contacts" class="col-md-4 control-label">Emergency Contacts:</label>
                        <div class="col-md-6">
                            <button class="btn btn-default btn-sm btn-primary modal-button" id="emergency_contact" data-toggle="modal" data-target="#modalEmergencyContact">EMERGENCY CONTACT INFORMATION</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="driver_info" class="col-md-4 control-label">Driver Information:</label>
                        <div class="col-md-2">
                            <button class="btn btn-default btn-sm btn-primary modal-button" id="driver_info" data-toggle="modal" data-target="#modalDriverInfo">DRIVER INFORMATION</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="licensure_certifications" class="col-md-4 control-label">Licensure/Certifications:</label>
                        <div class="col-md-2">
                            <button class="btn btn-default btn-sm btn-primary modal-button" id="licensure_certifications" data-toggle="modal" data-target="#modalLicensureCertifications">LICENSURE/CERTIFICATIONS</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employee_classification" class="col-md-4 control-label">Employee Classification:</label>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-primary btn-sm modal-button" id="edit_classification" data-toggle="modal" data-target="#modalClassifications">EMPLOYEE CLASSIFICATION</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="activation_email" class="col-md-4 control-label"><span class="text-danger">*</span>Send Activation Email Now?:</label>
                        <div class="col-md-6">
                            <input type="hidden" name="employee[activation_email]" value="0">
                            <input type="radio" class="activation_email" id="activation_email_yes" name="employee[activation_email]" value="1">&nbsp;&nbsp;<label class="control-label" for="activation_email_yes">YES</label>&nbsp;&nbsp;
                            <input type="radio" class="activation_email" id="activation_email_no" name="employee[activation_email]" value="0" checked>&nbsp;&nbsp;<label class="control-label" for="activation_email_no">NO</label>
                            <input type="hidden" id="employee_status" name="employee[employee_status]" value="Inactive">
                        </div>
                    </div>

                    <div class="col-md-12 text-center buttons">
                        <a href="/user/employees" class="btn btn-default btn-sm btn-primary">CANCEL</a>
                        <button type="submit" class="btn btn-default btn-sm btn-primary">SAVE</button>
                    </div>
                </div>

                <!-- MODALS -->
                @include('user.employees.modals.emergency_contact')
                @include('user.employees.modals.drivers_license')
                @include('user.employees.modals.licensure_certifications')
                @include('user.employees.modals.employee_classification')
                @include('user.employees.modals.salary')
                @include('user.modals.managerAuthConfirm')
            <!-- /MODALs -->


                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        $('.modal-button').on('click', function (e) {
            e.preventDefault();
        });

        $(".date-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+10",
        });

        $('#employee_classification').on('change', function () {
            if ($(this).find('option:selected').val() === "other") {
                $('#employee_classification_name').removeClass('hidden').attr('disabled', false);
            } else {
                $('#employee_classification_name').addClass('hidden').attr('disabled', true);
            }
        });

        $('.emergency_relationship').on('change', function () {
            var valueSelected = this.value;
            var otherInput = $(this).attr('data-target');
            var name;

            if (valueSelected == 'other') {
                name = $(this).attr('name');

                $(otherInput).removeClass('hidden');
                $(otherInput).attr('name', name);

                $(this).attr('name', '');
            } else {
                name = $(otherInput).attr('name');

                $(otherInput).addClass('hidden');
                $(otherInput).attr('name', '');

                $(this).attr('name', name);
            }
        });
    </script>
    <script src="/assets/js/employee/managerAuthModal.js"></script>
@stop
