@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">

				<!-- -->
				<div class="content">
					<div class="row">
						<p class="col-md-4"><a href="/user">Dashboard</a> > <a href="/user/employees">Employees</a> > <a href="/user/employees/time-off-requests">Time Off Requests</a></p>
						<h4 class="col-md-4 text-center">Edit Time Off Requests</h4>
						<div class="col-md-1">{!! $help->button("u3101") !!}</div>
					</div>
				</div>
				<div class="content">
                    <div class="row">
					    <div class="col-md-6 col-md-offset-3">

						<form method="post" action="/user/employees/time-off-requests/{{ $timeOffRequest->id }}/editRequest">
							{{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $timeOffRequest->id }}">
                            <input type="hidden" name="user_id" value="{{ $employee->id }}">
							<div class="row">
								<div class="col-md-5">
									<div class="row">
										<label for="" class="col-md-12 text-left">Employee:</label>
										<div class="col-md-12">
											<a href="/user/employees/{{ $employee->id }}/edit">{{ $employee->prefix }} {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->suffix }}</a>
										</div>
									</div>
									<div class="row">
										<label for="request_status" class="col-md-12 text-left">Status:</label>
										<div class="col-md-12">
											<select class="form-control fa-select time-off" id="request_status" name="status">
												<option value="">Select Status</option>
												<option value="approved" class="icon_green" @if($timeOffRequest->status == "approved") selected @endif >&#xf00c;Approved</option>
												<option value="denied" class="icon_red" @if($timeOffRequest->status == "denied") selected @endif >&#xf00d; Denied</option>
												<option value="pending" class="icon_black" @if($timeOffRequest->status == "pending") selected @endif >&#xf0c8; Pending</option>
												<option value="leave" class="icon_blue" @if($timeOffRequest->status == "leave") selected @endif >&#xf0c8; Leave of Absence</option>
											</select>
										</div>
									</div>
									<div class="row">
										<label for="request_type" class="col-md-12 text-left">Type:</label>
										<div class="col-md-12">
											{!! Form::select(
                                                    'type',
                                                    $types,
                                                    str_replace(' ', '_', strtolower($timeOffRequest->type)) , ['class' => 'form-control time-off', 'id' => 'request_type']) !!}
										</div>
									</div>
									<div class="row">
										<label for="start_at" class="col-md-12 text-left">Start Date:</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" class="form-control date-picker time-off" id="start_at" name="start_at" value="{{ date('m/d/Y', strtotime($timeOffRequest->start_at)) }}">
												<span class="input-group-addon">
													<label for="start_at"><i class="fa fa-calendar"></i></label>
												</span>
											</div>
                                            {!! $errors->first('start_at', '<span style="color:red;">:message</span>') !!}
                                        </div>
									</div>
									<div class="row">
										<label for="end_at" class="col-md-12 text-left">End Date:</label>
										<div class="col-md-12">
											<div class="input-group">
												<input type="text" class="form-control date-picker time-off" id="end_at" name="end_at" value="{{ date('m/d/Y', strtotime($timeOffRequest->end_at)) }}">
												<span class="input-group-addon">
													<label for="end_at"><i class="fa fa-calendar"></i></label>
												</span>
											</div>
                                            {!! $errors->first('end_at', '<span style="color:red;">:message</span>') !!}
                                        </div>
									</div>
								</div>
								<div class="col-md-7">
									<div class="row">
										<label for="request_reason" class="col-md-12 text-left">Reason:</label>
										<div class="col-md-12">
											<textarea class="form-control" id="request_reason" name="reason">{{ $timeOffRequest->reason }}</textarea>
										</div>
									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col-md-12 text-center buttons">
									<a href="/user/employees/time-off-requests" class="btn btn-default btn-xs btn-primary">CANCEL</a>
									<button type="submit" class="btn btn-default btn-xs btn-primary">UPDATE</button>
								</div>
							</div>
                            @if (Session::has('message'))
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div style="font-size: 25px;">{{ Session::get('message') }}</div>
                                    </div>
                                </div>
                            @endif
						</form>
					</div>
                    </div>
                </div>
				<!-- -->

			</div>
		</div>
@stop

@section('foot')
	@parent
	<script src="/assets/scripts/plugins/calendar/calendar.min.js"></script>
	<script src="/assets/scripts/plugins/calendar/underscore-min.js"></script>
	<script>
		$(document).ready(function () {
          $(".date-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+10",
            onSelect: function (selectedDate) {
              let id = $(this).attr("id");
              if (id == "start_at") {
                $("#end_at").datepicker('option', 'minDate', selectedDate);
              }
            }
          });
        });

	</script>
@stop
