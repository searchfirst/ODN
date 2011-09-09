	dac.Website = cbb.Model.extend({
		name: 'Website',
		url: function(){
			var id = this.get('id');
			return '/websites' + (id !== undefined ? '/' + id : '');
		}
	});
	dac.WebsitesCollection = cbb.Collection.extend({
		name: 'websites',
		model: dac.Website,
	});
