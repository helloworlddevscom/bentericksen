@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
					<div class="col-md-12 heading">
						<h3><a href="/admin/policies">Policy List</a> >> <a href="/admin/policies/sort/{{ $category->id }}">{{ $category->name }}</a> >> Sort Policies</h3>
					</div>
					<div class="col-md-12 content">
						<div class="row text-center">
							<h4>{{ $category->name }}</h4>
						</div>
					<div>
					</div>
						<div class="row admin_policy_sort">
								<div class="col-md-6 col-md-offset-3 table-responsive">
									{!! Form::open(['route'	=> 'sortTemplateUpdate', 'method' => 'put', 'id' => 'sortForm']) !!}
										<table class="table table-striped">
											<tbody id="sortable">
												@foreach($templates as $template)
													<tr>
														<td class="">
															<div class="col-md-12">
																<input type="hidden" class="templateSort" name="policyTemplate[{{ $template->id }}]" value="{{ $template->order }}">
																<i class="fa fa-arrows-v"></i> {{ $template->admin_name }} ( {{ $template->manual_name }} )
															</div>
														</td>
													</tr>
												@endforeach
											</tbody>
											<tfoot>
												<tr>
													<td class="text-center">
														<button type="submit" class="btn btn-default btn-primary btn-xs btn-sort">SAVE</button>
													</td>
												</tr>
											</tfoot>
										</table>
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
		window.PAGE_INIT.push(function() {
			$( "#sortable" ).sortable()
			
			$('#sortForm').on('submit', function() {
				let count = 0
				$('.templateSort').each(function() {
					$(this).val(count)
					count++
				})
			})
		})
	</script>
@stop