    dac.FacadesView = cbb.PageView.extend({
        events: {
            'focus .customer.list .filter_hooks a': '_filterCustomers',
            'focus .note.list .filter_hooks a': '_filterNotes'
        },
        index: function() {
            this.trigger('reset')
                .trigger('rendering')
                .bind('rendered', this.renderedIndex)
                .render();
        },
        renderedIndex: function() {
            var customers = new dac.CustomersCollection({
                    baseUrl: '/customers/by_service',
                    page: 1,
                    params: {status: 2, limit: 'all'}
                }),
                notes = new dac.NotesCollection({
                    baseUrl: '/notes/you',
                    page: 1,
                    params: {flagged: 0, limit: 10}
                });

            this.addSubView('customers', new dac.CustomersListView({
                    collection: customers,
                    itemListTemplateStem: 'DetailsItemView',
                    itemTagName: 'article',
                    showButtons: false
                }))
                .addSubView('notes', new dac.NotesListView({
                    collection: notes,
                    itemListTemplateStem: 'DetailsItemView',
                    itemTagName: 'article',
                    showButtons: false
                }));

            notes.fetch();
            customers.fetch();
        },
        _filterCustomers: function(e) {
            e.preventDefault();
            var $target = $(e.target),
                $current = this.$('.customer.list .current'),
                $li = $target.parent('li'),
                paramField = $target.data('paramField'),
                paramVal = $target.data('paramVal'),
                view = this.views.customers
                collection = view.collection,
                hasChanged = false;

            if (collection.params[paramField] !== paramVal) {
                collection.params[paramField] = paramVal;
                hasChanged = true;
            }

            if (hasChanged === true) {
                collection.page = 1;
                collection.fetch({
                    success: function() {
                        $current.removeClass('current');
                        $li.addClass('current');
                    }
                });
            }
        },
        _filterNotes: function(e) {
            e.preventDefault();
            var $target = $(e.target),
                $current = $('.current', $target.parent().parent('ul')),
                $li = $target.parent('li'),
                paramField = $target.data('paramField'),
                paramVal = $target.data('paramVal'),
                view = this.views.notes,
                collection = view.collection,
                hasChanged = false;

            if (paramField === 'flagged' && collection.params[paramField] !== paramVal) {
                collection.params[paramField] = paramVal;
                hasChanged = true;
            } else if (paramField === 'you') {
                if (paramVal === 1 && collection.baseUrl === undefined) {
                    collection.baseUrl = '/notes/you';
                    hasChanged = true;
                } else if (paramVal === 0 && collection.baseUrl !== undefined) {
                    delete collection.baseUrl;
                    hasChanged = true;
                }
            }

            if (hasChanged === true) {
                collection.page = 1;
                collection.fetch({
                    success: function() {
                        $current.removeClass('current');
                        $li.addClass('current');
                    }
                });
            }
        }
    });
