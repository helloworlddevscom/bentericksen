@extends('admin.wrap')

@section('content')
	
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">	
	
				<div class="col-md-12 heading">
					<h3><a href="/admin/help">Help</a> / New Help</h3>
				</div>
				<div class="col-md-12 content">
					{!! Form::open(['route' => 'admin.help.store', 'class' => 'form-horizontal']) !!}
						<div class="form-group">
							<label for="help_title" class="col-md-3 control-label">Title:</label>
							<div class="col-md-6">
								@if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
								<input type="text" class="form-control" id="help_title" name="title" placeholder="Title">
							</div>
						</div>
						
						<div class="form-group">
							<label for="help_section" class="col-md-3 control-label">Section:</label>
							<div class="col-md-6">
								{!! Form::select('section', $sections->section(), null, ['id' => 'help_section', 'class' => 'form-control help_section']) !!}
							</div>
						</div>
						
						<div class="form-group">
							<label for="help_sub_section" class="col-md-3 control-label">Sub-Section:</label>
							<div class="col-md-6">
								<select name="sub_section" id="help_sub_section" class="form-control help_sub_section">
									<option value=""> - Select One - </option>
									@foreach( $sections->subSections() as $key => $section )
										@foreach( $section as $ke => $val )
											<option value="{{ $ke }}" class="{{ $key }} hidden">{{ $val }}</option>
										@endforeach
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-10 col-md-offset-1">
								@if ($errors->has('answer')) <p class="help-block">{{ $errors->first('answer') }}</p> @endif
								<textarea class="form-control ckeditor" id="help_answer" name="answer"></textarea>
							</div>
						</div>

					<div class="col-md-12 text-center buttons">
						<a href="/admin/help" class="btn btn-default btn-xs ">CANCEL</a>
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
		$( '#help_section' ).on('change', function() {
			key = $(this).val();
			
			$( '#help_sub_section option[value!=""]' ).addClass( 'hidden' );
			$( '#help_sub_section option[value=""]' ).prop( 'selected', true);			
			$( '#help_sub_section option.'+ key ).removeClass( 'hidden' );
		});
		
		key = $( '#help_section' ).val();
		
		$( '#help_sub_section option[value!=""]' ).addClass( 'hidden' );
		$( '#help_sub_section option[value=""]' ).prop( 'selected', true);			
		$( '#help_sub_section option.'+ key ).removeClass( 'hidden' );	

		CKEDITOR.replace('answer', {
			customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
		});		
	</script>
@stop