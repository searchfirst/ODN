(function(window,document,cbb,duxAppClasses,undefined){
	var CustomersView = cbb.PageView.extend({
		events: {
			'submit .p_form form[action="/customers/add"]': 'add',
			'click a[href^=/invoices/view]': '_navigateInvoiceView',
			'click ul[data-field="filter"] span': '_filterBy'
		},
		view: function(id) {
			var extras = {
					users: new duxAppClasses.UsersCollection({watch: {parent: this.model, event: 'childAdd'}}),
					websites: new duxAppClasses.WebsitesCollection({page:1,params:{limit:'all',customer_id:id},watch: {parent: this.model, event: 'childAdd'}}),
					services: new duxAppClasses.ServicesCollection({page:1,params:{limit:'all',customer_id:id},watch: {parent: this.model, event: 'childAdd'}})
				};
			var e = {
				users: new duxAppClasses.UsersCollection({
					page:1,
					limit:'all',
					watch:{parent: this.model, event: 'childAdd'}
				}),
				websites: new duxAppClasses.WebsitesCollection({
					page:1,
					params:{limit:'all', customer_id:id},
					watch:{parent: this.model, event: 'childAdd'}
				}),
				services: new duxAppClasses.ServicesCollection({
					page:1,
					params:{limit:'all', customer_id:id},
					watch:{parent: this.model, event: 'childAdd'}
				}),
			};
			e.users.fetch();
			e.websites.fetch();
			e.services.fetch();
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
						contactsView = new cbb.ListView({
							modelName: 'Contact',
							el: $('.contact.list').get(0),
							collection: new duxAppClasses.ContactsCollection(collectionParams),
							parentModel: this.model,
							itemWidgets: {
								'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
								'cnrsCollapse .collapse': [{}]
							},
							itemTagName: 'article'
						}),
						notesView = new cbb.ListView({
							modelName: 'Note',
							el: $('.note.list').get(0),
							collection: new duxAppClasses.NotesCollection(collectionParams),
							extras: {
								users: {
									collection: e.users,
									config: {
										findEl: 'select[data-selectable-for="user_id"]',
										keyName: 'id',
										valueName: 'name',
										modelFieldName: 'user_id'
									}
								}
							},
							parentModel: this.model,
							itemWidgets: {
								'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
								'cnrsCollapse .collapse': [{}]
							},
							itemTagName: 'article',
							showButtons: false,
							hideFormOnSubmit: false
						}),
						websitesView = new cbb.ListView({
							modelName: 'Website',
							el: $('.website.list').get(0),
							collection: new duxAppClasses.WebsitesCollection(collectionParams),
							parentModel: this.model,
							itemWidgets: {
								'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
								'cnrsCollapse .collapse': [{}]
							},
							itemTagName: 'article'
						}),
						servicesView = new cbb.ListView({
							modelName: 'Service',
							el: $('.service.list').get(0),
							collection: new duxAppClasses.ServicesCollection(collectionParams),
							extras: {
								users: {
									collection: e.users,
									config: {
										findEl: 'select[data-selectable-for="user_id"]',
										keyName: 'id',
										valueName: 'name',
										modelFieldName: 'user_id'
									}
								},
								websites: {
									collection: e.websites,
									config: {
										findEl: 'select[data-selectable-for="website_id"]',
										keyName: 'id',
										valueName: 'uri',
										modelFieldName: 'website_id'
									}
								},
							},
							parentModel: this.model,
							itemWidgets: {
								'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
								'cnrsSelectable .selectable[data-field="status"]': [{
									save: 'cb_update',
									options: duxAppClasses.ServicesCollection.prototype.status
								}],
								'cnrsSelectable .selectable[data-field="user_id"]': [{
									save: 'cb_update',
									options: extras.users.toStatusList()
								}],
								'cnrsCollapse .collapse': [{}]
							},
							itemTagName: 'article'
						}),
						invoicesView = new cbb.ListView({
							modelName: 'Invoice',
							el: $('.invoice.list').get(0),
							collection: new duxAppClasses.InvoicesCollection(collectionParams),
							extras: {
								users: {
									collection: e.users,
									config: {
										findEl: 'select[data-selectable-for="user_id"]',
										keyName: 'id',
										valueName: 'name',
										modelFieldName: 'user_id'
									}
								},
								services: {
									collection: e.services,
									config: {
										findEl: 'select[data-selectable-for="service_id"]',
										keyName: 'id',
										valueName: 'title',
										modelFieldName: 'service_id'
									}
								},
								websites: {
									collection: e.websites,
									config: {
										findEl: 'select[data-selectable-for="website_id"]',
										keyName: 'id',
										valueName: 'uri',
										modelFieldName: 'website_id'
									}
								},
							},
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
						customersView = new cbb.ListView({
							modelName: 'Customer',
							el: $('.customer.list').get(0),
							collection: new duxAppClasses.CustomersCollection(collectionParams),
							parentModel: this.model,
							gotoViewOnAdd: true
						});
					this.trigger('renderChildren');
				});
		},
		index: function(filter) {
			this
				.bind('rendered', function(){
					var customersView = new cbb.ListView({
							modelName: 'Customer',
							el: $('.customer.list').get(0),
							collection: new duxAppClasses.CustomersCollection({
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
			e.preventDefault();
			var filter = $(e.target).text();
			this.router.navigate('customers?f='+filter,true);
		},
		_navigateInvoiceView: function(e) {
			e.preventDefault();
			var url = $(e.target).attr('href').match(/^\/?(.*)$/)[1];
			this.router.navigate(url,true);
		}
	});
	duxAppClasses.CustomersView = CustomersView;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
