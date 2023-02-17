<div class="row">
	<div class="col-md-8 col-md-offset-2 sub_content">
		<h4 class="text-center">Employee Classification</h4>
	</div>
	<div class="col-md-8 col-md-offset-2 text-center content">
		<p><em>Please Select All Employee Calssification(s) You Offer</em></p>
	</div>
	<div class="col-md-6 col-md-offset-3 sub-content">
		<div class="row classifications">
			@foreach($fields->get('classifications') AS $key => $classification)
				<div class="col-md-12 classification @if( $key % 2 == 0)odd @else even @endif">
					<p class="col-md-1 col-md-offset-1 text-center">
						{!! Form::hidden('classification[' . $classification->id . '][is_enabled]', 0) !!}
						{!! Form::checkbox('classification[' . $classification->id . '][is_enabled]', 1, $classification->is_enabled, ['class' => 'check-box']) !!}
						
					</p>
					<p class="col-md-8">
						{!! Form::text('classification[' . $classification->id . '][name]', $classification->name, ['class' => 'date-box form-control']) !!}
					</p>
				</div>
			@endforeach
		</div>
		<p class="col-md-8 col-md-offset-1">
			<a class="btn add-classification">
				<img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add Another Classification Level
			</a>
		</p>
	</div>
</div>


@section('foot')
	@parent			
	<script>
		//this should be using css for odd/even color alternation.
		classificationCounter = 0;
	
		$('.add-classification').on('click', function() {
			classificationCount = $('.classification').length;
			
			//move this odd even stuff to a pure css fix
			if(classificationCount % 2 == 0)
			{
				classificationSwitch = "odd";
			} else {
				classificationSwitch = "even";
			}
			
			var classification = "<div class='col-md-12 classification " + classificationSwitch + "'><p class='col-md-1 col-md-offset-1 text-center'><input type='hidden' name='classification[new][" + classificationCounter + "][is_enabled]' value='0'><input class='check-box' type='checkbox' name='classification[new][" + classificationCounter + "][is_enabled]' value='1'></p><p class='col-md-8'><input type='text' name='classification[new][" + classificationCounter + "][name]' class='date-box form-control'></p></div>";
			$('.classifications').append(classification);
			classificationCounter++;
		});
	</script>
@stop