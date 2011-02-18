var CustomerModel = Backbone.Model.extend({
	url: function() { return '/customers/' + this.get('id') },
	fetch: function(options) {
		options || (options = {});
		var self = this;
		var success = options.success;
		options.success = function(resp) {
			if (success) { success(self, resp); }
			self.trigger('fetched',self);
		};
		Backbone.Model.prototype.fetch.call(self, options);
	},
	initialize: function() {

	}
}),
Customer = new CustomerModel(),
CustomersCollection = Backbone.Collection.extend({
	model: Customer,
	url: '/customers',
});

