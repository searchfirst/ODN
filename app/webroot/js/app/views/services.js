dac.ServicesListView = cbb.ListView.extend({
    initialize: function(options) {
        cbb.ListView.prototype.initialize.call(this, options);
        this.setElement($('.service.list').get(0));
    },
    itemTagName: 'article',
    modelName: 'Service'
});
