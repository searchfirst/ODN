    dac.Contact = cbb.Model.extend({
            name: 'Contact',
            url: function(){return '/contacts' + ( this.get('id') ? '/' + this.get('id'): '' )}
    });
    dac.ContactsCollection = cbb.Collection.extend({
            name: 'contacts',
            model: dac.Contact,
    });
