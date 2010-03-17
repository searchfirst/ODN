function setup_modal_dialogs() {

	prepare_overlay();

	if($('#flashMessage').is('div.message')) {
		$('#flashMessage').modal({minHeight:35});
	}	

	$('a.modalAJAX').bind('click',function(event){
		var modal_url = $(this).attr('href');
		popupBoxAjax(modal_url);
		return false;
	});

}

function prepare_overlay() {
	$('body').append('<div id="modalDarkOverlay"><div><img src="/img/theme/loading_animation.gif"></div></div>')
}

function toggle_overlay() {
	$('#modalDarkOverlay').toggleClass('visible');
}

function popupBoxAjax(pba_url) {
	toggle_overlay();
	var error = '<h2>Error Retrieving Content</h2><p>There was a problem opening this page.</p>';
	var max_modal_height = $(document).height() - 44;
	$.ajax({
		url: pba_url,
		dataType: 'html',
		success: function(data,textStatus,XMLHttpRequest) {
			$.modal(data,{maxHeight:max_modal_height,autoResize:true});
			$.ajax({url:'/js/modal_load.js',dataType:'script'});
			toggle_overlay();
		},
		error: function(XMLHttpRequest,textStatus,errorThrown) {
			$.modal(error);
			toggle_overlay();
		}
	});
}