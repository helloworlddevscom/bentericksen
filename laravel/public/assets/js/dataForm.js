$(document).ready(function() {
	var selectField = function(element, target, effect)
	{
		selected = element.find(":selected");
		target = selected.attr('data-target');
		effect = selected.attr('data-effect');
		
		// remove this soon
		special = selected.attr('data-special');

		if(special == "true")
		{
			$('.wizardOverlay').remove();
		}
		
		if(typeof target != "undefined" && effect == "show")
		{
			element.find("option").each(function() {
				if(!$(this).prop('selected'))
				{
					notShown = $(this).attr('data-target');
					$(notShown).addClass('hidden');
				}
			});
			
			$(target).removeClass('hidden');
		}
		
		if(typeof target == "undefined" && typeof effect == "undefined")
		{
			element.find("option").each(function() {
				if(!$(this).prop('selected'))
				{
					notShown = $(this).attr('data-target');
					$(notShown).addClass('hidden');
				}
			});
		}
	}

	$('.dataForm').on('change', function(event) {
		
		element = $(this);
		elementType = element.prop('type');
		target = element.attr('data-target');
		effect = element.attr('data-effect');
		
		if(elementType == "checkbox")
		{
			checkbox(element, target, effect);
		}
		
		if(elementType == "radio")
		{
			radio(element, target, effect);
		}
		
		if(elementType == "select-one")
		{		
			selectField(element, target, effect);
		}
		
		if(elementType == "button")
		{
			button(element);
		}
	});
	
	function checkbox(element, target, effect)
	{
		
		if(effect == "toggle")
		{				
			if($(target).hasClass('hidden'))
			{
				$(target).removeClass('hidden');
			} else {
				$(target).addClass('hidden');
			}
		}
		
		if(effect == "overlay")
		{
			overlay = "<div class='wizardOverlay'></div>";
			linked = element.attr('data-linked');
			if(typeof linked != 'undefined');
			{
				$("[name='"+linked+"']").prop('checked', false);
			}					
		
			if(element.prop('checked') && $(target).children('.wizardOverlay').length == 0)
			{
				$(target).append(overlay);
			}
			
			if(!element.prop('checked') && $(target).children('.wizardOverlay').length >= 1)
			{
				$(target).children('.wizardOverlay').remove();
			}		
		}
	}
	
	function radio(element, target, effect)
	{		
		if(effect == "show")
		{	
			if($(target).hasClass('hidden'))
			{
				$(target).removeClass('hidden');
			}
		} 
		
		if(effect == "hide")
		{
			$(target).addClass('hidden');
		} 				
	}


	
	$('form.dataForm').on('submit', function() {
		$('.dataFormWrap').each(function() {
			if($(this).hasClass('hidden'))
			{
				$(this).remove();
			}
		});
	});
	
	
	$('input.dataForm').each(function() {
		element = $(this);
		elementType = element.prop('type');
		target = element.attr('data-target');
		effect = element.attr('data-effect');
		
		if(elementType == "checkbox")
		{
			if(element.prop('checked'))
			{
				checkbox(element, target, effect);
			}
		}
		
		if(elementType == "radio")
		{
			if(element.prop('checked'))
			{			
				radio(element, target, effect);
			}
		}
		
		if(elementType == "select-one")
		{	
			console.log(element);
			selectField(element, target, effect);
		}
		
		if(elementType == "button")
		{
			button(element);
		}
	});
	
	$('.dataSelect').each(function() {
		selected = $(this).attr('data-selected');
		if(selected === "")
		{
			selected = $(this).attr('data-default');
		}
		
		select = $(this).find('option[value='+selected+']');
		select.prop('selected', true);
		$('.dataFormWrap').addClass('hidden');
		target = select.attr('data-target');
		console.log(target);
		$(target).removeClass('hidden');
	});
	
});