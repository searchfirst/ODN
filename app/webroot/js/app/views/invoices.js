dac.InvoicesListView = cbb.ListView.extend({
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.invoice.list').get(0));
    },
    itemWidgets: {
        'cnrsEditable [contenteditable]': {save: 'update'},
        'cnrsCollapse .collapse': {}
    },
    itemTagName: 'article',
    modelName: 'Invoice',
    widgets: {
        'datepicker input[type="date"]': { dateFormat: 'yy-mm-dd' }
    }
});
dac.InvoicesView = cbb.PageView.extend({
    view: function(id) {
        this.trigger('reset')
            .trigger('rendering');
        this.model = new dac.Invoice({
            id: +id
        },
        {
            childOptions: {
                page: 1,
                params: {
                    invoice_id: +id
                },
                watcher: {
                    parent: this,
                    event: 'renderChildren'
                }
            }
        });
        this.model
            .bind('change', this.render, this)
            .fetch();
    },
    index: function() {
        this
            .trigger('reset')
            .trigger('rendering');
        this.collection = new dac.InvoicesCollection({
            page: 1,
            params: {
                limit: 'all',
            }
        });
        this.bind('rendered', function() {
                var invoices = this.collection;
                this.views.invoices = new cbb.ListView({
                    modelName: 'Invoice',
                    el: $('.invoice.list').get(0),
                    collection: invoices,
                    gotoViewOnAdd: true
                });
                invoices.fetch();
            })
            .render();
    },
});
