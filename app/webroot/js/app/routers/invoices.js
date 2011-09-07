(function(window,document,cbb,duxAppClasses,undefined){
	var InvoicesRouter = cbb.Router.extend({
		routes: {
			'invoices': 'index',
			'invoices/view/:id': 'view',
		},
		view: function(id) {
			var invoice = new duxAppClasses.Invoice({id: +id});
			this.pageView = new cbb.PageView({
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
		_renderView: function(id) {
			this.pageView.render().delegateEvents();
			var baseCollectionParams = { page: 1, params: {invoice_id: id} },
				contacts = new duxAppClasses.ContactsCollection(baseCollectionParams),
				contactsView = new cbb.ListView({
					modelName: 'Contact',
					el: $('.contact.list').get(0),
					collection: contacts
				});
			contacts.fetch();
		}
	});
	duxAppClasses.InvoicesRouter = InvoicesRouter;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
