@extends('auth.wrap')

@section('content')
    {!! Form::open(['url' => route('bonuspro.plan.login', ['plan' => $plan->id]), 'class' => 'form-inline']) !!}
    <div class="col-md-8 col-md-offset-2 content login_wrap">
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif
        <h2>{{ $plan->plan_name }}</h2>
        
        
        <div class="form-group">
            <label for="password" style="margin-right: 1.5rem;">Password:</label>
            <input type="password" class="form-control" id="password" name="password" style="margin-bottom: 0;margin-right: 1.5rem;" />
            <button type="submit" class="btn btn-default">Login</button>
        </div>
        <div class="col-12" style="margin-top: 1.5rem;">
            <div class="form-group">
                <a href="{{ route('bonuspro.plan.showLinkReset', ['plan' => $plan->id]) }}">
                    Forgot Password?
                </a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
