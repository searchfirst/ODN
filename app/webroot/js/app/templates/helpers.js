	Handlebars.registerHelper('consoleLog', function(h) {
        console.log(h);
        return '';
	});
	
	Handlebars.registerHelper('statusTag', function(h) {
		return new Handlebars.SafeString('<span class="flag ' + h.toLowerCase() + '">' + h + '</span>');
	});
	
	Handlebars.registerHelper('selectableStatusTag', function(statusText, fieldName, fieldData) {
		var outputTag = '';
		outputTag += '<span class="flag selectable ' + statusText.toLowerCase() + '" ';
		outputTag += 'data-field = "' + fieldName + '" ';
		outputTag += 'data-field-data="' + fieldData + '">';
		outputTag += statusText + '</span>';
		return new Handlebars.SafeString(outputTag);
	});
	
	Handlebars.registerHelper('dateFormat', function(t) {
        if (!t) {
            return '';
        }

		var parts = t.split(' '),dateParts = parts[0].split('-');
		return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
	});
	
	Handlebars.registerHelper('isoDate', function(dayOffset) {
		var date = new Date(Date.now() + (86400000 * dayOffset));
		return date.toISOString().substr(0,10);
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
				previousPage = pageInfo.page--, params = _(customerId).isString() ? '&customer_id=' + customerId : '',
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
