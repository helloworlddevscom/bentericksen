import controls from '@/pages/Admin/Policies/controls'
import $ from 'jquery'
import '@/lib/dataTables'

var table = $("#consultant_table").DataTable({
  "bStateSave": false,
  "orderClasses": false
});

controls(table)