    dac.User = cbb.Model.extend({
        name: 'User',
        url: function(){
            var id = this.get('id');
            return '/users' + (id ? '/' + id : '');
        }
    });
    dac.UsersCollection = cbb.Collection.extend({
        name: 'users',
        model: dac.User,
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
