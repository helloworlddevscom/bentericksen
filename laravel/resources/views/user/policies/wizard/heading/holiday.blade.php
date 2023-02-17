				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub_content text-center">
						<h4>Paid Holidays</h4>
						<p>
							<input type="hidden" value="0" name="holiday[is_offered]">
							<input type="checkbox" class="check-box not-offered dataForm" data-target="#offeredWrap" data-effect="overlay" @if(isset($fields->get('holiday')->is_offered) && $fields->get('holiday')->is_offered == 1) checked @endif value="1" name="holiday[is_offered]">
							<em>Not Offered</em>
						</p>
					</div>
				</div>