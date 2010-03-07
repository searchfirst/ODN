<?php
/**
 * Set debug level here:
 * - 0: production
 * - 1: development
 * - 2: full debug with sql
 * - 3: full debug with sql and dump of the current object
 *
 * In production, the "flash messages" redirect after a time interval.
 * With the other debug levels you get to click the "flash message" to continue.
 *
 */
	define('DEBUG', 0);
	define('CACHE_CHECK', false);
	define('LOG_ERROR', 2);
	define('CAKE_SESSION_SAVE', 'cake');
	define('CAKE_SESSION_TABLE', 'cake_sessions');
	define('CAKE_SESSION_STRING', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');
	define('CAKE_SESSION_COOKIE', 'dux');
	define('CAKE_SECURITY', 'low');
	define('CAKE_SESSION_TIMEOUT', '300000000');
	define('WEBSERVICES', 'off');
	define('COMPRESS_CSS', false);
	define('AUTO_OUTPUT', false);
	define('AUTO_SESSION', true);
	define('MAX_MD5SIZE', (5 * 1024) * 1024);
	define('ACL_CLASSNAME', 'DB_ACL');
	define('ACL_FILENAME', 'dbacl' . DS . 'db_acl');
?>