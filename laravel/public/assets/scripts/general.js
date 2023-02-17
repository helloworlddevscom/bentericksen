$(document).ready(function () {
/*Add active class and Caret to menu*/
	$('ul.nav li a').not('.dropdown-toggle').filter(function () {
		return this.href == window.location;
	}).parents('.navbar-nav li:last').addClass('active').append('<span class="caret"></span>');


	/*Modal launcher*/
	$('.btn-modal').on('click', function() {
		$('.help').addClass('hidden');
		$('.edit').addClass('hidden');
		var editId = $(this).attr('id');
		$('.'+editId+'').removeClass('hidden');
		$('#modal').modal('show');
		$('#modal').find('.modal-footer a').attr('href', $(this).attr('data-target'));
		return false;
	});

/*Accordion Settings*/
	var icons = {
		header: "ui-icon-triangle-1-e",
		activeHeader: "ui-icon-triangle-1-s"
	};

	$('#parameters').click();

});