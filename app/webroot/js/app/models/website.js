(function(window,document,cbb,duxAppClasses,undefined){
	var Website = cbb.Model.extend({
			name: 'Website',
			url: function(){
				var id = this.get('id');
				return '/websites' + (id !== undefined ? '/' + id : '');
			}
		}),
		WebsitesCollection = cbb.Collection.extend({
			name: 'websites',
			model: Website,
		});
	duxAppClasses.Website = Website;
	duxAppClasses.WebsitesCollection = WebsitesCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
