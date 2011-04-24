AppController = Backbone.Controller.extend({
	initialize: function() {
		$('h1, h2, #user_details > p').hookMenu();
		$('ul.tab_hooks').duxTab();
		$('#menu li').filter(function(i){ return $('div',this).length==1; }).bind({
			mouseenter: function(e){
					var leftpoint = $(this).position().left + 'px';
					$(this).children('div').css({position:'absolute',left:leftpoint }).fadeIn(100);
			},
			mouseleave: function(e){
					$(this).children('div').fadeOut('fast');
			}
		});
		window.DuxApp = this;
	}
});

$(function(){
	new AppController();
	new FacadesController;
	new CustomersController;
	$('nav#menu a').each(function(i){
		var $this = $(this);
		$this.attr('href','#'+$this.attr('href'));
	});
	$('a[href^="#/"]').live('click',function(e){
		var $this = $(this);
		if (!(e.altKey || e.shiftKey || e.ctrlKey || e.metaKey || $this.data('noroute'))) {
			e.preventDefault();
			href = $this.attr('href');
			$(window).scrollTop(0);
			Backbone.history.saveLocation(href);
			Backbone.history.loadUrl();
		}
	});
	Backbone.history.start();
});
