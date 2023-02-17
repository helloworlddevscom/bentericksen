@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div id="main" class="container">
			<div class="row main_wrap">
				<div class="col-md-12 text-center">
					<h2>Error</h2>
					<p>The following policies require action before a manual can be generated</p>
					<ul class="text-left">
						@foreach($stubs AS $stub)
							<li>{{ $stub->manual_name }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
@stop

@section('foot')
	@parent
@stop