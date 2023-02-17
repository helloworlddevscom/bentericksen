<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <link href="/assets/styles/plugins/calendar/calendar.min.css" rel="stylesheet">
    
    
    <title>HR Director</title>
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>
    @section('head')
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    @show
    <script>
        @include('shared.ck')
    </script>
</head>
<body>
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
                        <h4><em>Hello,
                                <a href="#">{{ auth()->user()->prefix }} {{ ucfirst( auth()->user()->first_name ) }} {{ ucfirst( auth()->user()->last_name ) }}</a></em>
                        </h4>
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
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-suitcase navigation_img"></i>BUSINESS LIST</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/business">BUSINESS LIST</a></li>
                                <li><a href="/admin/business/create">NEW BUSINESS SETUP</a></li>
                            </ul>
                        </li>
                        <li><a href="/admin/login-list"><i class="fa fa-users navigation_img"></i>LOGIN LIST</a></li>
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-copy navigation_img"></i>POLICIES</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/policies">POLICY LIST</a></li>
                                <li><a href="/admin/policies/updates">POLICY UPDATE LIST</a></li>
                                <li><a href="/admin/policies/create">NEW POLICY</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-comment-o navigation_img"></i>JOB DESCRIPTIONS</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/job-descriptions">JOB DESCRIPTIONS</a></li>
                                <li><a href="/admin/job-descriptions/create">NEW JOB DESCRIPTION</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/forms">FORMS</a></li>
                                <li><a href="/admin/forms/create">NEW FORM</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-alt navigation_img"></i>FAQ'S</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/faqs">FAQ'S</a></li>
                                <li><a href="/admin/faqs/create">ADD NEW FAQ</a></li>
                            </ul>
                        </li>
                        <li><a href="/admin/email-list"><i class="fa fa-envelope navigation_img"></i>Email Log</a></li>
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle navigation_img"></i>HELP</a>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/help">HELP</a></li>
                                <li><a href="/admin/help/create">NEW HELP</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

@include('shared.flash-message')

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

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<script src="/assets/js/cleave.min.js"></script>

<script type="text/javascript" src="/assets/scripts/general.js"></script>

@section('foot')
    @parent
    <script src="/assets/js/plugins/ckeditor/ckeditor.js"></script>
    <script src="/assets/js/plugins/ckeditor/plugins/nanospell/autoload.js"></script>
    <script src="/assets/js/plugins/ckeditor/plugins/lite/lite-interface.js"></script>
@show
<script type="text/javascript">
    
    $(document).ready(function () {
        
        // enabling spell checker plugin
        nanospell.ckeditor('all', {
            dictionary: "en",
            server: "php"
        });

        $('input[name=bonuspro_enabled]').on('click', function (e) {
            if (this.value > 0) {
                $('#bonuspro_fields').removeClass('hidden');
            } else {
                $('#bonuspro_fields').addClass('hidden');
            }
        });

        $('input[name=hrdirector_enabled]').on('click', function (e) {
            if (this.value > 0) {
                $('#hrdirector_fields').removeClass('hidden');
            } else {
                $('#hrdirector_fields').addClass('hidden');
            }
        });
    });
</script>
@include('user.modals.sessionTimeout')
<noscript>
    <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
    
<!-- built files will be auto injected -->
</body>
</html>
