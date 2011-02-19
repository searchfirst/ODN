var CnrsTemplates = function(window,document,$,undefined) {
	var cache = CnrsCache,
			tSelf = this;
	return {
		compile: function(key) {
			var content, compiled_tpl;
			if (!key) {return false;}
			compiled_tpl = this.getCached(key);
			if (compiled_tpl === false) {
				var from_dom = $('#'+key);
				if (from_dom.length) {
					content = $('#'+key).text();
					compiled_tpl = Handlebars.compile(content);
					cache.store('cnrs_template_'+key,compiled_tpl);
				} else {
					return false;
				}
			}
			return compiled_tpl;
		},
		addPartial: function(key, value) {
			Handlebars.registerPartial(key, value);
			return this;
		},
		add: function(key, value) {
			var compiled_tpl = Handlebars.compile(value);
			cache.store('cnrs_template_'+key, compiled_tpl);
			return this;
		},
		getCached: function(key) {
			return cache.load('cnrs_template_'+key);
		},
		list: function() {
			return cache.list('cnrs_template_');
		}
	}
}(this,this.document,jQuery);

Handlebars.registerHelper('statusTag', function(h) {
	return '<span class="flag ' + h.toLowerCase() + '">' + h + '</span>';
});

Handlebars.registerHelper('dateFormat', function(t) {
	var parts = t.split(' '),dateParts = parts[0].split('-');
	return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
});

Handlebars.registerHelper('nl2br', function(t) {
	t = t.replace(/^\s*/, '').replace(/\s*$/, '');
	return t.replace(/\n/g,'<br>');
});

Handlebars.registerHelper('emailList', function(e) {
	var emails = e.split(';');
	for(var i=0,l=emails.length;i<l;i++) {
		emails[i] = '<a href="mailto:'+ emails[i] + '">' + emails[i] + '</a>';
	}
	return emails.join(', ');
});

Handlebars.registerHelper('moneyFormat', function(n) {
	var curr = '£',amount = parseFloat(n).toFixed(2);
	return curr + amount;
});

Handlebars.registerHelper('txtlFlag', function(f,t) {
	if(f == '1') { t = '<span class="flag flagged">Flagged</span> ' + t; }
	t = t.replace(/\r/g,'');
	return linen(t);
});

$(function() {
	var $templatesInHtml = $('script[type="text/x-js-template"]');
	$templatesInHtml.each(function(i,obj) {
		var $obj = $(obj), key = $obj.attr('id'), tpl = $obj.text();
		CnrsTemplates.add(key, tpl);
		$obj.remove();
	});
});
