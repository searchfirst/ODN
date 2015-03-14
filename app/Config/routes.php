<?php
Router::parseExtensions('json', 'xml', 'pdf');
Router::mapResources(array( 'users','customers','contacts','notes','services','websites','invoices' ));
Router::connect('/',array('controller'=>'facades','action'=>'index'));
Router::connect('/customers/filter/:page',array('controller'=>'customers','action'=>'index'),array('page'=>'[a-z1-9]{1}'));
Router::connect('/pdf/:controller/:action/*',array('alt_content'=>'Pdf','url'=>array('ext'=>'pdf')),array());
CakePlugin::routes();
require CAKE . 'Config' . DS . 'routes.php';
