				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Harassment / Discrimination Reporting To</h4>
						<p>
							<input type="hidden" value="0" name="harassment[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" value="1"@if(isset($fields->get('harassment')->is_offered) && $fields->get('harassment')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay"  id="harassment_discrimination_reporting_to_is_offered" name="harassment[is_offered]">
							<label for="harassment_discrimination_reporting_to_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>