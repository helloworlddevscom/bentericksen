				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>New Employee Referral Bonus</h4>
						<p>
							<input type="hidden" value="0" name="referral[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" @if(isset($fields->get('referral')->is_offered) && $fields->get('referral')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" value="1" id="new_employee_referal_bonus_is_offered" name="referral[is_offered]">
							<label for="new_employee_referal_bonus_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>