var FacadesRouter = Backbone.Router.extend({
	templates: CnrsTemplates,
	facade: Facades,
	initialize: function() {
		this.view = new FacadesView({router: this});
		this.facade.bind('facade:fetched', this.view.render);
	},
	routes: {
		'': 'index'
	},
	index: function() {
		this.facade.fetch();
	},
});
