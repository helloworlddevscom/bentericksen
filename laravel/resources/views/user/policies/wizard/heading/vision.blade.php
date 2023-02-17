<div class="row">
	<div class="col-md-8 col-md-offset-2 sub_content">
		<h4 class="text-center">Vision Benefits</h4>
		<p class="text-center">
			<input type="hidden" name="vision[is_offered]" value="1">
			{!! Form::checkbox('vision[is_offered]', 0, (isset($fields->get('vision')->is_offered) && $fields->get('vision')->is_offered == 0 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '#visionWrap', 'data-effect' => 'overlay']) !!}
			<label class="control-label"><em>Not Offered</em></label>
		</p>
	</div>
</div>
<div id="visionWrap" class="row offered-container" style="position: relative;">
	<div class="col-md-8 col-md-offset-2 sub-content">
		<div class="row">
			<label for="fulltime_medical_waiting_period" class="col-md-3 control-label">Waiting Period</label>
			<div class="col-md-2 no-padding-right">
				<input type="text" class="form-control" name="vision[waiting_period]" value="{{ $fields->get('vision')->waiting_period or 0}}">
			</div>
			<div class="col-md-3">
				<label class="control-label strong">days</label>
			</div>
		</div>
	</div>