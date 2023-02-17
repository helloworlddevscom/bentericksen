@extends('employee.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/employee">Dashboard</a> >> My Information
                        </div>
                    </div>
                    <div class="row text-center border-bottom">
                        <div class="mini-heading">
                            <h4>
                                <i class="fa fa-user"> </i>
                                {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="/employee/info/{{ $employee->id }}" method="POST">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <div class="col-md-12 text-center">
                                            <h3>CONTACT INFORMATION</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mini-emp-content">
                                                <div class="row">
                                                    <label for="first_name" class="col-md-3 control-label">Name:</label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-5 no-padding-right"><input type="text"
                                                                                                          class="form-control"
                                                                                                          id="first_name"
                                                                                                          name="first_name"
                                                                                                          placeholder="First"
                                                                                                          value="{{ $employee->first_name }}">
                                                            </div>
                                                            <div class="col-md-2 no-padding-both"><input type="text"
                                                                                                         class="form-control"
                                                                                                         id="middle_name"
                                                                                                         name="middle_name"
                                                                                                         placeholder="Middle"
                                                                                                         value="{{ $employee->middle_name }}">
                                                            </div>
                                                            <div class="col-md-5 no-padding-left"><input type="text"
                                                                                                         class="form-control"
                                                                                                         id="last_name"
                                                                                                         name="last_name"
                                                                                                         placeholder="Last"
                                                                                                         value="{{ $employee->last_name }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="address1" class="col-md-3 control-label">Address
                                                        1:</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="address1"
                                                               name="address1" placeholder="Address 1"
                                                               value="{{ $employee->address1 }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="address2" class="col-md-3 control-label">Address
                                                        2:</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="address2"
                                                               name="address2" placeholder="Address 2"
                                                               value="{{ $employee->address2 }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="city"
                                                           class="col-md-3 control-label">City/St/ZIP:</label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-4 no-padding-right">
                                                                <input type="text" class="form-control" id="city"
                                                                       name="city" placeholder="City"
                                                                       value="{{ $employee->city }}">
                                                            </div>
                                                            <div class="col-md-4 no-padding-both">
                                                                {!! Form::select('state', $states, $employee->state, ['class' => 'form-control', 'id' => 'state']) !!}
                                                            </div>
                                                            <div class="col-md-4 no-padding-left">
                                                                <input type="text" class="form-control zip_code"
                                                                       id="postal_code" name="postal_code"
                                                                       placeholder="ZIP"
                                                                       value="{{ $employee->postal_code }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="phone1" class="col-md-3 control-label">Phone:</label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-4 no-padding-right">
                                                                <select class="form-control" id="phone1_type"
                                                                        name="phone1_type">
                                                                    <option value="">Select One</option>
                                                                    <option value="home">Home</option>
                                                                    <option value="cell">Cell</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8 no-padding-left">
                                                                <input type="text" class="form-control phone_number"
                                                                       id="phone1" name="phone1" placeholder="Phone"
                                                                       value="{{ $employee->phone1 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="phone2" class="col-md-3 control-label">Phone:</label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-4 no-padding-right">
                                                                <select class="form-control" id="phone2_type"
                                                                        name="phone2_type">
                                                                    <option value="">Select One</option>
                                                                    <option value="home">Home</option>
                                                                    <option value="cell">Cell</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8 no-padding-left">
                                                                <input type="text" class="form-control phone_number"
                                                                       id="phone2" name="phone2" placeholder="Phone"
                                                                       value="{{ $employee->phone2 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="email" class="col-md-3 control-label">Email:</label>
                                                    <div class="col-md-9">
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               placeholder="Email" value="{{ $employee->email }}">
                                                    </div>
                                                </div>
                                                <div style="width: 100%; text-align: center;">
                                                    <a href="#" class="btn btn-default btn-primary btn-xs modal-button" id="edit_emergency_contact">EMERGENCY CONTACTS</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit edit_emergency_contact hidden">
                                            @foreach ($employee->emergencyContacts as $key => $contact)
                                                <div class="row">
                                                    <div class="contact">
                                                        <h5 class="col-md text-center">
                                                            @if ($contact->is_primary)
                                                                Primary Emergency Contact
                                                            @else
                                                                Secondary Emergency Contact
                                                            @endif
                                                        </h5>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    {!! Form::label('emergency_' . $key . '_name', 'Name:', ['class' => 'col-md-3 control-label']); !!}
                                                                    <div class="col-md-9">
                                                                        {!! Form::hidden('emergency_contacts[' . $key . '][id]', $contact->id) !!}
                                                                        {!! Form::hidden('emergency_contacts[' . $key . '][is_primary]', $contact->is_primary) !!}
                                                                        {!! Form::text('emergency_contacts[' . $key . '][name]', $contact->name, ['id' => 'emergency_' . $key . '_name', 'class' => 'form-control', 'placeholder' => 'Name']) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    {!! Form::label('emergency_' . $key . '_phone1', 'Primary Phone:', ['class' => 'col-md-3 control-label']); !!}
                                                                    <div class="col-md-9">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                {!! Form::select('emergency_contacts[' . $key . '][phone1_type]', ['' => '- Select One -', 'cell' => 'Cell', 'home' => 'Home'], $contact->phone1_type, ['class' => 'form-control']) !!}
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                {!! Form::text('emergency_contacts[' . $key . '][phone1]', $contact->phone1, ['id' => 'emergency_' . $key . '_name', 'class' => 'form-control phone_number', 'placeholder' => '(000) 000-0000']) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    {!! Form::label('emergency_' . $key . '_phone2', 'Phone:', ['class' => 'col-md-3 control-label']); !!}
                                                                    <div class="col-md-9">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                {!! Form::select('emergency_contacts[' . $key . '][phone2_type]', ['' => '- Select One -', 'cell' => 'Cell', 'home' => 'Home'], $contact->phone2_type, ['class' => 'form-control']) !!}
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                {!! Form::text('emergency_contacts[' . $key . '][phone2]', $contact->phone2, ['id' => 'emergency_' . $key . '_name', 'class' => 'form-control phone_number', 'placeholder' => '(000) 000-0000']) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    {!! Form::label('emergency_' . $key . '_phone2', 'Phone:', ['class' => 'col-md-3 control-label']); !!}
                                                                    <div class="col-md-9">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                {!! Form::select('emergency_contacts[' . $key . '][phone2_type]', ['' => '- Select One -', 'cell' => 'Cell', 'home' => 'Home'], $contact->phone2_type, ['class' => 'form-control']) !!}
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                {!! Form::text('emergency_contacts[' . $key . '][phone2]', $contact->phone2, ['id' => 'emergency_' . $key . '_name', 'class' => 'form-control phone_number', 'placeholder' => '(000) 000-0000']) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    {!! Form::label('emergency_' . $key . '_relationship', 'Relationship:', ['class' => 'col-md-3 control-label']); !!}
                                                                    <div class="col-md-9">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                {!! Form::select('emergency_contacts[' . $key . '][relationship]', ['' => '- Select One -', 'spouse' => 'Spouse', 'parent' => 'Parent', 'other' => 'Other'], in_array($contact->relationship, ['spouse', 'parent', '']) ? $contact->relationship : 'other', ['class' => 'form-control emergency_relationship', 'data-target' => 'emergency_' . $key . '_relationship_other']) !!}
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                {!! Form::text('emergency_contacts[' . $key . '][relationship_other]', (in_array($contact->relationship, ['spouse', 'parent', '']) ? '' : $contact->relationship) , ['id' => 'emergency_' . $key . '_relationship_other', 'class' => (in_array($contact->relationship, ['spouse', 'parent', '']) ? 'form-control hidden' : 'form-control'), (in_array($contact->relationship, ['spouse', 'parent', '']) ? 'disabled' : ''), 'placeholder' => 'Other']) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row text-center">
                                            <a href="/employee" type="button" class="btn btn-default btn-primary btn-xs">CANCEL</a>
                                            <button type="submit" class="btn btn-default btn-primary btn-xs">SAVE</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        $('.modal-button').on('click', function () {
            $('.edit_emergency_contact').toggleClass('hidden');
        });

        $('.emergency_relationship').on('change', function () {
            var valueSelected = this.value;
            var otherInput = $(this).attr('data-target');

            if (valueSelected === 'other') {
                $('#' + otherInput).attr('disabled', false).removeClass('hidden');
            } else {
                $('#' + otherInput).attr('disabled', true).addClass('hidden');
            }
        });

        $('#emergency_s_relationship_type').on('change', function () {
            if ($(this).val() == 'other') {
                $('.secondary_other_contact').removeClass('hidden');
            } else {
                $('.secondary_other_contact').addClass('hidden');
            }
        });
    </script>
@stop
