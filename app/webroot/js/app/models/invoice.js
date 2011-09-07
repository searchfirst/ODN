(function(window,document,cbb,duxAppClasses,undefined){
	var Invoice = cbb.Model.extend({
			name: 'Invoice',
			url: function() {
				id = this.get('id');
				return '/invoices' + (id !== undefined ? '/' + id : '');
			}
		}),
		InvoicesCollection = cbb.Collection.extend({
			name: 'invoices',
			model: Invoice,
		});
	duxAppClasses.Invoice = Invoice;
	duxAppClasses.InvoicesCollection = InvoicesCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
