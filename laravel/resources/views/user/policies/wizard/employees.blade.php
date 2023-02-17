<div class="row">
	<div class="col-md-8 col-md-offset-2 sub_content">
		<h4 class="text-center">Number of Employees</h4>
	</div>
	<div class="col-md-8 col-md-offset-2 content even">
		<div class="row">
			<label for="employee_count" class="col-md-5 col-md-offset-1 control-label">*Number of Employees</label>
			<div class="col-md-6">
				<input type="text" class="form-control number-box" id="employee_count" name="employee_count" value="{{ $fields->get('employee_count') }}">
			</div>
		</div>
	</div>
	<div class="col-md-8 col-md-offset-2 sub_content">
		<div class="row">
			<p class="col-md-5 col-md-offset-3"><em>*Required</em></p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2 text-center content">
		<p><em>For more information see</em></p>
			{!! $help->text(67, null, 'How Do I Calculate My Number Of Employees?') !!}
			<br>
			{!! $help->text(68) !!}
	</div>
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2 error-summary">
	</div>
</div>