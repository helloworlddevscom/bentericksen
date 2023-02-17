@extends('auth.wrap')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset Password</div>
                    <div id="password-panel-body" class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {!! session('status') !!}
                            </div>
                        @endif

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="captcha-message" class="alert alert-danger hidden">
                            <strong>Please complete the captcha.</strong>
                        </div>

                        <form id="password-form" class="form-horizontal" role="form" onsubmit="return false">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div id="html_element"></div><br />
                                    <button id="password-btn" type="submit" class="btn btn-primary">Send Password Reset Link</button>
                                    @if(old('email'))
                                        <a href="{{ url('/auth/login') }}" type="submit" class="btn btn-default">Back</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
                                async defer>
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
