if(segment_1 == 'admin'){
	var toolSet =	[
						["Undo","Redo"],
						["Cut","Copy","Paste","PasteText","PasteFromWord"],
						["RemoveFormat"],
						["Bold","Italic","Underline","Strike"],
						["NumberedList","BulletedList"],
						["Outdent","Indent"],
						["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
						["Table"],
						["TextColor","BGColor"],
						["Maximize"],
						["Styles"],
						["Format"],
						["Font"],
						["Link"],
						["Accordion"],
						["Anchor"]
					];
}else if(segment_1 == 'user' && (segment_2 == 'new-job-description' || segment_2 == 'edit-job-description')){
	var toolSet =	[
						["Undo","Redo"],
						["Cut","Copy","Paste"],
						["Bold","Italic","Underline"],
						["NumberedList","BulletedList"],
						["Outdent","Indent"],
						["Maximize"],
						["Styles"],
						["Format"],
					];
}else if(segment_1 == 'user'){
	var toolSet =	[
						["Undo","Redo"],
						["Cut","Copy","Paste","PasteText","PasteFromWord"],
						["RemoveFormat"],
						["Bold","Italic","Underline","Strike"],
						["NumberedList","BulletedList"],
						["Outdent","Indent"],
						["JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
						["Table"],
						["TextColor","BGColor"],
						["Maximize"],
						["Styles"],
						["Format"],
						["Font"],
					];
}

Wygwam.configs["3"] = {
	"skin": "wygwam3",
	"toolbarCanCollapse": false,
	"dialog_backgroundCoverOpacity": 0,
	"entities_processNumerical": true,
	"forcePasteAsPlainText": false,
	"toolbar": toolSet,
	"height": "200",
	"resize_enabled": true,
	"parse_css": false,
	"language": "en",
	"autoGrow_minHeight": "200",
	"allowedContent": true,
	"extraPlugins": "wygwam,readmore,accordion"
};

$('.wygwam').each(function() {
	var name = $(this).children('textarea').attr('name');
	new Wygwam(name, "3", false);
});
$('.wygwam').each(function() {
	var name = $(this).children('textarea').attr('id');
	new Wygwam(name, "3", false);
});