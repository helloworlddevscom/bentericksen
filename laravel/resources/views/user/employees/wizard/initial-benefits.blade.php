@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
					<div class="row">
						<div class="col-md-8 col-md-offset-2 sub_content">
							<h4 class="text-center">Initial Benefits Amount</h4>
						</div>
						<div class="col-md-12 sub_content even">
							<label for="benefits_start_date" class="col-md-3 control-label">Start Date:</label>
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="form-control date-picker" id="benefits_start_date" name="parameters[benefits_start_date]" value="" placeholder="mm/dd/yyyy">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-12 sub-content">
							<div class="row">
								<div class="table-responsive">
									<table class="table table-striped" id="table">
										<thead>
											<tr>
												<th class="text-center">
													<label class="control-label">Name</label>
												</th>
												<th colspan="2" class="text-center">
													<label class="control-label">PTO / Vacation</label>
												</th>
												<th colspan="2" class="text-center">
													<label class="control-label">Sick Leave</label>
												</th>
												<th class="text-center">
													<label class="control-label">PDBA</label>
												</th>
											</tr>
											<tr>
												<th></th>
												<th class="text-center">
													<label class="control-label">Earned</label> {!! $help->button("u3502") !!}
												</th>
												<th class="text-center">
													<label class="control-label">Available</label> {!! $help->button("u3503") !!}
												</th>
												<th class="text-center">
													<label class="control-label">Earned</label>
												</th>
												<th class="text-center">
													<label class="control-label">Available</label>
												</th>
												<th></th>
											</tr>
										</thead>
										<tbody class="empSetup">
											@foreach( $employees as $employee )
												<tr>
													<td>
														{{ $employee->prefix }} {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->suffix }}
													</td>
													<td>
														<input type="text" class="form-control" id="pto_vac_earned" name="employees[{{ $employee->id }}][pto_vac_earned]" value="">
													</td>
													<td>
														<input type="text" class="form-control" id="pto_vac_avaliable" name="employees[{id}][pto_vac_avaliable]" value="">
													</td>
													<td>
														<input type="text" class="form-control" id="sick_earned" name="employees[{id}][sick_earned]" value="">
													</td>
													<td>
														<input type="text" class="form-control" id="sick_avaliable" name="employees[{id}][sick_avaliable]" value="">
													</td>
													<td>
														<input type="text" class="form-control" id="dental_pdba" name="employees[{id}][dental_pdba]" value="">
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row text-center buttons content">
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="previous">BACK</button>&nbsp;&nbsp;
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="next">NEXT</button>
						<br>
						<a href="/user/employees/wizard/accessibility">Next</a>						
					</div>
					<div class="row text-center content no-padding-top">
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="save">SAVE & EXIT</button>
					</div>
				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop