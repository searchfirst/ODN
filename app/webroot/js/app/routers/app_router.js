	dac.AppRouter = cbb.Router.extend({
		initialize: function(options) {
			cbb.Router.prototype.initialize.call(this, options);
			this.view = new dac.AppView({ router: this });
		}
	});
