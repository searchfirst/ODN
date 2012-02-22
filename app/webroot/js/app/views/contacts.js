dac.ContactsListView = cbb.ListView.extend({
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.contact.list').get(0));
    },
    itemWidgets: {
        'cnrsEditable [contenteditable]': { save: 'update' },
        'cnrsCollapse .collapse': {}
    },
    itemTagName: 'article',
    modelName: 'contact'
});
