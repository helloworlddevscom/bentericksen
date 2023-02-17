{{--
TODO: fix role based auth middleware (BENT-369)
NOTE: This is a quick+dirty hack to keep 'employee's out of the /user area, making QA-ing
BENT-435 easier. This major security hole was reported in BENT-369.
We're explicitly testing all the roles, since the role_user table in prod currently has
some users with mulitple roles, including employee, like consultants
Do not forget to remove the closing "@endif" at the end of the file, when this hack is removed.
--}}
@if(!$viewUser->hasRole('admin') && !$viewUser->hasRole('owner') && !$viewUser->hasRole('manager') && !$viewUser->hasRole('consultant'))
    <script>window.location = "/employee";</script>
@else
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta HTTP-EQUIV="EXPIRES" CONTENT="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!--
    <link href="/assets/styles/plugins/bootstrap/bootstrap.min.css?v=3" rel="stylesheet">
    <link href="/assets/styles/plugins/calendar/calendar.min.css?v=2" rel="stylesheet">
    <link href="/assets/styles/plugins/jqueryUi/jquery-ui.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/styles/jquery-ui-timepicker-addon.css" rel="stylesheet">
    <link href="/assets/styles/main.css?v={{ time() }}" rel="stylesheet">
    <link href="/assets/styles/main2.css?v={{ time() }}" rel="stylesheet">
    <link href="/assets/styles/user.css?v={{ time() }}" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
-->
    <title>HR Director</title>
    <link rel="shortcut icon" href="/assets/images/bea-logo.png"/>
     @include ('shared.client-data')
    
    <style type="text/css">
        .doe .ui-datepicker-calendar {
            display:none;
        }
    </style>

    @include ('shared.google-analytics')


</head>
<body>
@include('shared.curtain')

@include('shared.header')

@if(($viewUser->status == 'disabled') || ($viewUser->can_access_system == 0))
    <div style="width: 100%; height: 710px; background-color: #fff;">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    Your account has been disabled, please contact your administrator for more information.
                </div>
            </div>
        </div>
    </div>
@else
    @if(!$viewUser->permissions('m100'))
        <div style="width: 100%; height: 710px; background-color: #fff;">
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="col-md-12 content">
                        You Do Not Have Access to HR Director
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('shared.flash-message')

        @yield('content')
    @endif
@endif

<div id="footer">
    @section('foot')
        @parent
        <!--

        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="/assets/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
        <script src="/assets/scripts/plugins/jquery.maskedinput.min.js?v=1.1"></script>
        <script src="/assets/js/cleave.min.js"></script>
        <script src="/assets/scripts/masks.js?v=1.1"></script>
        <script src="/assets/js/plugins/ckeditor/ckeditor.js"></script>
        <script src="/assets/js/plugins/ckeditor/plugins/nanospell/autoload.js"></script>
        <script src="/assets/js/plugins/ckeditor/plugins/lite/lite-interface.js"></script>
        <script src="/assets/js/custom_cke.js"></script>

        <script src="/assets/js/app.js"></script>
      -->
        @include('shared.footer')
    @show
</div>

@if(isset($modals))
    @if(\Request::is('user'))
        @include('user.modals.renewalNotice')
    @endif

    @include('user.modals.policyUpdatesNotice')

    @foreach($modals as $modal)
        @if($modal == 'policyCompare')
            @if($policy_updates)
                @foreach($policy_updates as $key => $policy_update)
                    @foreach($old_policies as $old_policy_key => $old_policy)
                        @if(!empty($old_policy) && $old_policy->status == 'disabled' && $old_policy->requirement == 'optional')
                            @include('user.modals.policyDisabled', ['key' => $key, 'policy_update' => $policy_update, 'old_policy' => $old_policy])
                            <?php $key++; // disabled policies will have 2 modals ?>
                        @endif
                        @include('user.modals.' . $modal, ['key' => $key, 'length' => count($old_policies), 'policy_update' => $policy_update, 'old_policy' => $old_policy])
                    @endforeach
                @endforeach
            @endif
        @else
            @include('user.modals.' . $modal)
        @endif
    @endforeach
@else
    @include('user.modals.policyComplete')
@endif

@include('user.modals.sessionTimeout')

<noscript>
    <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
    
<!-- built files will be auto injected -->

</body>
</html>
@endif
