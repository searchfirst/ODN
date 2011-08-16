$(function(){
	var App = {
		appRouter: new DuxRouter,
		facadesRouter: new FacadesRouter,
		customersRouter: new CustomersRouter,
		invoicesRouter: new InvoicesRouter
	};
	$('nav#menu a').click(function(e){
		e.preventDefault();
		var url = $(this).attr('href').match(/^\/?(.*)/)[1];
		App.appRouter.navigate(url,true);
	});
	window.DuxApp = App;
	Backbone.history.start({pushState:true});
});
