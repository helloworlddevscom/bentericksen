@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
                <div class="col-md-12 heading">
                    <h3>Email &quot;{{ $email->subject }}&quot;</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="emails_table">

                        <tr>
                            <th>To</th>
                            <td>{{ $email->to_address }}</td>
                        </tr>

                        <tr>
                            <th>Date Sent</th>
                            <td>{{ $email->sent_at }}</td>
                        </tr>

                        <tr>
                            <th>Subject</th>
                            <td>{{ $email->subject }}</td>
                        </tr>

                        <tr>
                            <th>Body</th>
                            <td>{!! $email->body !!}</td>
                        </tr>

                        <tr>
                            <th>Related To</th>
                            <td>@if (!empty($email->related_type))
                                    {{ $email->related_type }} #{{ $email->related_id }}
                                @endif</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>{!! $email->status !!}</td>
                        </tr>

                        <tr>
                            <th>Error Message</th>
                            <td>{!! $email->error !!}</td>
                        </tr>

                    </table>
                </div>
                <div class="col-12 text-center">
                    <a class="btn btn-default" href="/admin/user/{{$email->user_id}}/emails">Back to Email List</a>
                </div>
			</div>
		</div>
    </div>
@stop
