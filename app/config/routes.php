<?php
Router::mapResources(array( 'users','customers','contacts','notes','services','websites','invoices' ));
Router::parseExtensions();
Router::connect('/',array('controller'=>'facades','action'=>'index'));
Router::connect('/customers/filter/:page',array('controller'=>'customers','action'=>'index'),array('page'=>'[a-z1-9]{1}'));
