function setup_modal_dialogs() {
	$('a.modalAJAX').live('click',function(e) {
		e.preventDefault();
		var uri = $(this).attr('href'),
			dialog_title = $(this).attr('title'),
			dialog;
		if(!$('div#ajax-modal-dialog').length) {
			dialog = $('<div id="ajax-modal-dialog"></div>').appendTo('body');
		} else {
			dialog = $('div#ajax-modal-dialog');
			dialog.html('');
		}
		dialog.load(uri,{},function(r,s,xHR){
			dialog.dialog({modal:true,minHeight:0,position:'right',title:dialog_title});
		});
	});
}
