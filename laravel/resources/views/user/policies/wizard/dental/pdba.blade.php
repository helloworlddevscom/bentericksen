	<div id="pdba" class="hidden dataFormWrap">
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
								<label class="col-md-3 col-md-offset-2 control-label">Credit:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][pdba_credit]" value="{{ $classification->dental->pdba_credit or 0 }}">
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label strong">per month</label>
								</div>
							</div>
							<div class="row odd">
								<label for="parttime_dental_pdba_cap_amount" class="col-md-3 col-md-offset-2 control-label">Cap Amount:</label>
								<div class="col-md-2 no-padding-right">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][pdba_cap_amount]" value="{{ $classification->dental->pdba_cap_amount or 0 }}">
									</div>
								</div>
								<div class="col-md-5 no-padding-left">
									{{ $help->button("u2906") }}
								</div>
							</div>
							<div class="row even">
								<div class="col-md-5 text-right">
									<h5>Does Family Receive Benefits?</h5>
								</div>
								<div class="radio_benefits">
									<div class="col-md-3">
										{!! Form::radio('classification['.$classification->id.'][dental][pdba_family_benefits]', 1, (isset($classification->medical->waive) && $classification->medical->waive == 1 ? true : false), ['class' => 'waived dataForm', 'data-target' => '.family_wrap_'.$classification->id, 'data-effect' => 'show']) !!}
										<label class="control-label strong">Yes</label>
									</div>
									<div class="col-md-3">
										{!! Form::radio('classification['.$classification->id.'][dental][pdba_family_benefits]', 0, (isset($classification->medical->waive) && $classification->medical->waive == 0 ? true : false), ['class' => 'waived dataForm', 'data-target' => '.family_wrap_'.$classification->id, 'data-effect' => 'hide']) !!}
										<label class="control-label strong">No</label>
									</div>
								</div>
								<div class="hidden family_wrap_{{ $classification->id }}">
									<div class="row">
										<div class="col-md-3 col-md-offset-5">
											<select class="form-control date-box dental_pdba_type dataForm" name="classification[{{ $classification->id }}][dental][pdba_family_benefits_type]">
												<option value=""> - Select One - </option>
												<option value="pdba">PDBA</option>
												<option data-target=".family_discount_{{ $classification->id }}" data-effect="show" value="discount">Discount</option>
											</select>
										</div>
									</div>
									<div class="row family_discount_{{ $classification->id }} hidden">
										<label class="col-md-3 col-md-offset-2 control-label">Family Percent:</label>
										<div class="col-md-2 no-padding-right">
											<div class="input-group">
												<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][pdba_family_benefits_percent]" value="{{ $classification->dental->pdba_family_benefits_percent or 0 }}">
												<span class="input-group-addon">%</span>
											</div>
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
