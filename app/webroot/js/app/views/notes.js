(function(window,document,cbb,duxAppClasses,undefined){
	var NotesView = cbb.View.extend({
		el: $('section[role=main]').get(0)
	});
	duxAppClasses.NotesView = NotesView;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
