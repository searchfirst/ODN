    dac.AppRouter = cbb.Router.extend({
        initialize: function(options) {
            cbb.Router.prototype.initialize.call(this, options);
            this.view = new dac.AppView({ router: this });
        },
        routes: {
            '': 'facadesIndex',
            'customers': 'customersIndex',
            'customers?f=:filter': 'customersIndex',
            'customers/view/:id': 'customersView',
            'invoices': 'invoicesIndex',
            'invoices/view/:id': 'invoicesView',
            'utilities': 'utilitiesIndex'
        },
        facadesIndex: function() {
            var view = new dac.FacadesView({
                el: $('[role=main]').get(0),
                router: this,
                viewTemplate: 'facadesIndex'
            });
            view.index();
        },
        customersView: function(id) {
            var view = new dac.CustomersView({
                    context: 'model',
                    el: $('[role=main]').get(0),
                    router: this,
                    viewTemplate: 'customersView',
                    widgets: {
                        'cnrsEditable [contenteditable]': [{save: 'cb_update'}],
                        'cnrsCollapse .collapse': [{}]
                    }
                });
            view.view(id);
        },
        customersIndex: function(filter) {
            var filter = filter || 'A',
                view = new dac.CustomersView({
                    el: $('[role=main]').get(0),
                    gotoViewOnAdd: true,
                    hideFormOnSubmit: false,
                    router: this,
                    viewTemplate: 'customersIndex'
                });
            view.index(filter);
        },
        utilitiesIndex: function() {
            var view = new dac.UtilitiesView({
                el: $('[role=main]').get(0),
                router: this,
                viewTemplate: 'utilitiesIndex'
            });
            view.index();
        },
        invoicesView: function(id) {
            var view = new dac.InvoicesView({
                context: 'model',
                el: $('[role=main]').get(0),
                router: this,
                viewTemplate: 'invoicesView'
            });
            view.view(id);
        }
    });
