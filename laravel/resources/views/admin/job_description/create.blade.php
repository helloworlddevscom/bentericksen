@extends('admin.wrap')

@section('content')
	
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">	
	
				<div class="col-md-12 heading">
					<h3><a href="/admin/job-description">Job Description</a> / Add New</h3>
				</div>
				<div class="col-md-12 content">
					{!! Form::open(['route' => 'admin.job-descriptions.store', 'class' => 'form-horizontal']) !!}
						<div class="form-group">
							<label for="job_description" class="col-md-3 col-md-offset-2 control-label">Job Description:</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="job_description" name="name" placeholder="Job Description">
							</div>
						</div>
						
						<div class="form-group">
							<label for="job_industry" class="col-md-3 col-md-offset-2 control-label">Industry Type:</label>
							<div class="col-md-3">
								<select name="industry" class="form-control industry_type" id="job_industry">
									<option value=""> - Select One - </option>
									@foreach($industries AS $key => $industry)
										<option value="{{ $key }}">{{ $industry['title'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="job_sub_type" class="col-md-3 col-md-offset-2 control-label">Sub-Type:</label>
							<div class="col-md-3">

								<select name="subtype[]" class="form-control industry_sub_type" id="job_sub_type" multiple>
									@foreach($industries AS $key => $industry)
										@foreach($industry['subtype'] as $ke => $subtype)
											<option class="hidden {{ $key }}" value="{{ $ke }}">{{ $subtype }}</option>
										@endforeach
									@endforeach
								</select>							
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-10 col-md-offset-1">
								<textarea class="form-control ckeditor" name="description"></textarea>
							</div>
						</div>

						<div class="col-md-12 text-center buttons">
							<a href="/admin/job-descriptions" class="btn btn-default btn-xs ">CANCEL</a>
							<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
						</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@stop

@section('foot')
	@parent
	<script>
		$(function () {
			$('#job_industry').on('change', function() {
				elem = $('#job_industry option:selected').val();
				
				$('#job_sub_type option[value!=""]').addClass('hidden');
				$('#job_sub_type option[value=""]').prop('selected', true);
				$('#job_sub_type option.'+elem).each(function() {
					$(this).removeClass('hidden');
				});
			});
		});

		CKEDITOR.replace('description', {
			customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
		});
	</script>		
@stop
