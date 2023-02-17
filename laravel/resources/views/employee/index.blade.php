@extends('employee.wrap')

@section('content')
    @if(($viewUser->status == 'disabled') || ($viewUser->can_access_system == 0))
        <div style="width: 100%; height: 710px; background-color: #fff;">
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="col-md-12 content">
                        Your account has been disabled, please contact your administrator for more information.
                    </div>
                </div>
            </div>
        </div>
    @else
        @if(($viewUser->hasRole('employee') && !$viewUser->permissions('e100')) || (!$viewUser->business->hrdirector_enabled))
            <div style="width: 100%; height: 710px; background-color: #fff;">
                <div class="container" id="main">
                    <div class="row main_wrap">
                        <div class="col-md-12 content">
                            You Do Not Have Access to HR Director
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div id="main_body">
                <div class="container" id="main">
                    <div class="row main_wrap">
                        <div class="col-md-12 content employee_information form-horizontal">
                            <div class="row">
                                @if($viewUser->hasRole('employee') && !$viewUser->permissions('e120'))

                                @else
                                    <div class="col-md-6">
                                        <div class="mini-heading">
                                            <h4><i class="fa fa-book"></i> Policy Manual</h4>
                                        </div>
                                        <div class="mini-content employee-bg">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <h4 class="employee-body-link">
                                                        @if (!$viewUser->business->manual || $viewUser->business->manual == '' || !$viewUser->business->finalized)
                                                            <a href="#" data-toggle="modal" data-target="#modalPolicyManualUnavailable">
                                                                <span class="text-policy"><b><em>View Policy Manual >></em></b></span>
                                                            </a>
                                                        @elseif (!empty($policy_updates_run))
                                                            <a href="#" class="js-force-modal" data-toggle='modal' data-dismiss='modal' data-target='#modalPolicyUpdatesReminder'>
                                                                <span class="text-policy"><b><em>View Policy Manual >></em></b></span>
                                                            </a>
                                                        @else
                                                            <a href="employee/policy/manual" target="_blank">
                                                                <span class="text-policy"><b><em>View Policy Manual >></em></b></span>
                                                            </a>
                                                        @endif
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mini-footer employee-bg">

                                        </div>
                                    </div>
                                @endif
                                @if($employee->sop_enabled && $viewUser->permissions('e230'))
                                        <div class="col-md-6">
                                            <div class="mini-heading">
                                                <h4><i class="fa fa-list"></i> SOPs</h4>
                                            </div>
                                            <div class="mini-content employee-bg">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h4 class="col-md-12 text-center employee-body-link">
                                                            <a href="/streamdent/login"><span class="text-policy"><b><em>View SOPs >></em></b></span></a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mini-footer employee-bg">
                                            </div>
                                        </div>

                                @endif
                                @if($viewUser->hasRole('employee') && !$viewUser->permissions('e200'))
                                    
                                @else
                                    <div class="col-md-6">
                                        <div class="mini-heading">
                                            <h4><i class="fa fa-clock-o"></i> Time Off Requests</h4>
                                        </div>
                                        <div class="mini-content employee-bg">
                                            <div class="row">
                                                @if($timeOff->isEmpty())
                                                    <div class="col-md-12 text-center">
                                                        <h4 class="col-md-12 text-center employee-body-link">
                                                            <a href="employee/time-off-request/{{ $employee['id'] }}"><span
                                                                        class="text-policy"><b><em>Request Time Off >></em></b></span></a>
                                                        </h4>
                                                    </div>
                                                @else
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped" id="form_table">
                                                                <thead>
                                                                <tr>
                                                                    <th>
                                                                        <span>DATES</span>
                                                                    </th>
                                                                    <th>
                                                                        <span>STATUS</span>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($timeOff->sortByDesc('start_at') as $time)
                                                                    <tr>
                                                                        <td>
                                                                            {{ date('m/d/Y', strtotime($time->start_at)) }} - {{ date('m/d/Y', strtotime($time->end_at)) }}
                                                                        </td>
                                                                        <td>
                                                                            <span class="fa"
                                                                                  data-status="{{ $time->status }}"
                                                                                  style="text-transform: capitalize;">&nbsp;{{ $time->status }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mini-footer employee-bg">
                                            @if(!$timeOff->isEmpty())
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        @if($viewUser->hasRole('employee') && !$viewUser->permissions('e200'))

                                                        @else
                                                            <h4 class="col-md-12 text-center">
                                                                <a href="employee/time-off-request/{{ $employee['id'] }}"><span
                                                                            class="text-policy"><b><em>Request Time Off >></em></b></span></a>
                                                            </h4>
                                                    @endif
                                                    <!--<a class="btn btn-default btn-primary btn-xs modal-button" id="time_off_history">TIME OFF HISTORY</a>-->
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($viewUser->permissions('e160'))
                                    <div class="col-md-6">
                                        <div class="mini-heading">
                                            <h4><i class="fa fa-comment-o"></i> Job Description</h4>
                                        </div>
                                        <div class="mini-content employee-bg job-description">
                                            <div class="row">
                                                <label for="" class="col-md-4 col-md-offset-1 control-label">JOB
                                                    TITLE:</label>
                                                <div class="col-md-6">
                                                    <h6>{{ $jobDescription['name'] }}</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="job_description_reports_to"
                                                       class="col-md-4 col-md-offset-1 control-label">REPORTS
                                                    TO:</label>
                                                <div class="col-md-6">
                                                    <h6>{{ $employee->job_reports_to }}</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="job_description_location"
                                                       class="col-md-4 col-md-offset-1 control-label">LOCATION:</label>
                                                <div class="col-md-6">
                                                    <h6>{{ $employee->job_location }}</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="job_description_department"
                                                       class="col-md-4 col-md-offset-1 control-label">DEPARTMENT:</label>
                                                <div class="col-md-6">
                                                    <h6>{{ $employee->job_department }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mini-footer employee-bg">
                                            <div class="row text-center">
                                                <a type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                   data-target="#jobDescriptionModal">View Job Description</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="mini-heading">
                                        <h4><i class="fa fa-user"></i> My Information</h4>
                                    </div>
                                    <div class="mini-content employee-bg">
                                        <div class="row">
                                            <label for="" class="col-md-4 col-md-offset-1 control-label">NAME:</label>
                                            <div class="col-md-6">
                                                <h6>{{ $employee['first_name'] }} @if($employee['middle_name']) {{ $employee['middle_name'] }} @endif {{ $employee['last_name'] }}</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for=""
                                                   class="col-md-4 col-md-offset-1 control-label">ADDRESS:</label>
                                            <div class="col-md-6">
                                                <h6>
                                                    {{ $employee['address1'] }}<br>
                                                    @if($employee['address2']){{ $employee['address2'] }}<br>@endif
                                                    {{ $employee['city'] }}, {{ $employee['state'] }}
                                                    . {{ $employee['postal_code'] }}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="" class="col-md-4 col-md-offset-1 control-label">OFFICE:</label>
                                            <div class="col-md-6">
                                                <h6>{{ $employee['business_name'] }}</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="" class="col-md-4 col-md-offset-1 control-label text-uppercase">Phone
                                                1:</label>
                                            <div class="col-md-6">
                                                <h6>{{ $employee['phone1'] }}</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="" class="col-md-4 col-md-offset-1 control-label text-uppercase">Phone
                                                2:</label>
                                            <div class="col-md-6">
                                                <h6>{{ $employee['phone2'] }}</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="" class="col-md-4 col-md-offset-1 control-label">EMAIL:</label>
                                            <div class="col-md-6">
                                                <h6>{{ $employee['email'] }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mini-footer employee-bg">
                                        <div class="row text-center">
                                            <h4 class="col-md-12">
                                                @if($viewUser->permissions('e221'))
                                                    <a href="employee/info/{{ $employee['id'] }}"><span
                                                                class="text-policy"><b><em>Edit Information >></em></b></span></a>
                                                @endif
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="jobDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog employee-dashboard-modal" role="document">
                                <div class="modal-content employee-dashboard-modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Job Description</h4>
                                    </div>
                                    <div class="modal-body">
                                        {!! $jobDescription['description'] !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endif
@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function () {
            $('#form_table tbody tr td span, #time_off_history_table tbody tr td span').each(function () {
                var span = $(this);
                var status = span.attr('data-status');

                if (status == 'approved') {
                    colorClass = 'icon_green';
                    span.prepend('&#xf00c;');
                }
                else if (status == 'denied') {
                    colorClass = 'icon_red';
                    span.prepend('&#xf00d;');
                }
                else if (status == 'pending') {
                    colorClass = 'icon_black';
                    span.prepend('&#xf0c8;');
                }
                else if (status == 'leave') {
                    colorClass = 'icon_blue';
                    span.prepend('&#xf0c8;');
                }

                span.addClass(colorClass);
            });
        });
    </script>
@stop
