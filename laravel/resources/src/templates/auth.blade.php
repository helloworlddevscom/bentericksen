<!DOCTYPE html>
<html lang="en">
<head>
    <title>HR Director</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>
    <script>
        const MINUTE = 1000 * 60
        const SESSION_LIFETIME = ({{ config('session.lifetime') }}) * MINUTE
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
<noscript>
    <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
<script>
    window.onloadCallback = () => {
        grecaptcha.render('html_element', {
            'sitekey': '{{ config('services.recaptcha.site_key') }}'
        })
    }
</script>
<!-- built files will be auto injected -->
</body>
</html>
