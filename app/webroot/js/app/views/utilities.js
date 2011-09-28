    dac.UtilitiesView = cbb.PageView.extend({
        index: function() {
            this
                .bind('rendered', function() {
                    var utilitiesCollection = new dac.UtilitiesCollection({
                            watch: {
                                parent: this,
                                event: 'renderChildren'
                            }
                        }),
                        utilitiesView = new cbb.ListView({
                            collection: utilitiesCollection,
                            el: $('.utilities.list').get(0),
                            modelName: 'Utility',
                            showButtons: false
                        });

                    window.uv = utilitiesView;
                })
                .render()
                .trigger('renderChildren');
        }
    });
