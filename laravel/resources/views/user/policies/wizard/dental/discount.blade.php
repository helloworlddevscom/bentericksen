<div id="discount" class="row hidden dataFormWrap">
	@foreach($fields->get('classifications') AS $key => $classification)
		@if($classification->is_enabled)
			<div class="col-md-8 col-md-offset-2 sub-content">
				<div class="row">
					<h5 class="col-md-3"><em>{{ $classification->name }}</em>:</h5>
						@if(!$classification->is_base)
							<div class="col-md-5">
								<input type="hidden" name="classification[{{$classification->id}}][dental][same_as_base]" value="0">
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
							<label for="" class="col-md-3 col-md-offset-2 control-label">Percent:</label>
							<div class="col-md-2 no-padding-right">
								<div class="input-group">
									<input type="text" class="form-control" name="classification[{{ $classification->id }}][dental][discount_percent]" value="{{ $classification->dental->discount_percent or 0 }}">
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<div class="row odd">
							<div class="col-md-5">
							Does Family Receive Benefits?
							</div>
							<div class="row radio_benefits">
								<div class="col-md-3">
									<input type="radio" class="dataForm" data-target=".waived-yes_{{ $classification->id }}" data-effect="show" @if( isset( $classification->dental->discount_family_benefits ) &&  $classification->dental->discount_family_benefits == 1) checked @endif class="waived waive_yes" value="1" name="classification[{{ $classification->id }}][dental][discount_family_benefits]">
									<label for="" class="control-label strong">Yes</label>
								</div>
								<div class="col-md-3">
									<input type="radio" class="dataForm" data-target=".waived-yes_{{ $classification->id }}" data-effect="hide"  @if( isset( $classification->dental->discount_family_benefits ) &&  $classification->dental->discount_family_benefits == 0) checked @endif class="waived waive_no" value="0" name="classification[{{ $classification->id }}][dental][discount_family_benefits]">
									<label for="" class="control-label strong">No</label>
								</div>
							</div>
							<div class="waived-yes_{{ $classification->id }}">
								<div class="row family_discount">
									<label for="" class="col-md-3 col-md-offset-2 control-label">Family Percent:</label>
									<div class="col-md-2 no-padding-right">
										<div class="input-group">
											<input type="text" name="classification[{{ $classification->id }}][dental][discount_family_percent]" class="form-control" value="{{ $classification->dental->discount_family_percent or "" }}">
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