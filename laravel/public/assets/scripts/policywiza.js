/*NEW JS*/
//onload
$('.check-box').each(function() {
	if($(this).attr('data-checkbox-checked') == 1)
	{
		$(this).prop('checked', true);
	}
});

//Classifications
//add classification
$('.add-classification').on('click', function() {
	classification = $(this).parents('.sub-content').children('.classifications');
	count = classification.attr('data-classification-count');
	
var input = ['<div class="col-md-12 odd">',
			'<a class="btn col-md-1 col-md-offset-1 js-del">',
				'<i class="fa fa-times"></i>',
			'</a>',
			'<p class="col-md-8">',
				'<input class="date-box form-control" type="text" placeholder="User Classification" name="addClassifications[][name]" value="">',
			'</p>'].join("\n");
			
classification.append(input);

count++;
classification.attr('data-classification-count', count);	

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
	console.log(count);
	parent.find('.pto-custom .customs').append(ptoTemplate(group, count));
	count++;
	parent.find('.customs').attr('data-field-count', count);
	return false;
});	

function ptoTemplate(group, count)
{
	
	pto = [ '<div class="col-md-12 custom dynamic-pto" data-field-id="n-'+count+'" data-field-type="'+group+'">',
				'<div class="row">',
					'<a class="btn col-md-1 remove-pto" data-group="'+group+'"><i class="fa fa-times"></i></a>',
					'<div class="col-md-11">',
						'After&nbsp;<input type="text" value="" name="pto['+group+']['+count+'][wait_pto_value]" class="form-control number-box">&nbsp;<input type="text" value="" name="pto['+group+']['+count+'][wait_pto_value]" class="form-control number-box">&nbsp;of continued service&nbsp;<input type="text" value="" name="pto['+group+']['+count+'][pto_value_earned]" class="form-control number-box">&nbsp;<input type="text" value="" name="pto['+group+']['+count+'][pto_value_earned_interval]" class="form-control number-box">&nbsp;of PTO earned per&nbsp;',
						'<input type="text" value="" name="pto['+group+']['+count+'][pto_interval]" class="form-control number-box">',
					'</div>',
				'</div>',
			'</div>'].join("\n");
	return pto;
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
	
	pto = [ '<div class="col-md-12 custom dynamic-pto" data-field-id="n-'+count+'" data-field-type="'+group+'">',
				'<div class="row">',
					'<a class="btn col-md-1 remove-sick-leave" data-group="'+group+'"><i class="fa fa-times"></i></a>',
					'<div class="col-md-11">',
						'After&nbsp;<input type="text" value="" name="sick_leave['+group+']['+count+'][wait_sick_leave_value]" class="form-control number-box">&nbsp;<input type="text" value="" name="sick_leave['+group+']['+count+'][wait_sick_leave_value]" class="form-control number-box">&nbsp;of continued service&nbsp;<input type="text" value="" name="sick_leave['+group+']['+count+'][sick_leave_value_earned]" class="form-control number-box">&nbsp;<input type="text" value="" name="sick_leave['+group+']['+count+'][sick_leave_value_earned_interval]" class="form-control number-box">&nbsp;of Sick Leave earned per&nbsp;',
						'<input type="text" value="" name="sick_leave['+group+']['+count+'][sick_leave_interval]" class="form-control number-box">',
					'</div>',
				'</div>',
			'</div>'].join("\n");
	return pto;
}

//Holidays
//add holiday
$('.add-holiday').on('click', function() {			
	var count = $('.holidays .holiday').length;
	console.log(count);
	$('.holidays').append(holidayTemplate(count));
});

function holidayTemplate(count)
{
	if(count % 2 == 0)
	{
		oswitch = "odd";		
	} else {
		oswitch = "even";
	}
	
	console.log(oswitch);
	
	var holiday = ['<div class="col-md-12 holiday '+oswitch+'">',
					'<div class="col-md-2">',
						'<input id="sick_leave_holiday_rosh_hashanah" class="check-box" type="checkbox" name="holidays[new]['+count+'][enabled]" value="1">',
					'</div>',
					'<div class="col-md-4"><input type="text" name="holidays[new]['+count+'][name]" value=""></div>',
					'<div class="col-md-4"><input type="text" name="holidays[new]['+count+'][description]" value=""></div>',
				'</div>'].join("\n");	
			
	return holiday;
}