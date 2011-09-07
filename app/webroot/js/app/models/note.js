(function(window,document,cbb,duxAppClasses,undefined){
	var Note = cbb.Model.extend({
			name: 'Note',
			url: function(){return '/notes' + ( this.get('id') ? '/' + this.get('id'): '' )}
		}),
		NotesCollection = cbb.Collection.extend({
			name: 'notes',
			model: Note,
		});
	duxAppClasses.Note = Note;
	duxAppClasses.NotesCollection = NotesCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
