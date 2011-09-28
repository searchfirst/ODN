    dac.UtilitiesRouter = cbb.Router.extend({
        routes: {
            'utilities': 'index'
        },
        index: function() {
            var view = new dac.UtilitiesView({
                el: $('[role=main]').get(0),
                viewTemplate: 'utilitiesIndex'
            });
            view.rendering().index();
        },
    });
