(function(window,document,$,duxAppClasses,undefined){
	$(function(){
		var duxApp = {
			appRouter: new duxAppClasses.AppRouter,
			facadesRouter: new duxAppClasses.FacadesRouter,
			customersRouter: new duxAppClasses.CustomersRouter,
			invoicesRouter: new duxAppClasses.InvoicesRouter
		};
		$('nav#menu a').click(function(e){
			var usedModifier = e.which == 1 && (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey),
				url = $(this).attr('href').match(/^\/?(.*)/)[1];
			if (!usedModifier) {
				e.preventDefault();
				duxApp.appRouter.navigate(url,true);
			}
		});
		Backbone.history.start({pushState:true});
		window.duxApp = duxApp;
	})
})(this,document,jQuery,this.duxAppClasses);
