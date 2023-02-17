import tableControls from '@/utils/tableControls'
import '@/lib/jquery'
import '@/lib/dataTables'

const table = $("#policy_table").DataTable({
  "bStateSave": false,
  "orderClasses": false,
  "columnDefs": [
      {
          "targets": [],
          "visible": false,
      }
  ]
});

tableControls(table)
