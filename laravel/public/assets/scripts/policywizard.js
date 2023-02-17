/*NEW JS*/
//onload
$('.check-box').each(function() {
	if($(this).attr('data-checkbox-checked') == 1)
	{
		$(this).prop('checked', true);
	}
});

//Classifications
//remove classification
$('form.form-horizontal').on('click', '.remove-classification', function() {
	/*
	parent = $(this).parents('.custom');
	type = parent.attr('data-field-type');
	id = parent.attr('data-field-id');
	parent.html('<input type="hidden" name="remove[]['+type+'][]" value="'+id+'" />');
	*/
	$(this).parent('div').remove();
});

//add classification
$('.add-classification').on('click', function() {
	var count = $('.classifications div').length - 2;
	$('.classifications').append(classificationTemplate(count));
});
function classificationTemplate(count){
	if(count % 2 == 0)
	{
		oswitch = "odd";
	} else {
		oswitch = "even";
	}
	/*var input = ['<div class="col-md-12 empClass '+oswitch+'" data-field-id="n-'+count+'" data-field-type="'+group+'">',*/
	var input = ['<div class="col-md-12 empClass '+oswitch+'">',
				'<a class="btn col-md-1 col-md-offset-1 remove-classification">',
					'<i class="fa fa-times"></i>',
				'</a>',
				'<p class="col-md-8">',
					'<input class="date-box form-control" type="text" placeholder="User Classification" name="addClassifications['+count+'][name]" value="">',
				'</p>'].join("\n");
	count++;
	return input;
}

//PTO
//remove pto
$('form.form-horizontal').on('click', '.remove-pto', function() {
	parent = $(this).parents('.custom');
	if(parent.hasClass('dynamic-pto'))
	{
		parent.remove();
	} else {
		id = parent.attr('data-field-id');
		parent.html('<input type="hidden" name="pto[remove][]" value="'+id+'" />');
	}
});

//add pto
$('.add-pto').on('click', function() {
	parent = $(this).parents('.pto-row');
	classification_id = $(this).attr('data-classification-id');
	count = $('#pto-custom-count').val();
	parent.find('.pto-custom .customs').append(ptoTemplate(count, classification_id));
	count++;
	$('#pto-custom-count').val(count);
	ptoStats();
	return false;
});	

function ptoTemplate(count, classifiation_id)
{
	if(count % 2 == 0)
	{
		oswitch = "even";
	} else {
		oswitch = "odd";
	}
	pto = [ '<div class="col-md-12 '+oswitch+' custom dynamic-pto" data-field-id="'+count+'">',
				'<div class="row">',
					'<a class="btn col-md-1 remove-pto" data-index="'+count+'"><i class="fa fa-times"></i></a>',
					'<div class="col-md-11 no-padding-both">',
						'After&nbsp;<input type="text" class="form-control number-box pto_value" name="pto[new]['+count+'][wait_value]">',
						'<span class="ann_year hidden">&nbsp;year<span class="years_of_service">s</span> of continuous service,</span>', 
						'<span class="cal_year hidden">&nbsp;calendar year<span class="years_of_service">s</span> of service,&nbsp;</span>',
						'<input type="text" class="form-control number-box" name="pto[new]['+count+'][value_earned]">&nbsp;',
						'<input type="text" class="form-control number-box earned_interval" name="pto[new]['+count+'][pto_interval]" readonly>&nbsp;',
						'of <span class="earning_type_pto">PTO</span><span class="earning_type_vacation">Vacation</span> earned per <span class="hour_worked hidden">Hour Worked</span>',
						'<span class="year_worked">Year</span>.',
						'<input type="hidden" name="pto[new]['+count+'][classification_id]" value="'+classification_id+'">',
					'</div>',
				'</div>',
			'</div>'].join("\n");
	return pto;
}
function ptoStats(){
	if( $('#vacation_earning_benefit_earned_on').val() == "calendar_year" ){
		$('.cal_year').removeClass('hidden');
	}else{
		$('.ann_year').removeClass('hidden');
	}
	if( $('#vacation_earning_benefit_provided').val() == 'hours' ){
		$('.earned_interval').val('Hours');
	}else if( $('#vacation_earning_benefit_provided').val() == 'days' ){
		$('.earned_interval').val('Days');
	}else if( $('#vacation_earning_benefit_provided').val() == 'weeks' ){
		$('.earned_interval').val('Weeks');
	}
	if( $('#vacation_earning_benefit_type').val() == 'vacation' ){
		$('.earning_type_pto').addClass('hidden');
		$('.earning_type_vacation').removeClass('hidden');
	}else if( $('#vacation_earning_benefit_type').val() == 'pto' ){
		$('.earning_type_pto').removeClass('hidden');
		$('.earning_type_vacation').addClass('hidden');
	}
	if( $('.are_benefits_earned').val() == 'per_hour' ){
		$('.hour_worked').removeClass( 'hidden' );
		$('.year_worked').addClass( 'hidden' );
	} else {
		$('.hour_worked').addClass( 'hidden' );
		$('.year_worked').removeClass( 'hidden' );
	}
}

