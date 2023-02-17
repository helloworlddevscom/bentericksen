@foreach($employee->emergencyContacts as $contact)
    @if($contact->is_primary)
        <div class="row primary_contact">
            {!! Form::hidden('emergency[primary][id]', $contact->id) !!}
            <div class="col-md-12 text-center emp-heading even">
                <h5>Primary Emergency Contact</h5>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_primary_name" class="col-md-3 col-md-offset-1 control-label">Name:</label>
                        <div class="col-md-6">
                            {!! Form::text('emergency[primary][name]', $contact->name, ['class' => 'form-control', 'placeholer' => 'name']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_primary_phone1" class="col-md-3 col-md-offset-1 control-label">Primary Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[primary][phone1_type]', $phoneTypes, $contact->phone1_type, ['id' => 'emergency_primary_phone1_type', 'class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7">
                                    {!! Form::text('emergency[primary][phone1]', $contact->phone1, ['id' => 'emergency_primary_phone1', 'class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_primary_phone2" class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[primary][phone2_type]', $phoneTypes, $contact->phone2_type, ['id' => 'emergency_primary_phone2_type', 'class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7">
                                    {!! Form::text('emergency[primary][phone2]', $contact->phone2, ['id' => 'emergency_primary_phone2', 'class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_primary_phone3" class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[primary][phone3_type]', $phoneTypes, $contact->phone3_type, ['id' => 'emergency_primary_phone3_type', 'class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7">
                                    {!! Form::text('emergency[primary][phone3]', $contact->phone3, ['id' => 'emergency_primary_phone3', 'class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_p_relationship_type" class="col-md-3 col-md-offset-1 control-label">Relationship:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[primary][relationship]', ['' => '- Select One -', 'spouse' => 'Spouse', 'parent' => 'Parent', 'other' => 'Other'],  in_array($contact->relationship, ['spouse', 'parent', '']) ? $contact->relationship : 'other', ['id' => 'emergency_select_' . $contact->id, 'class' => 'form-control emergency_relationship', 'data-target' => '#emergency_contact_' . $contact->id]) !!}
                                </div>
                                <div class="col-md-7 primary_other_contact">
                                    {!! Form::text('emergency[primary][relationship]', !in_array($contact->relationship, ['spouse', 'parent', '']) ? $contact->relationship : '', ['id' => 'emergency_contact_' . $contact->id, 'class' => in_array($contact->relationship, ['spouse', 'parent']) ? 'form-control hidden' : 'form-control', (in_array($contact->relationship, ['spouse', 'parent', '']) ? 'disabled' : ''), 'required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {!! Form::hidden('emergency[secondary][id]', $contact->id) !!}
        <div class="row secondary_contact">
            <div class="col-md-12 text-center emp-heading even">
                <h5>Secondary Emergency Contact</h5>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_secondary_name" class="col-md-3 col-md-offset-1 control-label">Name:</label>
                        <div class="col-md-6">
                            {!! Form::text('emergency[secondary][name]', $contact->name, ['class' => 'form-control', 'placeholer' => 'name']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_secondary_phone1" class="col-md-3 col-md-offset-1 control-label">Primary Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[secondary][phone1_type]', $phoneTypes, $contact->phone1_type, ['id' => 'emergency_primary_relation_type', 'class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7 primary_other_contact">
                                    {!! Form::text('emergency[secondary][phone1]', $contact->phone1, ['id' => 'emergency_primary_relationship', 'class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_secondary_phone2" class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[secondary][phone2_type]', $phoneTypes, $contact->phone2_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7 primary_other_contact">
                                    {!! Form::text('emergency[secondary][phone2]', $contact->phone2, ['class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_secondary_phone3" class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[secondary][phone3_type]', $phoneTypes, $contact->phone3_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-7 primary_other_contact">
                                    {!! Form::text('emergency[secondary][phone3]', $contact->phone3, ['class' => 'form-control phone_number']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="emergency_secondary_phone3" class="col-md-3 col-md-offset-1 control-label">Relationship:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    {!! Form::select('emergency[secondary][relationship]', ['' => '- Select One -', 'spouse' => 'Spouse', 'parent' => 'Parent', 'other' => 'Other'], in_array($contact->relationship, ['spouse', 'parent', '']) ? $contact->relationship : 'other', ['id' => 'emergency_select_' . $contact->id, 'class' => 'form-control emergency_relationship', 'data-target' => '#emergency_contact_' . $contact->id]) !!}
                                </div>
                                <div class="col-md-7 primary_other_contact">
                                    {!! Form::text('emergency[secondary][relationship]', !in_array($contact->relationship, ['spouse', 'parent', '']) ? $contact->relationship : '', ['id' => 'emergency_contact_' . $contact->id, 'class' =>  in_array($contact->relationship, ['spouse', 'parent']) ? 'form-control hidden' : 'form-control', (in_array($contact->relationship, ['spouse', 'parent', '']) ? 'disabled' : ''), 'required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
