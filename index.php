<?php
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('ROOT')) define('ROOT', dirname(__FILE__));
if (!defined('APP_DIR')) define('APP_DIR', 'app');
if (!defined('CAKE_CORE_INCLUDE_PATH')) define('CAKE_CORE_INCLUDE_PATH', ROOT.DS.'cake');

///////////////////////////////
//DO NOT EDIT BELOW THIS LINE//
///////////////////////////////
	if (!defined('WEBROOT_DIR')) {
		 define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		 define('WWW_ROOT', dirname(__FILE__) . DS);
	}
	if (!defined('CORE_PATH')) {
		 if (function_exists('ini_set')) {
			  ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS);
			  define('APP_PATH', null);
			  define('CORE_PATH', null);
		 } else {
			  define('APP_PATH', ROOT . DS . APP_DIR . DS);
			  define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
		 }
	}
	require CORE_PATH . 'cake' . DS . 'bootstrap.php';
	if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico') {
	} else {
		 $Dispatcher=new Dispatcher();
		 $Dispatcher->dispatch($url);
	}
/*	if (DEBUG) {
		 echo "<!-- " . round(getMicrotime() - $TIME_START, 4) . "s -->";
	}*/
?>
