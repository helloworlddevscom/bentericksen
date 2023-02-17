<div class="row primary_contact">
    <h5 class="col-md-12 text-center">Primary Emergency Contact</h5>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_primary_name"
                       class="col-md-3 col-md-offset-1 control-label">Name:</label>
                <div class="col-md-6">
                    {!! Form::text('emergency[primary][name]', null, ['id' => 'emergency_primary_name', 'class' => 'form-control', 'placeholder' => 'Name']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_primary_phone1"
                       class="col-md-3 col-md-offset-1 control-label">Primary
                    Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[primary][phone1_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[primary][phone1]', null, ['id' => 'emergency_primary_phone1', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_primary_phone2"
                       class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[primary][phone2_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[primary][phone2]', null, ['id' => 'emergency_primary_phone2', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_primary_phone3"
                       class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[primary][phone3_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[primary][phone3]', null, ['id' => 'emergency_primary_phone3', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_p_relationship_type"
                       class="col-md-3 col-md-offset-1 control-label">Relationship:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[primary][relationship]', ['' => '- Select One -', 'spouse' => 'Spouse', 'parent' => 'Parent', 'other' => 'Other'], '', ['id' => 'emergency_select', 'class' => 'form-control emergency_relationship', 'data-target' => '#emergency_contact_primary']) !!}
                        </div>
                        <div class="col-md-7 primary_other_contact">
                            {!! Form::text('emergency[primary][relationship]', null, ['id' => 'emergency_contact_primary', 'class' => 'form-control hidden'], 'disabled', 'required') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row secondary_contact">
    <h5 class="col-md-12 text-center">Secondary Emergency Contact</h5>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_secondary_name"
                       class="col-md-3 col-md-offset-1 control-label">Name:</label>
                <div class="col-md-6">
                    {!! Form::text('emergency[secondary][name]', null, ['id' => 'emergency_secondary_name', 'class' => 'form-control', 'placeholder' => 'Name']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_secondary_phone1"
                       class="col-md-3 col-md-offset-1 control-label">Primary
                    Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[secondary][phone1_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[secondary][phone1]', null, ['id' => 'emergency_secondary_phone1', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_secondary_phone2"
                       class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[secondary][phone2_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[secondary][phone2]', null, ['id' => 'emergency_secondary_phone2', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_secondary_phone3"
                       class="col-md-3 col-md-offset-1 control-label">Phone:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[secondary][phone3_type]', $phoneTypes, null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-7">
                            {!! Form::text('emergency[secondary][phone3]', null, ['id' => 'emergency_secondary_phone3', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="emergency_secondary_phone3"
                       class="col-md-3 col-md-offset-1 control-label">Relationship:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            {!! Form::select('emergency[secondary][relationship]', ['' => '- Select One -', 'spouse' => 'Spouse', 'parent' => 'Parent', 'other' => 'Other'], '', ['id' => 'emergency_select_2', 'class' => 'form-control emergency_relationship', 'data-target' => '#emergency_contact_secondary']) !!}
                        </div>
                        <div class="col-md-7 primary_other_contact">
                            {!! Form::text('emergency[secondary][relationship]', null, ['id' => 'emergency_contact_secondary', 'class' => 'form-control hidden'], 'disabled', 'required') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>