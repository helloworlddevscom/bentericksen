@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 heading">
                    <h3>New Business Setup</h3>
                </div>
                <div class="col-md-12 content">
                    {!! Form::open(['route'	=> ['admin.business.update', $business->id], 'class' => 'form-horizontal', 'method' => 'PUT']) !!}
                    {!! Form::hidden('primary_user_id', $business->primary_user_id) !!}
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="form-group">
                        <label for="business_name" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Business Name:</label>
                        {!! $errors->first('name', '<span style="color:red;">:message</span>') !!}
                        <div class="col-md-5">
                            {!! Form::text('name', $business->name, ['id' => 'business_name', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_address1" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Address:</label>
                        <div class="col-md-5">
                            {!! $errors->first('address1', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('address1', $business->address1, ['id' => 'business_address1', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-4">
                            {!! Form::text('address2', $business->address2, ['id' => 'business_address2', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_city" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>City, State, Postal Code:</label>
                        <div class="col-md-2">
                            {!! $errors->first('city', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('city', $business->city, ['id' => 'business_city', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! $errors->first('state', '<span style="color:red;">:message</span>') !!}
                            {!! Form::select('state', $states, $business->state, ['id'	=> 'state', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! $errors->first('postal_code', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('postal_code', $business->postal_code, ['id' => 'business_postal_code', 'class' => 'form-control zip_code']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone1" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Phone 1:</label>
                        <div class="col-md-3">
                            {!! $errors->first('phone1', '<span style="color:red;">:message</span>') !!}
                            {!! Form::text('phone1', $business->phone1, ['id' => 'business_phone1', 'class' => 'form-control phone_number']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone1_type', $phoneTypes, $business->phone1_type, ['id'	=> 'business_phone1_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone2" class="col-md-3 col-md-offset-1 control-label">Phone 2:</label>
                        <div class="col-md-3">
                            {!! Form::text('phone2', $business->phone2, ['id' => 'business_phone2', 'class' => 'form-control phone_number']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone2_type', $phoneTypes, $business->phone2_type, ['id'	=> 'business_phone2_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone3" class="col-md-3 col-md-offset-1 control-label">Phone 3:</label>
                        <div class="col-md-3">
                            {!! Form::text('phone3', $business->phone3, ['id' => 'business_phone3', 'class' => 'form-control phone_number']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone3_type', $phoneTypes, $business->phone3_type, ['id'	=> 'business_phone3_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_fax" class="col-md-3 col-md-offset-1 control-label">Fax:</label>
                        <div class="col-md-3">
                            {!! Form::text('fax', $business->fax, ['id' => 'business_fax', 'class' => 'form-control phone_number']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_webite" class="col-md-3 col-md-offset-1 control-label">Website:</label>
                        <div class="col-md-5">
                            {!! Form::text('website', $business->website, ['id' => 'business_webite', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_first_name" class="col-md-3 col-md-offset-1 control-label">Primary First Name:</label>
                        <div class="col-md-5">
                            {!! Form::text('primary_first_name', $business->primary->first_name, ['id' => 'business_primary_first_name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-sm" id="edit_primary" name="edit_primary" data-target=".primary_select">Switch Primary User</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_middle_name" class="col-md-3 col-md-offset-1 control-label">Primary Middle Name:</label>
                        <div class="col-md-5">
                            {!! Form::text('primary_middle_name', $business->primary->middle_name, ['id' => 'business_primary_middle_name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('new_primary_user', $employeeNames, null, ['id' => 'new_primary_user', 'name' => 'new_primary_user', 'class' => 'form-control primary_select hidden']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_last_name" class="col-md-3 col-md-offset-1 control-label">Primary Last Name:</label>
                        <div class="col-md-5">
                            {!! Form::text('primary_last_name', $business->primary->last_name, ['id' => 'business_primary_last_name', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_prefix" class="col-md-3 col-md-offset-1 control-label">Primary Prefix:</label>
                        <div class="col-md-5">
                            {!! Form::text('primary_prefix', $business->primary->prefix, ['id' => 'business_primary_prefix', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_suffix" class="col-md-3 col-md-offset-1 control-label">Primary Suffix:</label>
                        <div class="col-md-5">
                            {!! Form::text('primary_suffix', $business->primary->suffix, ['id' => 'business_primary_suffix', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_contact_email" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Primary Contact Email:</label>
                        <div class="col-md-5">
                            {!! $errors->first('primary_email', '<span style="color:red;">:message</span>') !!}
                            {!! Form::email('primary_email', $business->primary->email, ['id' => 'business_contact_email', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_role" class="col-md-3 col-md-offset-1 control-label">Primary Type:</label>
                        <div class="col-md-3">
                            {!! Form::select('primary_role', $roles, $business->primary->roles->first()->id, ['id' => 'business_primary_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="panel panel-secondary">
                        <div class="panel-heading panel-secondary_heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_secondary1_edit">
                                   <div class="accordion-arrow__position">Secondary 1 fields</div>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_secondary1_edit" class="panel-collapse collapse">
                            <div class="form-group">
                                <label for="business_secondary_1_first_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 First Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_1_first_name', $business->secondary_1_first_name, ['id' => 'business_secondary_1_first_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_middle_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Middle Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_1_middle_name', $business->secondary_1_middle_name, ['id' => 'business_secondary_1_middle_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_last_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Last Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_1_last_name', $business->secondary_1_last_name, ['id' => 'business_secondary_1_last_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_prefix" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Prefix:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_1_prefix', $business->secondary_1_prefix, ['id' => 'business_secondary_1_prefix', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_suffix" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Suffix:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_1_suffix', $business->secondary_1_suffix, ['id' => 'business_secondary_1_suffix', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_email" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Email:</label>
                                <div class="col-md-5">
                                    {!! Form::email('secondary_1_email', $business->secondary_1_email, ['id' => 'business_secondary_1_email', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_type" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Type:</label>
                                <div class="col-md-3">
                                    {!! Form::select('secondary_1_role', $roles, $business->secondary_1_role, ['id' => 'business_secondary_1_type', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-secondary">
                        <div class="panel-heading panel-secondary_heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_secondary2_edit">
                                    <div class="accordion-arrow__position">Secondary 2 fields</div>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_secondary2_edit" class="panel-collapse collapse">
                            <div class="form-group">
                                <label for="business_secondary_2_first_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 First Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_2_first_name', $business->secondary_2_first_name, ['id' => 'business_secondary_2_first_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_middle_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Middle Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_2_middle_name', $business->secondary_2_middle_name, ['id' => 'business_secondary_2_middle_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_last_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Last Name:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_2_last_name', $business->secondary_2_last_name, ['id' => 'business_secondary_2_last_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_prefix" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Prefix:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_2_prefix', $business->secondary_2_prefix, ['id' => 'business_secondary_2_prefix', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_suffix" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Suffix:</label>
                                <div class="col-md-5">
                                    {!! Form::text('secondary_2_suffix', $business->secondary_2_suffix, ['id' => 'business_secondary_2_suffix', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_email" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Email:</label>
                                <div class="col-md-5">
                                    {!! Form::email('secondary_2_email', $business->secondary_2_email, ['id' => 'business_secondary_2_email', 'class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_type" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Type:</label>
                                <div class="col-md-3">
                                    {!! Form::select('secondary_2_role', $roles, $business->secondary_2_role, ['id' => 'business_secondary_2_type', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -->

                    <div class="form-group">
                        <label for="business_owner_login_password" class="col-md-3 col-md-offset-1 control-label">Owner Login Password:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control pass_input deny" id="business_owner_login_password" name="owner_password" value="&bull; &bull; &bull; &bull; &bull; &bull; &bull; &bull; &bull; &bull;" disabled>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-sm" id="show_pass" name="show_pass">Show/Edit Password</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_type" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Business Type:</label>
                        <div class="col-md-3">
                            {!! $errors->first('type', '<span style="color:red;">:message</span>') !!}
                            {!! Form::select('type', $industryArray, $business->type, ['id' => 'business_type', 'class' => 'form-control industry_type']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_sub_type" class="col-md-3 col-md-offset-1 control-label">Sub-Type:</label>
                        <div class="col-md-3">
                            {!! $errors->first('subtype', '<span style="color:red;">:message</span>') !!}
                            <select name="subtype" id="business_sub_type" class="form-control industry_sub_type">
                                <option value=""> - Select One -</option>
                                @foreach($industries as $key => $industry)
                                    @foreach($industry['subtype'] as $ke => $subtype)
                                        <option @if($business->subtype == $ke) selected @endif class="@if($business->type != $key)hidden @endif {{ $key }}" value="{{ $ke }}">{{ $subtype }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_consultants" class="col-md-3 col-md-offset-1 control-label">Consultant:</label>
                        <div class="col-md-3">
                            <select name="consultant_user_id" class="form-control" id="business_consultants">
                                <option value=""> - Select One -</option>
                                @foreach( $consultants as $consultant )
                                    <option @if($business->consultant_user_id == $consultant->id) selected @endif value="{{ $consultant->id }}">{{ $consultant->prefix }} {{ $consultant->first_name }} {{ $consultant->middle_name }} {{ $consultant->last_name }} {{ $consultant->suffix }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if( $business->finalized )
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="ongoing_consultant_cc" value="0"/>
                                <label>
                                    {!! Form::checkbox('ongoing_consultant_cc', 1, $business->ongoing_consultant_cc, ['id' => 'ongoing_consultant_cc']) !!}
                                    {!! Form::label('Ongoing Consultant CC') !!}
                                </label>
                            </div>
                        @endif
                    </div>

                    <input type="hidden" value="" name="referral">

                    <div class="form-group">
                        <label for="business_status" class="col-md-3 col-md-offset-1 control-label">Status:</label>
                        <div class="col-md-3">
                            {!! Form::select('status', $statuses, $business->status, ['id' => 'business_status', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_" class="col-md-3 col-md-offset-1 control-label">Date License Agree Accepted:</label>
                        <div class="col-md-5">
                            @if( is_null( $business->primary->accepted_terms ) || $business->primary->accepted_terms === '0000-00-00 00:00:00' )
                                <div class="padding-top">Never</div>
                            @else
                                <div class="padding-top">{{ date('m/d/Y', strtotime( $business->primary->accepted_terms )) }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_" class="col-md-3 col-md-offset-1 control-label">Last Owner Login Date:</label>
                        <div class="col-md-5">
                            @if(is_null($business->primary->last_login))
                                <div class="padding-top">Never</div>
                            @else
                                <div class="padding-top">{{ date('m/d/Y', strtotime( $business->primary->last_login )) }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hrdirector_enabled_yes" class="col-md-3 col-md-offset-1 control-label">Enable HR Director:</label>
                        <div class="col-md-5">
                            <label class="radio-inline">{!! Form::radio('hrdirector_enabled', 1, $business->hrdirector_enabled == 1, ['id' => 'hrdirector_enabled_yes']) !!} Yes</label>
                            <label class="radio-inline">{!! Form::radio('hrdirector_enabled', 0, $business->hrdirector_enabled == 0, ['id' => 'hrdirector_enabled_no']) !!} No</label>
                            <div class="clear: left"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bonuspro_enabled_yes" class="col-md-3 col-md-offset-1 control-label">Enable BonusPro:</label>
                        <div class="col-md-5">
                            <label class="radio-inline">{!! Form::radio('bonuspro_enabled', 1, $business->bonuspro_enabled == 1, ['id' => 'bonuspro_enabled_yes']) !!} Yes</label>
                            <label class="radio-inline">{!! Form::radio('bonuspro_enabled', 0, $business->bonuspro_enabled == 0, ['id' => 'bonuspro_enabled_no']) !!} No</label>
                            <div class="clear: left"></div>
                        </div>
                    </div>


                    <fieldset id="bonuspro_fields" @if(!$business->bonuspro_enabled && old('bonuspro_enabled') == 0) class="hidden" @endif>
                        <div class="form-group">
                            <label for="bonuspro_expiration_date" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>BonusPro Expiration Date:</label>
                            <div class="col-md-3">
                                {!! $errors->first('bonuspro_expiration_date', '<span style="color:red;">:message</span>') !!}
                                <div class="input-group">
                                    {!! Form::text('bonuspro_expiration_date',
                                    !empty($business->bonuspro_expiration_date) ?
                                    $business->bonuspro_expiration_date->format('m/d/Y') : '', [
                                        'class' => 'form-control date-picker date-box',
                                        'id' => 'bonuspro_expiration_date',
                                        'placeholder' => !empty($business->bonuspro_expiration_date) ? $business->bonuspro_expiration_date->format('m/d/Y') : '',
                                    ]) !!}
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="bonuspro_lifetime_access" value="0"/>
                                <label>
                                    {!! Form::checkbox('bonuspro_lifetime_access', 1, $business->bonuspro_lifetime_access, ['id' => 'business_bonuspro_lifetime_access']) !!} Lifetime Access
                                </label>
                                <div style="clear:left"></div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="hrdirector_fields" @if(!$business->hrdirector_enabled && old('hrdirector_enabled') == 0) class="hidden" @endif>
                        <div class="form-group">
                            <label for="business_asa_type" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>ASA Type:</label>
                            <div class="col-md-3">
                                {!! $errors->first('asa.type', '<span style="color:red;">:message</span>') !!}
                                {!! Form::select('asa[type]', $asaTypes, $business->asa->type, ['id' => 'business_asa_type', 'class' => 'form-control dataReset',  'data-selected' => Request::old('asa')['type']]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="business_asa_expiration" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>ASA Expiration Date:</label>
                            <div class="col-md-3">
                                {!! $errors->first('asa.expiration', '<span style="color:red;">:message</span>') !!}
                                <div class="input-group">
                                    <input type="text" class="form-control date-picker date-box" id="business_asa_expiration" name="asa[expiration]" placeholder="ASA Expiration Date" @if($business->asa->expiration == "") value="" @else value="{{ date('m/d/Y', strtotime( $business->asa->expiration ))  }}" @endif>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="business_" class="col-md-3 col-md-offset-1 control-label">Policy Manual Generation Date:</label>
                            <div class="col-md-5">
                                @if($business->manual_created_at->timestamp > 0)
                                    <div class="padding-top">{{ $business->manual_created_at->format('m/d/Y') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group dental-only">
                            <label class="col-md-3 col-md-offset-1 control-label">Enable SOPs:</label>
                            <div class="col-md-5">
                                <label class="radio-inline">{!! Form::radio('enable_sop', 1, $business->enable_sop == 1, ['id' => 'enabled_sop_yes']) !!} Yes</label>
                                <label class="radio-inline">{!! Form::radio('enable_sop', 0, $business->enable_sop == 0, ['id' => 'enabled_sop_no']) !!} No</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-md-offset-1 control-label">Enable Payments:</label>
                            <div class="col-md-5">
                                <label class="radio-inline">{!! Form::radio('enable_payments', 1, $business->enable_payments == 1, ['id' => 'enabled_payments_yes']) !!} Yes</label>
                                <label class="radio-inline">{!! Form::radio('enable_payments', 0, $business->enable_payments == 0, ['id' => 'enabled_payments_no']) !!} No</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="do_not_contact" value="0"/>
                                <label>
                                    {!! Form::checkbox('do_not_contact', 1, $business->do_not_contact, ['id' => 'business_do_not_contact']) !!} Do Not Contact
                                </label>
                                <div style="clear:left"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="finalized" value="0"/>
                                <label>
                                    {!! Form::checkbox('finalized', 1, $business->finalized, ['id' => 'business_finalized']) !!} The Switch
                                </label>
                                <div class="clear: left"></div>
                                <p class="form-text text-muted" id="finalizedHelp">Finalize business to allow the policy manual to be printed.</p>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group text-center buttons">
                        <a href="/admin/business" class="btn btn-xs btn-default">CANCEL</a>
                        <button type="submit" class="btn btn-xs btn-primary">SAVE</button>
                        <button type="submit" class="btn btn-xs btn-primary" name="action" value="view-as">SAVE & VIEW AS</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

