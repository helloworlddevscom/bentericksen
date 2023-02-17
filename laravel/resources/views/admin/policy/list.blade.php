@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<div class="col-md-12 heading">
					<h3>Policy update list</h3>
				</div>
				
				<div class="col-md-12 sub_content">
					<br>
					<div class="col-md-12 table-responsive">
						<table class="table table-striped" id="policy_table">
							<thead>
								<tr>
									<th>
										<span>Title</span>
									</th>
									<th>
										<span>Send Date</span>
									</th>
									<th>
										<span>Current Client</span>
									</th>
									<th>
										<span>Past Client</span>
									</th>
									<th class="bg_none"></th>
								</tr>
							</thead>
							<tbody>
								@foreach($updates as $update)
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="text-right">
											<a href="/admin/policies/edit" class="btn btn-default btn-xs ">VIEW</a>
										</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td class="text-right" colspan="9">
										{!! $help->button('a1301') !!}
										<a href="/admin/policies/sort" class="btn btn-default btn-primary btn-xs">ADD NEW</a>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>				
				
			</div>
		</div>
@stop

@section('foot')
	@parent			
	<script>
	
	
	</script>
@stop