				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Reference Requests Referred To</h4>
						<p>
							<input type="hidden" value="0" name="reference[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" @if(isset($fields->get('reference')->is_offered) && $fields->get('reference')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" value="1" id="reference_request_refered_to_is_offered" name="reference[is_offered]">
							<label for="reference_request_refered_to_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>