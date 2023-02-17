
				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Severance Pay Week Numbers</h4>
						<p>
							<input type="hidden" value="0" name="severance[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" @if(isset($fields->get('severance')->is_offered) && $fields->get('severance')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" value="1" id="severance_pay_is_offered" name="severance[is_offered]">
							<label for="severance_pay_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>