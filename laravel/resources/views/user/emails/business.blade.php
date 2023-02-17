@extends('emails.wrap')
@section('content')
    <p>Dear <?php echo $user->first_name ?>, </p>
    <br>
    <p>This is a confirmation that you have made changes to the following policy: <b>{{ $policy->manual_name }}</b>. If you did not authorize this change, please contact us.</p>
@stop