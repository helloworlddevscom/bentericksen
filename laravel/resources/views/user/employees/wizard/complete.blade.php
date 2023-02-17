@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			

				<!-- -->
					<div class="row">
						<div class="col-md-8 col-md-offset-2 sub_content text-center">
							<h4>Employee Setup Wizard Complete!</h4>
							<p>The HR Director is ready to use.</p>
						</div>
					</div>
					<div class="row text-center buttons content">
						Continue to
						<a href="/user/employees" class="btn btn-defult btn-primary btn-xs">EMPLOYEE LIST</a>
						or
						<a href="/user/permissions" class="btn btn-defult btn-primary btn-xs">PERMISSIONS</a>
					</div>
				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop