@extends('user.wrap')

@section('head')
	@parent	
	<link rel="stylesheet" href="/css/wizard.css">
@stop

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">	

				{!! Form::open(['route' => ['wizard.update', $step['current']], 'class' => 'dataForm form-horizontal']) !!}
					
					@include('user.policies.wizard.heading.' . $step['heading'])

					@foreach($step['steps'] AS $key => $view)
						<div class="view_{{ $key }} @if($key > 0) @endif ">
							@include('user.policies.wizard.' . $view->view)
						</div>
					@endforeach
					
					{!! $buttons !!}		
				{!! Form::close() !!}
				@if($step['current'] > 1)
					<div class="col-md-6 col-md-offset-3 content">
						<div id="progressbar" class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $step['complete'] }}">
							<div class="ui-progressbar-value ui-widget-header ui-corner-left ui-corner-right" style="width: {{ $step['complete'] }}%;"></div>
						</div>
					</div>
				@endif
				
			</div>
		</div>
@stop

@section('foot')
	@parent			
	<script src="/assets/js/dataForm.js"></script>
	<script src="/assets/js/pto_vacation.sickleave.js"></script>
	<script>
		(function() {
			var count = $('.holidays-custom .holiday').length;
			
			$('.add-holiday').on('click', function() {
				$('.holidays-custom').append('<div class="row"><div class="col-md-12 holiday"><div class="col-md-3 text-center"><input type="hidden" name="holiday[new][' + count + '][is_enabled]" value="0"><input type="checkbox" class="check-box" value="1" name="holiday[new][' + count + '][is_enabled]"></div><div class="col-md-4"><input type="text" name="holiday[new][' + count + '][name]"></div><div class="col-md-4"><input type="text" name="holiday[new][' + count + '][info]"></div></div></div>');
				count++;
			});
		})();
	</script>	
	
@stop