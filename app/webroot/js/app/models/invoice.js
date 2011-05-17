var Invoice = DuxModel.extend({
	name: 'Invoice',
	url: function() {
		id = this.get('id');
		return '/invoices' + (id !== undefined ? '/' + id : '');
	}
}),
InvoicesCollection = DuxCollection.extend({
	name: 'invoices',
	model: Invoice,
});
