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



//formatting with commas
$.fn.digits = function(){ 
  return this.each(function(){ 
      $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
  })
}

/*Enter as Tab*/
$(".accordion input").keydown(checkForEnter);
function checkForEnter(event){
textboxes = $("input");
if(event.keyCode == 13){
  currentBoxNumber = textboxes.index(this);
  if (textboxes[currentBoxNumber + 1] != null) {
    nextBox = textboxes[currentBoxNumber + 1];
    nextBox.focus();
    nextBox.select();
  }
}
};
/*Salary Converter*/
$('.salary').on( 'change keyup', function(){
var hoursPerDay = parseFloat( $('#hoursPerDay').val() ) || 0;
var daysPerWeek = parseFloat( $('#daysPerWeek').val() ) || 0;
var weeksPerYear = parseFloat( $('#weeksPerYear').val() ) || 0;
var pay = parseFloat( $('#pay').val() ) || 0;
var payRate = $('#payRate').val();
var payTotalRate = $('#payTotalRate').val();

if( payRate == 'ph' ){
  if( payTotalRate == 'ph' ){
    total = pay;
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pd' ){
    total = ( pay * hoursPerDay );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pw' ){
    total = ( ( pay * hoursPerDay ) * daysPerWeek );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pm' ){
    total = ( ( ( ( pay * hoursPerDay ) * daysPerWeek ) * weeksPerYear ) / 12 );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'py' ){
    total = ( ( ( pay * hoursPerDay ) * daysPerWeek ) * weeksPerYear );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  };
}else if( payRate == 'pd' ){
  if( payTotalRate == 'ph' ){
    total = ( pay / hoursPerDay );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pd' ){
    total = pay;
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pw' ){
    total = ( pay * daysPerWeek );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pm' ){
    total = ( ( ( pay * daysPerWeek ) * weeksPerYear ) / 12 );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'py' ){
    total = ( ( pay * daysPerWeek ) * weeksPerYear );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  };
}else if( payRate == 'pw' ){
  if( payTotalRate == 'ph' ){
    total = ( ( pay / daysPerWeek ) / hoursPerDay );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pd' ){
    total = ( pay / daysPerWeek );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pw' ){
    total = pay;
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pm' ){
    total = ( ( pay * weeksPerYear ) / 12 );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'py' ){
    total = ( pay * weeksPerYear );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  };
}else if( payRate == 'pm' ){
  if( payTotalRate == 'ph' ){
    total = ( ( ( ( pay * 12 ) / weeksPerYear ) / daysPerWeek ) / hoursPerDay );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pd' ){
    total = ( ( ( pay * 12 ) / weeksPerYear ) / daysPerWeek );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pw' ){
    total = ( ( pay * 12 ) / weeksPerYear );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pm' ){
    total = pay;
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'py' ){
    total = ( pay * 12 );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  };
}else if( payRate == 'py' ){
  if( payTotalRate == 'ph' ){
    total = ( ( ( pay / weeksPerYear ) / daysPerWeek ) / hoursPerDay );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pd' ){
    total = ( ( pay / weeksPerYear ) / daysPerWeek );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pw' ){
    total = ( pay / weeksPerYear );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'pm' ){
    total = ( pay / 12 );
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  }else if( payTotalRate == 'py' ){
    total = pay;
    $('#payTotal').text( '$ ' + addCommas(total.toFixed(2)) );
  };
};
});
/*Turnover Cost*/
function totalHiringExpense(){
var hiringCostSubTotal = parseFloat( $('#hiringCostSubTotal').val() ) || 0;
var trainingAndStartUpCostsSubTotal = parseFloat( $('#trainingAndStartUpCostsSubTotal').val() ) || 0;
var terminationCostsSubTotal = parseFloat( $('#terminationCostsSubTotal').val() ) || 0;
var lostSalesCostsSubTotal = parseFloat( $('#lostSalesCostsSubTotal').val() ) || 0;

var totalHiringExpense = hiringCostSubTotal + trainingAndStartUpCostsSubTotal + terminationCostsSubTotal + lostSalesCostsSubTotal;

$('#totalHiringExpense').val( totalHiringExpense.toFixed(2) ).text( '$ ' + addCommas( totalHiringExpense.toFixed(2) ) );
};
$('.hiring').on( 'keyup', function(){
var agencyFees = parseFloat( $('#agencyFees').val() ) || 0;
var advertising = parseFloat( $('#advertising').val() ) || 0;
var hiringPersonnelDepartment = parseFloat( $('#hiringPersonnelDepartment').val() ) || 0;
var manager = parseFloat( $('#manager').val() ) || 0;
var materialsAndHandling = parseFloat( $('#materialsAndHandling').val() ) || 0;
var testingMaterials = parseFloat( $('#testingMaterials').val() ) || 0;
var otherHiring = parseFloat( $('#otherHiring').val() ) || 0;

var hiringCostSubTotal = agencyFees + advertising + hiringPersonnelDepartment + manager + materialsAndHandling + testingMaterials + otherHiring;

$('#hiringCostSubTotal').val( hiringCostSubTotal.toFixed(2) ).text( '$ ' + addCommas(hiringCostSubTotal.toFixed(2) ) );
totalHiringExpense();
});
$('.training').on( 'keyup', function(){
var trainer = parseFloat( $('#trainer').val() ) || 0;
var trainingMaterialsAndEquipment = parseFloat( $('#trainingMaterialsAndEquipment').val() ) || 0;
var outsideTrainingHelp = parseFloat( $('#outsideTrainingHelp').val() ) || 0;
var trainingFacility = parseFloat( $('#trainingFacility').val() ) || 0;
var supervisor = parseFloat( $('#supervisor').val() ) || 0;
var otherTraining = parseFloat( $('#otherTraining').val() ) || 0;

var trainingAndStartUpCostsSubTotal = trainer + trainingMaterialsAndEquipment + outsideTrainingHelp + trainingFacility + supervisor + otherTraining;

$('#trainingAndStartUpCostsSubTotal').val( trainingAndStartUpCostsSubTotal.toFixed(2) ).text( '$ ' + addCommas( trainingAndStartUpCostsSubTotal.toFixed(2) ) );
totalHiringExpense();
});
$('.termination').on( 'keyup', function(){
var counsellingTime = parseFloat( $('#counsellingTime').val() ) || 0;
var unemploymentBenefits = parseFloat( $('#unemploymentBenefits').val() ) || 0;
var legalFees = parseFloat( $('#legalFees').val() ) || 0;
var severancePay = parseFloat( $('#severancePay').val() ) || 0;
var personnelDepartment = parseFloat( $('#personnelDepartment').val() ) || 0;
var otherTermination = parseFloat( $('#otherTermination').val() ) || 0;

var terminationCostsSubTotal = counsellingTime + unemploymentBenefits + legalFees + severancePay + personnelDepartment + otherTermination;

$('#terminationCostsSubTotal').val( terminationCostsSubTotal.toFixed(2) ).text( '$ ' + addCommas( terminationCostsSubTotal.toFixed(2) ) );
totalHiringExpense();
});
$('.sales').on( 'keyup', function(){
var nplDuringLagBetweenEmployees = parseFloat( $('#nplDuringLagBetweenEmployees').val() ) || 0;
var nplDuringInitialPeriod = parseFloat( $('#nplDuringInitialPeriod').val() ) || 0;
var nplDueToPoorPerformance = parseFloat( $('#nplDueToPoorPerformance').val() ) || 0;
var nplDueToUnmannedPositions = parseFloat( $('#nplDueToUnmannedPositions').val() ) || 0;
var otherLost = parseFloat( $('#otherLost').val() ) || 0;

var lostSalesCostsSubTotal = nplDuringLagBetweenEmployees + nplDuringInitialPeriod + nplDueToPoorPerformance + nplDueToUnmannedPositions + otherLost;

$('#lostSalesCostsSubTotal').val( lostSalesCostsSubTotal.toFixed(2) ).text( '$ ' + addCommas( lostSalesCostsSubTotal.toFixed(2) ) );
totalHiringExpense();
});
$('#numberOfEmployees').on( 'keyup', function(){
var totalHiringExpense = parseFloat( $('#totalHiringExpense').val() ) || 0;
var numberOfEmployees = parseFloat( $('#numberOfEmployees').val() ) || 0;

var hiringCostPerEmployee = totalHiringExpense / numberOfEmployees;

console.log(totalHiringExpense+' / '+numberOfEmployees+' = '+hiringCostPerEmployee);

if( hiringCostPerEmployee == 'Infinity'){
  $('#hiringCostPerEmployee').text( '$ 00.00' );
}else{
  $('#hiringCostPerEmployee').text( '$ ' + addCommas( hiringCostPerEmployee.toFixed(2) ) );
};
});
/*Overtime Calculator*/
$('.overtime').on( 'change keyup', function(){
var regularHours = parseFloat( $('#regularHours').val() ) || 0;
var regularBaseWage = parseFloat( $('#regularBaseWage').val() ) || 0;
var overtimeHours = parseFloat( $('#overtimeHours').val() ) || 0;

var totalRegularPay = regularBaseWage * regularHours;
var overtimeRate = regularBaseWage * 1.5;
var totalOvertimePay = ( regularBaseWage * 1.5 ) * overtimeHours;
var totalPay = totalRegularPay + totalOvertimePay;

$('#overtimeRate').text( '$ ' + addCommas( overtimeRate.toFixed(2) ) );
$('#totalRegularPay').text( '$ ' + addCommas( totalRegularPay.toFixed(2) ) );
$('#totalOvertimePay').text( '$ ' + addCommas (totalOvertimePay.toFixed(2) ) );
$('#totalPay').text( '$ ' + addCommas( totalPay.toFixed(2) ) );
});

