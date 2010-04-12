jQuery.fn.hookPagination = function(settings) {
	settings = jQuery.extend({},settings);
	var pagination_hooks = new Array();
	var hook_links = new Array();
	
	this.each(function(i) {
		pagination_hooks[i] = jQuery(this);
		hook_links[i] = pagination_hooks[i].find('a');
		
		hook_links[i].each(function(j) {
			var this_hook_link = jQuery(this);
			this_hook_link.bind('click',{i:i,j:j},function(e) {
				var this_link = jQuery(this);
				var this_page_container = pagination_hooks[i].parent();
				this_page_container.fadeOut(200);
				this_page_container.load(this_link.attr('href'),function() {
					this_page_container.fadeIn(200);
					jQuery('ul.hook_ajax_pagination').hookPagination();
				});
				return false;
			});
		});
	});
	return this;
};