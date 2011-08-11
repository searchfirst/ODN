(function($,window,document,undefined){
	function restoreState($field, $widget, originalState) {
		var $select = $widget.find('select'),
			text = originalState.text,
			fieldData = originalState.fieldData;
		$field
			.data('originalState',originalState)
			.attr('data-field-data',originalState.fieldData);
		$select
			.val(originalState.fieldData);
	}

	function saveState($field, $widget) {
		var originalState = $field.data('originalState'),
			currentState = getState($widget);
		$field
			.data('originalState', currentState)
			.attr('data-field-data', currentState.fieldData);
	}

	function updateField($field, originalState) {
		var state = $field.data('originalState');
		$field
			.removeClass(originalState.text.toLowerCase())
			.addClass(state.text.toLowerCase())
			.text(state.text);
	}

	function getState($el) {
		return {
			text: $el.find('select :selected').text(),
			fieldData: $el.find('select').val()
		};
	}

	function hasChanged($el, $sel) {
		var originalState = $el.data('originalState'),
			currentState = getState($sel);
		return originalState.fieldData != currentState.fieldData;
	}

	function attachWidget($field, $widget) {
		var dimensions = { width: $field.outerWidth(), height: $field.outerHeight() },
			$select = $widget.find('select');
		$field.after($widget);
		$select
			.val($field.data('originalState').fieldData)
			.width(dimensions.width)
			.height(dimensions.height);
		$widget
			.width(dimensions.width)
			.height(dimensions.height)
			.position({
				my: 'top left',
				at: 'top left',
				of: $field
			});
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
			$field = e.data.$field,
			field = e.data.field,
			save = e.data.save,
			$widget = e.data.$widget,
			originalState = $field.data('originalState'),
			changed = hasChanged($field, $widget);

		if (changed) {
			//Run save & save callbacks
			if (save !== undefined) {
				saveState($field, $widget);
				save(field,{
					success: function(){
						updateField($field, originalState);
					},
					error: function(){
						restoreState($field, $widget, originalState);
					}
				});
			}
		}
		return false;
	}

	$.fn.cnrsSelectable = function(settings) {
		this.each(function(i) {
			var $this = $(this),
				save = settings ? settings.save || undefined : undefined,
				options = settings ? settings.options || undefined : undefined,
				id = settings ? settings.id || undefined : undefined,
				title = settings ? settings.title || undefined : undefined,
				$backboneEl = settings ? settings.$backboneEl || undefined : undefined,
				originalState = {
					text: $this.text(),
					fieldData: $this.attr('data-field-data')
				},
				field = $this.get(0),
				md5 = new gruft.MD5(),
				uid = md5.digest(id + title);
				widget;

			widget += '<span class="cs-widget"><label class="visuallyhidden" for="' + uid + '">Change service status for ' + title + '</label><select id="' + uid + '" class="seethrough">';
			for (o in options) {
				var t = options[o].text, d = options[o].fieldData;
				widget += '<option value="'+d+'">'+t+'</option>';
			}
			widget += '</select></span>';
			$widget = $(widget);
			$widget.val(originalState.fieldData);

			$this.data({ originalState: originalState });
			$widget.bind('change.cnrsSelectable', {
					$widget: $widget,
					save: save,
					field: $this.get(0),
					$field: $this
				}, activity);
			$backboneEl.bind('rendered',function(e) {
				attachWidget($this, $widget);
			});
		});
		return this;
	};
})(jQuery,this,this.document);
