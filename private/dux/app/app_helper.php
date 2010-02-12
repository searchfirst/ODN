<?php
class AppHelper extends Helper {
	/**
	 * This is the constructor for our AppHelper class that we use to make all helper output html 4.01
	 * compatible markup.
	 *
	 * @return AppHelper
	 */
	function __construct() {
		// Loop through all tags in this helper
		// Replace all xhtml style tag closings with html 4.01 strict compatible ones
		if(defined('MOONLIGHT_USE_HTML') && MOONLIGHT_USE_HTML)
			foreach ($this->tags as $tag => $html)
				$this->tags[$tag] = r('/>', '>', $html);
	}
}
?>