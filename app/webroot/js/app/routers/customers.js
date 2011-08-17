var CustomersRouter = DuxRouter.extend({
	routes: {
		'customers': 'index',
		'customers?f=:filter': 'index',
		'customers/add': 'add',
		'customers/add/customer_id::customer_id': 'add',
		'customers/view/:id': 'view',
		'customers/delete/:id': 'delete'
	},
	view: function(id) {
		var view = new CustomersView({
				context: 'model',
				el: $('[role=main]').get(0),
				model: new Customer({id: +id}),
				router: this,
				viewTemplate: 'customersView',
				widgets: {
					'cnrsEditable [contenteditable]': [{save: 'cb_update'}],
					'cnrsCollapse .collapse': [{}]
				}
			});
		view.view(id);
	},
	index: function(filter) {
		var filter = filter || 'A',
			view = new CustomersView({
				el: $('[role=main]').get(0),
				gotoViewOnAdd: true,
				hideFormOnSubmit: false,
				router: this,
				viewTemplate: 'customersIndex'
			});
		view.index(filter);
	}
});
