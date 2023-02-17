@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		

				<form>
					<div class="modal fade" id="email_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="modalLabel">Email List <span id="email_modal_name"></span></h4>
								</div>
								<div class="modal-body">
									<div class="form-group row hidden" id="email_list_active">
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Active Email List:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select1">
													{exp:freemember:members group_id="9" limit="9999"}
													<option value="{email}">{screen_name}</option>
													{/exp:freemember:members}
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="add">Add</button>
											</div>
										</div>
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Added Emails:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select2">
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="remove">Remove</button>
											</div>
										</div>
									</div>
									<div class="form-group row hidden" id="email_list_inactive">
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Inactive Email List:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select1">
													{exp:freemember:members group_id="6" limit="9999"}
													<option value="{email}">{screen_name}</option>
													{/exp:freemember:members}
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="add">Add</button>
											</div>
										</div>
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Added Emails:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select2">
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="remove">Remove</button>
											</div>
										</div>
									</div>
									<div class="form-group row hidden" id="email_list_other">
										<label for="" class="col-md-2 col-md-offset-2 control-label">:</label>
										<div class="col-md-4">
											<textarea>email_list_other</textarea>
										</div>
									</div>
									<div class="form-group row hidden" id="email_list_consultants">
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Consultants Email List:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select1">
													{exp:freemember:members group_id="8" limit="9999"}
													<option value="{email}">{screen_name}</option>
													{/exp:freemember:members}
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="add">Add</button>
											</div>
										</div>
										<div class="col-md-6">
											<label for="" class="col-md-10 col-md-offset-2 control-label">Added Emails:</label>
											<div class="col-md-6 col-md-offset-3">
												<select multiple class="form-control date-box" id="select2">
												</select>
												<button type="button" class="btn btn-default btn-xs btn-primary" id="remove">Remove</button>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">CANCEL</button>
									<button type="button" class="btn btn-primary btn-xs btn-set-email" data-dismiss="modal">SAVE</button>
								</div>
							</div>
						</div>
					</div>
					{/exp:channel:form}
				{/if}
				{if segment_4 == "step-3"}
					{exp:channel:form
						channel="update"
						url_title="{segment_3}"
						include_assets="no"
						include_jquery="no"
						class="form-horizontal"
						return="admin/policy-update-list"
					}
					<div class="col-md-12 content">
						<div class="row">
							<div class="col-md-10 col-md-offset-1 policies_content">
								<div class="form-group">
									<label for="update_title" class="col-md-4 control-label">Title:</label>
									<div class="col-md-4">
										<input type="text" class="form-control" id="update_title" name="update_title" value="{update_title}" placeholder="Title">
									</div>
								</div>
								<div class="form-group">
									<label for="update_send_date" class="col-md-4 control-label">Send Date:</label>
									<div class="col-md-2">
										<input type="text" class="form-control date-picker" id="update_send_date" name="update_send_date" value="{update_send_date}" placeholder="mm/dd/yyyy">
									</div>
								</div>
								<div class="form-group">
									<label for="business_name" class="col-md-4 control-label">Policy:</label>
									<div class="col-md-4">
										<a href="{path='admin/new-update/{segment_3}/step-1'}" class="btn btn-default btn-xs">VIEW LIST</a>
									</div>
								</div>
								<div class="form-group">
									<label for="business_name" class="col-md-4 control-label">Email List:</label>
									<div class="col-md-4">
										<a href="{path='admin/new-update/{segment_3}/step-2'}" class="btn btn-default btn-xs">VIEW LIST</a>
									</div>
								</div>
								<div class="form-group">
									<label for="update_active_clients_consultants" class="col-md-4 col-md-offset-2">Active Clients / Consultants:</label>
									<div class="col-md-8 col-md-offset-2">
										{field:update_active_clients_consultant}
									</div>
								</div>
								<div class="form-group">
									<label for="update_inactive_clients" class="col-md-4 col-md-offset-2">Inactive Clients:</label>
									<div class="col-md-8 col-md-offset-2">
										{field:update_inactive_clients}
									</div>
								</div>
								<div class="form-group">
									<label for="update_other_additional" class="col-md-4 col-md-offset-2">Other / Additional:</label>
									<div class="col-md-8 col-md-offset-2">
										{field:update_other_additional}
									</div>
								</div>
								<div class="row text-center buttons">
									<a href="{path='admin/policy-update-list'}" class="btn btn-default btn-xs ">CANCEL</a>
									<button type="submit" class="btn btn-default btn-xs btn-primary">FINALIZE UPDATE</button>
								</div>
								<div class="col-md-6 col-md-offset-3">
									<div id="progressbar"></div>
								</div>
							</div>
						</div>
					</div>
				{/exp:channel:form}
			
				--}}
			</div>
		</div>
@stop

@section('foot')
	@parent			
	<script>
    window.PAGE_INIT.push(() => {
      $("#policy_table").DataTable()    
    })
	</script>
@stop