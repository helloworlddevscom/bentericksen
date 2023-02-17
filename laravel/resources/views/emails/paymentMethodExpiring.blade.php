@extends('emails.wrap')
@section('content')
    <p>Dear Client,</p>
    <p>This is a friendly reminder that your {{ $event['card_brand'] }} card ending in {{ $event['card_last4'] }} expires at the end of the month.</p>
    <p>To update your payment method, please click <a href="https://hrdirector.bentericksen.com/auth/login/">here</a> to log into the HR Director. Then click on your Account page and select “Add/Edit Payment Method.”</p>
@stop
