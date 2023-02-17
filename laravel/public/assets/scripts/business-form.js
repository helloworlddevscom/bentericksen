$(window).load(function() {
  toggleBonusProExpiration($('#business_bonuspro_lifetime_access'));
});

$('#enable_bonus_pro_yes').on('click', function() {
  toggleBonusProExpiration($('#business_bonuspro_lifetime_access'));
});

$('#business_bonuspro_lifetime_access').on('click', function() {
  toggleBonusProExpiration($(this));
});

function toggleBonusProExpiration(lifetimeAccess) {
  if(lifetimeAccess.is(':checked')) {
    $('#bonuspro_expiration_date').closest('.form-group').addClass('hidden');
  } else {
    $('#bonuspro_expiration_date').closest('.form-group').removeClass('hidden');
  }
}
