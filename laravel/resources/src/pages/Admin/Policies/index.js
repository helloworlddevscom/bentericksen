import controls from './controls'
import $ from 'jquery'
import '@/lib/dataTables'

$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        var min = parseInt($('#picker1').val());
        var max = parseInt($('#picker2').val());
        var policyMin = parseInt(data[5]);
        var policyMax = parseInt(data[6]);

        //if( isNaN( policyMin ) || isNaN( policyMin ) ) return true;

        if (isNaN(min) && isNaN(max)) return true;
        if (isNaN(min) && max <= policyMax) return true;
        if (min >= policyMin && isNaN(max)) return true;
        console.log(min, max, policyMin, policyMax);
        if (min >= policyMin && max <= policyMax) return true;

        return false;
    }
);

$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        var requirement = $('#col_4_select_search').val();

        if (requirement === "") return true;

        var policyRequirement = $.parseJSON(data[3]);

        return inArray(requirement, policyRequirement);
        return true;

        function inArray(needle, haystack) {
            var length = haystack.length;
            for (var i = 0; i < length; i++) {
                if (haystack[i] === needle) return true;
            }
            return false;
        }
    }
);

const table = $('#policy_table').DataTable({
    "bStateSave": false,
    "orderClasses": false,
    "columnDefs": [
        {
            "targets": [2, 3, 4, 5, 6, 7],
            "visible": false,
        }
    ]
})

controls(table)