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
	if($('#simplemodal-container form').attr('action').match(/change_status/g)) {
		$(this).attr('disabled','disabled');
	}
});