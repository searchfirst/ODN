AppController = Backbone.Controller.extend({
	initialize: function() {
		$('h1, h2, #user_details > p').hookMenu();
		$('ul.tab_hooks').duxTab();
		//$('ul.hook_ajax_pagination').hookPagination();
		$('a[href^="/"]').live('click',function(e){
			e.preventDefault();
			href = $(this).attr('href');
			Backbone.history.saveLocation(href);
			Backbone.history.loadUrl();
		});
	}
});

$(function(){
	new AppController();
	new FacadesController;
	new CustomersController;
	Backbone.history.start();
});
