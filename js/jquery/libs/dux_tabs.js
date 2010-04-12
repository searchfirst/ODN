// hook class - tab_hooks
// children class - tab_page
jQuery.fn.duxTab = function(settings) {
	settings = jQuery.extend({},settings);
	var curr_hook = new Array();
	var curr_tab_options = new Array();
	var curr_tab_pages = new Array();
	var curr_tab_options_count = new Array;

	this.each(function(i) {
		//Initialise
		curr_hook[i] = jQuery(this);
		curr_tab_options[i] = curr_hook[i].children('li');
		curr_tab_options[i].css({cursor:'pointer'});
		curr_tab_options_count[i] = curr_tab_options[i].length;
		curr_element = curr_hook[i];
		var tab_pages = new Array();
		for(t=0;t<curr_tab_options_count[i];t++) {
			tab_pages[t] = curr_element.next('.tab_page');
			if(t>0){tab_pages[t].css('display','none');}
			if(t==0){curr_tab_options[i].eq(t).toggleClass('current');}
			curr_element = tab_pages[t];
		}
		curr_tab_pages[i] = tab_pages;
		//Nitty Gritty
		var j;
		for(j=0;j<curr_tab_options_count[i];j++) {
			curr_tab_options[i].eq(j).bind('click',{j:j},function(e){
				var j = e.data.j;
				var isChild = $(e.currentTarget).has($(e.relatedTarget)).length;
				if(!(isChild)) {
					var k;
					for(k=0;k<curr_tab_options_count[i];k++) {
						var curr_k_display = curr_tab_pages[i][k].css('display');
						if((j!=k) && (curr_k_display!='none')) {
							curr_tab_pages[i][k].fadeOut(200);
							curr_tab_options[i].eq(k).removeClass('current');
						} else if((j==k) && (curr_k_display=='none')) {
							curr_tab_pages[i][k].delay(200).fadeIn(200);
							curr_tab_options[i].eq(k).addClass('current');
						} else if(j!=k) {
							curr_tab_options[i].eq(k).removeClass('current');
						}
					}
				}
				return false;
			});
		}
	});
	return this;
};