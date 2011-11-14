    dac.Customer = cbb.Model.extend({
        name: 'Customer',
        url: function(){
            var id = this.get('id');
            return '/customers' + (id ? '/' + id : '');
        },
        initialize: function(options) {
            cbb.Model.prototype.initialize.call(this, options);
            if (this.collection == undefined && this.get('id')) {
                var childOptions = this.childOptions || {params: {customer_id: this.get('id')}};
                this.contacts = new dac.ContactsCollection(childOptions);
                this.websites = new dac.WebsitesCollection(childOptions);
                this.services = new dac.ServicesCollection(childOptions);
                this.invoices = new dac.InvoicesCollection(childOptions);
                this.customers = new dac.CustomersCollection(childOptions);
                this.notes = new dac.NotesCollection(childOptions);
                this.extras = {
                    users: new dac.UsersCollection({
                        page:1,
                        params: {
                            limit:'all'
                        },
                        watch: {
                            parent: this,
                            event: 'childAdd'
                        }
                    }),
                    websites: new dac.WebsitesCollection({
                        page:1,
                        params:{
                            limit: 'all',
                            customer_id: this.get('id')
                        },
                        watch: {
                            parent: this,
                            event: 'childAdd'
                        }
                    }),
                    services: new dac.ServicesCollection({
                        page:1,
                        params:{
                            limit: 'all',
                            customer_id: this.get('id')
                        },
                        watch: {
                            parent: this,
                            event: 'childAdd'
                        }
                    })
                };
                this.extras.users.fetch();
                this.extras.websites.fetch();
                this.extras.services.fetch();
            }
        }
    });
    dac.CustomersCollection = cbb.Collection.extend({
        name: 'customers',
        model: dac.Customer,
        comparator: function(customer) {
            return customer.get('company_name');
        }
    });
