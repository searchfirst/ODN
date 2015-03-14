dac.InvoicesListView = cbb.ListView.extend({
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.invoice.list').get(0));
    },
    itemWidgets: {
        'cnrsEditable [contenteditable]': {save: 'update'},
        'cnrsCollapse .collapse': {}
    },
    _parentModelSerialise: function () {
        var data = {};
        data = this.parentModel.toJSON();
        data.services = this.parentModel.services.toJSON();
        return data;
    },
    itemTagName: 'article',
    hideFormOnSubmit: true,
    modelName: 'Invoice',
    widgets: {
        'datepicker input[type="date"]': { dateFormat: 'yy-mm-dd' }
    },
    renderAddForm: function (e) {
        cbb.ListView.prototype.renderAddForm.apply(this, arguments);
        var customer_id = ('00000' + this.parentModel.get('id') + '').slice(-4);
        var count = ('000000' + (this.parentModel.invoices.length + 1) + '').slice(-3);
        var today = new Date();
        var dateStr = (today.getFullYear() + '').substr(2) + ('000' + (today.getMonth() + 1) + '').slice(-2) + ('000' + (today.getDate() + 1) + '').slice(-2);

        document.getElementById('InvoiceReference').value = customer_id + '-' + count + '-' + dateStr;
    }
});
dac.InvoicesView = cbb.PageView.extend({
    events: {
        'click [role=button][data-type=cancel]': '_doCancel',
        'click [role=button][data-type=generate-invoice]': '_doGenerateInvoice',
        'click [role=button][data-type=generate-paid-invoice]': '_doGeneratePaidInvoice',
        'click [role=button][data-type=mark-invoice-paid]': '_doMarkInvoicePaid'
    },
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
    _doCancel: function () {
        var invoice = this.model;
        var message = 'Are you sure?';
        var router = this.router;

        $('<p>'+message+'</p>').dialog({
            modal: true,
            buttons: {
                'Cancel invoice': function () {
                    var $this = $(this);
                    invoice.set('cancelled', true);
                    invoice.save(null, {
                        success: function () {
                            router.navigate('/customers/view/' + invoice.get('customer_id'), true);
                            $this.dialog('close');
                        }
                    });
                },
                'Don\'t cancel': function () {
                    $(this).dialog('close');
                }
            }
        });
    },
    _doMarkInvoicePaid: function () {
        var invoice = this.model;
        var router = this.router;
        var dateInvoicePaid = document.getElementById('date_invoice_paid').value;

        if (dateInvoicePaid != "") {
            dateInvoicePaid += ' 00:00:00';
            console.log(dateInvoicePaid);
            invoice.save('date_invoice_paid', dateInvoicePaid, {
                success: function () {
                    //router.navigate('/customers/view/' + invoice.get('customer_id'), true);
                }
            });
        }
        else {
            alert('Please select a date first');
        }
    },
    _doGenerateInvoice: function () {
        window.location.href='/invoices/view/' + this.model.get('id') + '.pdf';
    },
    _doGeneratePaidInvoice: function () {
        window.location.href='/invoices/view/' + this.model.get('id') + '.pdf?AdditionalInformation=Paid in full';
    }
});
