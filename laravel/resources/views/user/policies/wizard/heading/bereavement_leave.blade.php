				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Bereavement Leave</h4>
						<p>
							<input type="hidden" value="0" name="bereavement_leave[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" value="1" @if(isset($fields->get('bereavement_leave')->is_offered) && $fields->get('bereavement_leave')->is_offered == 1) checked @endif data-target="#offeredWrap" data-effect="overlay" id="bereavement_leave_is_offered" name="bereavement_leave[is_offered]">
							<label for="bereavement_leave_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>