/*Average Wage*/
$('#averageWages').on( 'keyup', '.average', function(){
var regularHours = parseFloat( $('#aveRegularHours').val() ) || 0;
var regularBaseWage = parseFloat( $('#aveRegularBaseWage').val() ) || 0;

var hoursArray = [];
var wagesArray = [];

$('.aveOtherHours').each(function( index, value ){
  hoursArray[ index ] = parseFloat( $(this).val() ) || 0;
});

$('.aveOtherWage').each(function(index, value){
  wagesArray[index] = parseFloat( $(this).val() ) || 0;
});

var hoursWages = regularHours * regularBaseWage;
var hours = regularHours;

$.each(hoursArray, function( index, value ){
  hoursWages += hoursArray[ index ] * wagesArray[ index ];
  hours += hoursArray[ index ];
});

var average = hoursWages / hours;

$('#aveTotalHours').text( ' ' + hours.toFixed( 2 ) );
$('#aveAverageWage').text( '$ ' + addCommas ( average.toFixed( 2 ) ) );
});
/*Add Elements*/
$('.js-add').on('click', function() {
group = $(this).attr('data-group');
count = $(this).attr('data-count');

//console.log( $(this).attr('data-count') );
if(group == "addAveWage"){
  $('.aveWage').append(
    "<div class='del-wrap'>"+
      "<div class='row'>"+
        "<a class='btn col-md-1 js-del'>"+
          "<i class='fa fa-times'></i>"+
        "</a>"+
        "<label for='' class='col-md-4 control-label text-right'>Additional Hours:</label>"+
        "<div class='col-md-2 no-padding-right'>"+
          "<input type='text' class='form-control average aveOtherHours' placeholder='0.00'>"+
        "</div>"+
      "</div>"+
      "<div class='row'>"+
        "<label for='' class='col-md-4 col-md-offset-1 control-label text-right'>Additional Wage:</label>"+
        "<div class='col-md-2 no-padding-right'>"+
          "<div class='input-group'>"+
            "<span class='input-group-addon'>$</span>"+
            "<input type='text' class='form-control average aveOtherWage' placeholder='00.00'>"+
          "</div>"+
        "</div>"+
        "<div class='col-md-3 padding-top'>Per Hour</div>"+
      "</div>"+
    "</div>"
  );
}
return false;
});
/*Delete Elements*/
$('form.form-horizontal').on('click', '.js-del', function() {
$(this).parents('.del-wrap').remove();
});

function addCommas( string )
{
string = string.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"); 

return string;
}