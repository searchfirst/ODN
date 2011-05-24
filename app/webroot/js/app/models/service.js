var Service = DuxModel.extend({
	name: 'Service',
	url: function(){
		var id = this.get('id');
		return '/services' + (id !== undefined ? '/' + id : '');
	}
}),
ServicesCollection = DuxCollection.extend({
	name: 'services',
	model: Service,
});
