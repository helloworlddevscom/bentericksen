				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Problem Resolution Reporting To</h4>
						<p>
							<input type="hidden" value="0" name="resolution[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" value="1" @if(isset($fields->get('resolution')->is_offered) && $fields->get('resolution')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" id="problem_resolution_reporting_to_is_offered" name="resolution[is_offered]">
							<label for="problem_resolution_reporting_to_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>