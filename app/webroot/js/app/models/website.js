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
        toStatusList: function(){
            var statusList = [];
            this.forEach(function(i){
                var item = {
                    fieldData: i.get('id'),
                    text: i.get('uri')
                };
                statusList.push(item);
            });
            return statusList;
        }
    });
