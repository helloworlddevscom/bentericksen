				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Sick Leave</h4>
						<p>
							<input type="hidden" name="sickleave[is_offered]" value="0">
							<input type="checkbox" name="sickleave[is_offered]" value="1" @if(isset($fields->get('sickleave')->is_offered) && $fields->get('sickleave')->is_offered == 1) checked @endif class="check-box dataForm" data-target='#offeredWrap' data-effect='overlay'>
							<label for="sick_leave_is_offered" class="control-label"><em>Not Offered</em></label>
						</p>
					</div>
				</div>