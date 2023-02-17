@extends('emails.wrap')
@section('content')
    <p>Dear Client,</p>
    @if($event['source_type'] === "credit_card")
        <p>We were unable to charge your {{ $event['card_brand'] }} card ending in {{ $event['card_last4'] }} for your HR Director {{ $event['service_type'] }} subscription.</p>
    @elseif($event['source_type'] === "bank_account")
        <p>We were unable to charge your ach debit account ending in {{ $event['card_last4'] }} for your HR Director {{ $event['service_type'] }} subscription.</p>
    @endif
    <br/>
    <p>Business: {{ $event['business'] }}</p>
    <p>Payment Date: {{ $event['payment_date'] }}</p>
    <p>Payment Amount: ${{  $event['amount_paid'] }}</p>
    <p>Reason: {{ $event['reason'] }}</p>
@stop
