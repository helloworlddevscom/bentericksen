@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
				<div class="col-md-12 text-center">
					<h3>Licensure / Certification</h3>
				</div>
				{!! Form::open(['route' => 'user.licensure-certifications.store']) !!}
					<div class="col-md-12 content">
						<div class="row form-group">
							<label for="licensure_certification_type" class="col-md-4 text-right padding-top">Licensure / Certification</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="" name="name" placeholder="Licensure / Certification Type">
							</div>
						</div>
					</div>
					<div class="col-md-12 text-center buttons">
						<a href="/user/licensure-certifications" class="btn btn-default btn-sm btn-primary">CANCEL</a>
						<button type="submit" class="btn btn-default btn-sm btn-primary">SAVE</button>
					</div>
				{!! Form::close() !!}
				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop