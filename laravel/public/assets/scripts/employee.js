/*NEW JS*/
//onload
$('.check-box').each(function() {
	if($(this).attr('data-checkbox-checked') == 1)
	{
		$(this).prop('checked', true);
	}
});

//PTO
//remove pto
$('form.form-horizontal').on('click', '.remove-pto', function() {
	parent = $(this).parents('.custom');
	type = parent.attr('data-field-type');
	id = parent.attr('data-field-id');
	parent.html('<input type="hidden" name="remove[pto]['+type+'][]" value="'+id+'" />');
});

//add pto
$('.add-pto').on('click', function() {
	parent = $(this).parents('.pto-row');
	group = $(this).attr('data-group');
	count = parent.find('.customs').attr('data-field-count');
	parent.find('.pto-custom .customs').append(ptoTemplate(group, count));
	count++;
	parent.find('.customs').attr('data-field-count', count);
	ptoStats();
	return false;
});	

function ptoTemplate(group, count)
{
	if(count % 2 == 0)
	{
		oswitch = "even";
	} else {
		oswitch = "odd";
	}
	pto = [ '<div class="col-md-12 '+oswitch+' custom dynamic-pto" data-field-id="n-'+count+'" data-field-type="'+group+'">',
				'<div class="row">',
					'<a class="btn col-md-1 remove-pto" data-group="'+group+'"><i class="fa fa-times"></i></a>',
					'<div class="col-md-11 no-padding-both">',
						'<p>',
						'&nbsp;&nbsp;&nbsp;After',
						'&nbsp;&nbsp;&nbsp;<input type="number" min="1" max="9999" step="0.01" class="form-control number-box pto_value" name="pto['+group+']['+count+'][wait_pto_value]">',
						'<span class="ann_year hidden">year<span class="years_of_service">s</span>&nbsp;of continuous service,</span><span class="cal_year hidden">calendar year<span class="years_of_service">s</span>&nbsp;of service,</span>&nbsp;',
						'</p>',
						'<p>',
						'<input type="number" min="1" max="9999" step="0.01" class="form-control number-box" name="pto['+group+']['+count+'][pto_value_earned]">',
						'<input type="text" class="form-control number-box earned_interval" name="pto['+group+']['+count+'][pto_value_earned_interval]" readonly>',
						'&nbsp;of&nbsp;<span class="earning_type_pto">PTO</span><span class="earning_type_vacation">Vacation</span>&nbsp;earned per&nbsp;<span class="hour_worked hidden">Hour Worked</span><span class="year_worked">Year</span>.',
						'</p>',
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

/*Licensure / Certification*/
$('.add-cert').on('click', function() {
	$('.empLicenseCertification').append(certTemplate());
	var options = $('.master-select > option').clone();
	$('.clone-select').append(options).removeClass('clone-select');
	return false;
});
function certTemplate()
{
	var count = 0;
	$('.new-cert').each(function(){
		count++;
	});
	cert = ['<tr class="cert-row">',
				'<td>',
					'<select class="form-control date-box clone-select certType" id="type" name="licensure['+count+'][type]">',
					'</select>',
				'</td>',
				'<td class="new-certs">',
					'<table class="new-cert-table">',
						'<tbody>',
							'<tr>',
								'<td>',
									'<div class="input-group">',
										'<input type="text" class="form-control date-picker date-box new-cert" id="new-cert-'+count+'" name="licensure['+count+'][expiration]" value="" placeholder="mm/dd/yyyy">',
										'<span class="input-group-addon">',
											'<label for="new-cert-'+count+'"><i class="fa fa-calendar"></i></label>',
										'</span>',
									'</div>',
								'</td>',
							'</tr>',
							'<tr class="newCertName hidden">',
								'<td class="padding-top">',
									'<input type="text" class="form-control" id="licensure_certification_type" name="licensure['+count+'][addType]" placeholder="Licensure / Certification Type">',
								'</td>',
							'</tr>',
						'</tbody>',
					'</table>',
				'</td>',
				'<td class="text-right">',
					'<button type="button" class="btn btn-default btn-primary btn-xs remove-cert">DELETE</button>',
				'</td>',
			'</tr>'].join("\n");
	return cert;
}
$('.empLicenseCertification').on('change', '.certType', function() {
	if ($(this).val() == 'add_new'){
		$(this).parents('.cert-row').find('.newCertName').removeClass('hidden');
	}else{
		$(this).parents('.cert-row').find('.newCertName').addClass('hidden');
	}
});
$('.empLicenseCertification').on('click', '.remove-cert', function() {
	$(this).parents('.cert-row').remove();
});

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
			$(this).parents('.row').next('.offered-container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
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
		$(this).parents('.row').next('.offered-container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
	} else {
		$(this).parents('.row').next('.offered-container').children('.overlay').remove();
	}
});
$('.same_as_fulltime').on('change', function() {
	if($(this).prop('checked'))
	{
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$(this).parents('.row').children().children('.does_not_receive').prop('checked', false);
	} else {
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
	}
});
$('.does_not_receive').on('change', function() {
	if($(this).prop('checked'))
	{
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
		$(this).parents('.sub-content').children().children('.does_not_receive_container')
				.find('input').each( function(){
					if( $(this).val() == '' ){
						$(this).val( 0 );
					}
				});
		$(this).parents('.sub-content').children().children('.does_not_receive_container').append('<div style="background:#000; width: 100%; height: 100%; position: absolute;top: 0;left:0; opacity:0.2;" class="overlay"></div>');
		$(this).parents('.row').children().children('.same_as_fulltime').prop('checked', false);
	} else {
		$(this).parents('.sub-content').children().children('.does_not_receive_container').children('.overlay').remove();
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
	sick_step_1();
	step_2();
	sick_step_2();
	earning_benefit_earned_on();
	sick_earning_benefit_earned_on();
	
	if( $('#vacation_earning_is_carry_over_allowed_yes').prop( "checked" ) == true ){
		$('.carry_over').removeClass('hidden');
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

$('form.ptoVacation').on('change', '.step_1', function() {
	step_1();
});
function step_1()
{
	var step_1 = 0;
	var steps = 0;
	$('.ptoVacation .step_1').each(function(e){
		steps++
		if( $(this).val() != '' ){
			step_1++
		}else{
			step_1 = 0
		}
	});
	if( step_1 != steps ){
		$('.ptoVacation .employee_classifications').addClass('hidden');
		$('.ptoVacation .step-3').addClass('hidden');
	}else{
		if( $('.ptoVacation .earning_benefit_provided').val() == 'hours' ){
			$('.ptoVacation .employee_classifications').addClass('hidden');
			$('.ptoVacation .step-3').removeClass('hidden');
			$('.ptoVacation .in_days').removeAttr('selected').addClass('hidden');
			$('.ptoVacation .in_weeks').removeAttr('selected').addClass('hidden');
			$('.ptoVacation .earned_interval').val('Hours');
		}else if( $('.ptoVacation .earning_benefit_provided').val() == 'days' ){
			$('.ptoVacation .employee_classifications').removeClass('hidden');
			$('.ptoVacation .benefit_weeks').addClass('hidden');
			$('.ptoVacation .step-3').addClass('hidden');
			$('.ptoVacation .in_days').removeClass('hidden');
			$('.ptoVacation .earned_interval').val('Days');
		}else if( $('.ptoVacation .earning_benefit_provided').val() == 'weeks' ){
			$('.ptoVacation .employee_classifications').removeClass('hidden');
			$('.ptoVacation .benefit_weeks').removeClass('hidden');
			$('.ptoVacation .step-3').addClass('hidden');
			$('.ptoVacation .in_days').removeClass('hidden');
			$('.ptoVacation .in_weeks').removeClass('hidden');
			$('.ptoVacation .earned_interval').val('Weeks');
		}else{
			$('.ptoVacation .employee_classifications').addClass('hidden');
			$('.ptoVacation .benefit_weeks').addClass('hidden');
			$('.ptoVacation .step-3').addClass('hidden');
			$('.ptoVacation .in_days').addClass('hidden');
			$('.ptoVacation .in_weeks').addClass('hidden');
			$('.ptoVacation .earned_interval').val('Hours');
		}
		step_2();
		if( $('.ptoVacation .earning_benefit_type').val() == 'vacation' ){
			$('.ptoVacation .earning_type_pto').addClass('hidden');
			$('.ptoVacation .earning_type_vacation').removeClass('hidden');
		}else if( $('.ptoVacation .earning_benefit_type').val() == 'pto' ){
			$('.ptoVacation .earning_type_pto').removeClass('hidden');
			$('.ptoVacation .earning_type_vacation').addClass('hidden');
		}
	}
}

$('.ptoVacation .are_benefits_earned').on('change', function() {
	if( this.value == 'per_hour' ){
		$('.ptoVacation .hour_worked').removeClass( 'hidden' );
		$('.ptoVacation .year_worked').addClass( 'hidden' );
	} else {
		$('.ptoVacation .hour_worked').addClass( 'hidden' );
		$('.ptoVacation .year_worked').removeClass( 'hidden' );
	}
});

$('form.ptoVacation').on('keyup blur click', '.step_2', function() {
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
	if( $('.ptoVacation .earning_benefit_provided').val() == 'days' ){
		var step_3 = 0;
		var days = 0;
		$('.ptoVacation .step_2_a').each(function(e){
			days++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == days ){
			$('.ptoVacation .step-3').removeClass('hidden');
		}else{
			$('.ptoVacation .step-3').addClass('hidden');
		}
	}else if( $('.ptoVacation .earning_benefit_provided').val() == 'weeks' ){
		var step_3 = 0;
		var weeks = 0;
		$('.ptoVacation .step_2_b').each(function(e){
			weeks++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == weeks ){
			$('.ptoVacation .step-3').removeClass('hidden');
		}else{
			$('.ptoVacation .step-3').addClass('hidden');
		}
	}
}

$('form.sickLeave').on('change', '.step_1', function() {
	sick_step_1();
});
function sick_step_1()
{
	var step_1 = 0;
	var steps = 0;
	$('.sickLeave .step_1').each(function(e){
		steps++
		if( $(this).val() != '' ){
			step_1++
		}else{
			step_1 = 0
		}
	});
	if( step_1 != steps ){
		$('.sickLeave .employee_classifications').addClass('hidden');
		$('.sickLeave .step-3').addClass('hidden');
	}else{
		if( $('.sickLeave .earning_benefit_provided').val() == 'hours' ){
			$('.sickLeave .employee_classifications').addClass('hidden');
			$('.sickLeave .step-3').removeClass('hidden');
			$('.sickLeave .in_days').removeAttr('selected').addClass('hidden');
			$('.sickLeave .in_weeks').removeAttr('selected').addClass('hidden');
			$('.sickLeave .earned_interval').val('Hours');
		}else if( $('.sickLeave .earning_benefit_provided').val() == 'days' ){
			$('.sickLeave .employee_classifications').removeClass('hidden');
			$('.sickLeave .benefit_weeks').addClass('hidden');
			$('.sickLeave .step-3').addClass('hidden');
			$('.sickLeave .in_days').removeClass('hidden');
			$('.sickLeave .earned_interval').val('Days');
		}else if( $('.sickLeave .earning_benefit_provided').val() == 'weeks' ){
			$('.sickLeave .employee_classifications').removeClass('hidden');
			$('.sickLeave .benefit_weeks').removeClass('hidden');
			$('.sickLeave .step-3').addClass('hidden');
			$('.sickLeave .in_days').removeClass('hidden');
			$('.sickLeave .in_weeks').removeClass('hidden');
			$('.sickLeave .earned_interval').val('Weeks');
		}else{
			$('.sickLeave .employee_classifications').addClass('hidden');
			$('.sickLeave .benefit_weeks').addClass('hidden');
			$('.sickLeave .step-3').addClass('hidden');
			$('.sickLeave .in_days').addClass('hidden');
			$('.sickLeave .in_weeks').addClass('hidden');
			$('.sickLeave .earned_interval').val('Hours');
		}
		sick_step_2();
		if( $('.sickLeave .earning_benefit_type').val() == 'vacation' ){
			$('.sickLeave .earning_type_pto').addClass('hidden');
			$('.sickLeave .earning_type_vacation').removeClass('hidden');
		}else if( $('.sickLeave .earning_benefit_type').val() == 'pto' ){
			$('.sickLeave .earning_type_pto').removeClass('hidden');
			$('.sickLeave .earning_type_vacation').addClass('hidden');
		}
	}
}

$('.sickLeave .are_benefits_earned').on('change', function() {
	if( this.value == 'per_hour' ){
		$('.sickLeave .hour_worked').removeClass( 'hidden' );
		$('.sickLeave .year_worked').addClass( 'hidden' );
	} else {
		$('.sickLeave .hour_worked').addClass( 'hidden' );
		$('.sickLeave .year_worked').removeClass( 'hidden' );
	}
});

$('form.sickLeave').on('keyup blur click', '.step_2', function() {
	if( $('.does_not_receive').prop( "checked" ) == true ){
		id = $(this).attr('data-checkbox-id');
		$('.step_2.'+id+'').each( function(){
			if( $(this).val() == '' ){
				$(this).val( 0 );
			}
		});
	}
	sick_step_2();
});
function sick_step_2(){
	if( $('.sickLeave .earning_benefit_provided').val() == 'days' ){
		var step_3 = 0;
		var days = 0;
		$('.sickLeave .step_2_a').each(function(e){
			days++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == days ){
			$('.sickLeave .step-3').removeClass('hidden');
		}else{
			$('.sickLeave .step-3').addClass('hidden');
		}
	}else if( $('.sickLeave .earning_benefit_provided').val() == 'weeks' ){
		var step_3 = 0;
		var weeks = 0;
		$('.sickLeave .step_2_b').each(function(e){
			weeks++
			if( $(this).val() != '' ){
				step_3++
			}else{
				step_3 = 0
			}
		});
		if( step_3 == weeks ){
			$('.sickLeave .step-3').removeClass('hidden');
		}else{
			$('.sickLeave .step-3').addClass('hidden');
		}
	}
}

$('.as_earn').on('change', function(){
	if( $(this).val() == 1 ){
		$('.start_using').val( 0 ).prop('readonly', true);
		$('.start_using_interval').prop('disabled', true);
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

$('.sick_earning_benefit_earned_on').on('change', function() {
	sick_earning_benefit_earned_on();
});
function sick_earning_benefit_earned_on(){
	if( $('.sick_earning_benefit_earned_on').val() == "calendar_year" ){
		$('.sick_calendar_year').removeClass('hidden');
		$('.sick_cal_year_step').addClass('step_1');
		sick_step_1();
	}else{
		$('.sick_calendar_year').addClass('hidden');
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

$('.sick_unused-benefits').on('change', function() {
	$('.sick_unused-benefits').prop('checked', false);
	$(this).prop('checked', true);
	if( $('.sick_forfeited_yes').prop( "checked" ) == true ){
		$('.sick_paid_out').addClass('hidden');
		$('.sick_forfeited-yes').removeClass('hidden');
	}else{
		$('.sick_paid_out').removeClass('hidden');
		$('.sick_forfeited-yes').addClass('hidden');
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
	if( $('#parttime_vacation_earning_pto_same_as_full').prop( "checked" ) == true ){
		$('#parttime_base_pto_value_earned').val( $('#').val() );
		$('#parttime_base_pto_value_earned_interval').val( $('#').val() );
		$('#parttime_base_pto_interval').val( $('#').val() );
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

$(document).ready(function () {
	$("select#employee_state option[value='{employee_state}']").prop("selected", true);
	$("select#employee_phone1_type option[value='{employee_phone1_type}']").prop("selected", true);
	$("select#employee_phone2_type option[value='{employee_phone2_type}']").prop("selected", true);
	$("select#emergency_primary_phone1_type option[value='{emergency_primary_phone1_type}']").prop("selected", true);
	$("select#emergency_primary_phone2_type option[value='{emergency_primary_phone2_type}']").prop("selected", true);
	$("select#emergency_primary_phone3_type option[value='{emergency_primary_phone3_type}']").prop("selected", true);
	$("select#emergency_p_relationship_type option[value='{emergency_p_relationship_type}']").prop("selected", true);
		if($('#emergency_p_relationship_type').val()=='other'){
			$('.primary_other_contact').removeClass('hidden');
		}
	$("select#emergency_secondary_phone1_type option[value='{emergency_secondary_phone1_type}']").prop("selected", true);
	$("select#emergency_secondary_phone2_type option[value='{emergency_secondary_phone2_type}']").prop("selected", true);
	$("select#emergency_secondary_phone3_type option[value='{emergency_secondary_phone3_type}']").prop("selected", true);
	$("select#emergency_s_relationship_type option[value='{emergency_s_relationship_type}']").prop("selected", true);
		if($('#emergency_s_relationship_type').val()=='other'){
			$('.secondary_other_contact').removeClass('hidden');
		}
	$("select#driver_license_state option[value='{driver_license_state}']").prop("selected", true);
});

$('.modal-select').on('change', function() {
	if($(this).children(':selected').hasClass('modal-popup')){
		$('.edit').addClass('hidden');
		var editId = $(this).attr('id');
		$('.edit_job_title').removeClass('hidden');
		$('#modal').modal('show');
		$('#modal').find('.modal-footer a').attr('href', $(this).attr('data-target'));
		return false;
	}
});
$('.modal-button').on('click', function() {
	$('.edit').addClass('hidden');
	var editId = $(this).attr('id');
	$('.'+editId+'').removeClass('hidden');
	$('#modal').modal('show');
	$('#modal').find('.modal-footer a').attr('href', $(this).attr('data-target'));
	return false;
});
$('.override-button').on('click', function() {
	$('.edit').addClass('hidden');
	var editId = $(this).attr('id');
	$('.'+editId+'').removeClass('hidden');
	$('#overrideModal').modal('show');
	$('#overrideModal').find('.modal-footer a').attr('href', $(this).attr('data-target'));
	return false;
});
$('#emergency_p_relationship_type').on('change', function() {
	if($(this).val()=='other'){
		$('.primary_other_contact').removeClass('hidden');
	} else {
		$('.primary_other_contact').addClass('hidden');
	};
});
$('#emergency_s_relationship_type').on('change', function() {
	if($(this).val()=='other'){
		$('.secondary_other_contact').removeClass('hidden');
	} else {
		$('.secondary_other_contact').addClass('hidden');
	};
});

$("#employee_tab a").click(function (e) {
	e.preventDefault();
	$(this).tab("show");
});

$("#benefits_tab li a").click(function (e) {
	$('#benefits_tab li .caret.emp-caret').remove();
	$(this).parent('li').append('<span class="caret emp-caret"></span>');
});
$("#non_benefits_tab li a").click(function (e) {
	$('#non_benefits_tab li .caret.emp-caret').remove();
	$(this).parent('li').append('<span class="caret emp-caret"></span>');
});
$("#authorization_tab li a").click(function (e) {
	$('#authorization_tab li .caret.emp-caret').remove();
	$(this).parent('li').append('<span class="caret emp-caret"></span>');
});

/*E-Signature*/
jQuery(document).ready(function () {
	//activate signature
	signature = $("#new_signature");
	signature.jSignature();
	//reset signature
	$(".signature_reset").on("click", function () {
		signature.jSignature("reset");
	});
	//approve signature
	$(".signature_save").on("click", function () {
		signature_svg = signature.jSignature("getData","svg");
		
		$("#signature_input").val(signature_svg[1]);
	});
});

$('.pto_vac_add_note').on('click', function(){
	$('.pto_vac_used_note').toggleClass('hidden');
});
$('.sick_leave_add_note').on('click', function(){
	$('.sick_leave_used_note').toggleClass('hidden');
});
$('.dental_add_note').on('click', function(){
	$('.dental_used_note').toggleClass('hidden');
});

/*Force DatePicker to work in Bootstrap modals*/
var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

$.fn.modal.Constructor.prototype.enforceFocus = function() {};