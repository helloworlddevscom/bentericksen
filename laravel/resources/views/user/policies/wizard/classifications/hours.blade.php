<div class="row">
	<div class="col-md-8 col-md-offset-2 sub_content">
		<h4 class="text-center">Employee Classification</h4>
	</div>
	@foreach($fields->get('classifications') AS $key => $classification)
		@if($classification->is_enabled)
			<div class="col-md-8 col-md-offset-2 sub-content @if( $key % 2 == 0)odd @else even @endif">
				<h4 class="col-md-8 col-md-offset-1">{{ $classification->name }}</h4>
				<div class="col-md-11 col-md-offset-1">
					@if($classification->is_base)
						<div class="col-md-12">
							What's the minimum hours they work?
							{!! Form::text('classification[' . $classification->id . '][minimum_hours]', $classification->minimum_hours, ['class' => 'form-control number-box default_blur']) !!}
							<span style="color:red;">*</span>
							hours/
							{!! Form::select('classification[' . $classification->id . '][minimum_hours_interval]', ['day' => 'day', 'week' => 'week'], $classification->minimum_hours_interval, ['class' => 'form-control date-box default_blur']) !!}
							<span style="color:red;">*</span>
						</div>									
					@else
						<div class="col-md-5">
							Minimum hours:
							{!! Form::text('classification[' . $classification->id . '][minimum_hours]', $classification->minimum_hours, ['class' => 'form-control number-box default_blur']) !!}
							<span style="color:red;">*</span>
						</div>
						<div class="col-md-7">
							Maximum hours:
							{!! Form::text('classification[' . $classification->id . '][maximum_hours]', $classification->maximum_hours, ['class' => 'form-control number-box default_blur']) !!}
							<span style="color:red;">*</span>
							{!! Form::select('classification[' . $classification->id . '][maximum_hours_interval]', ['day' => 'day', 'week' => 'week'], $classification->maximum_hours_interval, ['class' => 'form-control date-box default_blur']) !!}
							<span style="color:red;">*</span>
						</div>
					@endif
				</div>
			</div>
		@endif
	@endforeach
</div>