//SICK LEAVE
//remove sick-leave
$('form.form-horizontal').on('click', '.remove-sick-leave', function() {
	parent = $(this).parents('.custom');
	type = parent.attr('data-field-type');
	id = parent.attr('data-field-id');
	parent.html('<input type="hidden" name="remove[sick_leave]['+type+'][]" value="'+id+'" />');
	
});

//add sick-leave
$('.add-sick-leave').on('click', function() {
	parent = $(this).parents('.sick-leave-row');
	group = $(this).attr('data-group');
	count = parent.find('.customs').attr('data-field-count');
	parent.find('.customs').append(sickLeaveTemplate(group, count));
	count++;
	parent.find('.customs').attr('data-field-count', count);
	return false;
});	

function sickLeaveTemplate(group, count)
{
	if(count % 2 == 0)
	{
		oswitch = "even";
	} else {
		oswitch = "odd";
	}
	pto = [ '<div class="col-md-12 '+oswitch+' custom dynamic-pto" data-field-id="n-'+count+'" data-field-type="'+group+'">',
				'<div class="row">',
					'<a class="btn col-md-1 remove-sick-leave" data-group="'+group+'"><i class="fa fa-times"></i></a>',
					'<div class="col-md-11  no-padding-both">',
						'After&nbsp;',
						'<input type="text" name="sick_leave['+group+']['+count+'][wait_sick_leave_value]" class="form-control number-box">',
						'&nbsp;Years, receives&nbsp;',
						'<input type="text" name="sick_leave['+group+']['+count+'][sick_leave_value_earned]" class="form-control number-box">',
						'&nbsp;',
						'<input type="text" name="sick_leave['+group+']['+count+'][sick_leave_value_earned_interval]" class="form-control number-box">',
						'&nbsp;of Sick Leave earned per Year',
					'</div>',
				'</div>',
			'</div>'].join("\n");
	return pto;
}

//Holidays
//add holiday
$('.add-holiday').on('click', function() {			
	var count = $('.holidays .holiday').length;
	$('.holidays').append(holidayTemplate(count));
});

function holidayTemplate(count)
{
	if(count % 2 == 0)
	{
		oswitch = "even";
	} else {
		oswitch = "odd";
	}
	var holiday = [
				'<div class="col-md-12 holiday '+oswitch+'">',
					'<div class="row">',
						'<div class="col-md-3 text-center">',
							'<input id="sick_leave_holiday_rosh_hashanah" class="check-box" type="checkbox" name="holidays[new]['+count+'][enabled]" value="1">',
						'</div>',
						'<div class="col-md-4"><input type="text" class="form-control" name="holidays[new]['+count+'][name]" value=""></div>',
						'<div class="col-md-4"><input type="text" class="form-control" name="holidays[new]['+count+'][info]" value=""></div>',
					'</div>',
				'</div>'
				].join("\n");	
			
	return holiday;
}

