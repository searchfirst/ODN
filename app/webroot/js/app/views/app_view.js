(function(window,document,cbb,duxAppClasses,undefined){
	var AppView = cbb.View.extend({
		el: $('section[role=main]').get(0),
		events: {
			'click a[href^="/customers/view/"]': '_customerView'
		},
		initialize: function(options) {
			cbb.View.prototype.initialize(options);
			_.bindAll(this,'render');
			this.templates = CnrsTemplates;
		},
		_customerView: function(e) {
			var usedModifier = e.which == 1 && (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey),
				href = 'customers/view/' + $(e.target).attr('href').match(/\d+/);
			if (!usedModifier) {
				e.preventDefault();
				this.router.navigate(href,true);
			}
		}
	});
	duxAppClasses.AppView = AppView;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
