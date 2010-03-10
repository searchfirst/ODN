$("#ServiceTitle").each(function() {
	if($(this).val()!='other') {
		$(this).parent().next("input").removeAttr('name').hide();
	};
});

$("#ServiceTitle").change(function() {
	var desiredName = $(this).attr('name');
	if($(this).val()=='other') {
		$(this).parent().next("input").attr('name',desiredName).fadeIn('fast');
		$(this).parent().next("input").select();
	} else {
		$(this).parent().next("input").removeAttr('name').fadeOut('fast');
	}
});

$('#ServiceCancelled').hide();

$('#ServiceStatus').change(function(e) {
	if($(this).val()==0) {
		$('#ServiceCancelled').show();
		$('#ServiceCancelled select').each(function() {
			$(this).removeAttr('disabled');
		});
	} else {
		$('#ServiceCancelled').hide();
		$('#ServiceCancelled select').each(function() {
			$(this).attr('disabled','disabled');
		});
	}
});

$('#ServiceStatus option[selected=selected]').each(function() {
	$(this).attr('disabled','disabled');
});

/*
$('#date_pick_block').bind('click',function(){
	$(this).css('background-image','none').css('padding-left','0px')
	$('#date_pick_block').datePicker({
		inline:true,
		startDate:'2000-01-01'
	}).bind('dateSelected',function(e, selectedDate, $td){
		$('#DatePickHidden').attr('value',selectedDate.asString()+' 00:00:00');
	});
});
*/