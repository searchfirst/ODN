var FacadesView = Backbone.View.extend({
	el: $('section[role=main]').get(0),
	initialize: function() {
		_.bindAll(this,'render');
		//this.el = $('section[role=main]').get(0);
		this.templates = CnrsTemplates;
	},
	render: function(models) {
		var renderTemplate = this.templates.compile('facades_index'),
				$thisel = $(this.el);
		$thisel
			.html(renderTemplate(models))
			.find('ul.tab_hooks').duxTab();
	}
});
