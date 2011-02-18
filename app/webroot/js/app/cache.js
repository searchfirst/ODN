var CnrsCache = function(window,document,$,undefined) {
	var loaded = {};
	return {
		store: function(key,value) {
			loaded[key] = value;
			return this;
		},
		load: function(key) {
			if (key in loaded) {
				return loaded[key];
			} else {
				return false;
			}
		},
		list: function() {
			if (arguments[0] != undefined) {
				var snippet = arguments[0],
						regMatch = new RegExp('^'+snippet),
						fLoaded = {},
						keys = _(_(loaded).keys()).chain().select(function(v){ return !!v.match(regMatch)}).value();
				_(keys).each(function(v,k) { fLoaded[k] = v; });
				return fLoaded;
			} else {
				return loaded;
			}
		}
	}
}(this,this.document,jQuery);
_.extend(CnrsCache,Backbone.Events);
