var InvoicesController = DuxController.extend({
	routes: {
		'/invoices': 'index',
		'/invoices/view/:id': 'view',
	},
	view: function(id) {
		var invoice = new Invoice({id: +id});
		this.pageView = new DuxPageView({
			el: $('[role=main]').get(0),
			viewTemplate: 'invoicesView',
			model: invoice,
			context: 'model',
			widgets: {
				'cnrsEditable [contenteditable]': [
					{
						save: 'cb_update'
					}
				],
				'cnrsCollapse .collapse': [{}]
			}
		});
		this.pageView.rendering();
		invoice.bind('change', _.bind(this._renderView,this,invoice.id)).fetch();
	},
	index: function() {
		var customers = new CustomersCollection({page: 1,params:{limit:'all',filter:'A'}});
		this.pageView = new DuxPageView({
			el: $('[role=main]').get(0),
			events: {
				'submit .p_form form[action="/customers/add"]': 'add',
				'click ul[data-field="filter"] span': 'filterBy'
			},
			collection: customers,
			gotoViewOnAdd: true,
			hideFormOnSubmit: false,
			viewTemplate: 'invoicesIndex'
		});
		this.pageView.render();
		var customersView = new DuxListView({
				modelName: 'Customer',
				el: $('.customer.list').get(0),
				collection: customers,
				gotoViewOnAdd: true
			});

		customers.fetch();
	},
	_renderView: function(id) {
		this.pageView.render().delegateEvents();
		var baseCollectionParams = { page: 1, params: {invoice_id: id} },
			contacts = new ContactsCollection(baseCollectionParams),
			contactsView = new DuxListView({
				modelName: 'Contact',
				el: $('.contact.list').get(0),
				collection: contacts
			});

		contacts.fetch();
	}
});
