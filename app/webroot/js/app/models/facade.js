(function(window,document,cbb,duxAppClasses,undefined){
	var FacadesCollection = cbb.Collection.extend({
		modelName: 'facades',
		url: '/'
	});
	duxAppClasses.FacadesCollection = FacadesCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
