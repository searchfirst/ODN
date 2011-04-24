var Website = DuxModel.extend({
	name: 'Website',
	url: function(){return '/websites/'+this.get('id')}
}),
WebsitesCollection = DuxCollection.extend({
	name: 'websites',
	model: Website,
});
