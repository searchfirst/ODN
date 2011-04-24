var WebsitesListView = DuxListView.extend({
	initialize: function(options) {
		if (options !== undefined) {
			if (options.fetchServices === true) {
				this.fetchServices = true;
			}
		}
		DuxListView.prototype.initialize.call(this, options);
	},
	modelName: 'Website'
});
