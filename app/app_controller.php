<?php 
class AppController extends Controller {
    var $components = array(
        'Acl', 'Auth', 'Dux', 'RequestHandler',
        'Session', 'Sma.Sma'/*, 'AclMenu.AclMenu'*/
    );
    var $helpers = array(
        'Status', 'Html', 'Form', 'Time', 'TextAssistant', 'Js', 'Session', 'Sma.Sma'
    );
    var $uses = array('User');
    var $view = 'Theme';
    
    function beforeRender() {
        if($external_links = Configure::read('Dux.external_links')) $this->set('external_links',$external_links);
    }
}
