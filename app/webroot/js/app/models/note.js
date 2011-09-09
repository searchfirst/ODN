	dac.Note = cbb.Model.extend({
		name: 'Note',
		url: function(){return '/notes' + ( this.get('id') ? '/' + this.get('id'): '' )}
	});
	dac.NotesCollection = cbb.Collection.extend({
		name: 'notes',
		model: dac.Note,
	});
