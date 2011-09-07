(function(window,document,cbb,duxAppClasses,undefined){
	var Customer = cbb.Model.extend({
			name: 'Customer',
			url: function(){
				var id = this.get('id');
				return '/customers' + (id ? '/' + id : '');
			}
		}),
		CustomersCollection = cbb.Collection.extend({
			name: 'customers',
			model: Customer,
			comparator: function(customer) {
				return customer.get('company_name');
			}
		});
	duxAppClasses.Customer = Customer;
	duxAppClasses.CustomersCollection = CustomersCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
