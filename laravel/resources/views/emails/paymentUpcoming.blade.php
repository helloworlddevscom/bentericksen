@extends('emails.wrap')
@section('content')
    <p>Dear Client,</p>
    <p>This is a friendly reminder that your HR Director service {{ $event['service_type'] }} subscription will automatically renew on {{ $event['renewal_date'] }}.</p>
    <p>The payment method on file ({{ $event['last4'] }}) will be charged at that time.</p>
@stop
