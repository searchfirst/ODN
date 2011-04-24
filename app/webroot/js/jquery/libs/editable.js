(function($,window,document,undefined){
	function restoreState($el) {
		var multiEdit = $el.data('multiEdit'),
			originalState = $el.data('originalState');

		$el[multiEdit?'html':'text'](originalState);
	}

	function saveState($el) {
		var multiEdit = $el.data('multiEdit'),
			currentState = $el[multiEdit?'html':'text']();

		$el.data('originalState', currentState);
	}

	function hasChanged($el) {
		var multiEdit = $el.data('multiEdit'),
			originalState = $el.data('originalState'),
			currentState = $el[multiEdit?'html':'text']();

		return originalState != currentState;
	}

	function hideWidget(widget) {
		$(widget).fadeOut('fast');
	}

	function showWidget(widget) {
		$(widget).fadeIn('fast');
	}

	/**
	 * activity
	 * 
	 * @description The event handler for keypresses and clicks on the widget
	 * 
	 * @param e The event object with included e.data parameters from the plugin
	 * @access public
	 * @return void
	 */
	function activity(e) {
		var $this = $(e.target),
			$field = $(e.data.field),
			field = e.data.field,
			buttons = e.data.buttons,
			callbacks = e.data.callbacks,
			multiEdit = e.data.multiEdit,
			widget = e.data.widget,
			originalState = $field.data('originalState'),
			currentState = $field[multiEdit?'html':'text'](),
			changed = hasChanged($field),
			enter = e.which === 13 && !(e.altKey || e.shiftKey || e.metaKey || e.ctrlKey),
			escapeKey = e.which === 27,
			ctrlEnter = e.which === 13 && e.ctrlKey && !(e.altKey || e.shiftKey || e.metaKey),
			triggerCancel = escapeKey || (buttons && $this.attr('class') === 'cancel')
			triggerSave = (!multiEdit && enter) || (multiEdit && ctrlEnter) || (buttons && $this.attr('class') === 'save');

		if (changed && triggerSave) {
			//Run save & save callbacks
			if (callbacks.save !== undefined) {
				callbacks.save(field,{
					success: function(){
						saveState($field);
						hideWidget(widget);
					},
					error: function(){
						console.log('e');
						restoreState($field)}
				});
			}
		} else if (changed && triggerCancel) {
			restoreState($field);
			hideWidget(widget);
			$field.blur();
		} else if (changed) {
			showWidget(widget);
		} else if (!changed) {
			hideWidget(widget);
		}
		return false;
	}

	$.fn.cnrsEditable = function(settings) {
		this.each(function(i) {
			var $this = $(this),
				callbacks = settings ? settings.callbacks || {} : {},
				multiEdit = $this.data('multi') == '1' || $this.attr('tagName') == 'DIV',
				originalState = multiEdit ? $this.html() : $this.text(),
				widget = '<span class="ce-widget"><span class="save" tabindex="0" role="button">Save</span> <span class="cancel" tabindex="0" role="button">Cancel</span></span>',
				$widget = $(widget).insertAfter(this);

			$this
			.data({
				multiEdit: multiEdit,
				originalState: originalState
			})
			.bind('keydown.cnrsEditable', function(e) {return !(!multiEdit && e.which == 13);})
			.bind('keyup.cnrsEditable', {
				multiEdit: multiEdit,
				widget: $widget.get(0),
				callbacks: callbacks,
				field: $this.get(0),
				buttons: false
			}, activity);
			/*.bind('paste.cnrsEditable', function(e) {
				var text = $this.get(0).innerText.replace(/\r/g,'');
				console.log(multiEdit);
				console.log(text);
				if (multiEdit) {
					$this.html(linen($this.text().replace(/\r/g,'')));
				} else {
					$this.html($this.text().replace(/\r/g,'').replace(/\n/g,' '));
				}
			});*/
			$widget
			.bind('click.cnrsEditable,keypress.cnrsEditable', {
				multiEdit: multiEdit,
				widget: $widget.get(0),
				callbacks: callbacks,
				field: $this.get(0),
				buttons: true
			}, activity);
		});
		return this;
	};
})(jQuery,this,this.document);
