    dac.CustomersView = cbb.PageView.extend({
        events: {
            'submit .p_form form[action="/customers/add"]': 'add',
            'click a[href^=/invoices/view]': '_navigateInvoiceView',
            'click ul[data-field="filter"] span': '_filterBy'
        },
        view: function(id) {
            this.trigger('reset')
                .trigger('rendering');
            this.model = new dac.Customer({
                id: +id,
                childOptions: {
                    page: 1,
                    params: {
                        customer_id: +id
                    },
                    watch: {
                        parent: this,
                        event: 'renderChildren'
                    }
                }
            });
            this.model
                .bind('change', this.render, this)
                .fetch();
            this.bind('rendered', function() {
                var customer = this.model;
                this.views = {
                    contacts: new cbb.ListView({
                        modelName: 'Contact',
                        el: $('.contact.list').get(0),
                        collection: customer.contacts,
                        parentModel: customer,
                        itemWidgets: {
                            'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                            'cnrsCollapse .collapse': [{}]
                        },
                        itemTagName: 'article'
                    }),
                    notes: new cbb.ListView({
                        collection: customer.notes,
                        el: $('.note.list').get(0),
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
                        hideFormOnSubmit: false,
                        itemTagName: 'article',
                        itemWidgets: {
                            'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                            'cnrsCollapse .collapse': [{}]
                        },
                        modelName: 'Note',
                        parentModel: customer,
                        showButtons: false,
                        widgets: {
                            'autosaveable textarea.autosave': [{
                                uid: 'customerViewAddNote',
                                bind: [
                                    [customer.notes, 'add', 'removeState']
                                ]
                            }]
                        }
                    }),
                    websites: new cbb.ListView({
                        modelName: 'Website',
                        el: $('.website.list').get(0),
                        collection: customer.websites,
                        parentModel: customer,
                        itemWidgets: {
                            'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                            'cnrsCollapse .collapse': [{}]
                        },
                        itemTagName: 'article'
                    }),
                    services: new cbb.ListView({
                        modelName: 'Service',
                        el: $('.service.list').get(0),
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
                        parentModel: customer,
                        itemWidgets: {
                            'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                            'cnrsSelectable .selectable[data-field="status"]': [{
                                save: 'cb_update',
                                options: dac.ServicesCollection.prototype.status
                            }],
                            'cnrsSelectable .selectable[data-field="user_id"]': [{
                                save: 'cb_update',
                                options: customer.extras.users.toStatusList()
                            }],
                            'cnrsSelectable .selectable[data-field="website_id"]': [{
                                save: 'cb_update',
                                options: customer.extras.websites.toStatusList()
                            }],
                            'cnrsCollapse .collapse': [{}]
                        },
                        itemTagName: 'article'
                    }),
                    invoices: new cbb.ListView({
                        modelName: 'Invoice',
                        el: $('.invoice.list').get(0),
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
                        parentModel: customer,
                        itemWidgets: {
                            'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                            'cnrsCollapse .collapse': [{}]
                        },
                        itemTagName: 'article',
                        widgets: {
                            'datepicker input[type="date"]': [{ dateFormat: 'yy-mm-dd', }]
                        }
                    }),
                    customers: new cbb.ListView({
                        modelName: 'Customer',
                        el: $('.customer.list').get(0),
                        collection: customer.customers,
                        parentModel: customer,
                        gotoViewOnAdd: true
                    })
                };
            }, this);
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
