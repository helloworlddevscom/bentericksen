import '@/lib/jquery'
import '@/lib/dataTables'

var invisibleColumns = [
  1, // Category
  2, // Category Order
  3 // Template ID
];

var headerDividerLength = invisibleColumns.length > 0 ? 6 - invisibleColumns.length : 6;

var table = $('#policy_list').DataTable({
  "columnDefs": [{
      "visible": false,
      "targets": invisibleColumns
  }],
  "orderFixed": [
      [2, 'asc']          // Category Order
  ],
  "order": [[
      0, 'asc'            // Form
  ]],
  "iDisplayLength": 25,
  "displayLength": 9999,
  "drawCallback": function (settings) {
      var api = this.api();
      var rows = api.rows({page: 'current'}).nodes();
      var last = null;

      api.column(1, {page: 'current'}).data().each(function (group, i) {
          if (last !== group) {
              $(rows).eq(i).before(
                  '<tr class="group"><td colspan="' + headerDividerLength + '">' + group + '</td></tr>'
              );

              last = group;
          }
      });
  }
});

/*Order by the grouping*/
$('#policy_list tbody').on('click', 'tr.group', function () {
  var currentOrder = table.order()[0];
  if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
      table.order([1, 'desc']).draw();
  } else {
      table.order([1, 'asc']).draw();
  }
});
$("#global_search").on("keyup", function () {
  table
      .search(this.value)
      .draw();
});