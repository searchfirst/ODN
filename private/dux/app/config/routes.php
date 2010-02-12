<?php
Router::connect('/',array('controller'=>'Facades','action'=>'index'));
Router::connect('/customers/:page',array('controller'=>'Customers','action'=>'index'),array('page'=>'[a-z1-9]{1}'));
Router::connect('/ajax/:controller/:action/*',array('alt_content'=>'Ajax'));
Router::connect('/rss/:controller/:action/*',array('alt_content'=>'Rss'));
Router::connect('/pdf/:controller/:action/*',array('alt_content'=>'Pdf'));
/*
$Route->connect('/articles/*', array('controller' => 'Articles','action' => 'view'));
$Route->connect('/categories', array('controller' => 'Categories','action' => 'index'));
$Route->connect('/categories/*', array('controller' => 'Categories','action' => 'view'));
$Route->connect('/comments',array('controller'=>'Comments','action'=>'index'));
$Route->connect('/products/*', array('controller' => 'Products','action' => 'view'));
$Route->connect('/thumbs/*', array('controller'=>'Thumbs','action'=>'index'));
$Route->connect('/rss/:controller/', array('alt_content'=>'Rss','action'=>'index'));
$Route->connect('/rss/:controller/*', array('alt_content'=>'Rss','action'=>'view'));
$Route->connect('/contact', array('controller' => 'Contacts', 'action' => 'index'));
$Route->connect('/contact/:action', array('controller' => 'Contacts'));
$Route->connect('/archive/*', array('controller'=>'Sections','action'=>'archive','blog'));
$Route->connect('/*', array('controller' => 'Sections','action' => 'view'));
*/

?>