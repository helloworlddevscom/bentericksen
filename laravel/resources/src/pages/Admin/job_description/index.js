import controls from '../Policies/controls'
import jq from 'jquery'
import '@/lib/dataTables'

jq.fn.dataTable.ext.search.push( function( settings, data, dataIndex )
{
    var needle = $('#subtype_search').find('option:selected').text();

    var dataArray = data[1];

    var subtypes = dataArray.match( /(?=\S)[^,]+?(?=\s*(,|$))/g );

    if(needle === " - Sub-Type - ") return true;
    if(subtypes === null ) return false;

    return inArray(needle, subtypes);

    function inArray(needle, haystack)
    {
        var length = haystack.length;
        for(var i = 0; i < length; i++)
        {
            if(haystack[i] === needle) return true;
        }
        return false;
    }
    return true;
});

const table = jq("#job_description_table").DataTable({"bFilter": true});

controls(table)

$('#subtype_search').on('change', function() {
    table.draw();
});

$('#job_description_table').on('click', '.btn-delete', function(){
    $('#deletionModal').modal('show');

    var dataUrl = $(this).attr('data-url');

    $('#deletionModal form').attr('action', dataUrl);
});

$('.modal-button').on('click', function() {
    var target = $(this).attr('data-url');

    $('#deletionModal form').attr('action', target);
});

$('#col_1_select_search').on('change', function() {
    const type = $(this).val()
    $('#subtype_search option[value=""]').prop('selected', true);
    $('#subtype_search option[value!=""]').addClass('hidden');
    $('#subtype_search option.'+type).removeClass('hidden');

});