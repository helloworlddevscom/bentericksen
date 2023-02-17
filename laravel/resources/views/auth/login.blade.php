@extends('auth.wrap')

@section('content')
    {!! Form::open(['url' => '/auth/login', 'class' => 'form-horizontal']) !!}
    <div class="col-md-8 col-md-offset-2 content login_wrap">
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif
        <div class="form-horizontal form-group">
            <label for="email" class="col-md-2 col-md-offset-1 control-label">Email:</label>
            <div class="col-md-7">
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>
        </div>

        <div class="form-horizontal form-group">
            <label for="password" class="col-md-2 col-md-offset-1 control-label">Password:</label>
            <div class="col-md-7">
                <input type="password" class="form-control" id="password" name="password">
                <span>{{ session('disabled') }}</span>
            </div>
        </div>

        <div class="form-horizontal form-group">
            <div class="col-md-7 col-md-offset-3">
                <div class="row">
                    <div class="col-md-1"><input type="checkbox" class="form-control check-box" name="remember"></div>
                    <div class="col-md-8"><label for="auto_login" class="control-label">Remember Me</label></div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-default btn-sm pull-right">Login</button>
                    </div>
                </div>
                <a href="/password/email">Forgot Password?</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        const MINUTE = 1000 * 60
        const SESSION_LIFETIME = ({{ config('session.lifetime') }}) * MINUTE
        let timeout;

        let email = document.cookie.match('email=([^;]+)');

        if (email && email.length > 0) {
            $('input[name="email"]').val(decodeURIComponent(email[1]));
            $('input[name="remember"]').prop('checked', true);
        }

        timeout = setTimeout(() => {
            window.location.reload()
        }, SESSION_LIFETIME)
        
        document.addEventListener('visibilitychange', () => {
            document.visibilityState === 'visible' && window.location.reload()
        })
    </script>
@endsection
