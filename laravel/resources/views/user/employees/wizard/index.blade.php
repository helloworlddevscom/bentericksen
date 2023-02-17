@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		

				<!-- -->

					<div class="col-md-12">
						<h3 class="text-center">Employee Setup Wizard</h3>
					</div>

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-10 col-md-offset-1">
								<style>
									.welcome-screen {
										padding-top: 57px;
									}
									.welcome-screen h4 {
										color: #673366;
										margin-bottom: 35px;
									}
									.welcome-screen p {
										margin-bottom: 25px;
									}
								</style>
								<div class="welcome-screen">
									<div class="row">
										<div class="col-md-8 col-md-offset-2">
											<h4 class="text-center">Welcome to the Employee Setup Wizard</h4>
										</div>
										<div class="col-md-8 col-md-offset-2">
											<p class="text-justify">Welcome to the HR Director Employee Setup Wizard! This tool easily enters your employees into the Employee module. Using this Wizard will save time compared to entering employees manually. We recommend completing the Policy Setup Wizard and Employee Setup Wizard before using other HR Director features. You can exit anytime and come back, just click Save before you log out.												</p>
											<p class="text-center">Click Next to get started!</p>
										</div>
									</div>
									<div class="row text-center buttons">
										<a href="/user" class="btn btn-defult btn-primary btn-xs">CANCEL</a>
										<a href="/user/employees/wizard/staff" class="btn btn-defult btn-primary btn-xs" name="action" value="next">NEXT</a>
									</div>
								</div>
							</div>
						</div>
					</div>

				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop