(function(window,document,cbb,duxAppClasses,undefined){
	var FacadesRouter = cbb.Router.extend({
		templates: CnrsTemplates,
		routes: {
			'': 'index'
		},
		index: function() {
			var view = new duxAppClasses.FacadesView({
				context: 'collection',
				el: $('[role=main]').get(0),
				collection: new duxAppClasses.FacadesCollection,
				viewTemplate: 'facadesIndex'
			});
			window.fv = view;
			view.index();
		},
	});
	duxAppClasses.FacadesRouter = FacadesRouter;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
