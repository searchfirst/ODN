dac.CustomersListView = cbb.ListView.extend({
    gotoViewOnAdd: true,
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.customer.list').get(0));
    },
    modelName: 'Customer'
});
dac.CustomersView = cbb.PageView.extend({
    events: {
        'submit .p_form form[action="/customers/add"]': 'add',
        'click a[href^=/invoices/view]': '_navigateInvoiceView',
        'click ul[data-field="filter"] span': '_filterBy'
    },
    view: function(id) {
        this.trigger('reset').trigger('rendering');
        this.model = new dac.Customer({
                id: +id,
            },
            {
                childOptions: {
                    page: 1,
                    params: {
                        customer_id: +id
                    },
                    watcher: {
                        parent: this,
                        event: 'renderChildren'
                    }
                }
            }
        );
        this.model.bind('change', this.render, this).fetch();
        this.bind('rendered', this._renderedView, this);
    },
    _renderedView: function() {
        var customer = this.model;
        this
            .trigger('set_title', customer.get('company_name'))
            .addSubView('contacts', new dac.ContactsListView({
                collection: customer.contacts,
                parentModel: customer
            }))
            .addSubView('notes', new dac.NotesListView({
                collection: customer.notes,
                parentModel: customer,
                extras: {
                    users: {
                        collection: customer.extras.users,
                        config: {
                            findEl: 'select[data-selectable-for="user_id"]',
                            keyName: 'id',
                            valueName: 'name',
                            modelFieldName: 'user_id'
                        }
                    }
                },
                widgets: {
                    'autosaveable textarea.autosave': {
                        uid: 'customerViewAddNote',
                        bind: [
                            [customer.notes, 'add', 'removeState']
                        ]
                    }
                }
            }))
            .addSubView('websites', new dac.WebsitesListView({
                collection: customer.websites,
                parentModel: customer
            }))
            .addSubView('services', new dac.ServicesListView({
                collection: customer.services,
                extras: {
                    users: {
                        collection: customer.extras.users,
                        config: {
                            findEl: 'select[data-selectable-for="user_id"]',
                            keyName: 'id',
                            valueName: 'name',
                            modelFieldName: 'user_id'
                        }
                    },
                    websites: {
                        collection: customer.extras.websites,
                        config: {
                            findEl: 'select[data-selectable-for="website_id"]',
                            keyName: 'id',
                            valueName: 'uri',
                            modelFieldName: 'website_id'
                        }
                    },
                },
                itemWidgets: {
                    'cnrsEditable [contenteditable]': {save: 'update'},
                    'cnrsSelectable .selectable[data-field="status"]': {
                        save: 'update',
                        options: dac.ServicesCollection.prototype.status
                    },
                    'cnrsSelectable .selectable[data-field="user_id"]': {
                        save: 'update',
                        options: customer.extras.users.toStatusList()
                    },
                    'cnrsSelectable .selectable[data-field="website_id"]': {
                        save: 'update',
                        options: customer.extras.websites.toStatusList()
                    },
                    'cnrsCollapse .collapse': {}
                },
                parentModel: customer
            }))
            .addSubView('invoices', new dac.InvoicesListView({
                collection: customer.invoices,
                extras: {
                    users: {
                        collection: customer.extras.users,
                        config: {
                            findEl: 'select[data-selectable-for="user_id"]',
                            keyName: 'id',
                            valueName: 'name',
                            modelFieldName: 'user_id'
                        }
                    },
                    services: {
                        collection: customer.extras.services,
                        config: {
                            findEl: 'select[data-selectable-for="service_id"]',
                            keyName: 'id',
                            valueName: 'title',
                            modelFieldName: 'service_id'
                        }
                    },
                    websites: {
                        collection: customer.extras.websites,
                        config: {
                            findEl: 'select[data-selectable-for="website_id"]',
                            keyName: 'id',
                            valueName: 'uri',
                            modelFieldName: 'website_id'
                        }
                    },
                },
                parentModel: customer
            }))
            .addSubView('customers', new dac.CustomersListView({
                collection: customer.customers,
                parentModel: customer
            }));
    },
    index: function(filter) {
        this
            .trigger('reset')
            .trigger('rendering');
        this.collection = new dac.CustomersCollection({
            page: 1,
            params: {
                limit: 'all',
                filter: filter
            }
        });
        this.bind('rendered', function() {
                var customers = this.collection;
                this.trigger('set_title', 'Customers');
                this.views.customers = new cbb.ListView({
                    modelName: 'Customer',
                    el: $('.customer.list').get(0),
                    collection: customers,
                    gotoViewOnAdd: true
                });
                customers.fetch();
            })
            .render();
    },
    _filterBy: function(e) {
        e.preventDefault();
        var filter = $(e.target).text();
        if (this.collection) {
            var collection = this.collection,
                router = this.router;
            collection.params.filter = filter;
            collection.fetch({
                success: function() {
                    router.navigate('customers?f='+filter);
                }
            });
        } else {
            this.router.navigate('customers?f='+filter, true);
        }
    },
    _navigateInvoiceView: function(e) {
        e.preventDefault();
        var url = $(e.target).attr('href').match(/^\/?(.*)$/)[1];
        this.router.navigate(url,true);
    }
});
