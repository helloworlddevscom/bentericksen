@extends('emails.wrap')
@section('content')
    <p>Business account information has been changed by {{ $user->full_name }} for {{ $business->name }}.</p>
    <a href="{{ url('/') }}">HR Director</a>
@stop
