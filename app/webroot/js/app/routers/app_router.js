(function(window,document,cbb,duxAppClasses,undefined){
	var AppRouter = cbb.Router.extend({
		initialize: function() {
			this.view = new duxAppClasses.AppView({
				router: this
			});
		}
	});
	duxAppClasses.AppRouter = AppRouter;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
