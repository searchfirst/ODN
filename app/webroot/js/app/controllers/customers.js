var CustomersController = Backbone.Controller.extend({
	initialize: function() {
		this.customers = new CustomersCollection();
		this.view = new CustomersView({collection: this.customers});
		this.templates = CnrsTemplates;
	},
	routes: {
		"/customers/edit/:id": 'edit',
		"/customers": 'index',
		"/customers/view/:id": 'view',
		"/customers/delete/:id": 'delete'
	},
	view: function(id) {
		this.customers.model.bind('fetched', this.view.render);
		this.customers.model.set({id: id*1});
		this.customers.model.fetch();
	}
});
