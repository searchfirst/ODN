(function(window,document,cbb,duxAppClasses,undefined){
	var Contact = cbb.Model.extend({
			name: 'Contact',
			url: function(){return '/contacts' + ( this.get('id') ? '/' + this.get('id'): '' )}
		}),
		ContactsCollection = cbb.Collection.extend({
			name: 'contacts',
			model: Contact,
		});
	duxAppClasses.Contact = Contact;
	duxAppClasses.ContactsCollection = ContactsCollection;
})(this,document,this.connrsBackboneBoilerplate,this.duxAppClasses);
