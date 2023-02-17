import tableControls from '@/utils/tableControls'
import '@/lib/jquery'
import '@/lib/dataTables'

$(document).ready(function () {

  $('#add').click(function () {
      return !$('.select1 option:selected').remove().appendTo('.select2');
  });

  $('#remove').click(function () {
      return !$('.select2 option:selected').remove().appendTo('.select1');
  });

  $('.toggleStatus').on('click', function () {
      var path = $(this).attr('href');
      var $element = $(this);
      $.get(path, function (data) {
          if (data === "reload") {
              window.location.reload();
          } else {
              $element.html(data === "enabled" ? 'DISABLE' : 'ENABLE');
              $element.parents('tr').toggleClass('enabled disabled');
          }
      });

      return false;
  });
});

var table = $('#policy_list').DataTable({
  "columnDefs": [{
      "visible": false,
      "targets": [1, 2, 3]
  }],
  "order": [
      [
          2, 'asc'
      ],
      [
          1, 'asc'
      ]
  ],
  "iDisplayLength": 9999,
  "bPaginate": false,
  "displayLength": 9999,
  "drawCallback": function (settings) {
      var api = this.api();
      var rows = api.rows({page: 'current'}).nodes();
      var last = null;

      api.column(1, {
          page: 'current'
      })
          .data().each(function (group, i) {
          if (last !== group) {
              $(rows).eq(i).before(
                  '<tr class="group"><td colspan="5">' + group + '</td></tr>'
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
  console.log(this.value);
});