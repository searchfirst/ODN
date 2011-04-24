var Customer = DuxModel.extend({
	name: 'Customer',
	url: function(){
		var id = this.get('id');
		return '/customers' + (id ? '/' + id : '');
	}
}),
CustomersCollection = DuxCollection.extend({
	name: 'customers',
	model: Customer,
	comparator: function(customer) {
		return customer.get('company_name');
	}
});
