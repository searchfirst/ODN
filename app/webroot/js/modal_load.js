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

$('#invoice_raise *').bind('change',function(e){
//    $("#table_preview").html("<img src='/img/theme/loading-animation.gif' alt='Loading...' />");
	var invoice_summary = $('#invoice_raise textarea').val();
	var invoice_amount = $('#invoice_raise #InvoiceAmount').val();
	var invoice_vat_enabled = $('#invoice_raise #InvoiceVatIncluded').attr('checked');
	var invoice_csv = "<strong>Detail</strong>,<strong>Cost</strong>\n" + invoice_summary + invoice_totals;
	var final_csv = $.csv()(invoice_csv);
	$("#table_preview").table({replace:false,data:final_csv});
});

$('#simplemodal-container form').each(function(i) {
	$(this).bind('submit',function(e) {
		var error_display = $(this).find('#ErrorDisplay');
		var error_display_message = "";
		if(error_display.length) {
			error_display.remove();
		}
		
		var no_problems = true;
		var elements_exist = false;
		var note_description = $(this).find('#NoteDescription');
		note_description.parent().css({
			'background-color': 'transparent',
			'outline-color': 'transparent'
		});
		var note_description_val = note_description.val();
		if(note_description.length && note_description_val) {
			no_problems = no_problems && true;
		} else if(note_description.length) {
			note_description.parent().css({
				'background-color': '#F00',
				'outline': 'solid 1px #F00'
			});
			error_display_message = error_display_message + "<p>You must leave a message.</p>";
			no_problems = no_problems && false;
		}

		var note_service_id = $(this).find('#NoteServiceId[type!=hidden]');
		note_service_id.parent().css({
			'background-color': 'transparent',
			'outline-color': 'transparent'
		});
		var note_service_id_val = note_service_id.val();
		if(note_service_id.length && note_service_id_val) {
			no_problems = no_problems && true;
		} else if (note_service_id.length) {
			note_service_id.parent().css({
				'background-color': '#F00',
				'outline': 'solid 1px #F00'
			});
			error_display_message = error_display_message + "<p>You must choose a service</p>";
			no_problems = no_problems && false;
		}
		elements_exist = note_service_id.length || note_description.length;
		if(elements_exist && !no_problems) {
			$(this).prepend("<div id=\"ErrorDisplay\"" + error_display_message + "</div>");
		}
		return no_problems;
	});
});

$('ul.tab_hooks').duxTab();
$('ul.hook_ajax_pagination').hookPagination();
$('input[type=number]').preventNonFloatCharacters();