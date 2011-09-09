	dac.Service = cbb.Model.extend({
		name: 'Service',
		url: function(){
			var id = this.get('id');
			return '/services' + (id !== undefined ? '/' + id : '');
		},
		status: [
			{fieldData: 0, text: 'Cancelled'},
			{fieldData: 1, text: 'Pending'},
			{fieldData: 2, text: 'Active'},
			{fieldData: 3, text: 'Complete'},
		]
	});
	dac.ServicesCollection = cbb.Collection.extend({
		name: 'services',
		model: dac.Service,
		status: [
			{fieldData: 0, text: 'Cancelled'},
			{fieldData: 1, text: 'Pending'},
			{fieldData: 2, text: 'Active'},
			{fieldData: 3, text: 'Complete'},
		]
	});
