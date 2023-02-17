
				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Pet Care</h4>
						<p>
							<input type="hidden" value="0" name="petcare[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" @if(isset($fields->get('petcare')->is_offered) && $fields->get('petcare')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" value="1" id="pet_care_leave_is_offered" name="petcare[is_offered]">
							<label for="pet_care_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>
