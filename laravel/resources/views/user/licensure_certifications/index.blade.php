@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
				<div class="col-md-12 text-center">
					<h3>Licensure / Certification</h3>
				</div>
				<div class="col-md-12 content">
					<div class="col-md-8 col-md-offset-2 table-responsive">
						<table class="table table-striped" id="licensure_certification_table">
							<thead>
								<tr>
									<th>
										<span>Licensure / Certification</span>
									</th>
									<th class="bg_none"></th>
								</tr>
							</thead>
							<tbody>
								@foreach($licensure as $license)
									<tr>
										<td>{{ $license->name }}</td>
										<td class="text-right">
											<a href="/user/licensure-certifications/{{ $license->id }}/edit" class="btn btn-default btn-xs btn-primary">EDIT</a>
											<button class="btn btn-default btn-xs btn-delete btn-primary modal-button" data-license-id="{{ $license->id }}" data-toggle="modal" data-target="#deletionModal">DELETE</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-12 text-center">
					<a href="/user/licensure-certifications/create" class="btn btn-primary btn-xs">CREATE NEW LICENSURE/CERTIFICATION</a>
				</div>

				<div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							{!! Form::open(['route' => ['user.licensure-certifications.destroy', 0], 'method' => 'delete']) !!}
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
								</div>
								<div class="modal-body">
									Are you sure you want to delete this Licensure / Certification?
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button class="btn btn-primary">Delete</button>
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
	<script>
		$('.modal-button').on('click', function() {
			var licenseId = $(this).attr('data-license-id');
			var url = "/user/licensure-certifications/" + licenseId;
			
			$('#deletionModal form').attr('action', url);
		});
	</script>		

@stop