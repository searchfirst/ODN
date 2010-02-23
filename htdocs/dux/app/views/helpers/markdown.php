<?php
vendor('markdown');
vendor('smartypants');
class MarkdownHelper extends Helper {
	function text($text) {
		return SmartyPants(Markdown($text));
	}
}
?>