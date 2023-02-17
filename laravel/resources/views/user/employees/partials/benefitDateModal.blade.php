	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="row">
					<label for="employee_benefit_date" class="col-md-3 col-md-offset-2 control-label">Benefit Date:</label>
					<div class="col-md-4">
						<input type="text" class="form-control date-picker" id="employee_benefit_date" name="user[benefit_date]" placeholder="mm/dd/yyyy" value="{{ date('m/d/Y', strtotime($employee->user->getBenefitDate())) }}">
					</div>
				</div>
			</div>
		</div>
	</div>