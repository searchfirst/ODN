var Note = DuxModel.extend({
	name: 'Note',
	url: function(){return '/notes' + ( this.get('id') ? '/' + this.get('id'): '' )}
}),
NotesCollection = DuxCollection.extend({
	name: 'notes',
	model: Note,
});
