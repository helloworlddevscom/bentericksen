@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
					<div class="col-md-12 heading">
						<h3><a href="/admin/policies">Policy List</a> >> Sort Categories</h3>
					</div>
					<div class="col-md-12 content">
						<div class="row text-center">
							<h4>Categories</h4>
						</div>
						<div class="row" id="reorder_dashboard">
							<div class="col-md-6 col-md-offset-3">
								{!! Form::open(['route' => 'sortUpdate', 'method' => 'put', 'id' => 'sortForm']) !!}
									<div id="sortable">
										@foreach($categories as $category)
											<div class="row">
												<div class="col-md-10">
													<input type="hidden" class="categorySortOrder" name="category[{{ $category->id }}]" value="{{ $category->order }}">
													<label class="col-md-12 sort_order"> <i class="fa fa-arrows-v"></i> {{ $category->name }}</label>
												</div>
												<div class="col-md-2 text-center">
													<a href="/admin/policies/sort/{{ $category->id }}" class="btn btn-default btn-primary btn-xs">SORT</a>
												</div>
											</div>
										@endforeach
									</div>								
									<div class="col-md-12 text-center buttons">
										<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
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
		window.PAGE_INIT.push(function() {
			$( "#sortable" ).sortable()
			
			$('#sortForm').on('submit', function() {
				let count = 0
				$('.categorySortOrder').each(function() {
					$(this).val(count)
					count++
				})
			})
		})
	</script>
@stop