var FacadesController = Backbone.Controller.extend({
	templates: CnrsTemplates,
	facade: Facades,
	initialize: function() {
		this.view = new FacadesView();
		this.facade.bind('facade:fetched', this.view.render);
	},
	routes: {
		'/': 'index'
	},
	index: function() {
		this.facade.fetch();
	},
});
