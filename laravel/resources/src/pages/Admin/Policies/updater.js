import $ from 'jquery'
import '@/lib/dataTables'

var table = $("#policy_updater_table").DataTable({
    "order": [
        [ 2, "ASC" ]
    ]
});

$('.policy-updater-delete').on('click', function(e) {
    e.preventDefault();
    $('#modal_delete_confirm').attr('href', '/admin/policies/updates/' + $(this).data('updater') + '/delete');
    $('#modalDeleteWarning').modal('show');
});