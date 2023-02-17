@extends('emails.wrap')
@section('content')
    <p>Greetings,</p>
    <br>
    <p>We have reviewed your proposed changes to <b>{{ $policy->manual_name }}</b>. Unfortunately, your changes cannot be approved due to the following:</p>
    <br>
    <p>{{ $justification }}</p>
@stop