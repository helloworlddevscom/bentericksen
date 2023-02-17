<div class="row">
	<div class="col-md-8 col-md-offset-2 sub_content">
		<h4 class="text-center">Medical Benefits</h4>
		<p class="text-center">
			<input type="hidden" name="medical[is_offered]" value="1">
			{!! Form::checkbox('medical[is_offered]', 0, (isset($fields->get('medical')->is_offered) && $fields->get('medical')->is_offered == 0 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '#medicalWrap', 'data-effect' => 'overlay']) !!}
			<label for="medical" class="control-label"><em>Not Offered</em></label>
		</p>
	</div>
</div>
<div id="medicalWrap" class="row offered-container" style="position: relative;">
	<div class="col-md-8 col-md-offset-2 sub-content">
		<div class="row">
			<label for="fulltime_medical_waiting_period" class="col-md-3 control-label">Waiting Period</label>
			<div class="col-md-2 no-padding-right">
				<input type="text" class="form-control medical_waiting_period" name="medical[waiting_period]" value="{{ $fields->get('medical')->waiting_period or 0 }}">
			</div>
			<div class="col-md-3">
				<label for="fulltime_medical_waiting_period" class="control-label strong">days</label>
			</div>
		</div>
	</div>