$(document).ready(function() {

	$("#ServiceTitle").each(function() {
		//var name = $(this).attr('name');
		if($(this).val()!='other') {
			$(this).parent().next("input").removeAttr('name').hide();
		};
	});
	
	$("#ServiceTitle").change(onChange);
	
	$('.ModifyStatusButton').bind('click',function(e){
		$(this).parent().find('a.thickbox').click();
		$('.date_pick').datePicker();
		return false;
	});
	
	function onChange() {
		var desiredName = $(this).attr('name');
		if($(this).val()=='other') {
			$(this).parent().next("input").attr('name',desiredName).fadeIn('fast');
			$(this).parent().next("input").select();
		} else {
			$(this).parent().next("input").removeAttr('name').fadeOut('fast');
		}
	};
	
	$('.date_pick').datePicker({inline:true});
	
	$('.customer_menu').addClass('jd_menu').jdMenu();
	
	$('ul.note_list li,.service_info tr[class~=highlight]').bind('mouseover',function(e){
		var currClass = $(this).attr('class');
		var match = /highlight_/i;
		var start = currClass.search(match);
		var hstring = currClass.substr(start);
		//alert(hstring);
		$('.'+hstring).attr('rel','highlight');
	});

	$('ul.note_list li,.service_info tr[class~=highlight]').bind('mouseout',function(e){
		var currClass = $(this).attr('class');
		var match = /highlight_/i;
		var start = currClass.search(match);
		var hstring = currClass.substr(start);
		$('.'+hstring).attr('rel','');
	});

	$('.collapse_hook').bind('click',function(e){
		$(this).parent().find('.collapse').toggle('slow');
		$(this).toggleClass('hidemore');
	});
	
	if($('#flashMessage').is('div.message')) {
		$('#flashMessage').modal();
	}

	$('#d_menu li').filter(function(index){ return $('div',this).length==1; }).bind({
		mouseover: function(e){
			var isChild = $(e.currentTarget).has($(e.relatedTarget)).length;
			if(!isChild) {
				var leftpoint = $(this).position().left + 'px';
				$(this).children('div').css('left',leftpoint).fadeIn('fast');
			}
		},
		mouseout: function(e){
			var isChild = $(e.currentTarget).has($(e.relatedTarget)).length;
			if(!(isChild)) {
				$(this).children('div').fadeOut('fast')
			}
		}
	});

});