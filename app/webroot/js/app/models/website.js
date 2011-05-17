var Website = DuxModel.extend({
	name: 'Website',
	url: function(){
		var id = this.get('id');
		return '/websites' + (id !== undefined ? '/' + id : '');
	}
}),
WebsitesCollection = DuxCollection.extend({
	name: 'websites',
	model: Website,
});
