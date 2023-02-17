@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
		
				<div class="col-md-12 heading">
					<h3>Policy Update</h3>
				</div>

				<style>
					.form-horizontal .control-label {
						text-align: left;
					}
				</style>
				
					<div class="col-md-12 content">
						<div class="row">
							<div class="col-md-10 col-md-offset-1 policies_content">
								<a href="/admin/policies/updates"> (( Placeholder )) New Update Created</a>
							</div>
						</div>
					</div>
				
			</div>
		</div>
	</div>
	
@stop

@section('foot')
	@parent			
@stop