/*old js*/
function setSelectValues()
{
	$('select').each(function() {
		select = $(this).attr('data-selected-value');
		$(this).children('option[value='+select+']').prop('selected', true);
		$(this).prop('disabled', false);
	});
}
function setRadioValues()
{
	$('.radio_benefits').each(function() {
		value = $(this).attr('data-radio-select');
		
		if(value == "on" || value == 1)
		{
			$(this).find(':radio[value=1]').prop('checked', true);
		} else {
			$(this).find(':radio[value=0]').prop('checked', true);
		}
	});
}
function setCheckValues()
{
	$('.not-offered').each(function() {
		if($(this).prop('checked'))
		{
			$('.offered-container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		}
	});
	$('.same_as_fulltime').each(function() {
		if($(this).prop('checked'))
		{
			$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		}
	});
	$('.does_not_receive').each(function() {
		if($(this).prop('checked'))
		{
			$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		}
	});
}
$(document).ready(function() {
	$('select').prop('disabled', true);
	setSelectValues();
	setRadioValues();
	setCheckValues();
	dentalBenefitSelect();
});

$('.not-offered').on('change', function() {
	if($(this).prop('checked'))
	{
		$('.offered-container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
	} else {
		$('.offered-container').children('.overlay').remove();
	}
});
$('.same_as_fulltime').on('change', function() {
	var id = $(this).attr('data-checkbox-id');
	if($(this).prop('checked'))
	{
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$(this).parents('.row').children().children('.does_not_receive').prop('checked', false);
		$('.'+id+'_do_not_receive').prop('checked', false).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
	} else {
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
	}
});
$('.does_not_receive').on('change', function() {
	var id = $(this).attr('data-checkbox-id');
	if($(this).prop('checked'))
	{
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$(this).parents('.sub-content').children().children('.does_not_receive_container')
				.find('input').each( function(){
					if( $(this).val() == '' ){
						$(this).val( 0 );
					}
				});
		//$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$(this).parents('.row').children().children('.same_as_fulltime').prop('checked', false);
		$('.'+id+'_do_not_receive').prop('checked', true).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$('.'+id+'_does_not_receive').prop('checked', true).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$('.'+id+'_earning_pto_does_not_receive').prop('checked', true).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
	} else {
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$('.'+id+'_do_not_receive').prop('checked', false).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$('.'+id+'_does_not_receive').prop('checked', false).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$('.'+id+'_earning_pto_does_not_receive').prop('checked', false).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
	}
});

$('.faux-radio').on('change', function() {
	var input = $(this).attr('data-input');
	$(this).parents('.row').children().children('.faux-radio').prop('checked', false);
	$(this).prop('checked', true);
	if(input == "yes")
	{
		$(this).parents('.row').children('.faux-radio-container').removeClass('hidden');
	} else {
		$(this).parents('.row').children('.faux-radio-container').addClass('hidden');
	}
});

$('#dental_benefits_type').on('change', function() {
	$(this).attr('data-selected-value', $(this).val());
	dentalBenefitSelect();
});
function dentalBenefitSelect()
{
	selected = $('#dental_benefits_type').attr('data-selected-value');
	if(selected == "pdba" || selected == "")
	{
		$('#pdba').show();
		$('#discount').hide();
		$('#non-dental').hide();
	}
	else if(selected == "discount")
	{
		$('#discount').show();
		$('#pdba').hide();
		$('#non-dental').hide();
	}
	else
	{
		$('#discount').hide();
		$('#pdba').hide();
		$('#non-dental').show();
	}
}

/*Modal launch button*/
$('.btn-modal').on('click', function() {
	$('.info').addClass('hidden');
	var editId = $(this).attr('id');
	$('.'+editId+'').removeClass('hidden');
	$('#modal').modal('show');
	$('#modal').find('.modal-footer a').attr('href', $(this).attr('data-target'));
	return false;
});
/*Default hours confirmation*/
$('.default_blur').on('blur change', function() {
	$('.default_toggle').addClass('hidden');
	$('.default_confirm').removeClass('hidden');
});
$('.default_toggle').on('click', function() {
	$('.default_toggle').addClass('hidden');
	$('.default_confirm').removeClass('hidden');
	$('.default_confirm_warning').removeClass('hidden');
});
/*Page load Policy scripts*/
$(document).ready(function () {
	$('.waive_yes').each(function() {
		if( $(this).prop('checked') ){
			$(this).parents('.row').children('.waived-yes').removeClass('hidden');
		}else{
			$(this).parents('.row').children('.waived-yes').addClass('hidden');
		}
	});
	if( $('.dental_pdba_type').val() == "" ){
		$(this).parents('.waived-yes').children('.family_pdba').addClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').addClass('hidden');
	}else if( $('.dental_pdba_type').val() == "pdba" ){
		$(this).parents('.waived-yes').children('.family_pdba').removeClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').addClass('hidden');
	}else if( $('.dental_pdba_type').val() == "discount" ){
		$(this).parents('.waived-yes').children('.family_pdba').addClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').removeClass('hidden');
	}
	initial_calendar_year();
	step_1();
	step_2();
	earning_benefit_earned_on();
	
	if( $('#vacation_earning_is_carry_over_allowed_yes').prop( "checked" ) == true ){
		$('.carry-yes').removeClass('hidden');
	}
	if( $('#vacation_earning_is_unused_benefits_paid_out').prop( "checked" ) == true ){
		$('.paid_out').removeClass('hidden');
	}
	if( $('#vacation_earning_is_unused_benefits_paid_out').prop( "checked" ) == true ){
		$('.paid_out').removeClass('hidden');
		$('.forfeited-yes').addClass('hidden');
	}else if( $('#vacation_earning_is_benefits_forfeited').prop( "checked" ) == true ){
		$('.paid_out').addClass('hidden');
		$('.forfeited-yes').removeClass('hidden');
	}
	$('.pto_value').each( function(){
		if( $(this).val() == 1 ){
			$(this).parent('div').find('.years_of_service').addClass('hidden');
		} else {
			$(this).parent('div').find('.years_of_service').removeClass('hidden');
		}
	});
	/*Sick Leave*/
	if( $('#sick_leave_benefit_earned_year').val() == "calendar_year" ){
		$('.calendar_year').removeClass('hidden');
	}
	if( $('.forfeited_yes').prop( "checked" ) == true ){
		$('.paid_out').addClass('hidden');
		$('.forfeited-yes').removeClass('hidden');
	}else{
		$('.paid_out').removeClass('hidden');
		$('.forfeited-yes').addClass('hidden');
	}
	
	if( $('.carry-over.carry_yes').prop( "checked" ) == true ){
		$('.carry-yes').removeClass('hidden');
	}
});
/*On use Policy scripts*/
$('.waived').on('change', function() {
	if( $(this).val() == 1 ){
		$(this).parents('.row').children('.waived-yes').removeClass('hidden');
	}else{
		$(this).parents('.row').children('.waived-yes').addClass('hidden');
	}
});

/*Dental PDBA*/
$('.dental_pdba_type').on('change', function() {
	if( $(this).val() == "" ){
		$(this).parents('.waived-yes').children('.family_pdba').addClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').addClass('hidden');
	}else if( $(this).val() == "pdba" ){
		$(this).parents('.waived-yes').children('.family_pdba').removeClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').addClass('hidden');
	}else if( $(this).val() == "discount" ){
		$(this).parents('.waived-yes').children('.family_pdba').addClass('hidden');
		$(this).parents('.waived-yes').children('.family_discount').removeClass('hidden');
	}
});

/*Pto-Vacation*/
$('.initial_calendar_yea-r').on('change', function() {
	initial_calendar_year();
});
function initial_calendar_year(){
	if( $('.calendar_year_yes').prop('checked') )
	{
		$('.initial_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
	} else {
		$('.initial_container').children('.overlay').remove();
	}
}

$('form.form-horizontal').on('change', '.step_1', function() {
	step_1();
});
function step_1()
{
	var step_1 = 0;
	var steps = 0;
	$('.step_1').each(function(e){
		steps++
		if( $(this).val() != '' ){
			step_1++
		}else{
			step_1 = 0
		}
	});
	/*if( $('#vacation_earning_benefit_type').val() == '' || $('#vacation_earning_how_are_benefits_earned').val() == '' || $('#vacation_earning_benefit_earned_on').val() == '' || $('#vacation_earning_benefit_provided').val() == '' ){*/
	if( step_1 != steps ){
		$('.employee_classifications').addClass('hidden');
		$('.step-3').addClass('hidden');
	}else{
		if( $('.earning_benefit_provided').val() == 'hours' ){
			$('.employee_classifications').addClass('hidden');
			$('.step-3').removeClass('hidden');
			$('.in_days').removeAttr('selected').addClass('hidden');
			$('.in_weeks').removeAttr('selected').addClass('hidden');
			$('.earned_interval').val('Hours');
		}else if( $('.earning_benefit_provided').val() == 'days' ){
			$('.employee_classifications').removeClass('hidden');
			$('.benefit_weeks').addClass('hidden');
			$('.step-3').addClass('hidden');
			$('.in_days').removeClass('hidden');
			$('.earned_interval').val('Days');
		}else if( $('.earning_benefit_provided').val() == 'weeks' ){
			$('.employee_classifications').removeClass('hidden');
			$('.benefit_weeks').removeClass('hidden');
			$('.step-3').addClass('hidden');
			$('.in_days').removeClass('hidden');
			$('.in_weeks').removeClass('hidden');
			$('.earned_interval').val('Weeks');
		}else{
			$('.employee_classifications').addClass('hidden');
			$('.benefit_weeks').addClass('hidden');
			$('.step-3').addClass('hidden');
			$('.in_days').addClass('hidden');
			$('.in_weeks').addClass('hidden');
			$('.earned_interval').val('Hours');
		}
		step_2();
		if( $('.earning_benefit_type').val() == 'vacation' ){
			$('.earning_type_pto').addClass('hidden');
			$('.earning_type_vacation').removeClass('hidden');
		}else if( $('.earning_benefit_type').val() == 'pto' ){
			$('.earning_type_pto').removeClass('hidden');
			$('.earning_type_vacation').addClass('hidden');
		}
	}
}

$('.are_benefits_earned').on('change', function() {
	if( this.value == 'per_hour' ){
		$('.hour_worked').removeClass( 'hidden' );
		$('.year_worked').addClass( 'hidden' );
	} else {
		$('.hour_worked').addClass( 'hidden' );
		$('.year_worked').removeClass( 'hidden' );
	}
});

$('form.form-horizontal').on('keyup blur click', '.step_2', function() {
	if( $('.does_not_receive').prop( "checked" ) == true ){
		id = $(this).attr('data-checkbox-id');
		$('.step_2.'+id+'').each( function(){
			if( $(this).val() == '' ){
				$(this).val( 0 );
			}
		});
	}
	step_2();
});
function step_2(){
	if( $('.earning_benefit_provided').val() == 'days' ){
		var step_3 = 0;
		var days = 0;
		$('.step_2_a').each(function(e){
			days++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == days ){
			$('.step-3').removeClass('hidden');
		}else{
			$('.step-3').addClass('hidden');
		}
	}else if( $('.earning_benefit_provided').val() == 'weeks' ){
		var step_3 = 0;
		var weeks = 0;
		$('.step_2_b').each(function(e){
			weeks++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == weeks ){
			$('.step-3').removeClass('hidden');
		}else{
			$('.step-3').addClass('hidden');
		}
	}
}

$('.as_earn').on('change', function(){
	if( $(this).val() == 1 ){
		$('.start_using').val( 0 ).prop('readonly', true);
		/*$('.start_using').val( $('.start_earning').val() ).prop('readonly', true);*/
		$('.start_using_interval').prop('disabled', true);
		/*$('.start_using_interval').val( $('.start_earning_interval').val() ).prop('disabled', true);*/
		$('.is_recurring').val( 0 ).prop('checked', true).prop('disabled', true);
	}else{
		$('.start_using').prop('readonly', false);
		$('.start_using_interval').prop('disabled', false);
		$('.is_recurring').prop('disabled', false);
	}
});

$('.start_earning').on('keyup', function(){
	if( $('.as_earn:checked').val() == 1 ){
		$('.start_using').val( $('.start_earning').val() );
		$('.start_using').val( $('.start_earning').val() );
	}
});

$('#vacation_earning_waiting_period_to_start_earning_interval').on('change', function(){
	if( $('.as_earn:checked').val() == 1 ){
		$('#vacation_earning_waiting_period_to_start_using_interval').prop('disabled', false);
		$('#vacation_earning_waiting_period_to_start_using_interval').val( $('#vacation_earning_waiting_period_to_start_earning_interval').val() ).prop('disabled', true);
	}
});

$('.earning_benefit_earned_on').on('change', function() {
	earning_benefit_earned_on();
});
function earning_benefit_earned_on(){
	if( $('.earning_benefit_earned_on').val() == "calendar_year" ){
		$('.calendar_year').removeClass('hidden');
		$('.calendar_as_earn').addClass('hidden');
		$('.cal_recurring').addClass('hidden');
		$('.cal_year').removeClass('hidden');
		$('.ann_year').addClass('hidden');
		$('.cal_year_step').addClass('step_1');
		step_1();
		firstYearNone();
	}else{
		$('.calendar_year').addClass('hidden');
		$('.calendar_as_earn').removeClass('hidden');
		$('.cal_recurring').removeClass('hidden');
		$('.cal_year').addClass('hidden');
		$('.ann_year').removeClass('hidden');
	}
}

$('#vacation_earning_benefit_first_year_accrual').on('change', function() {
	firstYearNone();
});
function firstYearNone(){
	if( $('#vacation_earning_benefit_first_year_accrual').val() == 'none' ){
		$('.first_year_none').addClass('hidden');
	}else{
		$('.first_year_none').removeClass('hidden');
	}
}

$('.carry-over').on('change', function() {
	if( $(this).hasClass('carry_yes') ){
		$('.carry-yes').removeClass('hidden');
	}else{
		$('.carry-yes').addClass('hidden');
	}
});

$('.carry-over-amount').on('change', function() {
	$('.carry-over-amount').prop('checked', false);
	$(this).prop('checked', true);
});

$('.unused-benefits').on('change', function() {
	$('.unused-benefits').prop('checked', false);
	$(this).prop('checked', true);
	if( $('.forfeited_yes').prop( "checked" ) == true ){
		$('.paid_out').addClass('hidden');
		$('.forfeited-yes').removeClass('hidden');
	}else{
		$('.paid_out').removeClass('hidden');
		$('.forfeited-yes').addClass('hidden');
	}
});

$('#vacation_earning_waiting_period_to_start_earning').on('keyup', function() {
	$('#fulltime_base_pto_value_earned').val( $(this).val() )
});

$('#vacation_earning_waiting_period_to_start_earning_interval').on('change', function() {
	$('#fulltime_base_pto_value_earned_interval').val( $(this).val() )
});

$('#vacation_earning_how_are_benefits_earned').on('change', function() {
	$('#fulltime_base_pto_interval').val( $(this).val() )
});

$('#vacation_earning_is_unused_benefits_paid_out').on('change', function() {
	if( $('#vacation_earning_is_unused_benefits_paid_out').prop( "checked" ) == true ){
		$('.paid_out').removeClass('hidden');
		$('.forfeited-yes').addClass('hidden');
	}else if( $('#vacation_earning_is_benefits_forfeited').prop( "checked" ) == true ){
		$('.paid_out').addClass('hidden');
		$('.forfeited-yes').removeClass('hidden');
	}
});

$('form.form-horizontal').on('keyup', '.pto_value', function() {
	if(	$(this).val() == 1 ){
		$(this).parent('div').children().children('.years_of_service').addClass('hidden');
	} else {
		$(this).parent('div').children().children('.years_of_service').removeClass('hidden');
	}
});

/*Sick Leave*/
$('.benefits-provided').on('change', function() {
	$('.benefits-provided').prop('checked', false);
	$(this).prop('checked', true);
	if( $('#sick_leave_is_benefits_provided_earned').prop( "checked" ) == true ){
		$('.benefits_earned').removeClass('hidden');
		$('.lump_earned').addClass('hidden');
		$('.are_benefits_earned').addClass('step_1');
	}else{
		$('.benefits_earned').addClass('hidden');
		$('.lump_earned').removeClass('hidden');
		$('.are_benefits_earned').removeClass('step_1');
	}
	step_1();
});
$('#sick_leave_benefit_earned_year').on('change', function() {
	if( $('#sick_leave_benefit_earned_year').val() == "calendar_year" ){
		$('.calendar_year').removeClass('hidden');
	}else{
		$('.calendar_year').addClass('hidden');
	}
});
$('#sick_leave_is_carry_over_allowed_yes').on('change', function() {
	if( $('#sick_leave_is_carry_over_allowed_yes').prop( "checked" ) == true ){
		$('.carry_over').removeClass('hidden');
	}else{
		$('.carry_over').addClass('hidden');
	}
});
$('#sick_leave_is_unused_paid_out').on('change', function() {
	if( $('#sick_leave_is_unused_paid_out').prop( "checked" ) == true ){
		$('.paid_out').removeClass('hidden');
	}else{
		$('.paid_out').addClass('hidden');
	}
});
$('#sick_leave_is_benefits_provided_earned').on('change', function() {
	if( $('#sick_leave_is_benefits_provided_earned').prop( "checked" ) == true ){
		$('.earned').removeClass('hidden');
	}else{
		$('.earned').addClass('hidden');
	}
});

/*jQuery Validator*/
$(document).ready(function(){
	$("form.form-horizontal").validate({
		rules: {
			number_of_employees: {
				required: true,
				number: true,
				//min: 1,
				//max: 99999,
			}
		},
		messages: {
			number_of_employees: {
				required: "Enter a number between 1-99999",
				number: "Enter a valid number between 1-99999",
			}
		},
		//showErrors: function(errorMap, errorList) {
		//	$("#error-summary").html("Your form contains "
		//	+ this.numberOfInvalids()
		//	+ " errors, see details below.");
		//	this.defaultShowErrors();
		//},
		//submitHandler: function(form) {
		//	form.submit();
		//}
	});
});