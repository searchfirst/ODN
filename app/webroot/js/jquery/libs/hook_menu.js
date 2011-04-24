jQuery.fn.hookMenu = function(settings) {
	function toggleVisibility(hook,hook_elem_x,e) {
		if(e.target.nodeName!='A' || hook.find('a').last().get(0) == e.target) {
			if(hook.css('display')=='none') {
				recalcCoords(hook,hook_elem_x);
				hook.fadeIn('fast');
			} else {
				hook.fadeOut('fast');
			}
		}
	}
	function recalcCoords(hookMenu,hookElementX) {
		var hm_corner = hookCorner(hookElementX,hookMenu),hm_bottom = hookBottom(hookElementX);
		if(hm_corner.label=='left') hookMenu.css({'left':hm_corner.value,'top':hm_bottom});
		else hookMenu.css({'right':hm_corner.value,'top':hm_bottom});
	}
	function hookBottom(hook) {
		var font_size = parseInt(hook.css('font-size')),
			mid_font_size = font_size/2,
			mid_point = hook.height()/2,
			hook_bottom = hook.position().top + mid_point + mid_font_size;
		return hook_bottom;
	}
	function hookCorner(hook,popup) {
		var window_width = document.width,
			distance_to_right = window_width - hook.position().left,
			popup_width = popup.outerWidth();
		if(distance_to_right > popup_width)
			return {label:'left',value:hook.position().left + parseInt(hook.css('margin-left'))};
		else
			return {label:'right',value:(distance_to_right - hook.outerWidth())};
	}
	function preventParentClickBubble(h,x) {
		h.click(function(e){if(e.target==x.get(0)) return false});
	}
	settings = jQuery.extend({'growUp':false,'position':'absolute'},settings);
	this.each(function(i) {
		var ariaHookElementX = 'hook_menu_aria_'+i,
			hookElement = jQuery(this),
			hookMenu = hookElement.next('ul.hook_menu'),
			hookElementX;
		if(hookMenu.length) {
			hookMenu.css({position: settings.position,display: 'none','white-space': 'nowrap'})
					.attr({'id':ariaHookElementX})
					.find('a')
					.attr({'tabindex':0});
			hookElement.append('<span class="hook_menu_x" aria-owns="'+ariaHookElementX+'">Expand</span>');
			hookElementX = hookElement.find('span.hook_menu_x').eq(0);
			preventParentClickBubble(hookElement,hookElementX);
			hookMenu.recalcCoords = function() {
				var hm_corner = hookCorner(hookElementX,hookMenu),
					hm_bottom = hookBottom(hookElementX);
				if(hm_corner.label=='left') {
					hookMenu.css({'left':hm_corner.value,'top':hm_bottom});
				} else {
					hookMenu.css({'right':hm_corner.value,'top':hm_bottom});
				}
			}
			hookElementX.bind({
				mouseenter: function(e) {jQuery(this).parent().toggleClass('hook_highlight');},
				mouseleave: function(e) {jQuery(this).parent().removeClass('hook_highlight');},
				focusin: function(e) {toggleVisibility(hookMenu,hookElementX,e);return false;}
			}).attr({'tabindex':0,'aria-haspopup':'true'});
			hookMenu.bind({
				mouseleave: function(e) {
					toggleVisibility(hookMenu,hookElementX,e);
					hookElementX.blur();
				},
				focusout: function(e) {toggleVisibility(hookMenu,hookElementX,e);}
			});
		}
	});
	$(document).bind('scroll',function(){
		$(this).find('ul.hook_menu').filter(function(i){return $(this).css('display')=='block'}).mouseleave();
	});
	return this;
};
