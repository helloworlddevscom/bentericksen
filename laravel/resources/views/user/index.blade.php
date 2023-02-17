@extends('user.wrap')

@section('head')
    @parent
@stop

@section('content')
    <div id="main_body">
        @if ($viewUser->permissions('m240'))
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="row">
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif

                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>Anniversaries</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="anniversaries_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span># of YEARS</span>
                                        </th>
                                        <th>
                                            <span>DATE</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($anniversaries as $user)
                                        <tr>
                                            <td>
                                                <a href="/user/employees/{{ $user->id }}/edit">{{ $user->first_name }} {{ $user->last_name }}</a>
                                            </td>
                                            <td class="text-center">{{ $user->getTenure() }}</td>
                                            <td>{{ $user->getNextAnniversary()->format('n/j/Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>Birthdays</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="birthdays_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span>DATE</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($birthdays as $user)
                                        <tr>
                                            <td>
                                                <a href="/user/employees/{{ $user->id }}/edit">{{ $user->first_name }} {{ $user->last_name }}</a>
                                            </td>
                                            <td>{{ $user->getNextBirthday()->format('n/j/Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>Time Off Requests</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="time_off_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span>DATES</span>
                                        </th>
                                        <th class="bg_none">
                                            <span>APPROVE / DENY</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($timeOffRequests as $timeoff)
                                        <tr>
                                            <td>
                                                <a href="/user/employees/time-off-requests/view/{{$timeoff->id}}">{{ $timeoff->user->fullname }}</a>
                                            </td>
                                            <td>{{ date('m/d/Y', strtotime($timeoff->start_at)) }} - {{ date('m/d/Y', strtotime($timeoff->end_at)) }}</td>
                                            <td class="text-center">
                                                @if ($viewUser->permissions('m144'))
                                                    {!! Form::open(['url' => '/user/employees/timeoff/' . $timeoff->id, 'method' => 'post']) !!}
                                                    <input type="hidden" name="return" value="/user">
                                                    <button type="submit"
                                                        class="btn btn-default btn-xs form-btn"
                                                        value="approve" name="action">
                                                        <i class="fa fa-check icon_green"></i>
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-default btn-xs form-btn"
                                                        value="deny" name="action">
                                                        <i class="fa fa-times icon_red"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>License / Certification</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="license_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span>TYPE</span>
                                        </th>
                                        <th>
                                            <span>EXP. DATE</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        @foreach($user->licenses as $license)
                                            @if(Carbon\Carbon::parse($license->expiration) < Carbon\Carbon::now())
                                            <tr class="expired">
                                                @else
                                                    <tr>
                                                        @endif
                                                <td>
                                                    <a href="/user/employees/{{ $user->id }}/edit">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                </td>
                                                <td>{{ $license->name }}</td>

                                                <td>{{ date('m/d/Y', strtotime($license->expiration)) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>Leave Of Absence</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="leave_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span>DATES</span>
                                        </th>
                                        <th>
                                            <span>TYPE</span>
                                        </th>
                                        <th>
                                            <span>STATUS</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($leaveOfAbsence as $timeoff)
                                        <tr>
                                            <td>
                                                <a href="/user/employees/time-off-requests">{{ $timeoff->user->fullname }}</a>
                                            </td>
                                            <td>{{ date('m/d/Y', strtotime($timeoff->start_at)) }}
                                                - {{ date('m/d/Y', strtotime($timeoff->end_at)) }}</td>
                                            <td class="capitalize">{{ $timeoff->type }}</td>
                                            <td class="capitalize">{{ $timeoff->status }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="ui-state-default col-md-4 mini-content">
                            <div class="mini-heading">
                                <h3>Pending Paperwork</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="paperwork_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>EMPLOYEE</span>
                                        </th>
                                        <th>
                                            <span>PAPERWORK NEEDED</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        @if( (isset( $user->paperwork ) && count( $user->paperwork ) > 0 ) OR ( $user->job_description == 0 ) )
                                            <tr>
                                                <td>
                                                    <a href="/user/employees/{{ $user->id }}/edit#non_benefits/paperwork">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                </td>
                                                <td>
                                                    <ul style="margin: 0; padding: 0; list-style: none;">
                                                        @if($user->job_description == 0)
                                                            <li style="margin-bottom: 5px;"><a
                                                                    href="/user/job-descriptions">Job
                                                                    Description</a></li>@endif
                                                        @foreach($user->paperwork as $paperwork)
                                                            <li style="margin-bottom: 5px;">
                                                                {{ $paperwork->name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="col-md-12 content">
                        <div class="row">
                            <p class="text-center">Welcome to HR Director. Please choose an option from the menu above.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('foot')
    @parent
@stop
