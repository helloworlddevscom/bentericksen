	<div class="col-md-8 col-md-offset-2 sub-content">
		<br>
		Check all employees who receive Medical:
	</div>
	@foreach($fields->get('classifications') AS $key => $classification)
		@if($classification->is_enabled)
			<div class="col-md-8 col-md-offset-2 sub-content">
				<div class="row">
					<div class="col-md-4">
						<input type="hidden" name="classification[{{ $classification->id }}][medical][does_not_receive]" value="0">
						{!! Form::checkbox('classification['.$classification->id.'][medical][does_not_receive]', 1, (isset($classification->medical->does_not_receive) ? $classification->medical->does_not_receive : 0), ['id' => 'medical_receive' . $classification->id , 'class' => 'check-box dataForm', 'data-target' => '.classificationWrap_'.$classification->id, 'data-effect' => 'overlay', 'data-linked' => 'medical[classification]['.$classification->id.'][same_as_base]']) !!}							
						<label class="control-label" for="medical_receive{{$classification->id}}">
							<em>{{ $classification->name }}</em>
						</label>
					</div>				
				</div>
			</div>
		@endif
	@endforeach
</div>