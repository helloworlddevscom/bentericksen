@extends('emails.wrap')
@section('content')
    <p>Dear <?php echo $user->first_name ?>, </p>
    <br>
    <p>Click here to activate your account: {{ url('password/reset/'.$token) }}</p>
@stop