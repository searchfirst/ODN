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

Handlebars.registerHelper('txtl', function(t) {
	return linen(t);
});

Handlebars.registerHelper('editable', function(t,f,m) {
	if (!t) { t = ''; }
	var multiLine = m === "1",
		usePlaceholder = t === '',
		tag = multiLine ? 'div' : 'span',
		text = multiLine ? linen(t.replace(/\r/g,'')) : t,
		output = '';
	output += '<' + tag + ' data-field="' + f + '" contenteditable';
	if (usePlaceholder) {
		output += ' class="ed_placeholder">' + '&nbsp;';
	} else {
		output += '>' + text;
	}
	output += '</' + tag + '>';
	return new Handlebars.SafeString(output);
});

Handlebars.registerHelper('pageLinks', function(pageInfo,model,customerId) {
	if (pageInfo.pages < 2) { return ''; }
	var nextPage = pageInfo.page++,
			previousPage = pageInfo.page--, params = customerId ? '&customer_id=' + customerId : '',
			controller = model.toLowerCase() + 's',
			r = '<ul class="pagelinks">';
	r += '<li>';
	if (pageInfo.prev !==false) {
		r += '<a href="/' + controller + '?page=' + previousPage + params + '" data-noroute="1" class="prev">Back</a>';
	} else {
		r += 'Back';
	}
	r += '</li><li>' + pageInfo.page + ' of ' + pageInfo.pages + '</li><li>';
	if (pageInfo.next !==false) {
		r += '<a href="/' + controller + '?page=' + nextPage + params + '" data-noroute="1" class="next">Next</a>';
	} else {
		r += 'Next';
	}
	r += '</li></ul>';
	return new Handlebars.SafeString(r);
});

$(function() {
	var $templatesInHtml = $('script[type="text/x-js-template"]');
	$templatesInHtml.each(function(i,obj) {
		var $obj = $(obj), key = $obj.attr('id'), tpl = $obj.text();
		CnrsTemplates.add(key, tpl);
		$obj.remove();
	});
});
