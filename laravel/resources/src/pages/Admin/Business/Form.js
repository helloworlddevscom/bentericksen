
$(document).ready(function() {
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
  

$('.btn-password').on('click', function () {
    $.get('/generate-password', function (data) {
        $('#business_owner_login_password').val(data);
    });
});

$('#business_type').on('change', function () {
    elem = $('#business_type option:selected').val();

    $('#business_sub_type option[value!=""]').addClass('hidden');
    $('#business_sub_type option[value=""]').prop('selected', true);
    $('#business_sub_type option.' + elem).each(function () {
        $(this).removeClass('hidden');
    });

})

$(".date-box").datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true
});

$('.dataReset').each(function () {
    //id = $(this).attr('id');
    //dataSelected = '{{Request::old("'+id+'")}}';
    selected = $(this).attr('data-selected');
    if (selected !== "") {
        $(this).find('option[value="' + selected + '"]').prop('selected', true);
    }
})

$('input[name=bonuspro_enabled]').on('click', function (e) {
    if (this.value > 0) {
        $('#bonuspro_fields').removeClass('hidden');
    } else {
        $('#bonuspro_fields').addClass('hidden');
    }
});

$('input[name=hrdirector_enabled]').on('click', function (e) {
    if (this.value > 0) {
        $('#hrdirector_fields').removeClass('hidden');
    } else {
        $('#hrdirector_fields').addClass('hidden');
    }
});