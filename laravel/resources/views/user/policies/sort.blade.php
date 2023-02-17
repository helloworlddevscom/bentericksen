@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
					<div class="col-md-12 content">
						<p><a href="/user/policies">Policy List</a> > Policy Sort</p>
						<h4 class="text-center">Policy Sort</h4>
					</div>
					<div class="col-md-8 col-md-offset-2 content">
						{!! Form::open(['url' => '/user/policies/sort', 'method' => 'post']) !!}
							<div class="row">
								<h4 class="text-center"><em>Sort The Order For Policies</em></h4>
							</div>
							<div class="row">
								<div class="col-md-12 text-center">
									<select class="form-control date-box categories">
										<option value="category_select"> Select Category</option>
										@foreach($categories as $category)
											<option value="{{ $category->id }}">{{ $category->name }}</option>	
										@endforeach
									</select>
								</div>
							</div>
							<div class="row" id="reorder_dashboard">
								@foreach($categories as $category)
									<ul class="sortable category_{{ $category->id }} hidden categoryGroup">
										@foreach($policies as $policy)
											@if($category->id == $policy->category_id)
												<li>
													<input type="hidden" class="policy" name="policy[{{ $policy->category_id }}][{{ $policy->id }}]" value="{{ $policy->order }}">
													<label class="sort_order"><i class="fa fa-arrows-v"></i> {{ $policy->manual_name }}</label>
												</li>			
											@endif
										@endforeach
									</ul>
								@endforeach
							</div>
							<div class="row">
								<div class="col-md-12 text-center buttons">
									<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
				<!-- -->				

			</div>
		</div>
@stop

@section('foot')
	@parent			
	<script>
		$( ".sortable" ).sortable();
		$( ".sortable" ).disableSelection();
		
		$('form').on('submit', function() {
			$('.sortable').each(function() {
				var i = 0;
				$(this).find('li .policy').each(function() {
					$(this).val(i);
					i++;
				});
			});
		});
		
		$('.categories').on('change', function() {
			cat_id = $(this).find('option:selected').val();
			
			$('.sortable').addClass('hidden');
			$('.sortable.category_' + cat_id).removeClass('hidden');
		});
		
		$('.sortable.category_' + $('.categories option:selected').val()).removeClass('hidden');
		
	</script>
@stop