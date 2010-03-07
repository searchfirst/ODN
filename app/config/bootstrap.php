<?php

setlocale(LC_ALL, 'en_GB.UTF-8');
//setlocale(LC_MONETARY, 'en_GB.UTF-8');

/*
 * DB Config
 */
define('MOONLIGHT_DB_CONFIG','default');

/*
 * Miscellaneous Config & Section Titles
 */
define('MOONLIGHT_WEBMASTER_EMAIL','webmaster@example.com');
define('MOONLIGHT_WEBMASTER_NAME','Webmaster');
define('MOONLIGHT_WEBSITE_NAME','Dux');
define('MOONLIGHT_WEBSITE_DESCRIPTION','Moonlight Client description');

define('MOONLIGHT_SECTIONS_TITLE','Pages');
define('MOONLIGHT_ARTICLES_TITLE','Article');
define('MOONLIGHT_CATEGORIES_TITLE','Services');
define('MOONLIGHT_PRODUCTS_TITLE','Service');
define('MOONLIGHT_PROTECTED_SECTIONS_TITLE','Protected Area');
define('MOONLIGHT_PROTECTED_ITEMS_TITLE','Protected Item');

define('MOONLIGHT_USE_SUBCATEGORIES',false);
define('MOONLIGHT_USE_HTML',false);

/*
 * Resource types
 * Decorative (deco) is for non-informative elements that are present to 
 * complement existing data
 * Inline elements are elements placed inline within the flow of the document most 
 * usually have alternative information associated with it
 * Downloadables are elements that are available as perhaps ancillary to the document
 * PDFs or downloadable images.
 */
define('MOONLIGHT_RESTYPE_DECO','0');
define('MOONLIGHT_RESTYPE_INLINE','1');
define('MOONLIGHT_RESTYPE_DOWNLOAD','2');
define('MOONLIGHT_MEDIA_MAX_SIZE','1.5'); // Maximum upload size in megabytes

/*
 * Dux Constants
 */
define('USER_STATUS_EMPLOYED',1);
define('USER_STATUS_RESIGNED',0);
define('WEBSITE_STATUS_PENDING',1);
define('WEBSITE_STATUS_ACTIVE',2);
define('WEBSITE_STATUS_CANCELLED',0);
define('CUSTOMER_STATUS_PENDING',1);
define('CUSTOMER_STATUS_ACTIVE',2);
define('CUSTOMER_STATUS_CANCELLED',0);
define('SERVICE_STATUS_PENDING',1);
define('SERVICE_STATUS_ACTIVE',2);
define('SERVICE_STATUS_COMPLETE',3);
define('SERVICE_STATUS_CANCELLED',0);
define('GROUP_TYPE_SYSTEM',0);
define('GROUP_TYPE_USER',1);
define('GROUP_TYPE_AUTO',2);

/*
 * File upload location for media
 */
ini_set('upload_max_filesize',((integer) MOONLIGHT_MEDIA_MAX_SIZE * 1024));
define('MOONLIGHT_MEDIA_PATH',DS.'home'.DS.'SFDsites'.DS.'dux.searchfirst.co.uk'.DS.'user'.DS.'htdocs'.DS.'media'.DS);
define('MOONLIGHT_MEDIA_WEB_ROOT','/media/');
define('MOONLIGHT_MEDIA_ACCEPT_TYPES','image/jpeg|image/gif|image/png');
define('MOONLIGHT_MEDIA_ASSISTANT_TYPES','media|article|product');

/*
 * Media (Images) dimension restrictions and thumbnail config
 */
define('MOONLIGHT_IMAGE_IMAGEMAGICK_PATH','/usr/bin/convert');
define('MOONLIGHT_IMAGE_THUMBNAIL_CACHE',CAKE_CORE_INCLUDE_PATH.DS.'shared'.DS.'tmp'.DS.'thumbcache');
define('MOONLIGHT_IMAGE_DEFAULT_CONVERSION','crop');
define('MOONLIGHT_IMAGE_USE_THICKBOX',true);

/*
 * Protected Area
 */
define('MOONLIGHT_ENABLE_PROTECTED_AREA',false);

/*
 * Data handling (text)
 */
define('MOONLIGHT_ALLOW_HTML_IN_DESCRIPTIONS',false);
define('MOONLIGHT_PERMITTED_HTML_ELEMENTS','');
//define('MOONLIGHT_PERMITTED_HTML_ELEMENTS','<p><div><span><blockquote><dd><dl><dt><ul><li><ol><h4><h3><h2><pre><code><kbd><tt><q><cite>');

/*
 * Web service config (RSS)
 */
define('MOONLIGHT_DEFAULT_RSS_FEED',"http://{$_SERVER['HTTP_HOST']}/rss/blog");

/*
 * Article commenting and general info
 */
define('MOONLIGHT_ARTICLES_ENABLE_COMMENTS',false);
define('MOONLIGHT_ARTICLES_DISPLAY_INFO',false);
define('MOONLIGHT_AKISMET_API_KEY','123456789');
?>