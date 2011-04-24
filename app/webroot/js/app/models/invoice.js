var Invoice = DuxModel.extend({
	name: 'Invoice',
	url: function(){return '/invoices/'+this.get('id')}
}),
InvoicesCollection = DuxCollection.extend({
	name: 'invoices',
	model: Invoice,
});
