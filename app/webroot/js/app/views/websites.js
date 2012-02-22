dac.WebsitesListView = cbb.ListView.extend({
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.website.list').get(0));
    },
    itemWidgets: {
        'cnrsEditable [contenteditable]': {save: 'update'},
        'cnrsCollapse .collapse': {}
    },
    itemTagName: 'article',
    modelName: 'Website'
});
