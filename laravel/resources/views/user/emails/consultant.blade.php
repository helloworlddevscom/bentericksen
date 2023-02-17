@extends('emails.wrap')
@section('content')
    <p>Dear {{ $user->first_name }},</p>
    <br>
    <p>{{ $user->full_name }} has modified a policy for {{ $business->name }}, {{ $business_owner->full_name }}. The policy is:  <b>{{ $policy->manual_name }}</b></p>
@stop
