<?php
/* The authentication file performs basic authentication over apache. */
/*
require('authentication.php');

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
	$auth=http_authenticate($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
	define('USER_AUTHENTICATED',$auth);
}
if(defined('USER_AUTHENTICATED') && USER_AUTHENTICATED){

}else{
	header('WWW-Authenticate: Basic realm="HTTP Auth Test"');
	header('HTTP/1.0 401 Unauthorized');
	exit('Authentication is required to view this page.');
}
*/

require(CAKE_CORE_INCLUDE_PATH.'/shared/config/bootstrap.php');
?>