var FacadesView = Backbone.View.extend({
	el: $('section[role=main]').get(0),
	initialize: function(options) {
		_.bindAll(this,'render');
		this.router = options !== undefined ? options.router || undefined : undefined;
		//this.el = $('section[role=main]').get(0);
		this.templates = CnrsTemplates;
	},
	render: function(models) {
		var renderTemplate = this.templates.compile('facades_index'),
				$thisel = $(this.el);
		$thisel
			.html(renderTemplate(models))
			.find('ul.tab_hooks').duxTab();
	},
	events: {
		'click a[href^="/customers/view/"]': 'routeToCustomer'
	},
	routeToCustomer: function(e) {
		e.preventDefault();
		var href = 'customers/view/' + $(e.target).attr('href').match(/\d+/);
		this.router.navigate(href,true);
	}
});
