@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">	
	
				<div class="col-md-12 heading">
					<h3><a href="/admin/categories">Category</a> / Add New</h3>
				</div>
				<div class="col-md-12 content">
					{!! Form::open(['url' => '/admin/categories', 'class' => 'form-horizontal']) !!}
						<div class="form-group">
							<label for="job_description" class="col-md-3 col-md-offset-2 control-label">Category Title:</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="job_description" name="name" placeholder="Category Title">
							</div>
						</div>
						
						<div class="form-group">
							<label for="job_industry" class="col-md-3 col-md-offset-2 control-label">Category Group:</label>
							<div class="col-md-3">
								<select name="grouping" class="form-control industry_type" id="job_industry">
									<option value="policies">Policies</option>
									<option value="forms">Forms</option>
									<option value="faqs">FAQs</option>
								</select>
							</div>
						</div>

						<div class="col-md-12 text-center buttons">
							<a href="/admin/forms" class="btn btn-default btn-xs ">CANCEL</a>
							<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
						</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop