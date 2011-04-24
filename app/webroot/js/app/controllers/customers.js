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
				'cnrsEditable [contenteditable]': [
					{callbacks:{save: 'update'}}
				],
				'cnrsCollapse .collapse': [{}]
			}
		});
		this.pageView.rendering();
		this.extras = {
			users: new UsersCollection(),
			websites: new WebsitesCollection({page:1,params:{limit:'all',customer_id:id}})
		};
		this.extras.users.fetch();
		this.extras.websites.fetch();
		customer.bind('change', _.bind(this._renderView,this,customer.id)).fetch();
	},
	add: function(customer_id) {
		var params = customer_id !== undefined ? {Customer:{customer_id:customer_id}}:{},
			customer = new Customer(params),
			customers = new CustomersCollection({
				page:1,
				params:{limit:'all'},
				comparator: function(customer) { return customer.get('company_name'); }
			});
		this.pageView = new DuxPageView({
			el: $('[role=main]').get(0),
			viewTemplate: 'customersAdd',
			model: customer,
			context: 'model'
		});
		this.pageView
			.rendering()
			.bind('rendered',function(view){
				var $select = $('select[name="data[Customer][customer_id]"]');
				customers
					.bind('fetched',function(){
						this.each(function(customer) {
							var Customer = customer.get('Customer');
							$select.append('<option value="' + Customer.id + '">' + Customer.company_name + '</option>');
						});
					})
					.fetch();
			})
			.render();
	},
	_renderView: function(id) {
		this.pageView.render().delegateEvents();
		var baseCollectionParams = {
				page: 1,
				params: {customer_id: id}
			},
			widgets = {
				'cnrsEditable [contenteditable]': [
					{callbacks:{save: 'update'}}
				],
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
						{callbacks:{save: 'update'}}
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
						{callbacks:{save: 'update'}}
					],
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
						{callbacks:{save: 'update'}}
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
						{callbacks:{save: 'update'}}
					],
					'cnrsCollapse .collapse': [{}]
				},
				itemTagName: 'article'
			}),
			notes = new NotesCollection(baseCollectionParams),
			notesView = new DuxListView({
				modelName: 'Note',
				el: $('.note.list').get(0),
				collection: notes,
				extras: this.extras,
				itemWidgets: {
					'cnrsEditable [contenteditable]': [
						{callbacks:{save: 'update'}}
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
