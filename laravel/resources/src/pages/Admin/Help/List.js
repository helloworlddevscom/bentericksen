import tableControls from '@/utils/tableControls'
import '@/lib/jquery'
import '@/lib/dataTables'

const table = $("#help_table").DataTable({
  "bStateSave": true,
  "orderClasses": false
})

tableControls(table)

$('.modal-button').on('click', function() {
  target = $(this).attr('data-target');
  $('#modal').modal('show');
  $('#modalForm').attr('action', target);
  return false;
})