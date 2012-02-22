dac.SearchView = cbb.PageView.extend({
    events: {
        'change input[type="search"]': 'search',
        'click .mini.list a': 'resultActivated',
        'keydown input[type="search"]': 'stopKeySubmission',
        'keyup input[type="search"]': 'search'
    },
    initialize: function(options) {
        this.setElement($('[role=search]').get(0));
        cbb.PageView.prototype.initialize.call(this, options);
        this.render();
        this.collection = new dac.SearchCollection({
            page: 1,
            params: {
                limit: 10,
                model: 'Customer'
            }
        });
        this.views = {
            search: new cbb.ListView({
                collection: this.collection,
                el: $('[role=search] .search.list').get(0),
                modelName: 'Search',
                showButtons: false
            })
        };
        this.searchTimer = 0;
        this.searchText = '';
    },
    resultActivated: function(e) {
        var type = e.type,
            url = $(e.target).attr('href').replace(/^\/?(.*)/, '$1');
        if (type == 'click') {
            e.preventDefault();
            this.resetSearch();
        }
        this.router.navigate(url, true);
    },
    resetSearch: function() {
        this.$('input').val('');
        this.collection.params.q = '';
        this.collection.reset();
    },
    search: function(e) {
        var $target = $(e.target),
            query = $target.val(),
            which = e.which,
            view = this.views.search,
            collection = view.collection,
            inputWasCleared = query === '',
            searchTextChanged = query !== collection.params.q,
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
            if (query !== '') {
                this.searchTimer = setTimeout(function() {
                    collection.page = 1;
                    collection.params.q = encodeURIComponent(query);
                    collection.fetch();
                }, 500);
            } else {
                collection.params.q = '';
                collection.page = 1;
                collection.reset();
            }
        }
    },
    stopKeySubmission: function(e) {
        var which = e.which;
        if (which === 13) {
            e.preventDefault();
        }
    }
});
