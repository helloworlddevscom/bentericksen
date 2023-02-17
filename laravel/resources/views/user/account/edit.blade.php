@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
				
				{!! Form::open(['route' => ['user.account.update', $business->id], 'method' => 'put']) !!}
					<div class="col-md-12 text-center">
						<h3>Edit Business Information</h3>
					</div>
					<div class="col-md-12 content">
						<div class="form-group">
							<div class="row">
								<label for="business_name" class="col-md-3 col-md-offset-1 control-label">Business Name:</label>
								<div class="col-md-5 padding-top">
									{{ $business->name }}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label for="address1" class="col-md-3 col-md-offset-1 control-label">Address:</label>
								<div class="col-md-5">
									{!! Form::text('address1', $business->address1, ['id' => 'address1', 'class' => 'form-control', 'placeholder' => 'Business Address 2']) !!}
									@if($errors->first('address1'))
										<p class="errors">Business Address is Required</p>
									@endif
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-5 col-md-offset-4">
									{!! Form::text('address2', $business->address2, ['class' => 'form-control', 'placeholder' => 'Business Address 2']) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label for="city" class="col-md-3 col-md-offset-1 control-label">City, State, Postal Code:</label>
								<div class="col-md-2">
									{!! Form::text('city', $business->city, ['id' => 'city', 'class' => 'form-control', 'placeholder' => 'city']) !!}
									@if($errors->first('city'))
										<p class="errors">City is Required</p>
									@endif									
								</div>
								<div class="col-md-2">
									<fieldset disabled>
										{!! Form::select(null, $states, $business->state, ['class' => 'form-control']) !!}
									</fieldset>
									<p class="text-danger">
										To change your state, please contact our support team.
									</p>
								</div>
								<div class="col-md-2">
									{!! Form::text('postal_code', $business->postal_code, ['class' => 'form-control', 'placeholder' => 'Postal Code']) !!}
									@if($errors->first('postal_code'))
										<p class="errors">Postal Code is Required</p>
									@endif										
								</div>
							</div>
						</div>								
						
						<div class="form-group">
							<div class="row">
								<label for="phone1" class="col-md-3 col-md-offset-1 control-label">Phone 1:</label>
								<div class="col-md-3">
									{!! Form::text('phone1', $business->phone1, ['id' => 'phone1', 'class' => 'form-control phone_number', 'placeholder' => 'Phone 1']) !!}
									@if($errors->first('phone1'))
										<p class="errors">Phone 1 is Required</p>
									@endif
								</div>
								<div class="col-md-2">
									{!! Form::select('phone1_type', ['cell' => 'Cell', 'office' => 'Office', 'home' => 'Home'], $business->phone1_type, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label for="phone2" class="col-md-3 col-md-offset-1 control-label">Phone 2:</label>
								<div class="col-md-3">
									{!! Form::text('phone2', $business->phone2, ['id' => 'phone2', 'class' => 'form-control phone_number', 'placeholder' => 'Phone 2']) !!}
								</div>
								<div class="col-md-2">
									{!! Form::select('phone2_type', ['cell' => 'Cell', 'office' => 'Office', 'home' => 'Home'], $business->phone2_type, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label for="phone3" class="col-md-3 col-md-offset-1 control-label">Phone 3:</label>
								<div class="col-md-3">
									{!! Form::text('phone3', $business->phone3, ['id' => 'phone3', 'class' => 'form-control phone_number', 'placeholder' => 'Phone 3']) !!}
								</div>
								<div class="col-md-2">
									{!! Form::select('phone3_type', ['cell' => 'Cell', 'office' => 'Office', 'home' => 'Home'], $business->phone3_type, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>								
						
						<div class="form-group">
							<div class="row">
								<label for="fax" class="col-md-3 col-md-offset-1 control-label">Fax:</label>
								<div class="col-md-3">
									{!! Form::text('fax', $business->fax, ['id' => 'fax', 'class' => 'form-control  phone_number', 'placeholder' => 'Fax']) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label for="business_webite" class="col-md-3 col-md-offset-1 control-label">Website:</label>
								<div class="col-md-5">
									{!! Form::text('website', $business->website, ['id' => 'website', 'class' => 'form-control', 'placeholder' => 'Website']) !!}
								</div>
							</div>
						</div>

						<div class="col-md-12 text-center buttons">
							<a href="/user/account" class="btn btn-default btn-xs ">CANCEL</a>
							<button type="submit" class="btn btn-default btn-primary btn-xs">SAVE</button>
						</div>
					</div>			
				{!! Form::close() !!}
			</div>
		</div>
@stop

@section('foot')
	@parent			
	
@stop