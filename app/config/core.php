<?php
Configure::write('debug', 1);
Configure::write('App.encoding', 'UTF-8');
define('LOG_ERROR', 2);
Configure::write('Session.save', 'cake');
Configure::write('Session.cookie', 'Dux');
Configure::write('Session.timeout', '300000000');
Configure::write('Session.start', true);
Configure::write('Session.checkAgent', true);
Configure::write('Security.level', 'low');
Configure::write('Security.salt', 'DYhG93b0qyJfI9981CNCoUubWwvniR2G0FgaC9mi');
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');
Cache::config('default', array('engine' => 'File'));
?>
