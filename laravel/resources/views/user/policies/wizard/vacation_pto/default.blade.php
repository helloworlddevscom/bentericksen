
	<div id="vacationPtoWrap" class="offered-container" style="position: relative;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 content">
				<div class="row even">
					<label for="vacation_earning_benefit_type" class="col-md-5 control-label">Benefit Type:</label>
					<div class="col-md-3">
						{!! Form::select('vacation_pto[benefit_type]', ['' => ' - Select One - ', 'vacation' => 'Vacation', 'pto' => 'Paid Time-Off'], (isset($fields->get('vacation_pto')->benefit_type) ? $fields->get('vacation_pto')->benefit_type : null), ['class' => 'form-control step_1 earning_benefit_type data-selection-element', 'id' => 'vacation_earning_benefit_type', 'data-value' => (isset($fields->get("vacation_pto")->benefit_type) ? $fields->get("vacation_pto")->benefit_type : null ) ]) !!}
					</div>
				</div>
				<div class="row odd">
					<label class="col-md-5 control-label">How Are Benefits Earned?</label>
					<div class="col-md-3">
						{!! Form::select('vacation_pto[benefits_earned]', 
							[
								'' => ' - Select One - ',
								'per_hour' => 'Per Hour Worked', 
								'per_week' => 'Per Week',
								'per_month' => 'Per Month',
								'per_year' => 'Per Year',
							], 
							(isset($fields->get('vacation_pto')->benefits_earned) ? $fields->get('vacation_pto')->benefits_earned : null), ['class' => 'form-control data-selection-element', 'id' => 'vacation_earning_benefit_rate_received', 'data-value' => (isset($fields->get("vacation_pto")->benefits_earned) ? $fields->get("vacation_pto")->benefits_earned : null) ]) 
						!!}
					</div>
				</div>
				<div class="row even">
					<label class="col-md-5 control-label">Benefits Are Earned On:</label>
					<div class="col-md-3">
						{!! Form::select('vacation_pto[benefit_earned_on]', 
							[
								'' => ' - Select One - ',
								'anniversary_year' => 'Anniversary Year', 
								'calendar_year' => 'Calendar Year',
							], 
							(isset($fields->get('vacation_pto')->benefit_earned_on) ? $fields->get('vacation_pto')->benefit_earned_on : null), ['class' => 'form-control data-selection-element', 'id' => 'benefits_are_earned_on', 'data-value' => (isset($fields->get("vacation_pto")->benefit_earned_on) ? $fields->get("vacation_pto")->benefit_earned_on : null) ]) 
						!!}					
					</div>
					<div class="col-md-2 no-padding-left">
						{!! $help->button("u2908") !!}
					</div>
					<div class="col-md-12 calendar_year hidden">
						<div class="row">
							<div class="col-md-5 text-right">
								<select class="form-control date-box cal_year_step data-selection-element" name="vacation_pto[benefit_earned_day]" data-value="{{ $fields->get('vacation_pto')->benefit_earned_day or "" }}">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
								</select>
								of
							</div>
							<div class="col-md-3">
								<select class="form-control cal_year_step data-selection-element" id="vacation_earning_benefit_earned_year" name="vacation_pto[benefit_earned_year]" data-value="{{ $fields->get('vacation_pto')->benefit_earned_year or "" }}">
									<option value="jan">January</option>
									<option value="feb">February</option>
									<option value="mar">March</option>
									<option value="apr">April</option>
									<option value="may">May</option>
									<option value="jun">June</option>
									<option value="jul">July</option>
									<option value="aug">August</option>
									<option value="sep">September</option>
									<option value="oct">October</option>
									<option value="nov">November</option>
									<option value="dec">December</option>
								</select>
							</div>
						</div>
						<div class="row">
							<label class="col-md-5 control-label">First Year Earned:</label>
							<div class="col-md-3">
								<select class="form-control cal_year_step dataForm data-selection-element" name="vacation_pto[benefit_first_year_accrual]" data-value="{{ $fields->get('vacation_pto')->benefit_first_year_accrual or "" }}">
									<option value="prorate">Prorate</option>
									<option value="full_amount">Full Amount</option>
									<option value="none">None</option>
								</select>
							</div>
						</div>
						<div class="row">
							<label class="col-md-8 control-label">Does The First Partial Year Count As A Year Of Service?</label>
							<div class="col-md-3 col-md-offset-5">
								<div class="row radio_benefits">
									<div class="col-md-6">
										<input type="radio" class="data-selection-element" value="1" name="vacation_pto[earning_benefit_first_partial_year_count]" data-value="{{ $fields->get('vacation_pto')->earning_benefit_first_partial_year_count or "" }}">
										<label for="vacation_earning_benefit_partial_yes" class="">Yes</label>
									</div>
									<div class="col-md-6">
										<input type="radio" class="data-selection-element" value="0" name="vacation_pto[earning_benefit_first_partial_year_count]" data-value="{{ $fields->get('vacation_pto')->earning_benefit_first_partial_year_count or "" }}">
										<label for="vacation_earning_benefit_partial_no" class="">No</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row odd">
					<label class="col-md-5 control-label">How Are Benefits Provided?:</label>
					<div class="col-md-3">
						{!! Form::select('vacation_pto[benefit_provided]', 
							[
								'' => ' - Select One - ',
								'hours' => 'In Hours', 
								'days' => 'In Days',
								'weeks' => 'In Weeks',
							], 
							(isset($fields->get('vacation_pto')->benefit_provided) ? $fields->get('vacation_pto')->benefit_provided : null), ['class' => 'form-control data-selection-element', 'id' => 'vacation_pto_benefit_interval', 'data-value' => isset($fields->get("vacation_pto")->benefit_provided) ? $fields->get("vacation_pto")->benefit_provided : null]) 
						!!}
					</div>
					<div class="col-md-2 no-padding-left">
						{!! $help->button("u2909") !!}
					</div>
				</div>
			</div>
		</div>
		
		<!-- Classifications -->
		{{--
			<div class="employee_classifications hidden" id="employee_classifications">
				<div class="row">
					@foreach($fields->get('classifications') AS $key => $classification)				
						@if($classification->is_enabled)
							<div class="col-md-8 col-md-offset-2 sub-content">
								<div class="row">
									<h5 class="col-md-5"><em>{{ $classification->name }}</em>:</h5>
									@if(!$classification->is_base)
										<div class="col-md-5">
											<input type="hidden" value="0" name="classification[{{ $classification->id }}][vacation_pto][pto_does_not_receive1]">
											<input type="checkbox" class="form-control check-box does_not_receive dataForm data-selection-element" name="classification[{{ $classification->id }}][vacation_pto][pto_does_not_receive1]" data-value="{{ $classification->vacation_pto->pto_does_not_receive1 or "" }}" data-target="#does_not_receive_container{{$classification->id}}" data-effect="overlay">
											<label class="control-label"><em>Not Offered</em></label>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12 does_not_receive_container" id="does_not_receive_container{{$classification->id}}">
									
										<div class="row odd hidden benefit_day_hours">
											<div class="col-md-12">
												<strong>Please Define:</strong>
											</div>
											<label class="col-md-6 control-label">How Many Hours Are In A Day?</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control step_2 step_2_a step_2_b {id}" value="{{ $classification->vacation_pto->hours_in_day or "" }}" name="classification[{{$classification->id}}][vacation_pto][hours_in_day]">
											</div>
											<div class="col-md-3">
												<label class="control-label strong">hours a day</label>
											</div>
										</div>
										
										<div class="row even benefit_weeks hidden benefit_week_hours">
											<label class="col-md-6 control-label">How Many Hours Are In A Week?</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control step_2 step_2_b {id}" value="{{ $classification->vacation_pto->hours_in_week or "" }}" name="classification[{{$classification->id}}][vacation_pto][hours_in_week]">
											</div>
											<div class="col-md-3">
												<label class="control-label strong">hours a week</label>
											</div>
										</div>
										
									</div>
								</div>
							</div>	
						@endif
					@endforeach
				</div>
			</div>
		--}}
		<!-- End Classifications -->
		
		<!-- Step 3 -->
			<div class="step-3 hidden" id="benefit_options">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 sub-content">
						<div class="row">
							<div class="col-md-12 initial_container">
								<div class="row even calendar_as_earn">
									<label for="" class="col-md-6 control-label">Can Employees Use Benefits As They Earn?</label>
									<div class="col-md-6">
										<div class="row radio_benefits">
											<div class="col-md-4">
												{!! Form::radio('vacation_pto[can_use_as_they_earn]', 1, (isset($fields->get('vacation_pto')->can_use_as_they_earn) && $fields->get('vacation_pto')->can_use_as_they_earn == 1 ? true : null ), ['class' => 'as_earn data-selection-element', 'data-value' => (isset($fields->get('vacation_pto')->can_use_as_they_earn) ? $fields->get('vacation_pto')->can_use_as_they_earn : null )]) !!}
												<label class=""> Yes</label>
											</div>
											<div class="col-md-4">
												{!! Form::radio('vacation_pto[can_use_as_they_earn]', 0, (isset($fields->get('vacation_pto')->can_use_as_they_earn) && $fields->get('vacation_pto')->can_use_as_they_earn == 0 ? true : null ), ['class' => 'as_earn data-selection-element', 'data-value' => (isset($fields->get('vacation_pto')->can_use_as_they_earn) ? $fields->get('vacation_pto')->can_use_as_they_earn : null )]) !!}
												<label class=""> No</label>
											</div>
										</div>
									</div>
								</div>

							</div>
							<div class="col-md-12 initial_container">
								<div class="row odd">
									<label class="col-md-6 control-label">Waiting Period To Start Using:</label> {!! $help->button("u2910") !!}
									<div class="col-md-2 no-padding-right">
										<input type="text" class="form-control start_using use_benefits" name="vacation_pto[waiting_period_to_start_using]" value="{{ $fields->get('vacation_pto')->waiting_period_to_start_using or "" }}">
									</div>
									<div class="col-md-3">
										{!! Form::select('vacation_pto[waiting_period_to_start_using_interval]', 
											[
												'' => ' - Select One - ',
												'days' => 'Days', 
												'weeks' => 'Weeks',
												'months' => 'Months',
											], 
											(isset($fields->get('vacation_pto')->waiting_period_to_start_using_interval) ? $fields->get('vacation_pto')->waiting_period_to_start_using_interval : null), ['class' => 'form-control date-box start_using_interval use_benefits data-selection-element'])
										!!}								
									</div>
								</div>
								<div class="row odd cal_recurring">
									<label class="col-md-6 control-label">Is This Waiting Period Recurring?</label>
									<div class="col-md-6">
										<div class="row radio_benefits">
											<div class="col-md-4">
												{!! Form::radio('vacation_pto[is_waiting_period_recurring]', 1, null, ['class' => 'is_recurring use_benefits data-selection-element', 'data-value' => (isset($fields->get('vacation_pto')->is_waiting_period_recurring) ? $fields->get('vacation_pto')->is_waiting_period_recurring : null )]) !!}
												<label> Yes</label>
											</div>
											<div class="col-md-4">
												{!! Form::radio('vacation_pto[is_waiting_period_recurring]', 0, null, ['class' => 'is_recurring use_benefits data-selection-element', 'data-value' => (isset($fields->get('vacation_pto')->is_waiting_period_recurring) ? $fields->get('vacation_pto')->is_waiting_period_recurring : null )]) !!}											
												<label> No</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row even">
							<label class="col-md-6 control-label">Do You Allow Carry-Over?</label> {!! $help->button("u2911") !!}
							<div class="col-md-6">
							
								<div class="row radio_benefits">
									<div class="col-md-4">
										{!! Form::radio('vacation_pto[allow_carry_over]', 1, null, ['class' => 'carry-over data-selection-element', 'id' => 'carry_over_yes', 'data-value' => (isset($fields->get('vacation_pto')->allow_carry_over) ? $fields->get('vacation_pto')->allow_carry_over : null )]) !!}
										<label class=""> Yes</label>
									</div>
									<div class="col-md-4">
										{!! Form::radio('vacation_pto[allow_carry_over]', 0, null, ['class' => 'carry-over data-selection-element', 'id' => 'carry_over_no', 'data-value' => (isset($fields->get('vacation_pto')->allow_carry_over) ? $fields->get('vacation_pto')->allow_carry_over : null )]) !!}
										<label> No</label>
									</div>
								</div>
							</div>
							<div class="col-md-12 hidden" id="carry_over_allowed">
							
								<div class="row carry-yes">
									<label class="col-md-6 col-md-offset-2 control-label">How much can carry-over?</label>
									<div class="col-md-3 col-md-offset-5">
										<input type="hidden" name="vacation_pto[is_carry_over_unlimited]" value="0">
										{!! Form::checkbox('vacation_pto[is_carry_over_unlimited]', 1, (isset($fields->get('vacation_pto')->is_carry_over_unlimited) ? $fields->get('vacation_pto')->is_carry_over_unlimited : null),  ['class' => 'form-control check-box carry-over-amount carry-unlimited data-selection-element pair_check', 'id' => 'carry_over_unlimited', 'data-pair' => '#carry_over_up_to', 'data-value' => (isset($fields->get('vacation_pto')->is_carry_over_unlimited) ? $fields->get('vacation_pto')->is_carry_over_unlimited : null )]) !!}
										<label>Unlimited</label>
									</div>
									<div class="col-md-6 col-md-offset-5">
										<input type="hidden" name="vacation_pto[is_up_to_set]" value="0">
										{!! Form::checkbox('vacation_pto[is_up_to_set]', 1, (isset($fields->get('vacation_pto')->is_up_to_set) ? $fields->get('vacation_pto')->is_up_to_set : null),  ['class' => 'form-control check-box carry-over-amount carry-up-to data-selection-element pair_check', 'id' => 'carry_over_up_to', 'data-pair' => '#carry_over_unlimited', 'data-value' => (isset($fields->get('vacation_pto')->is_carry_over_unlimited) ? $fields->get('vacation_pto')->is_carry_over_unlimited : null )]) !!}
										<label>Up To:</label>
										<input type="text" class="form-control date-box" name="vacation_pto[up_to_amount]" value="{{ $fields->get('vacation_pto')->up_to_amount ?? '0' }}">
										{!! Form::select('vacation_pto[up_to_interval]', 
											['' => ' - Select One - ',
											'hours' => 'Hours',
											'days' => ' Days',
											'weeks' => 'Weeks',
											],
											(isset($fields->get('vacation_pto')->up_to_interval) ? $fields->get('vacation_pto')->up_to_interval : null),
											['class' => 'form-control date-box data-selection-element', 'data-value' => 
											(isset($fields->get("vacation_pto")->up_to_interval) ? $fields->get("vacation_pto")->up_to_interval : null)]
											) !!}
									</div>
								</div>
								
							</div>
						</div>
						<div class="row odd">
							<label for="" class="col-md-6 col-md-offset-2 control-label">How Do You Handle Unused Benefits?</label>
							<div class="col-md-6 col-md-offset-5">
								<input type="hidden" name="vacation_pto[earning_is_unused_benefits_paid_out]" value="0">
								<input type="checkbox" class="form-control check-box unused-benefits dataForm data-selection-element" name="vacation_pto[earning_is_unused_benefits_paid_out]" id="vacation_pto_paid_out" data-value="{{ $fields->get('vacation_pto')->earning_is_unused_benefits_paid_out or "" }}" > <!--data-link=".forfeited_yes" data-target=".paid_out" data-effect="toggle"-->
								<label>Paid-Out</label>
								
								<div class="row paid_out hidden" id="paid_out">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<label>Up To:</label>
												<input type="text" class="form-control date-box" name="vacation_pto[earning_up_to_amount]" value="{{ $fields->get('vacation_pto')->earning_up_to_amount or 0}}">
												<select class="form-control date-box data-selection-element" name="vacation_pto[earning_unused_benefits_interval]" data-value="{{ $fields->get('vacation_pto')->earning_unused_benefits_interval or "" }}">
													<option value=""> - Select One - </option>
													<option value="hours">Hours</option>
													<option class="in_days " value="days">Days</option>hidden
													<option class="in_weeks " value="weeks">Weeks</option>hidden
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-md-offset-5">
								<input type="hidden" name="vacation_pto[earning_is_benefits_forfeited]" value="0">
								<input type="checkbox" value="1" class="form-control check-box unused-benefits forfeited_yes dataForm data-selection-element" name="vacation_pto[earning_is_benefits_forfeited]" id="vacation_pto_forfeited_yes" data-value="{{ $fields->get('vacation_pto')->earning_is_benefits_forfeited or "" }}"><!--data-link=".unused-benefits" data-target=".forfeited_yes" data-effect="toggle"-->
								<label>Forfeited</label>
							</div>
							<div class="col-md-10 col-md-offset-1 forfeited-yes hidden" id="forfeit_yes">
								<h5 class="text-center alert-danger"><em>Warning: Not All States Allow Forfeiture. Please Contact Bent Ericksen</em></h5>
							</div>
						</div>
					</div>
				</div>

				@foreach($fields->get('classifications') AS $key => $classification)
					@if($classification->is_enabled)
						<div class="row pto-row" data-count="1" data-classification-id="{{ $classification->id }}">
							<div class="col-md-8 col-md-offset-2 sub-content pto-custom">
								<div class="row">
									<h5 class="col-md-3"><em>{{ $classification->name }}</em>:</h5>
									@if(!$classification->is_base)
										<div class="col-md-5">
											<input type="hidden" name="classification[{{ $classification->id }}][vacation_pto][pto_same_as_base]" value="0">
											<input type="checkbox" class="form-control check-box same_as_fulltime {id}_same_as_fulltime dataForm data-selection-element pto_check" id="{{$classification->id}}_same_as_full_time" value="1" data-pair="#{{$classification->id}}_not_offered" data-value="{{ $classification->vacation_pto->same_as_base or "" }}" name="classification[{{ $classification->id }}][vacation_pto][pto_same_as_base]" data-target="#does_not_receive_wrap{{$classification->id}}" data-effect="overlay">
											<label class="control-label"><em>Receives Same As Full Time</em></label>
										</div>
										<div class="col-md-4">
											<input type="hidden" name="classification[{{ $classification->id }}][vacation_pto][pto_does_not_receive2]" value="0">
											<input type="checkbox" class="form-control check-box does_not_receive  dataForm data-selection-element pto_check" id="{{$classification->id}}_not_offered" value="1" data-pair="#{{$classification->id}}_same_as_full_time" data-value="{{ $classification->vacation_pto->pto_does_not_receive2 or "" }}" name="classification[{{ $classification->id }}][vacation_pto][pto_does_not_receive2]" data-target="#does_not_receive_wrap{{$classification->id}}" data-effect="overlay">
											<label class="control-label"><em>Do Not Receive</em></label>
										</div>
									@endif										
								</div>
								
								@if( isset($classification->vacation_pto->row) )
									<?php $i = 0; ?>
									@foreach( $classification->vacation_pto->row AS $key => $value )
										<div class="row parent-row">
											<div class="col-md-12 does_not_receive_container" id="does_not_receive_wrap{{$classification->id}}">
												<div class="row customs">
												
													<div class="custom main">
														<div class="row">
															@if( $i === 0 )
																<div class="col-md-1"></div>																
															@else
																<a class="btn col-md-1 remove-pto">
																	<i class="fa fa-times"></i>
																</a>
															@endif
															<div class="col-md-11 no-padding-both">
																After
																<input type="text" class="form-control number-box pto_value" name="classification[{{ $classification->id }}][vacation_pto][row][{{ $key }}][wait_value]" value="{{ $value->wait_value or "" }}">
																<span class="ann_year">year<span class="years_of_service"><!--hidden-->s</span> of continuous service,</span><span class="cal_year hidden">calendar year<span class="years_of_service">s</span> of service,</span>
																<input type="text" class="form-control number-box" name="classification[{{ $classification->id }}][vacation_pto][row][{{ $key }}][value_earned]" value="{{ $value->value_earned or "" }}">
																<input type="text" class="form-control number-box earned_interval pto_interval_text" name="classification[{{ $classification->id }}][vacation_pto][row][{{ $key }}][pto_interval]" readonly>
																of <span class="earning_type_pto hidden">PTO</span><span class="earning_type_vacation">Vacation</span> earned per <span class="hour_worked hidden">Hour Worked</span><span class="year_worked">Year</span>.
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
										<?php $i++; ?>
									@endforeach
								@else
										<div class="row parent-row">
											<div class="col-md-12 does_not_receive_container" id="does_not_receive_wrap{{$classification->id}}">
												<div class="row customs">
												
													<div class="custom main">
														<div class="row">
															<div class="col-md-1"></div>
															<div class="col-md-11 no-padding-both">
																After
																<input type="text" class="form-control number-box pto_value" name="classification[{{ $classification->id }}][vacation_pto][row][0][wait_value]" value="">
																<span class="ann_year">year<span class="years_of_service"><!--hidden-->s</span> of continuous service,</span><span class="cal_year hidden">calendar year<span class="years_of_service">s</span> of service,</span>
																<input type="text" class="form-control number-box" name="classification[{{ $classification->id }}][vacation_pto][row][0][value_earned]" value="">
																<input type="text" class="form-control number-box earned_interval pto_interval_text" name="classification[{{ $classification->id }}][vacation_pto][row][0][pto_interval]" readonly>
																of <span class="earning_type_pto hidden">PTO</span><span class="earning_type_vacation">Vacation</span> earned per <span class="hour_worked hidden">Hour Worked</span><span class="year_worked">Year</span>.
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>									
								@endif
							</div>

							<div class="col-md-8 col-md-offset-2 sub-content">
								<p class="col-md-8 col-md-offset-1">
									<a class="btn add-pto"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Level</a>
								</p>
							</div>

						</div>
					@endif
				@endforeach
			</div>
		<!-- End Step3 -->
	</div>
	
@section('foot')
	@parent			
	<script>
		$('#benefit_options').on('click', '.remove-pto', function() {
			$(this).parents('.parent-row').empty().html('');
		});
		$('.add-pto').on('click', function() {
			$parent = $(this).parents('.pto-row');
			classificationId = $parent.attr('data-classification-id');
			count = $parent.attr('data-count');
			if ($("#vacation_earning_benefit_rate_received option:selected").text() == "Per Hour Worked")
			{
				ending = "hour";
			} else {
				ending = "year";
			}
			
			interval = $parent.find('.custom.main .pto_interval_text').val();
			
			$parent.find('.pto-custom').append( getRow(classificationId, count, ending, interval ) );

			$parent.attr(count++);
		});
		
		function getRow( classificationId, count, ending, interval )
		{
			if(ending === "hour")
			{
				var ending_html = "<span class='hour_worked'>Hour Worked</span><span class='year_worked hidden'>Year</span>";
			} else {
				var ending_html = "<span class='hour_worked hidden'>Hour Worked</span><span class='year_worked'>Year</span>";
			}
			
			
			var segment = "<div class='parent-row row'><div class='col-md-12'><div class='row customs'><div class='row'><div class='custom'> <a class='btn col-md-1 remove-pto'><i class='fa fa-times'></i></a><div class='col-md-11 no-padding-both'> After <input type='text' class='form-control number-box pto_value' name='classification[" + classificationId + "][vacation_pto][new][" + count + "][wait_value]' value=''><span class='ann_year'> year<span class='years_of_service'>s</span> of continuous service, </span><span class='cal_year hidden'>calendar year<span class='years_of_service'>s</span> of service, </span><input type='text' class='form-control number-box' name='classification[" + classificationId + "][vacation_pto][new][" + count + "][value_earned]' value=''> <input type='text' value='" + interval + "' class='form-control number-box earned_interval pto_interval_text' name='classification[" + classificationId + "][vacation_pto][new][" + count + "][pto_interval]' readonly> of <span class='earning_type_pto hidden'>PTO</span><span class='earning_type_vacation'> Vacation</span> earned per " + ending_html + ".</div></div></div></div></div></div>";
			
			return segment;
		}
	</script>	
@stop
