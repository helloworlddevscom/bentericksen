@extends('emails.wrap')
@section('content')
    <p>Dear Client,</p>
    <p>Your payment was successful for your HR Director {{ $event['service_type'] }} subscription.</p><br />
    <p>Business: {{ $event['business'] }}</p>
    <p>Payment Date: {{ $event['payment_date'] }}</p>
    <p>Payment Amount: ${{  $event['sub_total'] }}</p>
    @if($event['discount'])
        <p>{{ $event['discount_name'] }}: -{{ $event['discount_amount'] }}</p>
        <p>Net Payment Amount: ${{ $event['amount_paid'] }}</p>
    @endif
    <p>Payment Method ending in: {{ $event['last4'] }}</p>
@stop
