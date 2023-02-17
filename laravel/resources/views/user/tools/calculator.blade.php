@extends('user.wrap')

@section('content')

	<div id="main_body">
		<div class="container calculators" id="main">
			<div class="row main_wrap">

				<form class="form-horizontal" method="get">
					<div class="col-md-8 col-md-offset-2 content">
						<div class="row">
							<h3 class="text-center">Calculators</h3>
						</div>
						<div class="col-md-12">
							<h5 id="salary-converter" class="even head" data-target=".salary-converter">
								<a href="#">Salary Converter</a>
							</h5>
							<div class="even salary-converter hidden">
								<div class="content">
									<div class="row">
										<label for="hoursPerDay" class="col-md-4 col-md-offset-1 control-label text-right">Hours Worked Per Day:</label>
										<div class="col-md-3">
											<input type="text" class="form-control salary" id="hoursPerDay" name="hoursPerDay" value="8" placeholder="8">
										</div>
									</div>
									<div class="row">
										<label for="daysPerWeek" class="col-md-4 col-md-offset-1 control-label text-right">Days Worked Per Week:</label>
										<div class="col-md-3">
											<input type="text" class="form-control salary" id="daysPerWeek" name="daysPerWeek" value="5" placeholder="5">
										</div>
									</div>
									<div class="row">
										<label for="weeksPerYear" class="col-md-4 col-md-offset-1 control-label text-right">Weeks Worked Per Year:</label>
										<div class="col-md-3">
											<input type="text" class="form-control salary" id="weeksPerYear" name="weeksPerYear" value="52" placeholder="52">
										</div>
									</div>
									<div class="row">
										<label for="pay" class="col-md-4 col-md-offset-1 control-label text-right">Wage:</label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control salary" id="pay" name="pay" value="" placeholder="00.00">
											</div>
										</div>
										<div class="col-md-3">
											<select class="form-control salary" id="payRate" name="payRate">
												<option value="ph">Per Hour</option>
												<option value="pd">Per Day</option>
												<option value="pw">Per Week</option>
												<option value="pm">Per Month</option>
												<option value="py">Per Year</option>
											</select>
										</div>
									</div>
									<div class="row">
										<label class="col-md-4 col-md-offset-1 control-label text-right">=</label>
										<div class="col-md-3 padding-top">
											&nbsp;<span id="payTotal" name="payTotal">$&nbsp;00.00</span>
										</div>
										<div class="col-md-3">
											<select class="form-control salary" id="payTotalRate" name="payTotalRate">
												<option value="ph">Per Hour</option>
												<option value="pd">Per Day</option>
												<option value="pw">Per Week</option>
												<option value="pm">Per Month</option>
												<option value="py">Per Year</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<h5 id="turnover-cost" class="odd head" data-target=".turnover-cost">
								<a href="#">Turnover Cost</a>
							</h5>
							<div class="odd turnover-cost hidden" style="overflow:auto;">
								<div class="content">
									<div class="col-md-6 calc_height" id="hiring_costs">
										<h4 class="text-center">Hiring Costs</h4>
										<div class="row">
											<label for="agencyFees" class="col-md-8 control-label text-right">Agency Fees:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="agencyFees" name="agencyFees" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="advertising" class="col-md-8 control-label text-right">Advertising:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="advertising" name="advertising" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u6001") }}</div>
											<label for="hiringPersonnelDepartment" class="col-md-7 control-label text-right">Personnel Department:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="hiringPersonnelDepartment" name="hiringPersonnelDepartment" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u6002") }}</div>
											<label for="manager" class="col-md-7 control-label text-right">Manager:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="manager" name="manager" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="materialsAndHandling" class="col-md-8 control-label text-right">Materials And Handling:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="materialsAndHandling" name="materialsAndHandling" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="testingMaterials" class="col-md-8 control-label text-right">Testing Materials:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="testingMaterials" name="testingMaterials" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="otherHiring" class="col-md-8 control-label text-right">Other:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control hiring" id="otherHiring" name="otherHiring" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="hiringCostSubTotal" class="col-md-8 control-label text-right">Sub Total</label>
											<div class="col-md-4 no-padding-both padding-top">
												<span class="subTotals" id="hiringCostSubTotal" name="hiringCostSubTotal" value="">$&nbsp;00.00</span>
											</div>
										</div>
									</div>
									<div class="col-md-6 calc_height" id="training_and_start-up_costs">
										<h4 class="text-center">Training And Start-up Costs</h4>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u60031") }}</div>
											<label for="trainer" class="col-md-7 control-label text-right">Trainer:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="trainer" name="trainer" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="trainingMaterialsAndEquipment" class="col-md-8 control-label text-right">Training Materials And Equipment:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="trainingMaterialsAndEquipment" name="trainingMaterialsAndEquipment" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="outsideTrainingHelp" class="col-md-8 control-label text-right">Outside Training Help:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="outsideTrainingHelp" name="outsideTrainingHelp" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="trainingFacility" class="col-md-8 control-label text-right">Training Facility:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="trainingFacility" name="trainingFacility" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u6004") }}</div>
											<label for="supervisor" class="col-md-7 control-label text-right">Supervisor:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="supervisor" name="supervisor" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="otherTraining" class="col-md-8 control-label text-right">Other:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control training" id="otherTraining" name="otherTraining" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="trainingAndStartUpCostsSubTotal" class="col-md-8 control-label text-right">Sub Total</label>
											<div class="col-md-4 no-padding-both padding-top">
												<span class="subTotals" id="trainingAndStartUpCostsSubTotal" name="trainingAndStartUpCostsSubTotal" value="">$&nbsp;00.00</span>
											</div>
										</div>
									</div>
									<div class="col-md-6 calc_height" id="termination_costs">
										<h4 class="text-center">Termination Costs</h4>
										<div class="row">
											<label for="counsellingTime" class="col-md-8 control-label text-right">Counselling Time:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="counsellingTime" name="counsellingTime" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="unemploymentBenefits" class="col-md-8 control-label text-right">Unemployment Benefits:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="unemploymentBenefits" name="unemploymentBenefits" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="legalFees" class="col-md-8 control-label text-right">Legal Fees:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="legalFees" name="legalFees" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="severancePay" class="col-md-8 control-label text-right">Severance Pay:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="severancePay" name="severancePay" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u6005") }}</div>
											<label for="personnelDepartment" class="col-md-7 control-label text-right">Personnel Department:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="personnelDepartment" name="personnelDepartment" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="otherTermination" class="col-md-8 control-label text-right">Other:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control termination" id="otherTermination" name="otherTermination" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="terminationCostsSubTotal" class="col-md-8 control-label text-right">Sub Total</label>
											<div class="col-md-4 no-padding-both padding-top">
												<span class="subTotals" id="terminationCostsSubTotal" name="terminationCostsSubTotal" value="">$&nbsp;00.00</span>
											</div>
										</div>
									</div>
									<div class="col-md-6 calc_height" id="lost_sales_costs">
										<h4 class="text-center">Lost Sales Costs</h4>
										<div class="row">
											<label for="nplDuringLagBetweenEmployees" class="col-md-8 control-label text-right">Net Profit Lost During Lag Between Employees:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control sales" id="nplDuringLagBetweenEmployees" name="nplDuringLagBetweenEmployees" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="nplDuringInitialPeriod" class="col-md-8 control-label text-right">Net Profit Lost During Initial Period:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control sales" id="nplDuringInitialPeriod" name="nplDuringInitialPeriod" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="nplDueToPoorPerformance" class="col-md-8 control-label text-right">Net Profit Lost Due To Poor Performance:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control sales" id="nplDueToPoorPerformance" name="nplDueToPoorPerformance" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="nplDueToUnmannedPositions" class="col-md-8 control-label text-right">Net Profit Lost Due To Unmanned Positions:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control sales" id="nplDueToUnmannedPositions" name="nplDueToUnmannedPositions" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="otherLost" class="col-md-8 control-label text-right">Other Sales Losses:</label>
											<div class="col-md-4 no-padding-both">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control sales" id="otherLost" name="otherLost" placeholder="00.00">
												</div>
											</div>
										</div>
										<div class="row">
											<label for="lostSalesCostsSubTotal" class="col-md-8 control-label text-right">Sub Total</label>
											<div class="col-md-4 no-padding-both padding-top">
												<span class="subTotals" id="lostSalesCostsSubTotal" name="lostSalesCostsSubTotal" value="">$ 00.00</span>
											</div>
										</div>
									</div>
									<hr>
									<div class="col-md-12" id="totalHiringExpenses">
										<div class="row">
											<label for="totalHiringExpense" class="col-md-8 control-label text-right">Total Hiring Expense</label>
											<div class="col-md-2 padding-top">
												<span id="totalHiringExpense" name="totalHiringExpense" value="">$&nbsp;00.00</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 no-padding-both padding-top-5">{{ $help->button("u6006") }}</div>
											<label for="numberOfEmployees" class="col-md-7 control-label text-right">Number Of Employees Hired Within Past Year:</label>
											<div class="col-md-2">
												<input type="text" class="form-control" id="numberOfEmployees" name="numberOfEmployees" placeholder="0">
											</div>
										</div>
										<div class="row">
											<label for="hiringCostPerEmployee" class="col-md-8 control-label text-right">Hiring Cost Per Employee</label>
											<div class="col-md-2 padding-top">
												<span id="hiringCostPerEmployee" name="hiringCostPerEmployee" value="">$&nbsp;00.00</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<h5 id="overtime-calculator" class="even head" data-target=".overtime-calculator">
								<a href="#">Overtime Calculator</a>
							</h5>
							<div class="even overtime-calculator hidden" style="overflow:auto;">
								<div class="content">
									<div class="col-md-12" id="regular_wage">
										<div class="row">
											<label for="regularHours" class="col-md-4 col-md-offset-1 control-label text-right">Regular Hours:</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control overtime" id="regularHours" name="regularHours" placeholder="00.00">
											</div>
										</div>
										<div class="row">
											<label for="regularBaseWage" class="col-md-4 col-md-offset-1 control-label text-right">Regular / Base Wage:</label>
											<div class="col-md-2 no-padding-right">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control overtime" id="regularBaseWage" name="regularBaseWage" placeholder="00.00">
												</div>
											</div>
											<div class="col-md-3 padding-top">
												<span id="" name="">Per Hour</span>
											</div>
										</div>
										<div class="row">
											<label for="overtimeHours" class="col-md-4 col-md-offset-1 control-label text-right">Overtime Hours:</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control overtime" id="overtimeHours" name="overtimeHours" placeholder="00.00">
											</div>
										</div>
									</div>
									<div class="col-md-12" id="overtime_wage">
										<div class="row">
											<label for="overtimeRate" class="col-md-4 col-md-offset-1 control-label text-right">Overtime Rate:</label>
											<div class="col-md-2 padding-top">
												<span id="overtimeRate" name="overtimeRate">$&nbsp;00.00</span>
											</div>
											<div class="col-md-2 padding-top">
												<span id="" name="">Per Hour</span>
											</div>
										</div>
										<div class="row">
											<label for="totalRegularPay" class="col-md-4 col-md-offset-1 control-label text-right">Total Regular Pay:</label>
											<div class="col-md-2 padding-top">
												<span id="totalRegularPay" name="totalRegularPay">$&nbsp;00.00</span>
											</div>
										</div>
										<div class="row">
											<label for="totalOvertimePay" class="col-md-4 col-md-offset-1 control-label text-right">Total Overtime Pay:</label>
											<div class="col-md-2 padding-top">
												<span id="totalOvertimePay" name="totalOvertimePay">$&nbsp;00.00</span>
											</div>
										</div>
										<div class="row">
											<label for="totalPay" class="col-md-4 col-md-offset-1 control-label text-right">Total Pay:</label>
											<div class="col-md-2 padding-top">
												<span id="totalPay" name="totalPay">$&nbsp;00.00</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<h5 id="average-wage" class="odd head" data-target=".average-wage">
								<a href="#">Average Wage</a>
							</h5>
							<div class="odd average-wage hidden" style="overflow:auto;">
								<div class="content">
									<div class="col-md-12" id="averageWages">
										<div class="row">
											<label for="aveRegularHours" class="col-md-4 col-md-offset-1 control-label text-right">Regular Hours:</label>
											<div class="col-md-2 no-padding-right">
												<input type="text" class="form-control average" id="aveRegularHours" name="aveRegularHours" placeholder="0.00">
											</div>
										</div>
										<div class="row">
											<label for="aveRegularBaseWage" class="col-md-4 col-md-offset-1 control-label text-right">Regular / Base Wage:</label>
											<div class="col-md-2 no-padding-right">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control average" id="aveRegularBaseWage" name="aveRegularBaseWage" placeholder="00.00">
												</div>
											</div>
											<div class="col-md-3 padding-top">
												Per Hour
											</div>
										</div>
										<div class="aveWage">

										</div>
										<div class="row">
											<div class="col-md-4 col-md-offset-4">
												<p class="col-md-8 col-md-offset-1"><a class="btn js-add" data-count="0" data-group="addAveWage"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add Another Rate</a></p>
											</div>
										</div>
										<div class="row">
											<label for="aveTotalHours" class="col-md-4 col-md-offset-1 control-label text-right">Total Hours:</label>
											<div class="col-md-2 padding-top">
												<span id="aveTotalHours" name="aveTotalHours">&nbsp00.00</span>
											</div>
										</div>
										<div class="row">
											<label for="aveAverageWage" class="col-md-4 col-md-offset-1 control-label text-right">Average Wage:</label>
											<div class="col-md-2 padding-top">
												<span id="aveAverageWage" name="aveAverageWage">$&nbsp;00.00</span>
											</div>
											<div class="col-md-3 padding-top">
												Per Hour
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
@stop

@section('foot')
	@parent
	<script src="/assets/scripts/calculators.js"></script>
	<script>
		$('.head').click(function(event) {
			target = $(this).attr('data-target');

			if($(target).hasClass('hidden'))
			{
				$(target).removeClass('hidden');
			} else {
				$(target).addClass('hidden');
			}
			event.preventDefault();
		});
	</script>

@stop
