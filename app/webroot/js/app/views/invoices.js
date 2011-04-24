var InvoicesView = DuxView.extend({
	el: $('section[role=main]').get(0)
}),
InvoiceItemView = DuxItemView.extend({
	tagName: "article"
}),
InvoicesListView = DuxView.extend({
	initialize: function(options) {
		$(this.el).addClass('paginated');
		this.collection.bind('fetched', _.bind(this.redrawItems,this));
		this.collection.bind('fetching', _.bind(this.fetchingItems,this));
		DuxView.prototype.initialize.call(this, options);
	},
	events: {
		'click .next': 'next',
		'click .prev': 'prev'
	},
	fetchingItems: function() {
		$(this.el).fadeTo(0.5,0.5);
	},
	redrawItems: function() {
		var paginationTemplate = this.templates.compile('pagination'),
			$thisEl = $(this.el);
		this.$('article, .pagelinks, .emptycollection').remove();
		this.$('.loading').fadeOut('fast').remove();
		if (this.collection.models.length > 0) {
			for (i in this.collection.models) {
				var invoice = this.collection.models[i],
					invoiceItemView = new InvoiceItemView(
						{viewTemplate: 'invoiceItemView',model: invoice}
					);

				$thisEl.append(invoiceItemView.render().el);
			}
			$thisEl.append(paginationTemplate({
				model: 'Invoice',
				pageInfo: this.collection.pageInfo()
			}));
		} else {
			$thisEl.append('<p class="emptycollection">No Invoices</p>');
		}
		$thisEl.fadeTo(0.5,1);
	}
});
