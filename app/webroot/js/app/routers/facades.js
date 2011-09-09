	dac.FacadesRouter = cbb.Router.extend({
		routes: {
			'': 'index'
		},
		index: function() {
			var view = new dac.FacadesView({
				context: 'collection',
				el: $('[role=main]').get(0),
				collection: new dac.FacadesCollection,
				viewTemplate: 'facadesIndex'
			});
			view.index();
		},
	});
