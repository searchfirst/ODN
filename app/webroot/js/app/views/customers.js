var CustomersView = Backbone.View.extend({
	el: $('section[role=main]').get(0),
	initialize: function() {
		_.bindAll(this,'render');
		this.templates = CnrsTemplates;
	},
	render: function() {
		var renderTemplate = this.templates.compile('customersView'),
				customer = this.collection.model.toJSON(),
				$thisel = $(this.el);
		console.log(new Date);
		$thisel
			.html(renderTemplate(customer))
			.find('ul.tab_hooks').duxTab();
		$thisel.find('h1, h2').hookMenu();
	}
});
