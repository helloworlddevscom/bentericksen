@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">
				<form method="post" action="/user/employees/wizard/staff" enctype="multipart/form-data" class="form-horizontal">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<!-- -->
					<div class="row">
						<div class="col-md-8 col-md-offset-2 sub_content">
							<h4 class="text-center">Staff List</h4>
						</div>
						<div class="col-md-8 col-md-offset-2 content">
							<div class="col-md-12">
								<div class="row emp-group">
									<div class="col-md-7 col-md-offset-3 text-center no-padding-both padding-bottom">
										<a href="/assets/uploads/EmployeeSetupWizard.xlsx" class="btn btn-defult btn-primary btn-xs" target="_blank">DOWNLOAD EXCEL FILE</a> {!! $help->button("u3501") !!}
									</div>
									<label class="control-label col-md-4">Upload Excel File</label>
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-12 no-padding-left">
												<div class="input-group">
													<span class="input-group-btn">
														<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
													</span>
													<input type="text" class="form-control" id="file-toggle" readonly="" style="border-right:0;">
													<span class="input-group-addon">
														<span class="btn btn-sm btn-file">
															<img src="http:/assets/images/BEA_folder.jpeg.jpg">
															<input type="file" id="employee_setup_assistant" name="file">
															<span class="btn btn-defult btn-primary btn-xs">UPLOAD</span>
														</span>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row text-center buttons content">
						<a href="/user" class="btn btn-defult btn-primary btn-xs" name="action" value="previous">BACK</a> &nbsp;&nbsp;
						<button type="submit" class="btn btn-defult btn-primary btn-xs input-toggle">NEXT</button>
					</div>
					<div class="row text-center content no-padding-top">
						<button type="submit" class="btn btn-defult btn-primary btn-xs input-toggle" name="action" value="save">SAVE & EXIT</button>
					</div>

				</form>

				<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title text-center" id="modalLabel">File Not Uploaded</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="row">
														<div class="col-md-8 col-md-offset-2">
															<p><em>While it is wisest to use the excel upload,<br> some may choose not to go that route and that's ok.<br> Do you wish to continue?</em></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
										<a href="/user/employees/wizard/initial-benefits" class="btn btn-default btn-primary">CONTINUE</a>
									</div>
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
	<script>
	$('.input-toggle').on('click', function() {
		if( $('#file-toggle').val().length == 0 )
		{
			$('#confirmModal').modal('show');
			return false;
		}
	});

	$('#employee_setup_assistant').on('change', function() {
		$('#file-toggle').val($(this).val());
	});

	</script>
@stop
