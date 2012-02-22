<?php 
class AppController extends Controller {
    var $components = array(
        'Acl',
        'Auth' => array(
            'loginError' => "There was an error logging you in",
            'authError' => "You don't have permission to access this area. You may need to log in.",
            'authorize' => array(
                'Actions' => array(
                    'actionPath' => 'controllers'
                )
            ),
            'actionPath' => 'controllers',
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username'=>'email')
                )
            )
        ),
        'Odn',
        'Session',
        'Sma.Sma'
    );
    var $helpers = array(
        'Html',
        'Form',
        'Paginator',
        'Time',
        'Text' => array('className' => 'OdnText'),
        'Session',
        'Sma.Sma'
    );
    var $uses = array('User');
    var $view = 'Theme';
}
