(function(window,document,cbb,duxAppClasses,undefined){
	var FacadesView = cbb.PageView.extend({
		index: function() {
			this.collection
				.bind('fetched', this.render, this)
				.fetch();
		}
	});
	duxAppClasses.FacadesView = FacadesView;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
