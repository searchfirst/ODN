var User = DuxModel.extend({
		name: 'User',
		url: function(){
			var id = this.get('id');
			return '/users' + (id ? '/' + id : '');
		}
	}),
	UsersCollection = DuxCollection.extend({
		name: 'users',
		model: User
	});
