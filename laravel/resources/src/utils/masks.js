import '@/lib/maskedInput/maskedInput'
import Cleave from 'cleave.js'

/*jQuery mask*/
$(document).ready(function () {
    $.mask.definitions['t'] = '[ap]';
    $.mask.definitions['m'] = '[m]';
    $(".phone_number").mask("(999) 999-9999"); // extension section removed for the time being   ? x99999
    $(".zip_code").mask("99999");
    $(".date-picker").mask("99/99/9999");
    $(".time-picker").mask("99/99/9999 99:99 tm");

    if ($('.input-money').length > 0) {
        var money = new Cleave('.input-money', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    }
});