@extends('terms.wrap')

@section('content')
<div class="container-fluid">
	<div class="row">
		@include('terms.' . $modal_type)
	</div>
</div>
@endsection
