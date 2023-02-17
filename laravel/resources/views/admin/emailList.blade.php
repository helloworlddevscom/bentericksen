@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap" id="app">

                <div class="col-md-12 heading">
                    <h3>Email Log</h3>
                </div>

                <div class="col-md-12 content">
                    <div class="row">
                    </div>
                    <div class="row">
                        <email-list auth-id="{{ Auth::user()->id }}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop
