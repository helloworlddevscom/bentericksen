				
				<div id="offeredWrap" class="offered-container" style="position: relative;">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 sub-content">
							<div class="row odd">
								<div class="benefits_earned">
									
									<label for="sick_leave_benefits_earned_interval" class="col-md-5 control-label">When Are Benefits Given?</label>
									<div class="col-md-3">
										<select class="form-control are_benefits_earned data-selection-element" id="sick_leave_benefits_earned_interval" name="sickleave[benefits_earned_interval]" data-value="{{ $fields->get('sickleave')->benefits_earned_interval or "" }}">
											<option value=""> - Select One - </option>
											<option value="per_hour">Per Hour Worked</option>
											<option value="per_week">Per Week</option>
											<option value="per_month">Per Month</option>
											<option value="per_quarter">Per Quarter Year</option>
											<option value="per_6_months">Per Half Year</option>
											<option value="per_year">Per Year</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row even">
								<label for="sick_leave_benefit_earned_year" class="col-md-5 control-label">Benefits Are Earned On:</label>
								<div class="col-md-3">
									<select class="form-control step_1 earning_benefit_earned_on data-selection-element" id="sick_leave_benefit_earned_year" name="sickleave[benefit_earned_year]" data-value="{{ $fields->get('sickleave')->benefit_earned_year or "" }}">
										<option value=""> - Select One - </option>
										<option value="anniversary_year">Anniversary Year</option>
										<option value="calendar_year">Calendar Year</option>
									</select>
								</div>
								<div class="col-md-12 calendar_year hidden" id="calendar_fields">
									<div class="row">
										<div class="col-md-5 text-right">
											<select class="form-control date-box" id="sick_leave_benefit_earned_day data-selection-element" name="sickleave[benefit_earned_day]" data-value="{{ $fields->get('sickleave')->benefit_earned_day or "" }}">
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
											<select class="form-control" id="sick_leave_benefit_earned_month data-selection-element" name="sickleave[benefit_earned_month]" data-value="{{ $fields->get('sickleave')->benefit_earned_month or "" }}">
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
										<label for="sick_leave_benefit_first_year_accrual" class="col-md-5 control-label">First Year Earned:</label>
										<div class="col-md-3">
											<select class="form-control data-selection-element" id="sick_leave_benefit_first_year_accrual" name="sickleave[benefit_first_year_accrual]" data-value="{{ $fields->get('sickleave')->benefit_first_year_accrual or "" }}">
												<option value=""> - Select One - </option>
												<option value="prorate">Prorate</option>
												<option value="none">None</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row odd">
								<label for="sick_leave_benefit_provided" class="col-md-5 control-label">How Are Benefits Provided?:</label>
								<div class="col-md-3">
									<select class="form-control step_1 earning_benefit_provided data-selection-element" id="sick_leave_benefit_provided" name="sickleave[benefit_provided]" data-value="{{ $fields->get('sickleave')->benefit_provided or "" }}">
										<option value=""> - Select One - </option>
										<option value="hours">In Hours</option>
										<option value="days">In Days</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="employee_classifications hidden" id="employee_classifications">
						<div class="row">
							@foreach($fields->get('classifications') AS $key => $classification)
								@if($classification->is_enabled)
									<div class="col-md-8 col-md-offset-2 sub-content">
										<div class="row">
											<h5 class="col-md-5"><em>{{ $classification->name }}</em>:</h5>
											@if(!$classification->is_base)
												<div class="col-md-5">
													<input type="hidden" value="0" name="classification[{{ $classification->id }}][sickleave][does_not_receive]">
													<input type="checkbox" class="form-control check-box does_not_receive dataForm data-selection-element" value="1" name="classification[{{ $classification->id }}][sickleave][does_not_receive]" data-value="{{ $classification->sickleave->does_not_receive or "" }}" data-target="#part_time_wrap_{{$classification->id}}" data-effect="overlay">
													<label class="control-label"><em>Not Offered</em></label>
												</div>
											@endif
										</div>
										<div class="row">
											<div class="col-md-12 does_not_receive_container" id="part_time_wrap_{{$classification->id}}">
												<div class="row odd">
													<div class="col-md-12">
														<strong>Please Define:</strong>
													</div>
													<label for="{id}_sick_leave_hours_in_day" class="col-md-6 control-label">How Many Hours Are In A Day?</label>
													<div class="col-md-2 no-padding-right">
														<input type="text" class="form-control step_2 step_2_a" id="{id}_sick_leave_hours_in_day" name="classification[{{ $classification->id }}][sickleave][hours_in_day]" value="{{ $classification->sickleave->hours_in_day or "" }}">
													</div>
													<div class="col-md-4">
														<label for="{id}_sick_leave_hours_in_day" class="control-label strong">hours a day</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endif
							@endforeach
						</div>
					</div>
					<div class="step-3 hidden" id="sick_leave_waiting_period_carry_over">
						<div class="row">
							<div class="col-md-8 col-md-offset-2 sub-content">
								<div class="row">
									<div class="col-md-12 initial_container">
										<div class="row even">
											<label for="sick_leave_waiting_period_to_start_using" class="col-md-6 control-label">Waiting Period To Start Using:</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control start_using" id="sick_leave_waiting_period_to_start_using" name="sickleave[waiting_period_to_start_using]" value="{{ $fields->get('sickleave')->waiting_period_to_start_using or 0}}">
											</div>
											<div class="col-md-3">
												<select class="form-control date-box start_using_interval data-selection-element" id="sick_leave_waiting_period_to_start_using_interval" name="sickleave[waiting_period_to_start_using_interval]" data-value="{{ $fields->get('sickleave')->waiting_period_to_start_using_interval or "" }}">
													<option value=""> - Select One - </option>
													<option value="days">Days</option>
													<option value="weeks">Weeks</option>
													<option value="months">Months</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<label for="" class="col-md-6 control-label">Do You Allow Carry-Over?</label>
									<div class="col-md-6">
										<div class="row radio_benefits">
											<div class="col-md-4">
												<input type="radio" class="carry-over carry_yes data-selection-element" value="1" id="sick_leave_is_carry_over_allowed_yes" name="sickleave[is_carry_over_allowed]" data-value="{{ $fields->get('sickleave')->is_carry_over_allowed or "" }}">
												<label for="sick_leave_is_carry_over_allowed_yes">Yes</label>
											</div>
											<div class="col-md-4">
												<input type="radio" class="carry-over carry_no data-selection-element" value="0" id="sick_leave_is_carry_over_allowed_no" name="sickleave[is_carry_over_allowed]" data-value="{{ $fields->get('sickleave')->is_carry_over_allowed or "" }}">
												<label for="sick_leave_is_carry_over_allowed_no">No</label>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="row carry-yes" id="carry_over">
											<label for="" class="col-md-6 col-md-offset-2 control-label">How much can carry-over?</label>
											<div class="col-md-3 col-md-offset-5">
												<input type="hidden" value="0" name="sickleave[is_carry_over_unlimited]">
												<input type="checkbox" class="form-control check-box carry-over-amount carry-unlimited data-selection-element" value="1" id="sick_leave_is_carry_over_unlimited" name="sickleave[is_carry_over_unlimited]" data-value="{{ $fields->get('sickleave')->is_carry_over_unlimited or "" }}">
												<label for="sick_leave_is_carry_over_unlimited">Unlimited</label>
											</div>
											<div class="col-md-6 col-md-offset-5">
												<input type="hidden" value="0" name="sickleave[is_up_to_set]">
												<input type="checkbox" class="form-control check-box carry-over-amount carry-up-to data-selection-element" value="1" id="sick_leave_is_up_to_set" name="sickleave[is_up_to_set]" data-value="{{ $fields->get('sickleave')->is_up_to_set or "" }}">
												<label for="sick_leave_is_up_to_set">Up To:</label>
												<input type="text" class="form-control date-box" id="sick_leave_up_to_amount" name="sickleave[up_to_amount]" value="{{ $fields->get('sickleave')->up_to_amount or 0}}">
												<select class="form-control date-box data-selection-element" id="sick_leave_up_to_amount_interval" name="sickleave[up_to_amount_interval]" data-value="{{ $fields->get('sickleave')->up_to_amount_interval or "" }}">
													<option value=""> - Select One - </option>
													<option value="hours">Hours</option>
													<option value="days">Days</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<label for="" class="col-md-6 col-md-offset-2 control-label">How Do You Handle Unused Sick Leave?</label>
									<div class="col-md-6 col-md-offset-5 .sick_leave_unused">
										<input type="hidden" value="0" name="sickleave[is_unused_paid_out]">
										<input type="checkbox" class="form-control check-box unused-benefits data-selection-element" id="sick_leave_is_unused_paid_out" name="sickleave[is_unused_paid_out]" data-value="{{ $fields->get('sickleave')->is_unused_paid_out or "" }}">
										<label for="sick_leave_is_unused_paid_out" class="">Paid-Out</label>
										<div class="row paid_out" id="paid_out">
											<div class="col-md-12" id="sick_leave_is_unused_paid_out_options">
												<div class="row">
													<div class="col-md-12">
														<label for="sick_leave_paid_out_amount">Up To:</label>
														<input type="text" class="form-control date-box" id="sick_leave_paid_out_amount" name="sickleave[paid_out_amount]" value="{{ $fields->get('sickleave')->paid_out_amount or 0}}">
														<select class="form-control date-box data-selection-element" id="sick_leave_paid_out_amount_interval" name="sickleave[paid_out_amount_interval]" data-value="{{ $fields->get('sickleave')->paid_out_amount_interval or "" }}">
															<option value=""> - Select One - </option>
															<option value="hours">Hours</option>
															<option value="days">Days</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3 col-md-offset-5 .sick_leave_unused">
										<input type="hidden" value="0" name="sickleave[is_unused_forfeited]">
										<input type="checkbox" class="form-control check-box unused-benefits forfeited_yes data-selection-element" value="1" id="sick_leave_is_unused_forfeited" name="sickleave[is_unused_forfeited]" data-value="{{ $fields->get('sickleave')->is_unused_forfeited or "" }}">
										<label for="sick_leave_is_unused_forfeited" class="">Forfeited</label>
									</div>
									<div class="col-md-10 col-md-offset-1 forfeited-yes hidden" id="sick_leave_is_unused_forfeited_warning">
										<h5 class="text-center alert-danger"><em>Warning: Not All States Allow Forfeiture. Please Contact Bent Ericksen</em></h5>
									</div>
								</div>
							</div>
						</div>
					@foreach($fields->get('classifications') AS $key => $classification)
						@if($classification->is_enabled)
							<div class="row sick-leave-row" data-count="1" data-classification-id="{{ $classification->id }}">
								<div class="col-md-8 col-md-offset-2 sub-content">
									<div class="row">
										<h5 class="col-md-3"><em>{{ $classification->name }}</em>:</h5>
										@if(!$classification->is_base)
											<div class="col-md-5">
												<input type="hidden" value="0" name="classification[{{$classification->id}}][sickleave][same_as_base]">
												<input type="checkbox" class="form-control check-box same_as_fulltime {id}_same_as_fulltime dataForm data-selection-element pair_check" name="classification[{{ $classification->id }}][sickleave][same_as_base]" value="1" id="{{$classification->id}}_same_as_fulltime" data-pair="#{{$classification->id}}_not_offered" data-value="{{ $classification->sickleave->same_as_base or "" }}" data-target="#same_as_full_time_wrap_{{$classification->id}}" data-effect="overlay">
												<label for="{id}_sick_leave_pto_same_as_fulltime" class="control-label"><em>Receives Same As Full Time</em></label>
											</div>
											<div class="col-md-4">
												<input type="hidden" value="0" name="classification[{{ $classification->id }}][sickleave][does_not_receive]">
												<input type="checkbox" class="form-control check-box does_not_receive {id}_sick_leave_earning_pto_does_not_receive dataForm data-selection-element pair_check" name="classification[{{ $classification->id }}][sickleave][does_not_receive]" value="1" id="{{$classification->id}}_not_offered" data-pair="#{{$classification->id}}_same_as_fulltime" data-value="{{ $classification->sickleave->does_not_receive or "" }}" data-target="#same_as_full_time_wrap_{{$classification->id}}" data-effect="overlay">
												<label for="{id}_sick_leave_earning_pto_does_not_receive" class="control-label"><em>Not Offered</em></label>
											</div>
										@endif
									</div>
									<div class="customs">
										<div class="row">
											<div class="col-md-12 does_not_receive_container" id="same_as_full_time_wrap_{{$classification->id}}">
												<div class="row custom main">
													<div class="col-md-12">
														Receives
														
														<input type="text" class="form-control number-box" name="classification[{{ $classification->id }}][sickleave][base_value_earned]" value="{{ $classification->sickleave->base_value_earned or "" }}">
														<input type="text" class="form-control number-box earned_interval" name="classification[{{$classification->id}}][sickleave][rate][0][base_earned_interval]" value="{{ $classification->sickleave->base_earned_interval or "" }}" readonly>
														of Sick Leave earned per <span class="hour_worked hidden">Hour Worked</span><span class="year_worked">Year</span>.
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{--
								<div class="col-md-8 col-md-offset-2 sub-content">
									<p class="col-md-8 col-md-offset-1">
										<a class="btn add-sickleave"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Level</a>
									</p>
								</div>									
								--}}
							</div>						
						@endif
					@endforeach
				</div>
			</div>
			
@section('foot')
	@parent			
	<script>
		$('.remove-sickleave').on('click', function() {
			$(this).parents('.parent-row').empty().html('');
		});
		
		$('.add-sickleave').on('click', function() {
			$parent = $(this).parents('.sick-leave-row');
			
			classificationId = $parent.attr('data-classification-id');
			count = $parent.attr('data-count');
			if ($("#sick_leave_benefits_earned_interval option:selected").text() == "Per Hour Worked")
			{
				ending = "hour";
			} else {
				ending = "year";
			}

			interval = $parent.find('.custom.main .pto_interval_text').val();

			$parent.find('.customs').append( getRow(classificationId, count, ending, interval ) );

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
			
			
			var segment = "After <input type='text' class='form-control number-box' name='classification[][sickleave][base_value_earned]' value=''> <input type='text' class='form-control number-box earned_interval' name='classification[{{$classification->id}}][sickleave][rate][0][base_earned_interval]' value='' readonly> of Sick Leave earned per <span class='hour_worked hidden'>Hour Worked</span><span class='year_worked'>Year</span>.";
			
			return segment;
		}
	</script>	
@stop			