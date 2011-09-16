<?php 
class AppController extends Controller {
    var $components = array(
        'Acl',
        'Auth' => array(
            'loginError' => "There was an error logging you in",
            'authError' => "You don't have permission to access this area. You may need to log in.",
            'fields' => array('username'=>'email','password'=>'password'),
            'actionPath' => 'controllers/',
            'authorize' => 'actions'
        ),
        'Dux',
        'RequestHandler',
        'Session',
        'Sma.Sma'
        /*, 'AclMenu.AclMenu'*/
    );
    var $helpers = array(
        'Status',
        'Html',
        'Form',
        'Time',
        'TextAssistant',
        'Js',
        'Session',
        'Sma.Sma'
    );
    var $uses = array('User');
    var $view = 'Theme';
}
