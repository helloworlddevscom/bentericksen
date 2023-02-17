<!DOCTYPE html><html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><link href="/assets/styles/plugins/calendar/calendar.min.css" rel="stylesheet"><title>HR Director</title><link rel="shortcut icon" href="/assets/images/bea-logo.png">@section('head')<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">@show<script>@include('shared.ck')</script><link href="http://hrdirector.localhost:3000/dist/css/chunk-11efa404.87a639d8.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-1bbc04a8.c869d919.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-1edbe1e8.02ec3b8a.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-2532ec0a.c40a9581.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-43e4d85b.d9d4eec0.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-52841f22.4537f56a.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-540ac4b7.806998e9.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-74ae780b.b18b5d6f.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-74d2747e.02ec3b8a.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-770b9cf9.17a59247.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-770dda5a.d780124d.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-77126138.6310f492.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-772740d8.87a639d8.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-79abc62a.3b80743c.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-8e9bd5a0.60c3f0dd.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-c14aab1c.44a3934f.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-caa35c2e.b378dfe0.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-e7bc579c.a703758a.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/css/chunk-f7c55c34.8aab5c77.css" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-073226fc.eda31c8b.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-08ca2d0c.a89f1672.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-0e21bf9e.d3a3bc06.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-11efa404.1f332e6f.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-138103e6.484f3931.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-1455fde2.af12c916.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-154f69ee.f213b28c.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-1556c60c.727bd1e1.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-15f8063f.c3f48dac.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-1bbc04a8.80bffd50.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-1edbe1e8.65039851.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-23ad554b.122aaba9.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2532ec0a.82b3000a.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-29191afb.bf7074e7.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0aa5b8.c32e7151.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0aad07.a6c7dabd.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0b6b48.e6486acc.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0bd229.f197ccba.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0da2e8.c84db16b.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0da37e.ff876927.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d0efd38.b23363f0.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d20fd08.cf93de65.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d21367f.530de294.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d22d0b3.161e77a8.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d2383e7.754ef654.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-2d62bb98.f206af54.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-36817588.b63904c6.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-397c88e6.ff487a48.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-3b569322.c30ec37c.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-3b9a35c0.c983033d.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-41257c1e.8b252fb4.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-431e95c4.361b47ae.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-43e4d85b.29bfdaa7.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-4b6b822c.247c59f2.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-4ce1c53d.aa958ea8.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-52841f22.3acece1c.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-540ac4b7.7bf83c41.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-5602dea4.780ac6eb.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-59d4c40e.a450a6a0.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-6879b8b2.d3b37699.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-723d2681.e3ab2056.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-74ae780b.9b0db9ec.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-74d2747e.35d5b50b.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-770b9cf9.94648659.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-770dda5a.001847ac.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-77126138.2922514b.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-772740d8.3caa0822.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-79abc62a.fdfece2d.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-7bee3fc1.85d186d0.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-7f0e8558.af7d9cf8.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-8e9bd5a0.f4dca557.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-934a19fa.b9abb13e.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-977f025c.ef5ff43f.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-a341795a.b991d996.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-a6de1e74.c44733e8.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-adf33344.17dda562.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-af82ccd0.32c6f359.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-b4f2816e.32e15be2.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-bbdd6358.c7eb596a.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-bbdf47a2.4c4d2962.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-c14aab1c.4fc4f0c7.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-caa35c2e.06ab466d.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-db8502da.0d5e7436.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-e7bc579c.11d1420a.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-ed5760b0.9bbf661b.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-f0958a12.2e788842.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-f6103792.80d271d1.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/chunk-f7c55c34.a1fd5835.js" rel="prefetch"><link href="http://hrdirector.localhost:3000/dist/js/admin-inertia.0274a243.js" rel="preload" as="script"><link href="http://hrdirector.localhost:3000/dist/js/chunk-common.18905f27.js" rel="preload" as="script"><link href="http://hrdirector.localhost:3000/dist/js/chunk-vendors.b004cad8.js" rel="preload" as="script"></head><body><div id="header_wrap"><div class="container"><div class="row"><div class="col-md-8 pull-left"><div class="row"><div class="col-md-2"><a href="/"><img src="/assets/images/bea-logo.png" alt="Bent Ericken & Associates Logo" height="83" width="83"></a></div><div class="col-md-10"><h2 class="header_wrap_h2">HR Director</h2></div></div></div><div class="col-md-4 pull-right" id="user_log_in_out"><div class="row"><div class="col-md-7 text-right"><h4><em>Hello, <a href="#">{{ auth()->user()->prefix }} {{ ucfirst( auth()->user()->first_name ) }} {{ ucfirst( auth()->user()->last_name ) }}</a></em></h4></div><div class="col-md-5 pull-right"><h4><a href="/auth/logout">LOGOUT</a></h4></div></div></div></div></div></div><div id="main_navigation_wrap"><nav class="navbar navbar-inverse" role="navigation"><div class="container"><div class="row"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin_navigation"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button></div><div class="collapse navbar-collapse center-block" id="admin_navigation"><ul class="nav navbar-nav"><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-suitcase navigation_img"></i>BUSINESS LIST</a><ul class="dropdown-menu"><li><a href="/admin/business">BUSINESS LIST</a></li><li><a href="/admin/business/create">NEW BUSINESS SETUP</a></li></ul></li><li><a href="/admin/login-list"><i class="fa fa-users navigation_img"></i>LOGIN LIST</a></li><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-copy navigation_img"></i>POLICIES</a><ul class="dropdown-menu"><li><a href="/admin/policies">POLICY LIST</a></li><li><a href="/admin/policies/updates">POLICY UPDATE LIST</a></li><li><a href="/admin/policies/create">NEW POLICY</a></li></ul></li><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-comment-o navigation_img"></i>JOB DESCRIPTIONS</a><ul class="dropdown-menu"><li><a href="/admin/job-descriptions">JOB DESCRIPTIONS</a></li><li><a href="/admin/job-descriptions/create">NEW JOB DESCRIPTION</a></li></ul></li><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a><ul class="dropdown-menu"><li><a href="/admin/forms">FORMS</a></li><li><a href="/admin/forms/create">NEW FORM</a></li></ul></li><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-alt navigation_img"></i>FAQ'S</a><ul class="dropdown-menu"><li><a href="/admin/faqs">FAQ'S</a></li><li><a href="/admin/faqs/create">ADD NEW FAQ</a></li></ul></li><li><a href="/admin/email-list"><i class="fa fa-envelope navigation_img"></i>Email Log</a></li><li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle navigation_img"></i>HELP</a><ul class="dropdown-menu"><li><a href="/admin/help">HELP</a></li><li><a href="/admin/help/create">NEW HELP</a></li></ul></li></ul></div></div></div></nav></div>@include('shared.flash-message') @yield('content')<div id="footer"><div class="container text-center"><div class="row"><div class="col-md-4"><h4>POWERED BY BENT ERICKSEN & ASSOCIATES</h4></div><div class="col-md-4"><h4>HR DIRECTOR {{ env('APP_VERSION') }}<br>COPYRIGHT {{ \Carbon\Carbon::now()->format('Y') }}</h4></div><div class="col-md-4"><h4>CONTACT SUPPORT</h4><p><em>800-679-2760<br><a href="mailto:info@bentericksen.com">info@bentericksen.com</a></em></p></div></div></div></div><script src="https://code.jquery.com/jquery-1.11.3.min.js"></script><script src="/assets/js/cleave.min.js"></script><script src="/assets/scripts/general.js"></script>@section('foot') @parent<script src="/assets/js/plugins/ckeditor/ckeditor.js"></script><script src="/assets/js/plugins/ckeditor/plugins/nanospell/autoload.js"></script><script src="/assets/js/plugins/ckeditor/plugins/lite/lite-interface.js"></script>@show<script>$(document).ready(function () {
        
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
    });</script>@include('user.modals.sessionTimeout')<noscript><strong>We're sorry but resources doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript><script src="http://hrdirector.localhost:3000/dist/js/chunk-vendors.b004cad8.js"></script><script src="http://hrdirector.localhost:3000/dist/js/chunk-common.18905f27.js"></script><script src="http://hrdirector.localhost:3000/dist/js/admin-inertia.0274a243.js"></script></body></html>