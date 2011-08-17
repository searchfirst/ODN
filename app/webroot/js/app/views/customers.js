var CustomersView = DuxPageView.extend({
	events: {
		'submit .p_form form[action="/customers/add"]': 'add',
		'click ul[data-field="filter"] span': '_filterBy'
	},
	view: function(id) {
		var extras = {
				users: new UsersCollection({watch: {parent: this.model, event: 'childAdd'}}),
				websites: new WebsitesCollection({page:1,params:{limit:'all',customer_id:id},watch: {parent: this.model, event: 'childAdd'}}),
				services: new ServicesCollection({page:1,params:{limit:'all',customer_id:id},watch: {parent: this.model, event: 'childAdd'}})
			};
		this.model
			.bind('change', this.render, this)
			.fetch();
		this
			.bind('rendered', function() {
				var collectionParams = {
						page: 1,
						params: { customer_id: id },
						watch: { parent: this, event: 'renderChildren' }
					},
					customersView = new DuxListView({
						modelName: 'Customer',
						el: $('.customer.list').get(0),
						collection: new CustomersCollection(collectionParams),
						extras: this.extras,
						parentModel: this.model,
						gotoViewOnAdd: true
					}),
					contactsView = new DuxListView({
						modelName: 'Contact',
						el: $('.contact.list').get(0),
						collection: new ContactsCollection(collectionParams),
						extras: extras,
						parentModel: this.model,
						itemWidgets: {
							'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
							'cnrsCollapse .collapse': [{}]
						},
						itemTagName: 'article'
					}),
					servicesView = new DuxListView({
						modelName: 'Service',
						el: $('.service.list').get(0),
						collection: new ServicesCollection(collectionParams),
						extras: extras,
						parentModel: this.model,
						itemWidgets: {
							'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
							'cnrsSelectable .selectable': [ {save: 'cb_update', options: ServicesCollection.prototype.status} ],
							'cnrsCollapse .collapse': [{}]
						},
						itemTagName: 'article'
					}),
					websitesView = new DuxListView({
						modelName: 'Website',
						el: $('.website.list').get(0),
						collection: new WebsitesCollection(collectionParams),
						extras: extras,
						parentModel: this.model,
						itemWidgets: {
							'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
							'cnrsCollapse .collapse': [{}]
						},
						itemTagName: 'article'
					}),
					invoicesView = new DuxListView({
						modelName: 'Invoice',
						el: $('.invoice.list').get(0),
						collection: new InvoicesCollection(collectionParams),
						extras: extras,
						parentModel: this.model,
						itemWidgets: {
							'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
							'cnrsCollapse .collapse': [{}]
						},
						itemTagName: 'article',
						widgets: {
							'datepicker input[type="date"]': [{ dateFormat: 'yy-mm-dd', }]
						}
					}),
					notesView = new DuxListView({
						modelName: 'Note',
						el: $('.note.list').get(0),
						collection: new NotesCollection(collectionParams),
						extras: extras,
						parentModel: this.model,
						itemWidgets: {
							'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
							'cnrsCollapse .collapse': [{}]
						},
						itemTagName: 'article',
						showButtons: false,
						hideFormOnSubmit: false
					});
				this.trigger('renderChildren');
			});
		extras.users.fetch();
		extras.websites.fetch();
		extras.services.fetch();
	},
	index: function(filter) {
		this
			.bind('rendered', function(){
				var customersView = new DuxListView({
						modelName: 'Customer',
						el: $('.customer.list').get(0),
						collection: new CustomersCollection({
							page: 1,
							params:{limit: 'all',filter: filter},
							watch: { parent: this, event: 'renderChildren' }
						}),
						gotoViewOnAdd: true
					});
				this.trigger('renderChildren');
			})
			.render();
	},
	_filterBy: function(e) {
		var filter = $(e.target).text();
		this.router.navigate('customers?f='+filter,true);
	}
});
