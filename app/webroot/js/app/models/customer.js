	dac.Customer = cbb.Model.extend({
		name: 'Customer',
		url: function(){
			var id = this.get('id');
			return '/customers' + (id ? '/' + id : '');
		}
	});
	dac.CustomersCollection = cbb.Collection.extend({
		name: 'customers',
		model: dac.Customer,
		comparator: function(customer) {
			return customer.get('company_name');
		}
	});
