@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<div class="col-md-12 heading">
					<h3>Help list</h3>
				</div>
				<div class="col-md-12 content">
					<div class="row">
						<div class="col-md-12 text-right">
							<div class="col-md-4 col-md-offset-8">
								<p><a href="/admin/help/create"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Help Topic</a></p>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped" id="help_table">
							<thead>
								<tr>
									<th class="form-group">
										<select class="form-control policy_search_inputs" id="col_1_select_search" name="col_1_select_search">
											<option value="">All</option>
											@foreach($sections->section() as $key => $section)
												<option value="{{ $key }}">{{ $section }}</option>
											@endforeach
										</select>
									</th>
									<th class="form-group">
									
										<select class="form-control policy_search_inputs" id="col_2_select_search" name="col_2_select_search">
											<option value="">All</option>
											@foreach($sections->subSections() as $key => $section)
												@foreach( $section as $ke => $value )
													<option value="{{ $ke }}">{{ $value }}</option>
												@endforeach
											@endforeach
										</select>
									</th>
									<th class="form-group">
										<input type="text" class="form-control" id="col_3_search" placeholder="Title">
									</th>
									<th class="form-group">
										<input type="text" class="form-control" id="col_4_search" placeholder="Answer">
									</th>
									<th>&nbsp;</th>
								</tr>
								<tr>
									<th>
										<span>Section</span>
									</th>
									<th>
										<span>Sub-Section</span>
									</th>
									<th>
										<span>Title</span>
									</th>
									<th>
										<span>Answer</span>
									</th>
									<th class="bg_none">
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($helps as $help)
									<tr>
										<td>
											{{ $help->section }}
										</td>
										<td>
											{{ $help->sub_section }}
										</td>
										<td>
											{{ $help->title }}
										</td>
										<td>
											{!! mb_strimwidth(strip_tags($help->answer), 0, 50, "...</p>") !!}
										</td>
										<td>
											<div style="width: 105px;" class="text-right">
												<a href="/admin/help/{{ $help->id }}/edit" style="display: inline-block;" class="btn btn-default btn-xs ">EDIT</a>
												<button class="btn btn-default btn-xs btn-delete modal-button" style="display: inline-block;" data-target="/admin/help/{{ $help->id }}">DELETE</button>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							{!! Form::open(['method' => 'delete', 'id' => 'modalForm']) !!}
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
								</div>
								<div class="modal-body">
									<!--Update this into low variables or the like-->
									Are you sure you want to delete this Help entry?
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary">Delete</button>
								</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
@stop

@section('foot')
	@parent
	<script>
		var table = $("#help_table").DataTable({
				"bStateSave": true,
				"orderClasses": false
			});	
			
	</script>
	<script src="/assets/admin/tables.js"></script>		
	<script>
		$('.modal-button').on('click', function() {
			target = $(this).attr('data-target');
			$('#modal').modal('show');
			$('#modalForm').attr('action', target);
			return false;
		});
	</script>				
@stop