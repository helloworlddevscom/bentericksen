
				<div id="offeredWrap" class="offered-container" style="position: relative;">
					<div class="row parameters">
						<div class="col-md-10 col-md-offset-1">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3 text-center"><h5>Check if paid:</h5></div>
										<div class="col-md-4"><h5>Holiday</h5> {!! $help->button("u2912") !!}</div>
										<div class="col-md-4"><h5>Date</h5></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1 sub-content">
							<div class="row holidays">
								@foreach( $fields->getHolidays() AS $holiday )
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-3 text-center">
												<input type="hidden" value="0" name="holiday[{{ $holiday->id }}][is_offered]">
												<input type="checkbox" class="check-box" @if( isset($fields->get('holiday')->{$holiday->id}->is_offered ) && $fields->get('holiday')->{$holiday->id}->is_offered == 1) checked @endif value="1" id="sick_leave_holiday_new_years_day_is_offered" name="holiday[{{ $holiday->id }}][is_offered]">
											</div>
											<div class="col-md-4"><h5>{{ $holiday->name }}</h5></div>
											<div class="col-md-4"><h5>{{ $holiday->info }}</h5></div>
										</div>
									</div>
								@endforeach
	
						
								<div class="holidays-custom">
								{{--
									{holidays}
										<div class="row">
											<div class="col-md-12 holiday">
												<div class="col-md-3 text-center"><input type="hidden" name="holidays[{holiday_id}][is_enabled]" value="0">
													<input type="checkbox" class="check-box" value="1" name="holidays[{holiday_id}][is_enabled]" data-checkbox-checked="{is_enabled}">
												</div>
												<div class="col-md-4"><h5>{name}</h5><input type="hidden" name="holidays[{holiday_id}][name]" value="{name}"></div>
												<div class="col-md-4"><h5>{info}</h5><input type="hidden" name="holidays[{holiday_id}][info]" value="{info}"></div>
											</div>
										</div>
									{/holidays}
								--}}
								</div>
							</div>
							<p class="col-md-8 col-md-offset-1">
								<a class="btn add-holiday" data-status="odd" data-group="holiday">
									<img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Holiday
								</a>
							</p>
						</div>
					</div>
				</div>