	dac.Invoice = cbb.Model.extend({
		name: 'Invoice',
		url: function() {
			id = this.get('id');
			return '/invoices' + (id !== undefined ? '/' + id : '');
		}
	});
	dac.InvoicesCollection = cbb.Collection.extend({
		name: 'invoices',
		model: dac.Invoice,
	});
