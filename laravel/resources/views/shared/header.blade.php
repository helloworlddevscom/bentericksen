{!! $banner !!}
<div id="header_wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-7 pull-left">
                <div class="row">
                    <div class="col-md-2">
                        <a href="/"><img class="logo" src="/assets/images/bea-logo.png" alt="Bent Ericken & Associates Logo" height="83" width="83"></a>
                    </div>
                    <div class="col-md-10">
                        <h2 class="header_wrap_h2">HR Director</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-5 pull-right" id="user_log_in_out">
                <div class="row">
                    <div class="col-md-8 text-right">
                        <h4><em>Hello, <a href="/user/account">{{ $viewUser->prefix }} {{ $viewUser->first_name }} {{ $viewUser->last_name }}</a></em></h4>
                    </div>
                    <div class="col-md-4 pull-right">
                        <h4>
                            <a href="/auth/logout">LOGOUT</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('shared.navigation')