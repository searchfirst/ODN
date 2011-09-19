    dac.CustomersView = cbb.PageView.extend({
        events: {
            'submit .p_form form[action="/customers/add"]': 'add',
            'click a[href^=/invoices/view]': '_navigateInvoiceView',
            'click ul[data-field="filter"] span': '_filterBy'
        },
        view: function(id) {
            var e = {
                    users: new dac.UsersCollection({
                        page:1,
                        limit:'all',
                        watch:{parent: this.model, event: 'childAdd'}
                    }),
                    websites: new dac.WebsitesCollection({
                        page:1,
                        params:{limit:'all', customer_id:id},
                        watch:{parent: this.model, event: 'childAdd'}
                    }),
                    services: new dac.ServicesCollection({
                        page:1,
                        params:{limit:'all', customer_id:id},
                        watch:{parent: this.model, event: 'childAdd'}
                    }),
                };
            e.users.fetch();
            e.websites.fetch();
            e.services.fetch();
            this.model
                .bind('change', this.render, this)
                .fetch();
            this
                .bind('rendered', function() {
                    var collectionParams = {
                            page: 1,
                            params: { customer_id: id },
                            watch: { parent: this, event: 'renderChildren' }
                        },
                        contactsCollection = new dac.ContactsCollection(collectionParams),
                        notesCollection = new dac.NotesCollection(collectionParams),
                        websitesCollection = new dac.WebsitesCollection(collectionParams),
                        servicesCollection = new dac.ServicesCollection(collectionParams),
                        invoicesCollection = new dac.InvoicesCollection(collectionParams),
                        customersCollection = new dac.CustomersCollection(collectionParams),
                        contactsView = new cbb.ListView({
                            modelName: 'Contact',
                            el: $('.contact.list').get(0),
                            collection: contactsCollection,
                            parentModel: this.model,
                            itemWidgets: {
                                'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                                'cnrsCollapse .collapse': [{}]
                            },
                            itemTagName: 'article'
                        }),
                        notesView = new cbb.ListView({
                            collection: notesCollection,
                            el: $('.note.list').get(0),
                            extras: {
                                users: {
                                    collection: e.users,
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
                            parentModel: this.model,
                            showButtons: false,
                            widgets: {
                                'autosaveable textarea.autosave': [{
                                    uid: 'customerViewAddNote',
                                    bind: [
                                        [notesCollection, 'add', 'removeState']
                                    ]
                                }]
                            }
                        }),
                        websitesView = new cbb.ListView({
                            modelName: 'Website',
                            el: $('.website.list').get(0),
                            collection: websitesCollection,
                            parentModel: this.model,
                            itemWidgets: {
                                'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                                'cnrsCollapse .collapse': [{}]
                            },
                            itemTagName: 'article'
                        }),
                        servicesView = new cbb.ListView({
                            modelName: 'Service',
                            el: $('.service.list').get(0),
                            collection: servicesCollection,
                            extras: {
                                users: {
                                    collection: e.users,
                                    config: {
                                        findEl: 'select[data-selectable-for="user_id"]',
                                        keyName: 'id',
                                        valueName: 'name',
                                        modelFieldName: 'user_id'
                                    }
                                },
                                websites: {
                                    collection: e.websites,
                                    config: {
                                        findEl: 'select[data-selectable-for="website_id"]',
                                        keyName: 'id',
                                        valueName: 'uri',
                                        modelFieldName: 'website_id'
                                    }
                                },
                            },
                            parentModel: this.model,
                            itemWidgets: {
                                'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                                'cnrsSelectable .selectable[data-field="status"]': [{
                                    save: 'cb_update',
                                    options: dac.ServicesCollection.prototype.status
                                }],
                                'cnrsSelectable .selectable[data-field="user_id"]': [{
                                    save: 'cb_update',
                                    options: e.users.toStatusList()
                                }],
                                'cnrsCollapse .collapse': [{}]
                            },
                            itemTagName: 'article'
                        }),
                        invoicesView = new cbb.ListView({
                            modelName: 'Invoice',
                            el: $('.invoice.list').get(0),
                            collection: invoicesCollection,
                            extras: {
                                users: {
                                    collection: e.users,
                                    config: {
                                        findEl: 'select[data-selectable-for="user_id"]',
                                        keyName: 'id',
                                        valueName: 'name',
                                        modelFieldName: 'user_id'
                                    }
                                },
                                services: {
                                    collection: e.services,
                                    config: {
                                        findEl: 'select[data-selectable-for="service_id"]',
                                        keyName: 'id',
                                        valueName: 'title',
                                        modelFieldName: 'service_id'
                                    }
                                },
                                websites: {
                                    collection: e.websites,
                                    config: {
                                        findEl: 'select[data-selectable-for="website_id"]',
                                        keyName: 'id',
                                        valueName: 'uri',
                                        modelFieldName: 'website_id'
                                    }
                                },
                            },
                            parentModel: this.model,
                            itemWidgets: {
                                'cnrsEditable [contenteditable]': [ {save: 'cb_update'} ],
                                'cnrsCollapse .collapse': [{}]
                            },
                            itemTagName: 'article',
                            widgets: {
                                'datepicker input[type="date"]': [{ dateFormat: 'yy-mm-dd', }]
                            }
                        }),
                        customersView = new cbb.ListView({
                            modelName: 'Customer',
                            el: $('.customer.list').get(0),
                            collection: customersCollection,
                            parentModel: this.model,
                            gotoViewOnAdd: true
                        });
                    this.trigger('renderChildren');
                });
        },
        index: function(filter) {
            this
                .bind('rendered', function(){
                    var customersView = new cbb.ListView({
                            modelName: 'Customer',
                            el: $('.customer.list').get(0),
                            collection: new dac.CustomersCollection({
                                page: 1,
                                params:{limit: 'all',filter: filter},
                                watch: { parent: this, event: 'renderChildren' }
                            }),
                            gotoViewOnAdd: true
                        });
                    this.trigger('renderChildren');
                })
                .render();
        },
        _filterBy: function(e) {
            e.preventDefault();
            var filter = $(e.target).text();
            this.router.navigate('customers?f='+filter,true);
        },
        _navigateInvoiceView: function(e) {
            e.preventDefault();
            var url = $(e.target).attr('href').match(/^\/?(.*)$/)[1];
            this.router.navigate(url,true);
        }
    });
