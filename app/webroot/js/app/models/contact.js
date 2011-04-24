var Contact = DuxModel.extend({
	name: 'Contact',
	url: function(){return '/contacts' + ( this.get('id') ? '/' + this.get('id'): '' )}
}),
ContactsCollection = DuxCollection.extend({
	name: 'contacts',
	model: Contact,
});
