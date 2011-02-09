$(document).ready(function() {

	setup_modal_dialogs();
	setup_search_input_filter();
	
	$("#ServiceTitle").each(function() {
		if($(this).val()!='other') {
			$(this).parent().next("input").removeAttr('name').hide();
		};
	});
	
	$("#ServiceTitle").change(function() {
		var desiredName = $(this).attr('name');
		if($(this).val()=='other') {
			$(this).parent().next("input").removeAttr('name').fadeOut('fast');
		}
	});

	$('ul.note_list li,.infobox div[class*=highlight]').bind('mouseover',function(e){
		var currClass = $(this).attr('class');
		var match = /highlight_/i;
		var start = currClass.search(match);
		var hstring = currClass.substr(start);
		if(hstring!='') {
			$('.'+hstring).attr('rel','highlight').data('highlighted','yes');
		}
	});

	$('ul.note_list li,.infobox div[class*=highlight]').bind('mouseout',function(e){
		var currClass = $(this).attr('class');
		var match = /highlight_/i;
		var start = currClass.search(match);
		var hstring = currClass.substr(start);
		var isChild = $(e.currentTarget).has($(e.relatedTarget)).length;
		if(!(isChild) && hstring!='') {
			$('.'+hstring).data('highlighted','no');
			window.setTimeout(function() {
				if($('.'+hstring).data('highlighted')=='no') {
					$('.'+hstring).attr('rel','');
				}
			},500);
		}
	});

	$('.collapse_hook').bind('click',function(e){
		$(this).parent().find('.collapse').slideToggle('slow');
		$(this).toggleClass('hidemore');
	});
	
	$('#menu li').filter(function(index){ return $('div',this).length==1; }).bind({
		mouseenter: function(e){
				var leftpoint = $(this).position().left + 'px';
				$(this).children('div').css({position:'absolute',left:leftpoint }).fadeIn(100);
		},
		mouseleave: function(e){
				$(this).children('div').fadeOut('fast');
		}
	});

	$('h1, h2, #user_details > p').hookMenu();
	$('ul.tab_hooks').duxTab();
	$('ul.hook_ajax_pagination').hookPagination();

});
