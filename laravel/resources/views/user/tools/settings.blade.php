@extends('user.wrap')

@section('head')
    @parent
@stop

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                @if(session()->has('message'))
                    <div class="col-md-8 col-md-offset-2">
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    </div>
                @endif
                <div class="col-md-12 text-center">
                    <h3>General Settings</h3>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <form action="/user/settings/submit" method="POST">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="business_id" value="">
                        <div class="col-md-12 heading">
                            <h5>Dashboard</h5>
                        </div>
                        <div class="col-md-12 content">
                            <div class="row odd">
                                <label for="" class="col-md-9 padding-top">Display Birthdays, Anniversaries, and Certification Renewals date range:</label>
                                <div class="col-md-3">
                                    {!! Form::select('dashboard_reminders_days', ['30' => '30 Days', '60' => '60 Days', '90' => '90 Days', '120' => '120 Days', '150' => '150 Days', '180' => '180 Days'], $settings->dashboard_reminders_days, ['class' => 'form-control']) !!}
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
@stop

@section('foot')
    @parent
@stop