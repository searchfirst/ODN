<?php
Configure::load('dux_configuration');
setlocale(LC_ALL, 'en_GB.UTF-8');
//setlocale(LC_MONETARY, 'en_GB.UTF-8');

/*
 * DB Config
 */
//define('MOONLIGHT_DB_CONFIG','default');

/*
 * Miscellaneous Config & Section Titles
 */
/*
define('MOONLIGHT_WEBMASTER_EMAIL','webmaster@example.com');
define('MOONLIGHT_WEBMASTER_NAME','Webmaster');
define('MOONLIGHT_WEBSITE_NAME','Dux');
define('MOONLIGHT_WEBSITE_DESCRIPTION','Moonlight Client description');
*/

define('MOONLIGHT_USE_HTML',true);
define('MOONLIGHT_ALLOW_HTML_IN_DESCRIPTIONS',false);
define('MOONLIGHT_PERMITTED_HTML_ELEMENTS','');
define('MOONLIGHT_MEDIA_ACCEPT_TYPES','image/jpeg|image/gif|image/png');

/*
 * Dux Constants
 */
define('USER_STATUS_EMPLOYED',1);
define('USER_STATUS_RESIGNED',0);
define('WEBSITE_STATUS_PENDING',1);
define('WEBSITE_STATUS_ACTIVE',2);
define('WEBSITE_STATUS_CANCELLED',0);
define('CUSTOMER_STATUS_PENDING',1);
define('CUSTOMER_STATUS_ACTIVE',0);
define('CUSTOMER_STATUS_CANCELLED',2);
define('SERVICE_STATUS_PENDING',1);
define('SERVICE_STATUS_ACTIVE',2);
define('SERVICE_STATUS_COMPLETE',3);
define('SERVICE_STATUS_CANCELLED',0);
define('GROUP_TYPE_SYSTEM',0);
define('GROUP_TYPE_USER',1);
define('GROUP_TYPE_AUTO',2);
?>
