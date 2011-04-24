(function($,window,document,undefined){
	function toggleContent(e) {
		var $treeitem = e.data.$treeitem,
			$content = e.data.$content,
			alreadyExpanded = $treeitem.attr('aria-expanded') == "true",
			triggeredOnTreeItem = e.target == e.currentTarget;

		if (alreadyExpanded) {
			collapseContent(e);
		} else {
			expandContent(e);
		}
	}

	function expandContent(e) {
		var $treeitem = e.data.$treeitem,
			$content = e.data.$content,
			alreadyExpanded = $treeitem.attr('aria-expanded') == "true",
			triggeredOnTreeItem = e.target == e.currentTarget;

		if (triggeredOnTreeItem && !alreadyExpanded) {
			$treeitem.attr('aria-expanded','true');
			$treeitem.addClass('expanded');
		}
	}

	function collapseContent(e) {
		var $treeitem = e.data.$treeitem,
			$content = e.data.$content,
			alreadyCollapsed = $treeitem.attr('aria-expanded') == 'false',
			triggeredOnTreeItem = e.target == e.currentTarget;

		if (triggeredOnTreeItem && !alreadyCollapsed) {
			$treeitem.attr('aria-expanded','false');
			$treeitem.removeClass('expanded');
		}
	}


	$.fn.cnrsCollapse = function(settings) {
		this.each(function(i) {
			var hook = this,
				$hook = $(hook),
				widget = '<span class="cc-widget" role="presentation"></span>',
				$content = $hook.next(),
				content = $content.get(0),
				$widget = $(widget).prependTo(hook),
				$treeitem = $hook.add(content).wrapAll('<div role="treeitem" tabindex="0" aria-expanded="false"></div>').parent();
				treeitem = $treeitem.get(0),
				$tree = $(treeitem).wrap('<div class="collapse_tree" role="tree"></div>').parent()
				tree = $tree.get(0);

			$treeitem.bind('keyup',function(e) {
				if (e.data === undefined) {e.data = {}}
				e.data.$hook = $hook;
				e.data.$content = $content;
				e.data.$treeitem = $treeitem;
				if (e.which === 39) {
					expandContent(e);
				} else if (e.which === 37) {
					collapseContent(e);
				}
			});

			$widget.bind('click',{
				'$hook': $hook,
				'$content': $content,
				'$treeitem': $treeitem
			},toggleContent);
		});
		return this;
	}
})(jQuery,this,this.document);
