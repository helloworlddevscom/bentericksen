/* PTO/Vacation */
$(document).ready(function() {
	/*
	if ($("#sick_leave_paid_out_amount_interval").attr("data-select") != "")
	{
		$("#sick_leave_paid_out_amount_interval").val($("#sick_leave_paid_out_amount_interval").attr("data-select")).change();
	}
	console.log($("#vacation_earning_benefit_type").prop("type"));*/
	$(".data-selection-element").each(function(index, element) {
		element = $(this);
		elementType = $(element).prop('type');
		elementAttr = 'data-value';
		
		if (elementType == 'select-one')
		{
			option = $(element).attr(elementAttr);
			$(element).val(option).change();
		}
		else if (elementType == 'checkbox')
		{
			checked = $(element).attr(elementAttr);
			//console.log($(element).attr(elementAttr));
			if (checked == 1 || checked == "on")
			{	
				$(element).prop("checked", true).change();
			}
		}
		else if(elementType == 'radio')
		{
			checked = $(element).attr(elementAttr);
			if (checked == 1 && $(element).val() == 1)
			{
				$(element).prop("checked", true).change();
			}
			else if (checked == 0 && $(element).val() == 0)
			{
				$(element).prop("checked", true).change();
			} 
		}
	});
});

/* Start Dropdowns */
$("#vacation_earning_benefit_type").change(function() {
	if ($("#vacation_earning_benefit_type option:selected").text() == "Paid Time-Off" && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#benefits_are_earned_on option:selected").val() != "" && $("#vacation_pto_benefit_interval option:selected").val() != "")
	{
		$("span.earning_type_pto").removeClass("hidden");
		$("span.earning_type_vacation").addClass("hidden");
		$("#employee_classifications").removeClass("hidden");
		$("#benefit_options").removeClass("hidden");
	}
	else if ($("#vacation_earning_benefit_type option:selected").text() == "Vacation"  && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#benefits_are_earned_on option:selected").val() != "" && $("#vacation_pto_benefit_interval option:selected").val() != "")
	{
		$("span.earning_type_pto").addClass("hidden");
		$("span.earning_type_vacation").removeClass("hidden");
		$("#employee_classifications").removeClass("hidden");
		$("#benefit_options").removeClass("hidden");
	}
	else 
	{
		$("#employee_classifications").addClass("hidden");
		$("#benefit_options").addClass("hidden");
	}
});

$("#vacation_earning_benefit_rate_received").change(function() {
	if ($("#vacation_earning_benefit_rate_received option:selected").text() == "Per Hour Worked")
	{
		$("span.hour_worked").removeClass("hidden");
		$("span.year_worked").addClass("hidden");
	}
	else
	{
		$("span.hour_worked").addClass("hidden");
		$("span.year_worked").removeClass("hidden");
	}
});

$("#benefits_are_earned_on").change(function() {
	if ($("#benefits_are_earned_on option:selected").text() == "Anniversary Year" && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#vacation_earning_benefit_type option:selected").val() != "" && $("#vacation_pto_benefit_interval option:selected").val() != "")
	{
		$("span.ann_year").removeClass("hidden");
		$("span.cal_year").addClass("hidden");
		$("div.calendar_year").addClass("hidden");
	}
	else if ($("#benefits_are_earned_on option:selected").text() == "Calendar Year")
	{
		$("span.ann_year").addClass("hidden");
		$("span.cal_year").removeClass("hidden");
		$("div.calendar_year").removeClass("hidden");
		$("#employee_classifications").addClass("hidden");
		$("#benefit_options").addClass("hidden");
	}
	else 
	{
		$("#employee_classifications").addClass("hidden");
		$("#benefit_options").addClass("hidden");
	}
});

$("#vacation_pto_benefit_interval").change(function() {
	if ($("#vacation_pto_benefit_interval option:selected").text() == "In Hours" && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#vacation_earning_benefit_type option:selected").val() != "" && $("#benefits_are_earned_on option:selected").val() != "")
	{
		$("#employee_classifications").addClass("hidden");
		$("#benefit_options").removeClass("hidden");
		$(".pto_interval_text").val("hours");
	}
	else if ($("#vacation_pto_benefit_interval option:selected").text() == "In Days" && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#vacation_earning_benefit_type option:selected").val() != "" && $("#benefits_are_earned_on option:selected").val() != "")
	{
		$("#employee_classifications").removeClass("hidden");
		$(".benefit_day_hours").removeClass("hidden");
		$(".benefit_week_hours").addClass("hidden");
		$("#benefit_options").removeClass("hidden");
		$(".pto_interval_text").val("days");
	}
	else if ($("#vacation_pto_benefit_interval option:selected").text() == "In Weeks" && $("#vacation_earning_benefit_rate_received option:selected").val() != "" && $("#vacation_earning_benefit_type option:selected").val() != "" && $("#benefits_are_earned_on option:selected").val() != "")
	{
		$("#employee_classifications").removeClass("hidden");
		$(".benefit_day_hours").removeClass("hidden");
		$(".benefit_week_hours").removeClass("hidden");
		$("#benefit_options").addClass("hidden");
		
	}
	else 
	{
		$("#employee_classifications").addClass("hidden");
		$("#benefit_options").addClass("hidden");
	}
});


$("input[name='vacation_pto[allow_carry_over]']").change(function() {
	if ($(this).val() == 1)
	{
		$("#carry_over_allowed").removeClass("hidden");
	}
	else
	{
		$("#carry_over_allowed").addClass("hidden");
	}
});

$("input[name='vacation_pto[can_use_as_they_earn]']").change(function() {
	if ($(this).val() == 1)
	{
		$(".use_benefits").prop("disabled", true);
	}
	else
	{
		$(".use_benefits").prop("disabled", false);
	}
});

/* End Dropdowns */

$("#vacation_pto_paid_out").change(function() {
	if ($("#vacation_pto_paid_out").is(":checked"))
	{
		$("#vacation_pto_forfeited_yes").prop('checked', false);
		$("#paid_out").removeClass("hidden");
		$("#forfeit_yes").addClass("hidden");
	}
});

$("#vacation_pto_forfeited_yes").change(function() {
	if ($("#vacation_pto_forfeited_yes").is(":checked"))
	{
		$("#vacation_pto_paid_out").prop('checked', false);
		$("#paid_out").addClass("hidden");
		$("#forfeit_yes").removeClass("hidden");
	}
});

/* Sick Leave */

$("#sick_leave_benefits_earned_interval").change(function() {
	if ($("#sick_leave_benefits_earned_interval option:selected").text() == "Per Hour Worked")
	{
		$("span.hour_worked").removeClass("hidden");
		$("span.year_worked").addClass("hidden");
	}
	else
	{
		$("span.hour_worked").addClass("hidden");
		$("span.year_worked").removeClass("hidden");
	}
});

$("#sick_leave_benefit_earned_year").change(function() {
	if ($("#sick_leave_benefit_earned_year option:selected").text() == "Calendar Year")
	{
		$("#calendar_fields").removeClass("hidden");
		$("#calendar_fields").removeClass("hidden");
	}
	else 
	{
		$("#calendar_fields").addClass("hidden");
	}
});

$("#sick_leave_benefit_provided").change(function() {
	if ($("#sick_leave_benefit_provided option:selected").text() == "In Hours")
	{
		$("#sick_leave_waiting_period_carry_over").removeClass("hidden");
		$("#employee_classifications").addClass("hidden");
	}
	else if ($("#sick_leave_benefit_provided option:selected").text() == "In Days")
	{
		$("#sick_leave_waiting_period_carry_over").addClass("hidden");
		$("#employee_classifications").removeClass("hidden");
	}
	else
	{
		$("#sick_leave_waiting_period_carry_over").addClass("hidden");
		$("#employee_classifications").addClass("hidden");
	}
});

$("input[name='sickleave[is_carry_over_allowed]']").change(function() {
	if ($(this).val() == 1)
	{
		$("#carry_over").removeClass("hidden");
	}
	else
	{
		$("#carry_over").addClass("hidden");
	}
});

$("#sick_leave_is_unused_paid_out").change(function() {
	if ($("#sick_leave_is_unused_paid_out").is(":checked"))
	{
		$("#sick_leave_is_unused_forfeited").prop('checked', false);
		$("#sick_leave_is_unused_paid_out_options").removeClass("hidden");
		$("#sick_leave_is_unused_forfeited_warning").addClass("hidden");
	}
});

$("#sick_leave_is_unused_forfeited").change(function() {
	if ($("#sick_leave_is_unused_forfeited").is(":checked"))
	{
		$("#sick_leave_is_unused_paid_out").prop('checked', false);
		$("#sick_leave_is_unused_paid_out_options").addClass("hidden");
		$("#sick_leave_is_unused_forfeited_warning").removeClass("hidden");
	}
});

$("#sick_leave_is_carry_over_unlimited").change(function() {
	if ($("#sick_leave_is_carry_over_unlimited").is(":checked"))
	{
		$("#sick_leave_is_up_to_set").prop('checked', false);
	}
});

$("#sick_leave_is_up_to_set").change(function() {
	if ($("#sick_leave_is_up_to_set").is(":checked"))
	{
		$("#sick_leave_is_carry_over_unlimited").prop('checked', false);
	}
});

$(".pair_check").change(function() {
	self = $(this);
	pair = $(self).attr("data-pair");
	if ($(self).is(":checked"))
	{
		$(pair).prop('checked', false);
	}
});