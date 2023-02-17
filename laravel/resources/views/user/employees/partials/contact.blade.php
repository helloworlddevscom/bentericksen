<div class="col-md-12 text-center">
    <div class="col-md-12 text-center emp-heading even">
        <h3>CONTACT INFORMATION</h3>
    </div>
</div>
<div class="col-md-12">
    @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
    <div class="form-group">
        <label for="employee_first_name" class="col-md-3 no-padding-both control-label">Name:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::text('contact[first_name]', $employee->first_name, ['id' => 'employee_first_name', 'class' => 'form-control', 'placeholder' => 'First', 'disabled' => 'disabled']) !!}
                </div>
                <div class="col-md-2 no-padding-both">
                    {!! Form::text('contact[middle_name]', $employee->middle_name, ['id' => 'employee_middle_name', 'class' => 'form-control', 'placeholder' => 'Middle', 'disabled' => 'disabled']) !!}
                </div>
                <div class="col-md-5 no-padding-both">
                    {!! Form::text('contact[last_name]', $employee->last_name, ['id' => 'employee_last_name', 'class' => 'form-control', 'placeholder' => 'Last', 'disabled' => 'disabled']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 no-padding-right">
                    {!! Form::text('contact[prefix]', $employee->prefix, ['id' => 'employee_prefix', 'class' => 'form-control', 'placeholder' => 'Prefix', 'disabled' => 'disabled']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 no-padding-right">
                    {!! Form::text('contact[suffix]', $employee->suffix, ['id' => 'employee_suffix', 'class' => 'form-control', 'placeholder' => 'Suffix', 'disabled' => 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_address_1" class="col-md-3 no-padding-both control-label">Address 1:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[address1]', $employee->address1, ['id' => 'employee_address_1', 'class' => 'form-control', 'placeholder' => 'Address 1', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="employee_address_2" class="col-md-3 no-padding-both control-label">Address 2:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[address2]', $employee->address2, ['id' => 'employee_address_2', 'class' => 'form-control', 'placeholder' => 'Address 2', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="employee_city" class="col-md-3 no-padding-both control-label">City/St/ZIP:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    <input type="text" class="form-control" id="employee_city" name="contact[city]" placeholder="City" value="{{ $employee->city }}" disabled>
                </div>
                <div class="col-md-4 no-padding-both">
                    {!! Form::select('contact[state]', $states, $employee->state, ['id' => 'state', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                </div>
                <div class="col-md-3 no-padding-both">
                    <input type="text" class="form-control zip_code" id="employee_zip" name="contact[postal_code]" placeholder="ZIP" value="{{ $employee->postal_code }}" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_phone1" class="col-md-3 no-padding-both control-label">Phone:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::select('contact[phone1_type]', ['home' => 'Home', 'cell' => 'Cell'], $employee->phone1_type, ['id' => 'employee_phone1_type', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                </div>
                <div class="col-md-7 no-padding-both">
                    {!! Form::text('contact[phone1]', $employee->phone1, ['id' => 'employee_phone1', 'class' => 'form-control phone_number', 'placeholder' => 'Phone', 'disabled' => 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_phone2" class="col-md-3 no-padding-both control-label">Phone:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::select('contact[phone2_type]', ['home' => 'Home', 'cell' => 'Cell'], $employee->phone2_type, ['id' => 'employee_phone1_type', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                </div>
                <div class="col-md-7 no-padding-both">
                    {!! Form::text('contact[phone2]', $employee->phone2, ['id' => 'employee_phone2', 'class' => 'form-control phone_number', 'placeholder' => 'Phone', 'disabled' => 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_email" class="col-md-3 no-padding-both control-label">Email:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[email]', $employee->email, ['id' => 'employee_email', 'class' => 'form-control', 'placeholder' => 'Email', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="col-md-12 text-center">
        {!! Form::button('SAVE', ['class' => 'btn btn-default btn-xs btn-primary', 'type' => 'submit', 'name' => 'action', 'value' => 'contact', 'disabled' => 'disabled']) !!}
    </div>
    
    @else 
    <div class="form-group">
        <label for="employee_first_name" class="col-md-3 no-padding-both control-label">Name:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::text('contact[first_name]', $employee->first_name, ['id' => 'employee_first_name', 'class' => 'form-control', 'placeholder' => 'First']) !!}
                </div>
                <div class="col-md-2 no-padding-both">
                    {!! Form::text('contact[middle_name]', $employee->middle_name, ['id' => 'employee_middle_name', 'class' => 'form-control', 'placeholder' => 'Middle']) !!}
                </div>
                <div class="col-md-5 no-padding-both">
                    {!! Form::text('contact[last_name]', $employee->last_name, ['id' => 'employee_last_name', 'class' => 'form-control', 'placeholder' => 'Last']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 no-padding-right">
                    {!! Form::text('contact[prefix]', $employee->prefix, ['id' => 'employee_prefix', 'class' => 'form-control', 'placeholder' => 'Prefix']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 no-padding-right">
                    {!! Form::text('contact[suffix]', $employee->suffix, ['id' => 'employee_suffix', 'class' => 'form-control', 'placeholder' => 'Suffix']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_address_1" class="col-md-3 no-padding-both control-label">Address 1:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[address1]', $employee->address1, ['id' => 'employee_address_1', 'class' => 'form-control', 'placeholder' => 'Address 1']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="employee_address_2" class="col-md-3 no-padding-both control-label">Address 2:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[address2]', $employee->address2, ['id' => 'employee_address_2', 'class' => 'form-control', 'placeholder' => 'Address 2']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="employee_city" class="col-md-3 no-padding-both control-label">City/St/ZIP:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    <input type="text" class="form-control" id="employee_city" name="contact[city]" placeholder="City" value="{{ $employee->city }}">
                </div>
                <div class="col-md-4 no-padding-both">
                    {!! Form::select('contact[state]', $states, $employee->state, ['id' => 'state', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-3 no-padding-both">
                    <input type="text" class="form-control zip_code" id="employee_zip" name="contact[postal_code]" placeholder="ZIP" value="{{ $employee->postal_code }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_phone1" class="col-md-3 no-padding-both control-label">Phone:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::select('contact[phone1_type]', ['home' => 'Home', 'cell' => 'Cell'], $employee->phone1_type, ['id' => 'employee_phone1_type', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-7 no-padding-both">
                    {!! Form::text('contact[phone1]', $employee->phone1, ['id' => 'employee_phone1', 'class' => 'form-control phone_number', 'placeholder' => 'Phone']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_phone2" class="col-md-3 no-padding-both control-label">Phone:</label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-5 no-padding-right">
                    {!! Form::select('contact[phone2_type]', ['home' => 'Home', 'cell' => 'Cell'], $employee->phone2_type, ['id' => 'employee_phone1_type', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-7 no-padding-both">
                    {!! Form::text('contact[phone2]', $employee->phone2, ['id' => 'employee_phone2', 'class' => 'form-control phone_number', 'placeholder' => 'Phone']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="employee_email" class="col-md-3 no-padding-both control-label">Email:</label>
        <div class="col-md-8 no-padding-right">
            {!! Form::text('contact[email]', $employee->email, ['id' => 'employee_email', 'class' => 'form-control', 'placeholder' => 'Email']) !!}
        </div>
    </div>
    <div class="col-md-12 text-center">
        {!! Form::button('SAVE', ['class' => 'btn btn-default btn-xs btn-primary', 'type' => 'submit', 'name' => 'action', 'value' => 'contact']) !!}
    </div>
    @endif
    
</div>