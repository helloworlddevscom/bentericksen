	$(document).ready(function() {
		
	});
	
	$(".pui").on("change", function() {
		var pui = "";
		$("input.pui:checked").each( function() {
			pui = pui + $( this ).data("entry") + "-" ;
			$("#update_polices_list").val( pui );
		});
		console.log( "pui:"+pui );
	});
	$(".faqui").on("change", function() {
		var faqui = "";
		$("input.faqui:checked").each( function() {
			faqui = faqui + $( this ).data("entry") + "-" ;
			$("#update_faqs_list").val( faqui );
		});
		console.log( "faq:"+faqui );
	});
	$(".jui").on("change", function() {
		var jui = "";
		$("input.jui:checked").each( function() {
			jui = jui + $( this ).data("entry") + "-" ;
			$("#update_jobs_list").val( jui );
		});
		console.log( "jui:"+jui );
	});
	$(".fui").on("change", function() {
		var fui = "";
		$("input.fui:checked").each( function() {
			fui = fui + $( this ).data("entry") + "-" ;
			$("#update_forms_list").val( fui );
		});
		console.log( "fui:"+fui );
	});
	
	$(document).ready(function() {
		$('#add').click(function() {
			return !$('#select1 option:selected').remove().appendTo('#select2');
		});
		$('#remove').click(function() {
			return !$('#select2 option:selected').remove().appendTo('#select1');
		});
	});
	
	$(".btn-email").on( "click", function () {
		var $this = $(this)
		if( $this.val() == "active" ){
			$("#active_list").removeClass( "hidden" );
			$("#inactive_list").addClass( "hidden" );
			$("#other_list").addClass( "hidden" );
			$("#consultants_list").addClass( "hidden" );
			$("#email_list_label").text( "Active" );
		}else if( $this.val() == "inactive" ){
			$("#active_list").addClass( "hidden" );
			$("#inactive_list").removeClass( "hidden" );
			$("#other_list").addClass( "hidden" );
			$("#consultants_list").addClass( "hidden" );
			$("#email_list_label").text( "Inactive" );
		}else if( $this.val() == "other" ){
			$("#active_list").addClass( "hidden" );
			$("#inactive_list").addClass( "hidden" );
			$("#other_list").removeClass( "hidden" );
			$("#consultants_list").addClass( "hidden" );
			$("#email_list_label").text( "Other" );
		}else if( $this.val() == "consultants" ){
			$("#active_list").addClass( "hidden" );
			$("#inactive_list").addClass( "hidden" );
			$("#other_list").addClass( "hidden" );
			$("#consultants_list").removeClass( "hidden" );
			$("#email_list_label").text( "Consultants" );
		};
	});