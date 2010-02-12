<?php
class RssHelper extends Helper {
	function relToAbs($description) {
		return preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#','$1="http://'.$_SERVER['HTTP_HOST'].'$2$3',$description);
	}
}
?>