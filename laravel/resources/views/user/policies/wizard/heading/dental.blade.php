		<div class="row">
			<div class="col-md-8 col-md-offset-2 sub_content text-center">
				<h4>Dental Benefits</h4>
				<p>
					<input type="hidden" name="dental[is_offered]" value="1">
					{!! Form::checkbox('dental[is_offered]', 0, (isset($fields->get('dental')->is_offered) && $fields->get('dental')->is_offered == 0 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '#dentalWrap', 'data-effect' => 'overlay']) !!}
					<label for="dental_is_offered" class="control-label"><em>Not Offered</em></label>
				</p>
			</div>
		</div>
		<div id="dentalWrap" class="row offered-container" style="position: relative;">
			
			@if(count($step['steps']) > 1)
				<div class="col-md-12 text-center sub_content">
					@if($business->type === "dental")
						<p>
							<select class="form-control date-box dataSelect dataForm" id="dental_benefits_type" name="dental[benefits_type]" data-default="pdba" data-selected="{{ $fields->get('dental')->benefits_type }}">
								<option data-target="#pdba" data-effect="show" value="pdba">PDBA</option>
								<option data-target="#discount" data-effect="show" value="discount">Discount</option>
								{{--<option data-target="#non-dental" data-effect="show" value="non-dental">Non-Dental</option>--}}
							</select>
							<a class="btn btn-sm icon_grey btn-modal"><i class="fa fa-question-circle"></i></a>
						</p>
					@endif
				</div>
			@endif
			
			<div class="col-md-8 col-md-offset-2 sub_content">
				<div class="row odd">
					<label for="dental_pdba_waiting_period" class="col-md-3 control-label">Waiting Period</label>
					<div class="col-md-2 no-padding-right">
						<input type="text" class="form-control" name="dental[waiting_period]" value="{{ $fields->get('dental')->waiting_period or 0}}">
					</div>
					<div class="col-md-3">
						<label for="dental_pdba_waiting_period" class="control-label strong">days</label>
					</div>
				</div>
			</div>
