@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                @if (session('status'))
                    <div class="alert alert-success">
                        {!! session('status') !!}
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="row">
                        <h3 class="col-md-2 col-md-offset-5 text-right no-padding-right">Permissions</h3>
                        <div class="col-md-1 no-padding-left offcenter-help">{!! $help->button("u4601") !!}</div>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <form action="/user/permissions/submit" method="POST">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="col-md-12 nav-tabs">
                                <div class="row">
                                    <ul class="nav nav-tabs" role="tablist" id="employee_tab">
                                        <li class="active">
                                            <a href="#managers" role="tab" data-toggle="tab">Managers</a>
                                        </li>
                                        <li>
                                            <a href="#employees" role="tab" data-toggle="tab">Employees</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="managers">
                                            <div class="col-md-12 heading">
                                                <h5>Access</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Managers Access the HR Director?:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m100', ['0' => 'No', '1' => 'Yes'], $permissions->get('m100'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @if($businessObject->getIsBonusProEnabledAttribute())
                                                <div class="col-md-12 content">
                                                    <div class="row odd">
                                                        <label for="" class="col-md-9 padding-top">Can Managers Access BonusPro?:</label>
                                                        <div class="col-md-3">
                                                            {!! Form::select('m101', ['0' => 'No', '1' => 'Yes'], $permissions->get('m101'), ['class' => 'form-control']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-12 heading">
                                                <h5>Policies</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Policy Manual And Policy List:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m120', ['1' => 'View/Edit', '2' => 'View Manual Only'], $permissions->get('m120'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">Policy Update Process:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m121', ['1' => 'Accept Updates', '0' => 'No Access'], $permissions->get('m121'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Employees</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Basic Employee Info:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m140', ['1' => 'View/Edit', '0' => 'No Access'], $permissions->get('m140'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Approve/Deny Time Off Requests:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m144', ['1' => 'Yes', '0' => 'No'], $permissions->get('m144'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">Can Approve/Deny Leave of Absence Requests:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m145', ['1' => 'Yes', '0' => 'No'], $permissions->get('m145'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can View History Events, Add Notes to History:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m148', ['1' => 'Yes', '0' => 'No'], $permissions->get('m148'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Job Description</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">View/Edit list of saved job descriptions, Assign to employees:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m160', ['1' => 'Full', '0' => 'No Access'], $permissions->get('m160'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Forms</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">View/Edit/Print All Forms:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m180', ['1' => 'Full', '0' => 'No Access'], $permissions->get('m180'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Tools and Reports</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Access the HR FAQ's:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m200', ['1' => 'Yes', '0' => 'No'], $permissions->get('m200'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">Can Use Calculators:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m201', ['1' => 'Yes', '0' => 'No'], $permissions->get('m201'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Dashboard</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">Can View/Edit the Company Dashboard:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m240', ['1' => 'Yes', '0' => 'No'], $permissions->get('m240'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Account Settings</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can View/Edit Company Account Info:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m260', ['1' => 'Yes', '0' => 'No'], $permissions->get('m260'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Payment Access</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Manager update and change payment information:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m280', ['1' => 'Yes', '0' => 'No'], $permissions->get('m280'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->sop_enabled)
                                            <div class="col-md-12 heading">
                                                <h5>SOP Access</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can edit SOPs:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('m300', ['1' => 'Yes', '0' => 'No'], $permissions->get('m300'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Employees -->
                                        <div class="tab-pane fade" id="employees">
                                            <div class="col-md-12 heading">
                                                <h5>Access</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Employees Access the HR Director?:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e100', ['1' => 'Yes', '0' => 'No'], $permissions->get('e100'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>Policies</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row even">
                                                    <label for="" class="col-md-9 padding-top">Can View the latest Policy Manual:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e120', ['1' => 'Yes', '0' => 'No'], $permissions->get('e120'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 heading">
                                                <h5>Job Description</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can View their Current Job Description:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e160', ['1' => 'Yes', '0' => 'No'], $permissions->get('e160'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 heading">
                                                <h5>Time Off / Leave Requests</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Request Time Off/Leave of Absence:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e200', ['1' => 'Yes', '0' => 'No'], $permissions->get('e200'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 heading">
                                                <h5>My Info</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can Edit Personal Contact Info:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e221', ['1' => 'Yes', '0' => 'No'], $permissions->get('e221'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->sop_enabled)
                                            <div class="col-md-12 heading">
                                                <h5>SOP Access</h5>
                                            </div>
                                            <div class="col-md-12 content">
                                                <div class="row odd">
                                                    <label for="" class="col-md-9 padding-top">Can view SOPs:</label>
                                                    <div class="col-md-3">
                                                        {!! Form::select('e230', ['1' => 'Yes', '0' => 'No'], $permissions->get('e230'), ['class' => 'form-control']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center padding-top">
                                <a href="/user" class="btn btn-default btn-xs btn-primary">CANCEL</a>
                                <button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop
