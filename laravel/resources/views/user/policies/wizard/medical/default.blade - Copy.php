	@foreach($fields->get('classifications') AS $key => $classification)
		@if($classification->is_enabled)
			<div class="col-md-8 col-md-offset-2 sub-content">
				<div class="row">
					<h5 class="col-md-3"><em>{{ $classification->name }}</em>:</h5>
					@if(!$classification->is_base)
						<div class="col-md-5">
							<input type="hidden" name="classification[{{ $classification->id }}][medical][same_as_base]" value="0">
							{!! Form::checkbox('classification['.$classification->id.'][medical][same_as_base]', 1, (isset($classification->medical->same_as_base) ? $classification->medical->same_as_base : 0), ['class' => 'check-box dataForm', 'data-target' => '.classificationWrap_'.$classification->id, 'data-effect' => 'overlay', 'data-linked' => 'medical[classification]['.$classification->id.'][does_not_receive]']) !!}
							<label class="control-label"><em>Receives Same As Full Time</em></label>
						</div>
						<div class="col-md-4">
							<input type="hidden" name="classification[{{ $classification->id }}][medical][does_not_receive]" value="0">
							{!! Form::checkbox('classification['.$classification->id.'][medical][does_not_receive]', 1, (isset($classification->medical->does_not_receive) ? $classification->medical->does_not_receive : 0), ['class' => 'check-box dataForm', 'data-target' => '.classificationWrap_'.$classification->id, 'data-effect' => 'overlay', 'data-linked' => 'medical[classification]['.$classification->id.'][same_as_base]']) !!}							
							<label class="control-label"><em>Do Not Receive Medical</em></label>
						</div>
					@endif
				</div>
				<div class="row">
					<div class="col-md-12 classificationWrap_{{ $classification->id }}">
						<div class="row even">
							<label class="col-md-3 control-label">Pay Up To:</label>
							<div class="col-md-2 no-padding-right">
								<div class="input-group">
									<input type="text" class="form-control" name="classification[{{ $classification->id }}][medical][pay_up_to]" value="{{ $classification->medical->pay_up_to or 0 }}">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-md-6">
								<label class="control-label">of monthly premium</label>
							</div>
						</div>
						<div class="row odd">
							<label class="col-md-3 control-label">Cap Amount:</label>
							<div class="col-md-2 no-padding-right">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" class="form-control" name="classification[{{ $classification->id }}][medical][cap_amount]" value="{{ $classification->medical->cap_amount or "" }}">
								</div>
							</div>
							<div class="col-md-6 no-padding-left">
								{!! $help->button("u2903") !!}
							</div>
						</div>
						<div class="row even radio_benefits">
							<div class="col-md-12">
								If staff waives medical, do you offer consideration pay? {!! $help->button("u2904") !!}
							</div>
							<div class="col-md-3 col-md-offset-3">
								{!! Form::radio('classification['.$classification->id.'][medical][consideration_pay]', 1, (isset($classification->medical->consideration_pay) && $classification->medical->consideration_pay == 1 ? true : false), ['class' => 'waived dataForm', 'data-target' => '.consideration_pay_wrap_'.$classification->id, 'data-effect' => 'show']) !!}
								<label class="control-label strong">Yes</label>
							</div>
							<div class="col-md-3">
								{!! Form::radio('classification['.$classification->id.'][medical][consideration_pay]', 0, (isset($classification->medical->consideration_pay) && $classification->medical->consideration_pay == 0 ? true : false), ['class' => 'waived dataForm', 'data-target' => '.consideration_pay_wrap_'.$classification->id, 'data-effect' => 'hide']) !!}
								<label class="control-label strong">No</label>
							</div>
							<div class="dataFormContainer consideration_pay_wrap_{{ $classification->id }}">
								<div class="col-md-2 col-md-offset-3 no-padding-right">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][medical][consideration_pay_amount]" value="{{ $classification->medical->consideration_pay_amount or "" }}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	@endforeach
</div>