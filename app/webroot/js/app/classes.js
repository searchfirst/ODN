var DuxModel = Backbone.Model.extend({
	fetch: function(options) {
		options || (options = {});
		var self = this,
				success = options.success;
		self.trigger('fetching');
		options.success = function(resp) {
			if (success) { success(self, resp); }
			self.trigger('fetched',self);
		};
		Backbone.Model.prototype.fetch.call(self, options);
	}
}),
DuxController = Backbone.Controller.extend({
	initialize: function(options) {
		this.bind('route',function(){
			this.viewVars = {};
			this.viewTemplate = false;
		});
	},
	parseData: function() {
		if (window.location && window.location.search) {
			var rawMeat = window.location.search.substr(1).split('&'),
			p = _(rawMeat)
			.chain()
			.select(function(v){ return v.match(/^data/); })
			.map(function(x){
				var t = x.substr(4), s = t.split('='), v = s[1], k = s[0].replace(/\]\[/g,' ').replace(/\[|\]/g,'').split(' ');
				return {key:k, val:v};
			})
			.reduce(function(memo,x) {
				var c = x.key.length, b = false;
				for (var y=0;y<c;y++) {
					if (!b && !memo[x.key[y]]) {
						memo[x.key[y]] = y<c-1 ? {} : x.val;
						b = memo[x.key[y]];
					} else if (!b[x.key[y]]) {
						b[x.key[y]] = y<c-1 ? {} : x.val;
						b = b[x.key[y]];
					} else {
						if (y == c-1) { b[x.key[y]] = x.val; }
						b = b[x.key[y]];
					}
				}
				return memo;
			}, {})
			.value();
			return p;
		} else { return false; }
	},
	templates: CnrsTemplates,
	render: function(template,data) {this.view.render(this.templates.compile(template),data);},
	route: function(route, name, callback) {
		Backbone.history || (Backbone.history = new Backbone.History);
		if (!_.isRegExp(route)) route = this._routeToRegExp(route);
		Backbone.history.route(route, _.bind(function(fragment) {
			var args = this._extractParameters(route, fragment);
			callback.apply(this, args);
			this.trigger.apply(this, ['route:' + name].concat(args));
			this.trigger.apply(this, ['route']);
		}, this));
	}
}),
DuxCollection = Backbone.Collection.extend({
	initialize: function(options) {
		if (options) {
			if (options.page !== undefined && options.page != 0) {
				this.page = options.page;
			}
			if (options.params && typeof options.params == 'object') {
				this.params = options.params;
			}
			if (options.modelName !== undefined) {
				this.modelName = options.modelName;
			}
		}
	},
	comparator: function(model) {
		var created = model.get('created');
		return created ? created.replace(/\D/g,'') * -1 : 0;
	},
	fetch: function(options) {
		options || (options = {});
		var self = this,
				success = options.success;
		self.trigger('fetching');
		options.success = function(resp) {
			if (success) { success(self, resp); }
			self.trigger('fetched',self);
		};
		Backbone.Collection.prototype.fetch.call(self, options);
	},
	parse: function(resp) {
		if (resp.page != undefined && resp.per_page != undefined && resp.total != undefined) {
			this.page = resp.page;
			this.perPage = resp.per_page;
			this.total = resp.total;
			return resp.models
		} else {
			return resp;
		}
	},
	fresh: function() {
		delete this.perPage;
		delete this.total;
		this._reset();
		this.page = 1;
		return this;
	},
	url: function() {
		var base = '', params = this.page ? '?' + $.param(_.extend(this.params||{},{page: this.page})) : '';
		if (this.baseUrl) {
			base = typeof this.baseUrl == 'function' ? this.baseUrl() : this.baseUrl;
		} else if (this.name) {
			base = '/' + this.name;
		}
		return base + params;
	},
	pageInfo: function() {
		if (this.page && this.perPage && this.total) {
			var info = {
				total: this.total,
				page: this.page,
				perPage: this.perPage,
				pages: Math.ceil(this.total / this.perPage),
				prev: false,
				next: false
			};
			var max = Math.min(this.total, this.page * this.perPage);
			if (this.total == this.pages * this.perPage) {
				max = this.total;
			}
			info.range = [(this.page - 1) * this.perPage + 1, max];
			if (this.page > 1) {
				info.prev = this.page - 1;
			}
			if (this.page < info.pages) {
				info.next = this.page + 1;
			}
			return info;
		} else {
			return undefined;
		}
	},
	nextPage: function() {
		if (this.page && this.pageInfo().next) {
			this.page = this.page + 1;
			this.fetch();
			return this;
		} else {
			return false;
		}
	},
	previousPage: function() {
		if (this.page && this.pageInfo().prev) {
			this.page = this.page - 1;
			this.fetch();
			return this;
		} else {
			return false;
		}
	}
}),
DuxView = Backbone.View.extend({
	initialize: function(options) {
		if (options) {
			if (options.model !== undefined) { this.model = options.model; delete options.model; }
			if (options.widgets !== undefined) { this.widgets = options.widgets; delete options.widgets; }
			if (options.itemWidgets !== undefined) { this.itemWidgets = options.itemWidgets; delete options.itemWidgets; }
			if (options.modelName !== undefined) { this.modelName = options.modelName; delete options.modelName; }
			if (options.itemTagName !== undefined) { this.itemTagName = options.itemTagName; delete options.itemTagName; }
			if (options.extras !== undefined) { this.extras = options.extras; delete options.extras; }
			if (options.gotoViewOnAdd !== undefined) {
				this.gotoViewOnAdd = options.gotoViewOnAdd;
				delete options.gotoViewOnAdd;
			}
			if (options.hideFormOnSubmit !== undefined) {
				this.hideFormOnSubmit = options.hideFormOnSubmit;
				delete options.hideFormOnSubmit;
			}
			if (options.showButtons !== undefined) {
				this.showButtons = options.showButtons;
				delete options.showButtons;
			}
		}
	},
	widgets: {
		// 'plugin selector': []
	},
	templates: CnrsTemplates,
	commonWidgets: function($rootElement) {
		$rootElement.find('ul.tab_hooks').duxTab();
		//$rootElement.find('h1,h2').hookMenu();
		for (widget in this.widgets) {
			var eventSplitter = /^(\w+)\s*(.*)$/,
				match = widget.match(eventSplitter),
				$selector = $(match[2],$rootElement.get()),
				method = match[1],
				params = this.widgets[widget],
				_params = [];

			for (p in params) {
				_params[p] = _.clone(params[p]);
			}

			for (a in _params) {
				for (b in _params[a]) {
					console.log(typeof _params[a][b]);
					if (typeof _params[a][b] === 'string' && _params[a][b].match(/^cb_/)) {
						var callback = _params[a][b].substr(3);
						_params[a][b] = _.bind(this[callback],this);
					}
				}
			}

			if (_params !== undefined) {
				$selector[method].apply($selector, _params);
			} else {
				$selector[method]();
			}
		}
	},
	render: function(template,data) {
		var $thisel = typeof this.el == 'function' ? $(this.el()) : $(this.el);
		if ($thisel.length > 0) {
			$thisel.html(template(data));
			this.commonWidgets($thisel);
		}
		return this;
	},
	redirect: function(url) {
		Backbone.history.saveLocation('#' + url);
		Backbone.history.loadUrl();
	},
	asdfdelegateEvents: function(events) {
		var eventSplitter = /^(\w+)\s*(.*)$/,
				$thisel = typeof this.el == 'function' ? $(this.el()) : $(this.el);
		if (!(events || (events = this.events))) return;
		$thisel.unbind();
		for (var key in events) {
			var methodName = events[key],
					match = key.match(eventSplitter),
					eventName = match[1], selector = match[2],
					method = _.bind(this[methodName], this);
			if (selector === '') {
				$thisel.bind(eventName, method);
			} else {
				$thisel.delegate(selector, eventName, method);
			}
		}
	},
	add: function(e) {
		e.preventDefault();
		var $target = $(e.target),
			target = e.target,
			collection = this.collection,
			$inputs = $('input,textarea,select',e.target).not('input[type=submit]'),
			inputSplitter = /^((\w+)\.)?(\w+)$/,
			gotoViewOnAdd = this.gotoViewOnAdd,
			hideFormOnSubmit = this.hideFormOnSubmit,
			thisview = this,
			model = {};

		$inputs.each(function(i){
			var value = $(this).val(),
				match = $(this).attr('name').match(inputSplitter),
				mField = match[2],
				field = match[3];
			if (mField !== undefined) {
				model[mField] || (model[mField] = {})
				model[mField][field] = value;
			} else {
				model[field] = value;
			}
		});

		var newModel = new this.collection.model(model);
		newModel.save(null,{
			success: function(model, response){
				collection.add([model]);
				$target.removeClass('ajax-error');
				target.reset();
				if (gotoViewOnAdd) {
					thisview.redirect('/' + collection.name + '/view/' + model.get('id'));
				}
				if (hideFormOnSubmit) {
					$target.fadeOut('fast');
				}
			},
			error: function(model, response) {
				$target.addClass('ajax-error');
			}
		});

	},
	update: function(el,callbacks) {
		var saveSet = {},
			model = this.model,
			$el = $(el),
			multiEdit = el.nodeName == 'DIV',
			field = $el.data('field'),
			value = multiEdit ? $el.html() : $el.text(),
			r = {success: false,ret: false};

		model.attributes[field] = value;
		model._changed = true;
		model.save(saveSet, {
			silent: true,
			success: function(model, resp) {
				callbacks.success();
				model.trigger('change');
			},
			error: callbacks.error
		});
		
		return r.success;
	},
	next: function() {
		this.collection.nextPage();
		return false;
	},
	prev: function() {
		this.collection.previousPage();
		return false;
	}
}),
DuxPageView = DuxView.extend({
	initialize: function(options) {
		DuxView.prototype.initialize.call(this,options);
		if (options) {
			if (options.context) {
				this.context = options.context;
			}
			if (options.viewTemplate) {
				if (typeof options.viewTemplate === 'string') {
					this.viewTemplate = this.templates.compile(options.viewTemplate);
				} else {
					this.viewTemplate = options.viewTemplate;
				}
			}
			if (options.events) {
				this.events || ( this.events = {} );
				_(this.events).extend(options.events);
				delete options.events;
				this.delegateEvents();
			}
		}
		this.bind('rendered',this.rendered);
	},
	render: function() {
		var $thisEl = $(this.el),
			data = (this.context && this[this.context]) ? this[this.context].toJSON() : {};
		$thisEl.html(this.viewTemplate(data));
		this.commonWidgets($thisEl);
		this.trigger('rendered',this.rendered);
		return this;
	},
	rendering: function() {
		$(this.el).fadeTo(0.5,0.5);
		return this;
	},
	filterBy: function(e) {
		var filter = $(e.target).text();
		this.collection.params.filter = filter;
		this.collection.fetch();
	},
	rendered: function() {
		$(this.el).fadeTo(0.5,1);
		return this;
	}
}),
DuxListView = DuxView.extend({
	modelName: undefined,
	itemTagName: 'li',
	gotoViewOnAdd: false,
	showButtons: true,
	hideFormOnSubmit: true,
	initialize: function(options) {
		DuxView.prototype.initialize.call(this, options);
		$(this.el).addClass('paginated');
		this.collection
			.bind('add', _.bind(this.redrawItems,this))
			.bind('fetched', _.bind(this.redrawItems,this))
			.bind('fetching', _.bind(this.fetchingItems,this));
	},
	events: {
		'click .next': 'next',
		'click .prev': 'prev',
		'click .p_form a[data-type="add"]': 'renderAddForm',
		'submit form[action*="add"]': 'add'
	},
	fetchingItems: function() {
		$(this.el).fadeTo(0.5,0.5);
	},
	renderAddForm: function(e) {
		e.preventDefault();
		var $thisel = $(this.el),
			formTemplate = this.templates.compile(this.modelName.toLowerCase() + 'ItemAdd'),
			data = this._extendDataWithExtras({ customer_id: this.collection.params.customer_id }),
			$pForm = $thisel.find('.p_form'),
			$form = $(formTemplate(data)).insertBefore($pForm.get(0));

		this.commonWidgets($form);
		console.log($form.get(0));
		$pForm.fadeOut('fast');
	},
	_extendDataWithExtras: function(data) {
		if (this.extras !== undefined) {
			if (this.extras.users !== undefined) {
				data.users = this.extras.users.toJSON();
			}
			if (this.extras.websites !== undefined) {
				data.websites = this.extras.websites.toJSON();
			}
			if (this.extras.services !== undefined) {
				data.services = this.extras.services.toJSON();
			}
		}
		return data;
	},
	redrawItems: function() {
		var paginationTemplate = this.templates.compile('pagination'),
			buttonTemplate = this.templates.compile(this.modelName.toLowerCase() + 'Buttons'),
			$thisEl = $(this.el);
		this.$('article, .pagelinks, .emptycollection, ul[data-icontainer], .p_form').remove();
		this.$('.loading').fadeOut('fast').remove();
		if (this.collection.models.length > 0) {
			if (this.itemTagName === 'li') {
				$itemContainer = $thisEl.find('ul[data-icontainer]');
				if ($itemContainer.length === 0) {
					$itemContainer = $('<ul data-icontainer="1" class="mini list"></ul>').appendTo(this.el);
				}
			}
			for (i in this.collection.models) {
				var model = this.collection.models[i],
					itemView = new DuxItemView({
						tagName: this.itemTagName,
						viewTemplate: this.modelName.toLowerCase() + 'ItemView',
						widgets: this.itemWidgets,
						model: model
					});

				if (this.itemTagName === 'li') {
					$itemContainer.append(itemView.render().el);
				} else {
					$thisEl.append(itemView.render().el);
				}
			}
			$thisEl.append(paginationTemplate({
				model: this.modelName,
				pageInfo: this.collection.pageInfo()
			}));
		} else {
			$thisEl.append(this.templates.compile('emptyCollection')({
				modelName: this.modelName
			}));
		}
		if (this.showButtons) {
			$thisEl.append(buttonTemplate());
		}
		$thisEl.fadeTo(0.5,1);
	}
}),
DuxItemView = DuxView.extend({
	modelName: undefined,
	tagName: 'li',
	initialize: function(options) {
		DuxView.prototype.initialize.call(this, options);
		if (options !== undefined) {
			if (options.viewTemplate && typeof options.viewTemplate == 'string') {
				this.viewTemplate = this.templates.compile(options.viewTemplate);
			}
		}
	},
	render: function() {
		var $thisel = $(this.el);
		$thisel.html(this.viewTemplate(this.model.toJSON()));
		this.commonWidgets($thisel);
		return this;
	}
});
