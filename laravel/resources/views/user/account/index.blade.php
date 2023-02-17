@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <style>
                    .control-label {
                        padding-top: 7px;
                        margin-bottom: 0;
                        text-align: right;
                    }
                    .table-striped td {
                        width: 50%;
                    }
                    .margin-bottom-none {
                        margin-bottom: 0;
                    }
                </style>
                <div class="col-md-12 content">
                    <div class="row">
                        <h3 class="text-center">Account Information</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mini-heading">
                                <h4>Contact Information</h4>
                            </div>
                            <div class="mini-content account_box even">
                                <div class="row">
                                    <label for="business_name" class="col-md-4 col-md-offset-1 control-label">Name:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        {{ $business->name }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_address1" class="col-md-4 col-md-offset-1 control-label">Address 1:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        {{ $business->address1 }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_address2" class="col-md-4 col-md-offset-1 control-label">Address 2:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        {{ $business->address2 }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_city" class="col-md-4 col-md-offset-1 control-label">City, State, ZIP:</label>
                                    <div class="col-md-2 padding-top no-padding-left">
                                        {{ $business->city }}
                                    </div>
                                    <div class="col-md-2 padding-top text-center">
                                        {{ $business->state }}
                                    </div>
                                    <div class="col-md-2 padding-top no-padding-left">
                                        {{ $business->postal_code }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_phone1" class="col-md-4 col-md-offset-1 control-label">Phone 1:</label>
                                    <div class="col-md-4 padding-top no-padding-left">
                                        {{ $business->phone1 }}
                                    </div>
                                    <div class="col-md-3 padding-top no-padding-left">
                                        {{ ucfirst($business->phone1_type) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_phone2" class="col-md-4 col-md-offset-1 control-label">Phone 2:</label>
                                    <div class="col-md-4 padding-top no-padding-left">
                                        {{ $business->phone2 }}
                                    </div>
                                    <div class="col-md-3 padding-top no-padding-left">
                                        {{ ucfirst($business->phone2_type) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_phone3" class="col-md-4 col-md-offset-1 control-label">Phone 3:</label>
                                    <div class="col-md-4 padding-top no-padding-left">
                                        {{ $business->phone3 }}
                                    </div>
                                    <div class="col-md-3 padding-top no-padding-left">
                                        {{ ucfirst($business->phone3_type) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_fax" class="col-md-4 col-md-offset-1 control-label">Fax:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        {{ $business->fax }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_primary_type" class="col-md-4 col-md-offset-1 control-label">Type:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        {{ ucfirst($business->type) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="business_webite" class="col-md-4 col-md-offset-1 control-label">Website:</label>
                                    <div class="col-md-6 padding-top no-padding-left">
                                        <a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center buttons">
                                        <a href="/user/account/{{ $business->id }}/edit" class="btn btn-default btn-primary btn-xs modal-button" id="edit_contact_info">EDIT</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mini-heading">
                                <h4>HR Specialists</h4>
                            </div>
                            <div class="content text-center even">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <b>To schedule a consultation with an HR specialist, call: 800-679-2760</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 no-padding-both">
                            <div class="mini-heading">
                                <div class="pull-right no-padding-left padding-top">{!! $help->button("u4701") !!}</div>
                                <h4>Users Who Receive Business Emails</h4>
                            </div>
                            <div class="mini-content account_box even users">
                                <div class="odd">
                                    <div class="row">
                                        <label for="business_primary_fullname" class="col-md-4 col-md-offset-1 control-label no-padding-both">Primary/Owner Name:</label>
                                        <div class="col-md-6 padding-top">
                                            {{ $business->primary->prefix }} {{ $business->primary->first_name }} {{ $business->primary->middle_name }} {{ $business->primary->last_name }} {{ $business->primary->suffix }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="business_contact_email" class="col-md-4 col-md-offset-1 control-label no-padding-both">Primary/Owner Email:</label>
                                        <div class="col-md-6 padding-top" style="overflow:hidden;">
                                            <a href="mailto:{{ $business->primary->email }}">{{ $business->primary->email }}</a>
                                        </div>
                                    </div>
                                </div>

                                @foreach($business->secondary as $index => $secondary)
                                    @if($secondary->is_enabled)
                                        <div>
                                            <div class="row">
                                                <label for="business_secondary_1_first_name" class="col-md-4 col-md-offset-1 control-label no-padding-both">Secondary {{ $index + 1 }} Name:</label>
                                                <div class="col-md-6 padding-top">
                                                    {{ $secondary->prefix }} {{ $secondary->first_name }} {{ $secondary->middle_name }} {{ $secondary->last_name }} {{ $secondary->suffix }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="business_secondary_1_email" class="col-md-4 col-md-offset-1 control-label no-padding-both">Secondary {{ $index + 1 }} Email:</label>
                                                <div class="col-md-6 padding-top" style="overflow:hidden;">
                                                    <a href="mailto:{{ $secondary->email }}">{{ $secondary->email }}</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-md-offset-6 buttons">
                                                    <div class="col-md-5">
                                                        <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" data-toggle="modal" data-target="#modalSecondary{{ $secondary->index }}">EDIT</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <form method="post" action="/user/account/secondary/{{ $secondary->index }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-default btn-primary btn-xs">DELETE</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                @foreach($business->secondary AS $secondary)
                                    @if(!$secondary->is_enabled)
                                        <div class="odd">
                                            <div class="row">
                                                <div class="col-md-12 text-center buttons">
                                                    <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" data-toggle="modal" data-target="#modalSecondary{{ $secondary->index }}">ADD ANOTHER EMAIL</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mini-heading">
                                <h4>Annual Support Agreement</h4>
                            </div>
                            <div class="content account_box even">
                                <div class="table-responsive odd">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th class="form-group">
                                                Type
                                            </th>
                                            <th class="form-group">
                                                Expires
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if( ! is_null( $business->asa ) )
                                            <tr>
                                                <td>{{ ucfirst( $business->asa->type ) }}</td>
                                                <td>{{ date('m/d/Y', strtotime($business->asa->expiration)) }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if($business->enable_payments)
                                        <div class="payment-ui">
                                            <table class="table table-striped stacked margin-bottom-none">
                                                <thead>
                                                <tr>
                                                    <th class="form-group">
                                                        Status
                                                    </th>
                                                    <th class="form-group">
                                                        Auto Pay?
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if( ! is_null( $business->asa ) )
                                                    <tr>
                                                        <td>{{ ucfirst( $business->status ) }}</td>
                                                        <td>{{ $business->payment_type === "subscription" ? "Yes" : "No" }}</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                            <div class="content odd">
                                                <div class="row text-center">
                                                    <strong>Payment Type</strong><br />
                                                    @if (count($business->defaultCardInfo))
                                                        {{ $business->defaultCardInfo['brand'] }} {{ $business->defaultCardInfo['last4'] }}
                                                    @else
                                                        None
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="content even">
                                                <div class="row">
                                                    <div class="col-md-12" id="payment">
                                                        <vue-payment />
                                                    </div>
                                                    {{--                                        <div class="col-md-12 text-center buttons">--}}
                                                    {{--                                            <a href="http://bentericksen.com/annual-support-agreements.html" target="_blank" class="btn btn-default btn-primary btn-xs btn-modal" id="edit_asa_info">RENEW / UPGRADE</a>--}}
                                                    {{--                                        </div>--}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row text-center">
                                    <h4 class="col-md-12">License Agreement</h4>
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" id="view_license_agreement" data-toggle="modal" data-target="#licenseAgreement">
                                            <em>view > ></em></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Primary -->
                <div class="modal fade" id="modalPrimary" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            {!! Form::open(['route' => ['user.account.secondary', $business->id], 'method' => 'put']) !!}
                            <div class="edit edit_status">
                                <div class="edit edit_salary">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                        </button>
                                        <h4 class="modal-title text-center" id="modalLabel">Edit Contact Information</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label for="prefix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Prefix:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][prefix]', $secondary->prefix, ['id' => 'prefix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Prefix'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label for="firstName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} First Name:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][first_name]', $secondary->first_name, ['id' => 'firstName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'First Name'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label for="middleName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Middle Name:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][middle_name]', $secondary->middle_name, ['id' => 'middleName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Middle Name'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label for="lastName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Last Name:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][last_name]', $secondary->first_name, ['id' => 'lastName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Last Name'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label for="suffix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Suffix Name:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][suffix]', $secondary->suffix, ['id' => 'suffix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Suffix'])!!}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label for="email{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Email:</label>
                                                        <div class="col-md-5">
                                                            {!! Form::text('secondary[' . $secondary->index . '][email]', $secondary->email, ['id' => 'email' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'email'])!!}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                                        <button type="submit" class="btn btn-default btn-primary">SAVE</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <!-- End Primary -->

                @foreach($business->secondary as $secondary)
                    <div class="modal fade" id="modalSecondary{{ $secondary->index }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                {!! Form::open(['route' => ['user.account.secondary', $business->id], 'method' => 'put']) !!}
                                <div class="edit edit_status">
                                    <div class="edit edit_salary">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                            </button>
                                            <h4 class="modal-title text-center" id="modalLabel">Edit Contact Information</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label for="prefix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Prefix:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][prefix]', $secondary->prefix, ['id' => 'prefix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Prefix'])!!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="firstName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} First Name:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][first_name]', $secondary->first_name, ['id' => 'firstName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'First Name'])!!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="middleName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Middle Name:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][middle_name]', $secondary->middle_name, ['id' => 'middleName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Middle Name'])!!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="lastName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Last Name:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][last_name]', $secondary->first_name, ['id' => 'lastName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Last Name'])!!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="suffix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Suffix Name:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][suffix]', $secondary->suffix, ['id' => 'suffix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Suffix'])!!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label for="email{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Email:</label>
                                                            <div class="col-md-5">
                                                                {!! Form::text('secondary[' . $secondary->index . '][email]', $secondary->email, ['id' => 'email' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'email'])!!}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer text-center">
                                            <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                                            <button type="submit" class="btn btn-default btn-primary">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="modal fade" id="licenseAgreement" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content license-agreement">
                            <h3>BENT ERICKSEN & ASSOCIATES</h3>
                            <h3>HR DIRECTOR LICENSE AGREEMENT</h3>
                            <p>
                                <strong>Bent Ericksen & Associates</strong> grants to you a non-exclusive, nontransferable, revocable license to access and utilize
                                <strong>Bent Ericksen & Associates’</strong>
                                web-based software and materials, the “HR Director.” The software and materials provided, have been created, designed, manufactured by, and are the solely owned property of
                                <strong>Bent Ericksen & Associates</strong>. The software and materials are distributed exclusively through
                                <strong>Bent Ericksen & Associates</strong> and its authorized dealers.
                                THE SOFTWARE AND MATERIALS LICENSED HEREUNDER ARE SUBJECT TO THE COMMON LAW AND STATUTORY COPYRIGHT PROTECTION OF ITS CREATORS, AND YOU ACQUIRE NO INTEREST IN SUCH SOFTWARE AND/OR
                                MATERIALS EXCEPT THE RIGHT TO ACCESS AND USE THE SOFTWARE AND MATERIALS FOR ITS INTENDED PURPOSES. This license does not grant to you or anyone else the right to copy, disassemble
                                the licensed software or materials, or any portion thereof. Neither this license, nor access to the licensed software and materials, or any portion thereof, may be sold, leased,
                                assigned, licensed, or otherwise transferred by you, except as expressly provided by
                                <strong>Bent Ericksen & Associates</strong>.
                            </p>

                            <p>
                                <strong>Bent Ericksen & Associates</strong> makes every attempt to ensure that its products and information are compliant with applicable federal and state based regulations.
                            </p>

                            <p>
                                As part of this license agreement, you acknowledge that the licensed software, materials, and supporting documentation are of an extremely confidential nature, and you agree to
                                receive, use, hold and maintain them as a confidential, proprietary trade secret and product of
                                <strong>Bent Ericksen & Associates</strong>. You shall not, without the prior
                                express written consent of
                                <strong>Bent Ericksen & Associates</strong>, cause or permit disclosure of all or any portion of the licensed software, materials, or supporting
                                documentation, in any form or component, to any person or entity. You shall take all reasonable steps to safeguard the licensed software and materials and supporting documentation
                                so as to insure that no unauthorized copy, in whole or in part, in any form, shall be made or distributed or otherwise given to any other person or entity.
                            </p>

                            <p>
                                As part of this license agreement, you acknowledge that your continued use of this license and access to
                                <strong>Bent Ericksen & Associates’</strong> web-based software and
                                updated materials, is limited and conditioned upon your maintaining a current valid annual support agreement with
                                <strong>Bent Ericksen & Associates</strong>.
                                <strong>Automatic license agreement and support/update renewal shall take place, via credit card payment, on an annual basis beginning one year from date of initial purchase.
                                    The annual license and support renewal fee will be
                                    <strong>Bent Ericksen & Associates’</strong> then current charge for applicable license, support and update option.</strong>
                            </p>

                            <p>
                                So long as, and only during such period as, you comply with all the terms and conditions set forth herein, the term of this license shall begin on the date of execution of this
                                license by
                                <strong>Bent Ericksen & Associates</strong>, and shall run until such time as you do not maintain a current valid annual support agreement, at which time this license
                                and your access to all
                                <strong>Bent Ericksen & Associates’</strong> online, web-based software and materials will be terminated.
                            </p>

                            <p>
                                Other than as set forth herein,
                                <strong>Bent Ericksen & Associates</strong> makes no other warranties, either expressed or implied, relating to the licensed software and materials,
                                including, but not limited to any implied warranty of merchantability or fitness for a particular purpose.
                                <strong>Bent Ericksen & Associates</strong> shall not be liable for direct,
                                indirect, special or consequential damages resulting from the use of the licensed software and materials.
                                <strong>Bent Ericksen & Associates</strong> reserves the right to immediately
                                terminate this license upon failure to comply with any of the terms, conditions or limitations described herein.
                            </p>
                            <div class="modal-footer text-center">
                                <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="licenseAgreement" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="width: 700px; height: 850px">
                            <div class="edit edit_status">
                                <div class="edit edit_salary">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                        </button>
                                        <h4 class="modal-title text-center" id="modalLabel">Edit Contact Information</h4>
                                    </div>
                                    <div class="modal-body license-agreement">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>BENT ERICKSEN & ASSOCIATES</h3>
                                                <h3>HR DIRECTOR LICENSE AGREEMENT</h3>
                                                <p>
                                                    <strong>Bent Ericksen & Associates</strong> grants to you a non-exclusive, nontransferable, revocable license to access and utilize
                                                    <strong>Bent Ericksen & Associates’</strong>
                                                    web-based software and materials, the “HR Director.” The software and materials provided, have been created, designed, manufactured by, and are the solely owned property of
                                                    <strong>Bent Ericksen & Associates</strong>. The software and materials are distributed exclusively through
                                                    <strong>Bent Ericksen & Associates</strong> and its authorized dealers.
                                                    THE SOFTWARE AND MATERIALS LICENSED HEREUNDER ARE SUBJECT TO THE COMMON LAW AND STATUTORY COPYRIGHT PROTECTION OF ITS CREATORS, AND YOU ACQUIRE NO INTEREST IN SUCH SOFTWARE AND/OR
                                                    MATERIALS EXCEPT THE RIGHT TO ACCESS AND USE THE SOFTWARE AND MATERIALS FOR ITS INTENDED PURPOSES. This license does not grant to you or anyone else the right to copy, disassemble
                                                    the licensed software or materials, or any portion thereof. Neither this license, nor access to the licensed software and materials, or any portion thereof, may be sold, leased,
                                                    assigned, licensed, or otherwise transferred by you, except as expressly provided by
                                                    <strong>Bent Ericksen & Associates</strong>.
                                                </p>

                                                <p>
                                                    <strong>Bent Ericksen & Associates</strong> makes every attempt to ensure that its products and information are compliant with applicable federal and state based regulations.
                                                </p>

                                                <p>
                                                    As part of this license agreement, you acknowledge that the licensed software, materials, and supporting documentation are of an extremely confidential nature, and you agree to
                                                    receive, use, hold and maintain them as a confidential, proprietary trade secret and product of
                                                    <strong>Bent Ericksen & Associates</strong>. You shall not, without the prior
                                                    express written consent of
                                                    <strong>Bent Ericksen & Associates</strong>, cause or permit disclosure of all or any portion of the licensed software, materials, or supporting
                                                    documentation, in any form or component, to any person or entity. You shall take all reasonable steps to safeguard the licensed software and materials and supporting documentation
                                                    so as to insure that no unauthorized copy, in whole or in part, in any form, shall be made or distributed or otherwise given to any other person or entity.
                                                </p>

                                                <p>
                                                    As part of this license agreement, you acknowledge that your continued use of this license and access to
                                                    <strong>Bent Ericksen & Associates’</strong> web-based software and
                                                    updated materials, is limited and conditioned upon your maintaining a current valid annual support agreement with
                                                    <strong>Bent Ericksen & Associates</strong>.
                                                    <strong>Automatic license agreement and support/update renewal shall take place, via credit card payment, on an annual basis beginning one year from date of initial purchase.
                                                        The annual license and support renewal fee will be
                                                        <strong>Bent Ericksen & Associates’</strong> then current charge for applicable license, support and update option.</strong>
                                                </p>

                                                <p>
                                                    So long as, and only during such period as, you comply with all the terms and conditions set forth herein, the term of this license shall begin on the date of execution of this
                                                    license by
                                                    <strong>Bent Ericksen & Associates</strong>, and shall run until such time as you do not maintain a current valid annual support agreement, at which time this license
                                                    and your access to all
                                                    <strong>Bent Ericksen & Associates’</strong> online, web-based software and materials will be terminated.
                                                </p>

                                                <p>
                                                    Other than as set forth herein,
                                                    <strong>Bent Ericksen & Associates</strong> makes no other warranties, either expressed or implied, relating to the licensed software and materials,
                                                    including, but not limited to any implied warranty of merchantability or fitness for a particular purpose.
                                                    <strong>Bent Ericksen & Associates</strong> shall not be liable for direct,
                                                    indirect, special or consequential damages resulting from the use of the licensed software and materials.
                                                    <strong>Bent Ericksen & Associates</strong> reserves the right to immediately
                                                    terminate this license upon failure to comply with any of the terms, conditions or limitations described herein.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">Close</button>
                                    </div>
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
@stop
