@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">

				<div class="col-md-12 heading">
					<h3>Job Description</h3>
				</div>
				<div class="col-md-12 content">
					<div class="row">
						<div class="col-md-12 text-right">
							<div class="col-md-4 col-md-offset-8">
								<p><a href="/admin/job-descriptions/create"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Job Description</a></p>
							</div>
						</div>
					</div>

					<div class="col-md-12 table-responsive">
						<table class="table table-striped" id="job_description_table">
							<thead>
								<tr>
									<th class="form-group">
										<select class="form-control industry_type" id="col_1_select_search" name="col_1_select_search">
											<option value=""> - Industry Type - </option>
											@foreach($industries AS $key => $industry)
												<option value="{{ $key }}">{{ $industry['title'] }}</option>
											@endforeach
										</select>
									</th>
									<th class="form-group">
										<select class="form-control industry_sub_type" id="subtype_search" name="col_2_select_search_subtype">
											<option value=""> - Sub-Type - </option>
											@foreach($industries AS $key => $industry)
												@foreach($industry['subtype'] as $ke => $subtype)
													<option class="hidden {{ $key }}" value="{{ $ke }}">{{ $subtype }}</option>
												@endforeach
											@endforeach
										</select>
									</th>
									<th class="form-group">
										<input type="text" class="form-control" id="col_3_search" placeholder="Job Title">
									</th>
								</tr>
								<tr>
									<th>
										<span>Industry</span>
									</th>
									<th>
										<span>Sub-Type</span>
									</th>
									<th>
										<span>Job Title</span>
									</th>
									<th class="bg_none"></th>
								</tr>
							</thead>
							<tbody>
								@foreach($jobDescriptions as $job)
									<tr>
										<td class="capitalize">{{ $job->industry }}</td>
										<td class="capitalize" data-subtypes="{{ $job->subtypes }}">{{ $job->subtypes_string }}</td>
										<td class="capitalize">{{ $job->name }}</td>
										<td class="text-right">
											<a href="/admin/job-descriptions/{{ $job->id }}/edit" class="btn btn-default btn-xs ">EDIT</a>
											<button type="submit" class="btn btn-default btn-xs btn-delete modal-button" data-url="/admin/job-descriptions/{{$job->id}}" >DELETE</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					{!! Form::open(['route' => ['admin.job-descriptions.destroy', 0], 'method' => 'delete']) !!}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
						</div>
						<div class="modal-body">
							Are you sure you want to delete this Job Description?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
							<button type="submit" class="btn btn-primary">DELETE</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
@stop

@section('foot')
	@parent

	<script>
		// dataTable.fnSort([ [ col, 'asc'] ]);
		$.fn.dataTable.ext.search.push( function( settings, data, dataIndex )
		{
			var needle = $('#subtype_search').find('option:selected').text();

			var dataArray = data[1];

			var subtypes = dataArray.match( /(?=\S)[^,]+?(?=\s*(,|$))/g );

			if(needle === " - Sub-Type - ") return true;
			if(subtypes === null ) return false;

			return inArray(needle, subtypes);

			function inArray(needle, haystack)
			{
				var length = haystack.length;
				for(var i = 0; i < length; i++)
				{
					if(haystack[i] === needle) return true;
				}
				return false;
			}
			return true;
		});

		table = $("#job_description_table").DataTable({"bFilter": true});

		$('#subtype_search').on('change', function() {
			table.draw();
		});


		$('#job_description_table').on('click', '.btn-delete', function(){
			$('#deletionModal').modal('show');

			var dataUrl = $(this).attr('data-url');

			$('#deletionModal form').attr('action', dataUrl);
		});

	</script>
	<script src="/assets/admin/tables.js"></script>
	<script>
		$('.modal-button').on('click', function() {
			var target = $(this).attr('data-url');

			$('#deletionModal form').attr('action', target);
		});

		$('#col_1_select_search').on('change', function() {
			type = $(this).val()
			$('#subtype_search option[value=""]').prop('selected', true);
			$('#subtype_search option[value!=""]').addClass('hidden');
			$('#subtype_search option.'+type).removeClass('hidden');

		});
	</script>

@stop
