<?php
require(CAKE_CORE_INCLUDE_PATH.'/shared/vendors/xmlrpc/xmlrpc.inc');
require(CAKE_CORE_INCLUDE_PATH.'/shared/vendors/blog-x-ping/blog-x-ping.php');
require(CAKE_CORE_INCLUDE_PATH.'/shared/vendors/simplepie/simplepie.inc');

define('BLOG_PING_SERVICE_LIST',CAKE_CORE_INCLUDE_PATH.'/shared/vendors/blog-x-ping/ping_services.txt');
define('BLOG_PING_LOG_FILE',CAKE_CORE_INCLUDE_PATH.'/shared/vendors/blog-x-ping/ping.log');
define('RSS_CACHE_DIR',CAKE_CORE_INCLUDE_PATH.'/shared/tmp/rss');

class RssComponent extends Object {
	
	function fetchRss($url) {
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_output_encoding("UTF-8");
		$feed->enable_order_by_date(false);
		$feed->set_cache_location(RSS_CACHE_DIR);
		$feed->init();
		return $feed->get_items();
	}
	
	function ping($path_to_post='') {
		do_ping(	MOONLIGHT_WEBSITE_NAME,
					"http://{$_SERVER['HTTP_HOST']}",
					"http://{$_SERVER['HTTP_HOST']}$path_to_post",
					MOONLIGHT_DEFAULT_RSS_FEED,
					BLOG_PING_SERVICE_LIST,
					'none',
					BLOG_PING_LOG_FILE);
	}
}
?>