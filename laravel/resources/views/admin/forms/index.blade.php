@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">
				<div class="col-md-12 heading">
					<h3>Forms</h3>
				</div>
				<div class="col-md-12 content">
					<div class="row">
						<div class="col-md-12 text-right">
							<div class="col-md-4 col-md-offset-8">
								<p><a href="/admin/forms/create"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Form</a></p>
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
										<span>Form Title</span>
									</th>
									<th class="bg_none"></th>
								</tr>
							</thead>
							<tbody>
								@foreach( $forms AS $form )
									<tr>
										<td class="capitalize">{{ $form->industry }}</td>
										<td class="capitalize" data-subtypes="{{ $form->subtypes }}">{{ $form->subtypes_string }}</td>
										<td class="capitalize">{!! $form->name !!}</td>
										<td class="text-right">
											<a href="/admin/forms/{{ $form->id }}/edit" class="btn btn-default btn-xs ">EDIT</a>
											<button type="submit" class="btn btn-default btn-xs btn-delete modal-button" data-url="/admin/forms/{{$form->id}}" data-toggle="modal" data-target="#deletionModal">DELETE</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							{!! Form::open(['url' => ['/admin/forms', 0], 'method' => 'delete']) !!}
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
								</div>
								<div class="modal-body">
									Are you sure you want to delete this Form?
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
									<button type="submit" class="btn btn-primary">DELETE</button>
								</div>
							{!! Form::close() !!}
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
