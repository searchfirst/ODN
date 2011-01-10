jQuery.fn.preventNonFloatCharacters = function() {return this.each(function(){$(this).keypress(function(e) {
	var key = e.which,
		input = String.fromCharCode(key),
		is_valid = /^[0-9\x2e]+$/.test(input);
	if(!(is_valid)) {
		// Don't block backspace, tab, delete, arrows
		if(!(key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40))) {
			return false;
		}
	}
})})};