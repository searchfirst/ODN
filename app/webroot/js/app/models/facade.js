var FacadesCollection = Backbone.Collection.extend({
	url: '/',
	fetch: function(options) {
		options || (options = {});
		var self = this;
		var success = options.success;
		options.success = function(resp) {
			self.notes = self.models[0].attributes.notes;
			self.projects = self.models[0].attributes.projects;
			self.trigger('facade:fetched',self);
			if (success) {
				success(self, resp);
			}
		};
		Backbone.Collection.prototype.fetch.call(self, options);
	},
}), Facades = new FacadesCollection;
