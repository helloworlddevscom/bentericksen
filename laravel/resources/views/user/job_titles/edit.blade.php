@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
				{!! Form::open(['route' => ['user.job-titles.update', $job->id], 'class' => 'form-horizontal', 'method' => 'put']) !!}
					<div class="col-md-12 text-center">
						<h3>New Job Title</h3>
					</div>
					<div class="col-md-12 content">						
						<div class="form-group">
							<label for="name" class="col-md-3 col-md-offset-1 control-label">Job Title:</label>
							<div class="col-md-5">
								{!! Form::text('name', $job->name, ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Job Title']) !!}
								@if($errors->first('name'))
									<p class="errors">Job Title is Required</p>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 col-md-offset-4">
								<a href="/user/job-titles" class="btn btn-default">Cancel</a> <input type="submit" class="btn btn-primary" value="Update">
							</div>
						</div>
					</div>
				{!! Form::close() !!}
				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop