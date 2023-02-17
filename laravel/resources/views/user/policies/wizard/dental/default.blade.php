	<div id="non-dental" class="row dataFormWrap">
		@foreach($fields->get('classifications') AS $key => $classification)
			@if($classification->is_enabled)	
				<div class="col-md-8 col-md-offset-2 sub-content">	
					<div class="row">
						<h5 class="col-md-3"><em>{{ $classification->name }}</em>:</h5>
						@if(!$classification->is_base)
							<div class="col-md-5">
								<input type="hidden" name="classification[{{ $classification->id }}][dental][same_as_base]" value="0">
								{!! Form::checkbox('classification['.$classification->id.'][dental][same_as_base]', 1, (isset($classification->dental->same_as_base) && $classification->dental->same_as_base == 1 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '.classificationWrap_'.$classification->id, 'data-effect' => 'overlay', 'data-linked' => 'dental[classification]['.$classification->id.'][does_not_receive]']) !!}
								<label class="control-label"><em>Receives Same As Full Time</em></label>
							</div>
							<div class="col-md-4">
								<input type="hidden" name="classification[{{ $classification->id }}][dental][does_not_receive]" value="0">
								{!! Form::checkbox('classification['.$classification->id.'][dental][does_not_receive]', 1, (isset($classification->dental->does_not_receive) && $classification->dental->does_not_receive == 1 ? true : false), ['class' => 'check-box dataForm', 'data-target' => '.classificationWrap_'.$classification->id, 'data-effect' => 'overlay', 'data-linked' => 'dental[classification]['.$classification->id.'][same_as_base]']) !!}							
								<label class="control-label"><em>Do Not Receive Dental</em></label>
							</div>
						@endif
					</div>
				
					<div class="row">
						<div class="col-md-12 classificationWrap_{{ $classification->id }}">
							<div class="row even">
								<label for="" class="col-md-3 control-label">Pay Up To:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][pay_up_to]" value="{{ $classification->dental->pay_up_to or 0 }}">
										<span class="input-group-addon">%</span>
									</div>
								</div>
								<div class="col-md-6">
									<label class="control-label">of monthly premium</label>
								</div>
							</div>
							<div class="row odd">
								<label for="{id}_dental_cap_amount" class="col-md-3 control-label">Cap Amount:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][cap_amount]" value="{{ $classification->dental->cap_amount or 0 }}">
									</div>
								</div>
								<div class="col-md-6 no-padding-left">
									{{ $help->button("u2907") }}
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</div>
</div>
