	dac.Search = cbb.Model.extend({
		name: 'SearchIndex',
		url: function(){
			var id = this.get('id');
			return '/search' + (id ? '/' + id : '');
		}
	});
	dac.SearchCollection = cbb.Collection.extend({
		name: 'search',
		model: dac.Search
	});
