@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 heading">
                    <h3>New Business Setup</h3>
                </div>
                <div class="col-md-12 content">
                    {!! Form::open(['route' => 'admin.business.store', 'id' => 'publishForm', 'class' => 'form-horizontal']) !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <p><b>An error has occurred. Please correct the following:</b></p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="business_name" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Business Name:</label>
                        <div class="col-md-5">
                            {!! $errors->first('name', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_name" name="name" placeholder="Business Name" value="{{Request::old('name')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_address1" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Address:</label>
                        <div class="col-md-5">
                            {!! $errors->first('address1', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_address1" name="address1" placeholder="Business Address" value="{{Request::old('address1')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_address2" class="col-md-3 col-md-offset-1 control-label">Address 2:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="business_address2" name="address2" placeholder="Business Address 2" value="{{Request::old('address2')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_city" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>City, State, Postal Code:</label>
                        <div class="col-md-2">
                            {!! $errors->first('city', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_city" name="city" placeholder="City" value="{{Request::old('city')}}">
                        </div>
                        <div class="col-md-2">
                            {!! $errors->first('state', '<span style="color:red;">:message</span>') !!}
                            <select class="form-control dataReset" id="business_state" name="state" data-selected="{{Request::old('state')}}" value="">
                                <option value=""> - Select One -</option>
                                <option value='AL'>Alabama</option>
                                <option value='AK'>Alaska</option>
                                <option value='AZ'>Arizona</option>
                                <option value='AR'>Arkansas</option>
                                <option value='CA'>California</option>
                                <option value='CO'>Colorado</option>
                                <option value='CT'>Connecticut</option>
                                <option value='DE'>Delaware</option>
                                <option value='DC'>District of Columbia</option>
                                <option value='FL'>Florida</option>
                                <option value='GA'>Georgia</option>
                                <option value='HI'>Hawaii</option>
                                <option value='ID'>Idaho</option>
                                <option value='IL'>Illinois</option>
                                <option value='IN'>Indiana</option>
                                <option value='IA'>Iowa</option>
                                <option value='KS'>Kansas</option>
                                <option value='KY'>Kentucky</option>
                                <option value='LA'>Louisiana</option>
                                <option value='ME'>Maine</option>
                                <option value='MD'>Maryland</option>
                                <option value='MA'>Massachusetts</option>
                                <option value='MI'>Michigan</option>
                                <option value='MN'>Minnesota</option>
                                <option value='MS'>Mississippi</option>
                                <option value='MO'>Missouri</option>
                                <option value='MT'>Montana</option>
                                <option value='NE'>Nebraska</option>
                                <option value='NV'>Nevada</option>
                                <option value='NH'>New Hampshire</option>
                                <option value='NJ'>New Jersey</option>
                                <option value='NM'>New Mexico</option>
                                <option value='NY'>New York</option>
                                <option value='NC'>North Carolina</option>
                                <option value='ND'>North Dakota</option>
                                <option value='OH'>Ohio</option>
                                <option value='OK'>Oklahoma</option>
                                <option value='OR'>Oregon</option>
                                <option value='PA'>Pennsylvania</option>
                                <option value='RI'>Rhode Island</option>
                                <option value='SC'>South Carolina</option>
                                <option value='SD'>South Dakota</option>
                                <option value='TN'>Tennessee</option>
                                <option value='TX'>Texas</option>
                                <option value='UT'>Utah</option>
                                <option value='VT'>Vermont</option>
                                <option value='VA'>Virginia</option>
                                <option value='WA'>Washington</option>
                                <option value='WV'>West Virginia</option>
                                <option value='WI'>Wisconsin</option>
                                <option value='WY'>Wyoming</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            {!! $errors->first('postal_code', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control zip_code" id="business_postal_code" name="postal_code" placeholder="Postal Code" value="{{Request::old('postal_code')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone1" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Phone 1:</label>
                        <div class="col-md-3">
                            {!! $errors->first('phone1', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control phone_number" id="business_phone1" name="phone1" placeholder="Phone 1" value="{{Request::old('phone1')}}">
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone1_type', $phoneTypes, Request::old('phone1_type'), ['id' => 'business_phone1_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone2" class="col-md-3 col-md-offset-1 control-label">Phone 2:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control phone_number" id="business_phone2" name="phone2" placeholder="Phone 2" value="{{Request::old('phone2')}}">
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone2_type', $phoneTypes, Request::old('phone2_type'), ['id' => 'business_phone2_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_phone3" class="col-md-3 col-md-offset-1 control-label">Phone 3:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control phone_number" id="business_phone3" name="phone3" placeholder="Phone 3" value="{{Request::old('phone3')}}">
                        </div>
                        <div class="col-md-2">
                            {!! Form::select('phone3_type', $phoneTypes, Request::old('phone3_type'), ['id' => 'business_phone3_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_fax" class="col-md-3 col-md-offset-1 control-label">Fax:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control phone_number" id="business_fax" name="fax" placeholder="Fax" value="{{Request::old('fax')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_webite" class="col-md-3 col-md-offset-1 control-label">Business Website:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="business_webite" name="website" placeholder="Business Website" value="{{Request::old('website')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_fullname" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Primary First Name:</label>
                        <div class="col-md-5">
                            {!! $errors->first('primary_first_name', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_primary_fullname" name="primary_first_name" placeholder="Primary First Name" value="{{Request::old('primary_first_name')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_fullname" class="col-md-3 col-md-offset-1 control-label">Primary Middle Name:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="business_primary_middle_name" name="primary_middle_name" placeholder="Primary Middle Name" value="{{Request::old('primary_middle_name')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_last_name" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Primary Last Name:</label>
                        <div class="col-md-5">
                            {!! $errors->first('primary_last_name', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_primary_last_name" name="primary_last_name" placeholder="Primary Last Name" value="{{Request::old('primary_last_name')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_prefix" class="col-md-3 col-md-offset-1 control-label">Primary Prefix:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="business_primary_prefix" name="primary_prefix" placeholder="Primary Prefix" value="{{Request::old('primary_prefix')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_suffix" class="col-md-3 col-md-offset-1 control-label">Primary Suffix:</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="business_primary_suffix" name="primary_suffix" placeholder="Primary Suffix" value="{{Request::old('primary_suffix')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_contact_email" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Primary Contact Email:</label>
                        <div class="col-md-5">
                            {!! $errors->first('primary_email', '<span style="color:red;">:message</span>') !!}
                            <input type="email" class="form-control" id="business_contact_email" name="primary_email" placeholder="Primary Contact Email" value="{{Request::old('primary_email')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_primary_type" class="col-md-3 col-md-offset-1 control-label">Primary Type:</label>
                        <div class="col-md-3">
                            {!! $errors->first('primary_role', '<span style="color:red;">:message</span>') !!}
                            {!! Form::select('primary_role', $roles, Request::old('primary_role') ? Request::old('primary_role') : 2, ['id' => 'business_primary_type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="panel panel-secondary">
                        <div class="panel-heading panel-secondary_heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_secondary1">
                                    <div class="accordion-arrow__position">Secondary 1 fields</div>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_secondary1" class="panel-collapse collapse">

                            <div class="form-group">
                                <label for="business_secondary_1_first_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 First Name:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_1_first_name" name="secondary_1_first_name" placeholder="Secondary 1 First Name" value="{{Request::old('secondary_1_first_name')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_middle_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Middle Name:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_1_middle_name" name="secondary_1_middle_name" placeholder="Secondary 1 Middle Name" value="{{Request::old('secondary_1_middle_name')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_last_name" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Last Name:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_1_last_name" name="secondary_1_last_name" placeholder="Secondary 1 Last Name" value="{{Request::old('secondary_1_last_name')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_prefix" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Prefix:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_1_prefix" name="secondary_1_prefix" placeholder="Secondary 1 Prefix" value="{{Request::old('secondary_1_prefix')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_suffix" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Suffix:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_1_suffix" name="secondary_1_suffix" placeholder="Secondary 1 Suffix" value="{{Request::old('secondary_1_suffix')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_email" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Email:</label>
                                <div class="col-md-5">
                                    <input type="email" class="form-control" id="business_secondary_1_email" name="secondary_1_email" placeholder="Secondary 1 Email" value="{{Request::old('secondary_1_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_1_type" class="col-md-3 col-md-offset-1 control-label">Secondary 1 Type:</label>
                                <div class="col-md-3">
                                    {!! Form::select('secondary_1_role', $roles, Request::old('secondary_1_role') ? Request::old('secondary_1_role') : 2, ['id' => 'business_secondary_1_type', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-secondary">
                        <div class="panel-heading panel-secondary_heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_secondary2">
                                    <div class="accordion-arrow__position">Secondary 2 fields</div>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_secondary2" class="panel-collapse collapse">
                            <div class="form-group">
                                <label for="business_secondary_2_first_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 First Name:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_2_first_name" name="secondary_2_first_name" placeholder="Secondary 2 First Name" value="{{Request::old('secondary_1_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_middle_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Middle Name:</label>
                                <div class="col-md-5">
                                    <input type="email" class="form-control" id="business_secondary_2_middle_name" name="secondary_2_middle_name" placeholder="Secondary 2 Middle Name" value="{{Request::old('secondary_1_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_last_name" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Last Name:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_2_last_name" name="secondary_2_last_name" placeholder="Secondary 2 Last Name" value="{{Request::old('secondary_1_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_prefix" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Prefix:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_2_prefix" name="secondary_2_prefix" placeholder="Secondary 2 Prefix" value="{{Request::old('secondary_2_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_suffix" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Suffix:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_2_suffix" name="secondary_2_suffix" placeholder="Secondary 2 Suffix" value="{{Request::old('secondary_2_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_email" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Email:</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="business_secondary_2_email" name="secondary_2_email" placeholder="Secondary 2 Email" value="{{Request::old('secondary_2_email')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="business_secondary_2_type" class="col-md-3 col-md-offset-1 control-label">Secondary 2 Type:</label>
                                <div class="col-md-3">
                                    {!! Form::select('secondary_2_role', $roles, Request::old('secondary_2_role'), ['id' => 'business_secondary_2_type', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_owner_login_password" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Owner Login Password:</label>
                        <div class="col-md-3">
                            {!! $errors->first('owner_login_password', '<span style="color:red;">:message</span>') !!}
                            <input type="text" class="form-control" id="business_owner_login_password" name="owner_login_password" placeholder="Owner Login Password" value="{{Request::old('owner_login_password')}}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-password btn-sm">Generate Password</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_type" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>Business Type:</label>
                        <div class="col-md-3">
                            {!! $errors->first('type', '<span style="color:red;">:message</span>') !!}
                            <select name="type" id="business_type" class="form-control industry_type dataReset" data-selected="{{Request::old('type')}}">
                                <option value=""> - Select One -</option>
                                @foreach($industries as $key => $industry)
                                    <option value="{{ $key }}">{{ $industry['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_sub_type" class="col-md-3 col-md-offset-1 control-label">Sub-Type:</label>
                        <div class="col-md-3">
                            <select name="subtype" id="business_sub_type" class="form-control industry_sub_type dataReset" data-selected="{{Request::old('subtype')}}">
                                <option value=""> - Select One -</option>
                                @foreach($industries as $key => $industry)
                                    @foreach($industry['subtype'] as $ke => $subtype)
                                        <option class="hidden {{ $key }}" value="{{ $ke }}">{{ $subtype }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_consultant" class="col-md-3 col-md-offset-1 control-label">Consultant:</label>
                        <div class="col-md-3">
                            <select class="form-control dataReset" id="business_consultant" name="consultant_user_id" value="">
                                <option value=""> - Select One -</option>
                                @foreach( $consultants as $consultant )
                                    <option value="{{ $consultant->id }}">{{ $consultant->prefix }} {{ $consultant->first_name }} {{ $consultant->middle_name }} {{ $consultant->last_name }} {{ $consultant->suffix }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" value="" name="referral">

                    <div class="form-group">
                        <label for="business_status" class="col-md-3 col-md-offset-1 control-label">Status:</label>
                        <div class="col-md-3">
                            {!! Form::select('status', $statuses, Request::old('status'), ['id' => 'business_status', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hrdirector_enabled_yes" class="col-md-3 col-md-offset-1 control-label">Enable HR Director:</label>
                        <div class="col-md-5">
                            <label class="radio-inline">{!! Form::radio('hrdirector_enabled', 1, true, ['id' => 'hrdirector_enabled_yes']) !!} Yes</label>
                            <label class="radio-inline">{!! Form::radio('hrdirector_enabled', 0, false, ['id' => 'hrdirector_enabled_no']) !!} No</label>
                            <div class="clear: left"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bonuspro_enabled_yes" class="col-md-3 col-md-offset-1 control-label">Enable BonusPro:</label>
                        <div class="col-md-5">
                            <label class="radio-inline">{!! Form::radio('bonuspro_enabled', 1, false, ['id' => 'bonuspro_enabled_yes']) !!} Yes</label>
                            <label class="radio-inline">{!! Form::radio('bonuspro_enabled', 0, true, ['id' => 'bonuspro_enabled_no']) !!} No</label>
                            <div class="clear: left"></div>
                        </div>
                    </div>

                    <fieldset id="bonuspro_fields" @if(old('bonuspro_enabled') == 0) class="hidden" @endif>
                        <div class="form-group">
                            <label for="bonuspro_expiration_date" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>BonusPro Expiration Date:</label>
                            <div class="col-md-3">
                                {!! $errors->first('bonuspro_expiration_date', '<span style="color:red;">:message</span>') !!}
                                <div class="input-group">
                                    {!! Form::text('bonuspro_expiration_date', null, [
                                        'class' => 'form-control date-picker date-box',
                                        'id' => 'bonuspro_expiration_date',
                                        'placeholder' => null,
                                    ]) !!}
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="bonuspro_lifetime_access" value="0" />
                                <label>
                                    {!! Form::checkbox('bonuspro_lifetime_access', 1, Request::old('bonuspro_lifetime_access') === "" ? false : true, ['id' => 'business_bonuspro_lifetime_access']) !!} Lifetime Access
                                </label>
                                <div style="clear:left"></div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="hrdirector_fields" @if(old('hrdirector_enabled') == 1) class="hidden" @endif>
                        <div class="form-group">
                            <label for="business_asa_type" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>ASA Type:</label>
                            <div class="col-md-3">
                                {!! $errors->first('asa.type', '<span style="color:red;">:message</span>') !!}
                                {!! Form::select('asa[type]', $asaTypes, Request::old('asa')['type'], ['id' => 'business_asa_type', 'class' => 'form-control dataReset',  'data-selected' => Request::old('asa')['type']]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="business_asa_expiration" class="col-md-3 col-md-offset-1 control-label"><span class="text-danger">* </span>ASA Expiration Date:</label>
                            <div class="col-md-3">
                                {!! $errors->first('asa.expiration', '<span style="color:red;">:message</span>') !!}
                                <div class="input-group">
                                    <input type="text" class="form-control date-picker date-box" id="business_asa_expiration" name="asa[expiration]" placeholder="ASA Expiration Date" value="{{ Request::old('asa')['expiration'] }}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group dental-only">
                            <label class="col-md-3 col-md-offset-1 control-label">Enable SOPs:</label>
                            <div class="col-md-5">
                                <label class="radio-inline">{!! Form::radio('enable_sop', 1, false, ['id' => 'enabled_sop_yes']) !!} Yes</label>
                                <label class="radio-inline">{!! Form::radio('enable_sop', 0, true, ['id' => 'enabled_sop_no']) !!} No</label>
                                <div class="clear: left"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <input type="hidden" name="do_not_contact" value="0"/>
                                <label>
                                    {!! Form::checkbox('do_not_contact', 1, Request::old('do_not_contact'), ['id' => 'business_do_not_contact']) !!} Do Not Contact
                                </label>
                                <div style="clear:left"></div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group">
                        <div class="col-md-5 col-md-offset-4">
                            <label>
                                <input type="checkbox" name="send_activation_email" value="1" />
                                Send activation email now?
                            </label>
                            <div style="clear:left"></div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center buttons">
                        <a href="/admin/business" class="btn btn-default btn-xs ">CANCEL</a>
                        <button type="submit" class="btn btn-default btn-xs btn-primary"> SAVE</button>
                        <button type="submit" class="btn btn-default btn-xs btn-primary" name="action" value="view-as"> SAVE & VIEW AS</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
