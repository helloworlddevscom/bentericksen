@extends('user.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<div class="col-md-12 text-center">
					<h3>Reports</h3>
					<button type="button" class="btn btn-default btn-primary btn-xs" data-toggle="modal" data-target="#reports_list">REPORTS LIST</button>
					<div class="modal fade text-left" id="reports_list" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="modalLabel">REPORTS LIST</h4>
								</div>
								<div class="modal-body">
									<div class="text-center">
										<h3><em>Select a Report to view</em></h3>
									</div>
									<div class="table-responsive reports_table">
										<table class="table table-striped" id="reports_table">
											<tbody>
												<tr><td><a href="{path='user/reports/birthdays_and_anniversaries'}">Birthdays and Anniversaries</a>		<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/employee_contact_info_report'}">Employee Contact Info Report</a>	<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/date_of_birth_and_age'}">Date of Birth & Age</a>					<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/status'}">Status</a>												<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/job_description'}">Job Description</a>								<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/attendance_report'}">Attendance Report</a>							<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/salary'}">Salary</a>												<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/performance_review'}">Performance Review</a>						<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/insurance_report'}">Insurance Report</a>							<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/dental_benefits_pdba'}">Dental Benefits (PDBA)</a>					<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/dental_benefits_discount'}">Dental Benefits (Discount)</a>			<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/sick_leave'}">Sick Leave</a>										<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/pto_vacation_summary'}">PTO/Vacation Summary</a>					<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/pto_vacation_detail'}">PTO/Vacation Detail</a>						<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/bereavement'}">Bereavement?</a>									<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/leaves_of_absence'}">Leaves of Absence</a>							<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/retirement'}">Retirement?</a>										<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/paperwork'}">Paperwork</a>											<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/time_off_report'}">Time Off Report</a>								<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/employee_summary_report'}">Employee Summary Report</a>				<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/counselling_memo_log'}">Counselling Memo Log</a>					<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/benefit_liability'}">Benefit Liability</a>							<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/benefit_date_and_year'}">Benefit Date and Year?</a>				<i class="fa fa-line-chart"></i></td></tr>
												<tr><td><a href="{path='user/reports/turnover'}">Turnover</a>											<i class="fa fa-line-chart"></i></td></tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 content">
					<div class="row sub_content">
						<div class="text-center">
							<h3><em>Select a Report to view</em></h3>
						</div>
						<div class="table-responsive reports_table">
							<table class="table table-striped" id="reports_table">
								<tbody>
									<tr><td><a href="{path='user/reports/birthdays_and_anniversaries'}">Birthdays and Anniversaries</a>		<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/employee_contact_info_report'}">Employee Contact Info Report</a>	<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/date_of_birth_and_age'}">Date of Birth & Age</a>					<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/status'}">Status</a>												<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/job_description'}">Job Description</a>								<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/attendance_report'}">Attendance Report</a>							<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/salary'}">Salary</a>												<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/performance_review'}">Performance Review</a>						<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/insurance_report'}">Insurance Report</a>							<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/dental_benefits_pdba'}">Dental Benefits (PDBA)</a>					<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/dental_benefits_discount'}">Dental Benefits (Discount)</a>			<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/sick_leave'}">Sick Leave</a>										<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/pto_vacation_summary'}">PTO/Vacation Summary</a>					<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/pto_vacation_detail'}">PTO/Vacation Detail</a>						<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/bereavement'}">Bereavement?</a>									<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/leaves_of_absence'}">Leaves of Absence</a>							<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/retirement'}">Retirement?</a>										<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/paperwork'}">Paperwork</a>											<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/time_off_report'}">Time Off Report</a>								<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/employee_summary_report'}">Employee Summary Report</a>				<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/counselling_memo_log'}">Counselling Memo Log</a>					<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/benefit_liability'}">Benefit Liability</a>							<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/benefit_date_and_year'}">Benefit Date and Year?</a>				<i class="fa fa-line-chart"></i></td></tr>
									<tr><td><a href="{path='user/reports/turnover'}">Turnover</a>											<i class="fa fa-line-chart"></i></td></tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-12 text-center">
					<a href="#" class="btn btn-primary btn-xs">PRINT</a>
					<a href="#" class="btn btn-primary btn-xs">EXPORT</a>
					<a href="#" class="btn btn-primary btn-xs">MEMORIZE</a>
				</div>						
			
			</div>
		</div>
@stop

@section('foot')
	@parent			

@stop