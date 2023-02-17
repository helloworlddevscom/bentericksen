<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <link href="/assets/styles/plugins/font-awesome/font-awesome.min.css?v=2" rel="stylesheet">
    <link href="/assets/styles/main.css?v=2" rel="stylesheet">
    <title>HR Director</title>
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script type="text/javascript" src="/assets/scripts/plugins/e-signature/flashcanvas.js"></script>
    <![endif]-->
    <script src="/assets/scripts/plugins/jquery/jquery-1.10.1.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

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
            @yield('content')
        </div>
    </div>
</div>
<div id="login-footer">
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
</body>
</html>
