    dac.SearchView = cbb.PageView.extend({
        el: $('[role=search]').get(0),
        events: {
            'keyup input[type="search"]': 'search',
            'change input[type="search"]': 'search',
            //'focusout': 'hide',
            'focusin': 'show',
            'keydown input[type="search"]': '_preventDefault'
        },
        initialize: function(options) {
            cbb.PageView.prototype.initialize.call(this, options);
            this.render();
            $('.search.list', this.el).addClass('empty');
            this.searchListView = new cbb.ListView({
                collection: new dac.SearchCollection({
                    page: 1,
                    params: {limit: 10, model: 'Customer'}
                }),
                el: $('[role=search] .search.list').get(0),
                modelName: 'Search',
                showButtons: false
            });
            this.searchTimer = 0;
            this.searchText = '';
            this.focusTimer = 0;
        },
        hide: function(e) {
            var view = this.searchListView,
                el = view.el,
                $el = $(el);

            setTimeout(function() {
                $el.addClass('empty', 500);
            }, 250);
        },
        show: function(e) {
            var view = this.searchListView,
                el = view.el,
                $el = $(el),
                $input = $('input[type="search"]', this.el);

            if ($input.val() !== '') {
                $el.removeClass('empty', 500);
            }
        },
        search: function(e) {
            var $target = $(e.target),
                query = $target.val(),
                which = e.which,
                view = this.searchListView,
                collection = view.collection,
                inputWasCleared = query === '',
                searchTextChanged = query !== this.searchText,
                pressedEscape = (which === 27),
                pressedEnter = (which === 13);

            if (pressedEnter) {
                clearTimeout(this.searchTimer);
                e.preventDefault();
            } else if (pressedEscape || inputWasCleared) {
                clearTimeout(this.searchTimer);
                collection.reset();
            } else if (searchTextChanged) {
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(function() {
                    collection.params.q = query;
                    collection.fetch();
                }, 500);
            }
            this.searchText = $target.val();
        },
        _preventDefault: function(e) {
            var which = e.which;
            if (which === 13) {
                e.preventDefault();
            }
        }
    });
