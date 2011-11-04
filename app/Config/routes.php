<?php
Router::parseExtensions('json', 'xml');
Router::mapResources(array( 'users','customers','contacts','notes','services','websites','invoices' ));
Router::connect('/',array('controller'=>'facades','action'=>'index'));
Router::connect('/customers/filter/:page',array('controller'=>'customers','action'=>'index'),array('page'=>'[a-z1-9]{1}'));
CakePlugin::routes();
require CAKE . 'Config' . DS . 'routes.php';
