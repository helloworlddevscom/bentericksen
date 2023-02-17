import '@/lib/jquery'
import '@/lib/dataTables'
import adminTables from '@/lib/admin/tables'


var table = $("#active_table").DataTable({
  "bStateSave": false,
  "orderClasses": false,
});

$('.status-update').on('click', function () {
  var $elem = $(this);
  var url = $elem.attr('href');
  $.get(url, function (data) {
      if (data === "terminated") {
          $elem.parents('tr').find('.status').html('Inactive');
          $elem.html('ENABLE');
      }
      if (data === "enabled") {
          $elem.parents('tr').find('.status').html('Active');
          $elem.html('TERMINATE');
      }
  });
  return false;
});

$('#col_2_select_search').on('change', function () {
  table.draw();
});


$(document).ready(function(){
  $('#col_2_emp_type_select_search option[value="All"]').prop('selected', true);
  $('#col_3_emp_status_select_search option[value="Active"]').prop('selected', true);

	table
		.columns(2)
		.search('Active', false, true, false)
		.draw();

});

$('#col_2_emp_type_select_search').on('change', function() {
  if ($(this).val() === 'All')
  {
    table
      .columns(1)
      .search('Owner|Manager|Employee', true, false)
      .draw()
  } else {
    table
      .columns(1)
      .search($(this).val(), false, true, false)
      .draw()
  }
});

$('#col_3_emp_status_select_search').on('change', function() {
	if($(this).val() === 'All')
  {
		table
		.columns(2)
		.search('Active|Inactive', true, false)
		.draw();
	} else {
		table
		.columns(2)
		.search($(this).val(), false, true, false)
		.draw()
	}
});

adminTables(table)
