<?php
Router::connect('/',array('controller'=>'Facades','action'=>'index'));
Router::connect('/customers/:page',array('controller'=>'Customers','action'=>'index'),array('page'=>'[a-z1-9]{1}'));
Router::connect('/ajax/:controller/:action/*',array('alt_content'=>'Ajax'));
Router::connect('/rss/:controller/:action/*',array('alt_content'=>'Rss','url'=>array('ext'=>'rss')));
Router::connect('/pdf/:controller/:action/*',array('alt_content'=>'Pdf','url'=>array('ext'=>'pdf')));
Router::parseExtensions();
