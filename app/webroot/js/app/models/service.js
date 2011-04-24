var Service = DuxModel.extend({
	name: 'Service',
	url: function(){return '/services/'+this.get('id')}
}),
ServicesCollection = DuxCollection.extend({
	name: 'services',
	model: Service,
});
