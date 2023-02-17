@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">
				<div class="col-md-12 content">
					<div class="col-md-8 col-md-offset-2 content">
						<div class="row text-center">
							<h3>Current Employee Number</h3>

							<form id="employees-number" method="post" action="/user/employees/number" class="form-horizontal">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-md-8 col-md-offset-2 content">
										<div class="col-md-12">
											<div class="row">
												<label class="control-label col-md-5">Current Employee Number
													@if( $employeeCount == 0)<small class="errors">Whoops! Your employee number is too low. Please enter 1 or more.</small>@endif
												</label>
												<div class="col-md-7">
													<div class="row">
														<div class="col-md-12 no-padding-left">
															<div class="input-group">
																<input type="text" class="form-control" name="employees" value="{{ $employeeCount }}">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div id="feedback" class="row text-left" style="font-size:14px; padding: 20px 0;"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row text-center buttons">
									<a href="/user" class="btn btn-primary btn-xs" name="action" value="previous">BACK</a> &nbsp;&nbsp;
									<button type="submit" class="btn btn-primary btn-xs input-toggle">UPDATE</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('foot')
	@parent
	<script src="{{ asset('assets/js/employee/number.js') }}"></script>
@stop