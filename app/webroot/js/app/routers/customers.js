	dac.CustomersRouter = cbb.Router.extend({
		routes: {
			'customers': 'index',
			'customers?f=:filter': 'index',
			'customers/add': 'add',
			'customers/add/customer_id::customer_id': 'add',
			'customers/view/:id': 'view',
			'customers/delete/:id': 'delete'
		},
		view: function(id) {
			var view = new dac.CustomersView({
					context: 'model',
					el: $('[role=main]').get(0),
					model: new dac.Customer({id: +id}),
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
				view = new dac.CustomersView({
                    collection: new dac.CustomersCollection,
					el: $('[role=main]').get(0),
					gotoViewOnAdd: true,
					hideFormOnSubmit: false,
					router: this,
					viewTemplate: 'customersIndex'
				});
			view.index(filter);
		}
	});
