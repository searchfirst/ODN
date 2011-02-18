// includes bindings for fetching/fetched
var PaginatedCollection = Backbone.Collection.extend({
	initialize: function() {
		_.bindAll(this, 'parse', 'url', 'pageInfo', 'nextPage', 'previousPage');
		this.page = 1;
	},
	fetch: function(options) {
		options || (options = {});
		this.trigger("fetching");
		var self = this;
		var success = options.success;
		options.success = function(resp) {
			self.trigger("fetched");
			if (success) {
				success(self, resp);
			}
		};
		Backbone.Collection.prototype.fetch.call(self, options);
	},
	parse: function(resp) {
		this.page = resp.page;
		this.perPage = resp.per_page;
		this.total = resp.total;
		return resp.models;
	},
	url: function() {
		return this.base_url + '?' + $.param({
			page: this.page
		});
	},
	pageInfo: function() {
		var info = {
			total: this.total,
			page: this.page,
			perPage: this.perPage,
			pages: Math.ceil(this.total / this.perPage),
			prev: false,
			next: false
		};

		var max = Math.min(this.total, this.page * this.perPage);

		if (this.total == this.pages * this.perPage) {
			max = this.total;
		}

		info.range = [(this.page - 1) * this.perPage + 1, max];

		if (this.page > 1) {
			info.prev = this.page - 1;
		}

		if (this.page < info.pages) {
			info.next = this.page + 1;
		}

		return info;
	},
	nextPage: function() {
		this.page = this.page + 1;
		this.fetch();
	},
	previousPage: function() {
		this.page = this.page - 1;
		this.fetch();
	}
});