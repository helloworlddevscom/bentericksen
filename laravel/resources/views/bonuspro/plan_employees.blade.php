@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div id="app">
                        <div class="row text-center">
                            <h3>BonusPro - {{ $plan->plan_name }}</h3>
                            <h4>Employees and Funds</h4>
                            <progress-bar active="employees"></progress-bar>
                            <div class="col-md-4 col-md-offset-8 text-left padding-top-18">
                                <p><a href="#" id="delete_plan" class="modal-button" data-toggle="modal" data-target="#" data-plan="{{ $plan->id }}"><span class="bp_add">+</span> ADD EMPLOYEE</a></p>
                                <p><a href="#" id="delete_plan" class="modal-button" data-toggle="modal" data-target="#" data-plan="{{ $plan->id }}"><span class="bp_add">+</span> ADD FUND</a></p>
                            </div>
                        </div>
                        <div class="col-md-8 col-md-offset-2 content">
                            @if(count($errors) > 0)
                                @include('shared.errors')
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped" id="active_table">
                                    <thead>
                                    <tr>
                                        <th><span>Employee / Fund</span></th>
                                        <th class="bg_none"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->fullname }}</td>
                                            <td class="text-right">
                                                <a href="/bonuspro/{{ $plan->id }}/employees/{{ $employee->id }}" class="btn btn-default btn-xs btn-wd btn-primary">VIEW</a>
                                                <a href="#" id="remove_employee" class="modal-button btn btn-danger btn-xs btn-wd" data-toggle="modal" data-target="#" data-plan="{{ $plan->id }}" data-user="{{ $employee->id }}">DELETE</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 content text-center">
                                <a href="#" class="btn btn-default btn-xs btn-primary">Previous</a>
                                <a href="/bonuspro" class="btn btn-default btn-xs btn-primary">Cancel</a>
                                <a href="#" class="btn btn-default btn-xs btn-primary">Next</a>
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
