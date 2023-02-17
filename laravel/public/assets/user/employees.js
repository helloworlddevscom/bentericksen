$(document).ready(function(){
	table
		.columns(1)
		.search('Active', false, true, false)
		.draw();

	$('#col_2_select_search option[value="Active"]').prop('selected', true);
});

$('#col_2_select_search').on('change', function(){
	if($(this).val() === 'All')
	{
		table
		.columns()
		.search('')
		.draw();
	} else {
		table
		.columns(1)
		.search($(this).val(), false, true, false)
		.draw()
	}
});