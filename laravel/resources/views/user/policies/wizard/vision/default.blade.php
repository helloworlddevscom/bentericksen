		@foreach($fields->get('classifications') AS $key => $classification)
		
			@if($classification->is_enabled)
				<div class="col-md-8 col-md-offset-2 sub-content">
					<div class="row">
						<h5 class="col-md-3"><em>{{ $classification->name or "" }}</em>:</h5>
						@if( ! $classification->is_base )
							<div class="col-md-5">
								<input type="hidden" name="classification[{{ $classification->id }}][vision][same_as_base]" value="0">
									
								<input type="checkbox" class="form-control check-box" value="1" @if(isset($classification->vision->same_as_base) && $classification->vision->same_as_base == 1) selected @endif name="classification[{{ $classification->id }}][vision][same_as_base]">
								<label class="control-label"><em>Receives Same As Full Time</em></label>
							</div>
							<div class="col-md-4">
								<input type="hidden" name="classification[{{ $classification->id }}][vision][does_not_receive]" value="0">
								<input type="checkbox" class="form-control check-box" value="1" name="classification[{{ $classification->id }}][vision][does_not_receive]">
								<label class="control-label"><em>Do Not Receive Vision</em></label>
							</div>
						@endif
					</div>
					<div class="row">
						<div class="col-md-12 does_not_receive_container">
							<div class="row even">
								<label for="{id}_vision_pay_up_to" class="col-md-3 control-label">Pay Up To:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][vision][pay_up_to]" value="{{ $classification->vision->pay_up_to or 0}}">
										<span class="input-group-addon">%</span>
									</div>
								</div>
								<div class="col-md-6">
									<label class="control-label">of monthly premium</label>
								</div>
							</div>
							<div class="row odd">
								<label for="{id}_vision_cap_amount" class="col-md-3 control-label">Cap Amount:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][vision][cap_amount]" value="{{ $classification->vision->cap_amount or 0}}">
									</div>
								</div>
								<div class="col-md-6 no-padding-left">
									{!! $help->button("u2905") !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</div>