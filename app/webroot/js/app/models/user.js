(function(window,document,cbb,duxAppClasses,undefined){
	var User = cbb.Model.extend({
			name: 'User',
			url: function(){
				var id = this.get('id');
				return '/users' + (id ? '/' + id : '');
			}
		}),
		UsersCollection = cbb.Collection.extend({
			name: 'users',
			model: User,
			toStatusList: function(){
				var statusList = [];
				this.forEach(function(i){
					var item = {
						fieldData: i.get('id'),
						text: i.get('name')
					};
					statusList.push(item);
				});
				return statusList;
			}
		});
	duxAppClasses.User = User;
	duxAppClasses.UsersCollection = UsersCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
