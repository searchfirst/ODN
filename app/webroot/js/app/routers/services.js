(function(window,document,cbb,duxAppClasses,undefined){
	var ServicesRouter = cbb.Router.extend({
		initialize: function() {
			var self = this;
			this.services = new duxAppClasses.ServicesCollection();
			this.service = new duxAppClasses.Service();
			this.view = new duxAppClasses.ServicesView({collection: this.services});
			DuxRouter.prototype.initialize.call(self);
		},
		routes: {
			'/services/add': 'add',
			'/services/edit/:id': 'edit',
			'/services': 'index',
			'/services/view/:id': 'view',
			'/services/delete/:id': 'delete'
		},
		view: function(id) {
			this.service
			.clear()
			.set({id: +id})
			.fetch({
				success: _.bind(function() {this.render('servicesView', this.service.toJSON());}, this)
			});
		},
		edit: function(id) {
		},
		add: function() {
		}
	});
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
