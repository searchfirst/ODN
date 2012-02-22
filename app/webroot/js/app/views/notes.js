dac.NotesListView = cbb.ListView.extend({
    hideFormOnSubmit: false,
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.note.list').get(0));
    },
    itemTagName: 'article',
    itemWidgets: {
        'cnrsEditable [contenteditable]': {save: 'update'},
        'cnrsCollapse .collapse': {}
    },
    modelName: 'Note',
    showButtons: false
});
