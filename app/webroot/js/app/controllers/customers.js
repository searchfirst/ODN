var CustomersController = DuxController.extend({
	routes: {
		'/customers': 'index',
		'/customers/add': 'add',
		'/customers/add/customer_id::customer_id': 'add',
		'/customers/view/:id': 'view',
		'/customers/delete/:id': 'delete'
	},
	view: function(id) {
		var customer = new Customer({id: +id});
		this.pageView = new DuxPageView({
			el: $('[role=main]').get(0),
			viewTemplate: 'customersView',
			model: customer,
			context: 'model',
			widgets: {
				'cnrsEditable [contenteditable]': [{save: 'cb_update'}],
				'cnrsCollapse .collapse': [{}]
			}
		});
		this.pageView.rendering();
		this.extras = {
			users: new UsersCollection(),
			websites: new WebsitesCollection({page:1,params:{limit:'all',customer_id:id}}),
			services: new ServicesCollection({page:1,params:{limit:'all',customer_id:id}})
		};
		this.extras.users.fetch();
		this.extras.websites.fetch();
		this.extras.services.fetch();
		customer.bind('change', _.bind(this._renderView,this,customer.id)).fetch();
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
			viewTemplate: 'customersIndex'
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
		var baseCollectionParams = {
				page: 1,
				params: {customer_id: id}
			},
			widgets = {
				'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
				'cnrsCollapse .collapse': [{}]
			};
			customers = new CustomersCollection(baseCollectionParams),
			customersView = new DuxListView({
				modelName: 'Customer',
				el: $('.customer.list').get(0),
				collection: customers,
				extras: this.extras,
				gotoViewOnAdd: true
			}),
			contacts = new ContactsCollection(baseCollectionParams),
			contactsView = new DuxListView({
				modelName: 'Contact',
				el: $('.contact.list').get(0),
				collection: contacts,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{save: 'cb_update'}
					],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article'
			}),
			services = new ServicesCollection(baseCollectionParams),
			servicesView = new DuxListView({
				modelName: 'Service',
				el: $('.service.list').get(0),
				collection: services,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{save: 'cb_update'}
					],
					'cnrsSelectable .selectable': [ {save: 'cb_update', options: services.status} ],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article'
			}),
			websites = new WebsitesCollection(baseCollectionParams),
			websitesView = new DuxListView({
				modelName: 'Website',
				el: $('.website.list').get(0),
				collection: websites,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{save: 'cb_update'}
					],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article'
			}),
			invoices = new InvoicesCollection(baseCollectionParams),
			invoicesView = new DuxListView({
				modelName: 'Invoice',
				el: $('.invoice.list').get(0),
				collection: invoices,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{save: 'cb_update'}
					],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article',
				widgets: {
					'datepicker input[type="date"]': [{
						dateFormat: 'yy-mm-dd',
					}]
				}
			}),
			notes = new NotesCollection(baseCollectionParams),
			notesView = new DuxListView({
				modelName: 'Note',
				el: $('.note.list').get(0),
				collection: notes,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{save: 'cb_update'}
					],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article',
				showButtons: false,
				hideFormOnSubmit: false
			});

		customers.fetch();
		notes.fetch();
		invoices.fetch();
		websites.fetch();
		services.fetch();
		contacts.fetch();
	}
});
