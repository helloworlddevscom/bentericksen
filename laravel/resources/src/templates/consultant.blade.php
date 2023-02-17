<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title>HR Director</title>
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>
    <script type="text/javascript">
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
                        <h4><em>Hello, <a href="#">{{ $viewUser->prefix }} {{ ucfirst( $viewUser->first_name ) }} {{ ucfirst( $viewUser->last_name ) }}</a></em></h4>
                    </div>
                    <div class="col-md-5 pull-right">
                        <h4>
                            <a href="/auth/logout">LOGOUT</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main_navigation_wrap">

</div>
<div id="main_body">
    <div class="container" id="main">
        <div class="row main_wrap">
            @include('shared.flash-message')

            @yield('content')
        </div>
    </div>
</div>
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
                <p><em>800-679-2760<br><a href="mailto:info@bentericksen.com">info@bentericksen.com</a></em></p>
            </div>
        </div>
    </div>
</div>

@foreach($businesses AS $business)
    @if(!$business->active_business)
        @include('consultant.modals.inactiveBusinessReject')
        @break
    @endif
@endforeach

@section('foot')
    @parent
@show
<script>
    window.PAGE_INIT.push(function () {
        // enabling spell checker plugin
        /*
        window.nanospell.ckeditor('all', {
            dictionary: "en",
            server: "php"
        });**/

    });
</script>
@include('user.modals.sessionTimeout')
<noscript>
    <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
    
<!-- built files will be auto injected -->
</body>
</html>
