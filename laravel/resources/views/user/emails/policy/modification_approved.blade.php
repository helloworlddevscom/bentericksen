@extends('emails.wrap')
@section('content')
    <p>Congratulations!</p>
    <br>
    <p>Your changes to <b>{{ $policy->manual_name }}</b> have been approved. You can view the policy by logging into the HR Director.</p>
    <br>
    <a href="{{ url('/') }}">HR Director</a>
@stop