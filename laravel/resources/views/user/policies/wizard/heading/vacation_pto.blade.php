		<div class="row">
			<div class="col-md-8 col-md-offset-2 sub_content text-center">
				<h4>Earning Vacation / Paid Time-Off</h4>
				<p>
					<input type="hidden" name="vacation_pto[is_offered]" value="1">
					{!! Form::checkbox('vacation_pto[is_offered]', 0, (isset($fields->get('vacation_pto')->is_offered) && $fields->get('vacation_pto')->is_offered == 0 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '#vacationPtoWrap', 'data-effect' => 'overlay']) !!}
					<label for="dental_is_offered" class="control-label"><em>Not Offered</em></label>						

				</p>
			</div>
		</div>