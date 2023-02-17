{{--
Variables available in this view:

From \Bentericksen\ViewComposers\EmployeePolicyManualViewComposer:
* $manual_available boolean whether to show the "manual unavailable" modal
* $switch_status boolean whether to show the "manual unavailable" modal based on switch status.

Additional variables are set in the View but not used here. See shared/navigation.blade.php
--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>HR Director</title>
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>

    <link href="/assets/styles/jquery-ui-timepicker-addon.css" rel="stylesheet">

    @section('head')
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    @show
    <script>
        @include('shared.ck')
        window.PAGE_INIT = []
    </script>
</head>
<body>
@include('shared.curtain')
<div id="header_wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-8 pull-left">
                <div class="row">
                    <div class="col-md-2">
                        <a href="/"><img src="/assets/images/bea-logo.png" alt="Bent Ericken & Associates Logo" height="83" width="83"></a>
                    </div>
                    <div class="col-md-10">
                        <h2 class="header_wrap_h2">HR Director</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pull-right" id="user_log_in_out">
                <div class="row">
                    <div class="col-md-7 text-right">
                        <h4><em>Hello, <a href="/">{{ $viewUser->prefix }} {{ $viewUser->first_name }} {{ $viewUser->last_name }}</a></em></h4>
                    </div>
                    <div class="col-md-5 pull-right">
                        <h4><a href="/auth/logout">LOGOUT</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(($viewUser->status == 'enabled') && ($viewUser->can_access_system == 1))
    <div id="main_navigation_wrap">
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin_navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse center-block" id="admin_navigation">
                        <ul class="nav navbar-nav">
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
@endif

@yield('content')

<div id="footer">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-4">
                <h4>POWERED BY BENT ERICKSEN & ASSOCIATES</h4>
            </div>
            <div class="col-md-4">
                <h4>HR DIRECTOR {{ env('APP_VERSION') }}
                    <br>COPYRIGHT {{ \Carbon\Carbon::now()->format('Y') }}</h4>
            </div>
            <div class="col-md-4">
                <h4>CONTACT SUPPORT</h4>
                <p><em>800-679-2760
                        <br><a href="mailto:info@bentericksen.com">info@bentericksen.com</a></em></p>
            </div>
        </div>
    </div>
</div>

@if(session()->get('policyUpdatesPending'))
    @include('employee.modals.policyUpdatesReminder')
@endif

@if((isset($manual_available) && $manual_available === false) || !$switch_status)
    @include('employee.policyManualUnavailableModal')
@endif

@section('foot')
    @parent
@show
<script>
    
</script>
<noscript>
    <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
    
<!-- built files will be auto injected -->
</body>
</html>
