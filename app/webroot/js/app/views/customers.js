var CustomersView = DuxPageView.extend({
	events: {
		'submit .p_form form[action="/customers/add"]': 'add',
		'click ul[data-field="filter"] span': 'filterBy'
	},

});
