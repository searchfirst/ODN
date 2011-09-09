	dac.FacadesView = cbb.PageView.extend({
		index: function() {
			this.collection
				.bind('fetched', this.render, this)
				.fetch();
		}
	});
