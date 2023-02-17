@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
				<!-- -->
				<form method="post">
					<input type="hidden" name="_token" value="{{  csrf_token() }}">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 sub_content">
							<h4 class="text-center">Employee Accessibility</h4>
						</div>
						<div class="col-md-12 sub-content even">
							<div class="col-md-6">
								<label class="text-center">Do you want the employees to have access to their information</label> {!! $help->button("u3504") !!}
							</div>
							<div class="col-md-6">
								<input type="radio" id="yes" name="employee_access" value="1">
								<label for="yes" class="control-label">Yes! Send the activation email now</label>
							</div>
						</div>
						<div class="col-md-12 sub-content odd">
							<p class="col-md-6 col-md-offset-6">
								<input type="radio" id="no" name="employee_access" value="0">
								<label for="no" class="control-label">No, do not allow employee access at this time</label>
							</p>
						</div>
					</div>
					<div class="row text-center buttons content">
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="previous">BACK</button>&nbsp;&nbsp;
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="next">NEXT</button>						
					</div>
					<div class="row text-center content no-padding-top">
						<button type="submit" class="btn btn-defult btn-primary btn-xs" name="action" value="save">SAVE & EXIT</button>
					</div>
				</form>
				<!-- -->				
			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop