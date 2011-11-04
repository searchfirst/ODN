<?php
class FacadesController extends AppController
{
    public $helpers = array(
        'Paginator'
    );
    public $layout = 'home';
    public $uses = array('Customer', 'Note');
    public $paginate = array(
        'paramType' => 'querystring',
        'recursive' => 0,
        'Note' => array(
            'order' => array('Note.created' => 'DESC'),
            'limit' => 10
        )
    );

    function index() {
        $title_for_layout = 'Dashboard';
        $current_user = User::getCurrent();
        $cuid = User::getCurrent("id");
        if($current_user) {
            App::uses('Service', 'Model');
            $customers = $this->Customer->find('allThroughService', array(
                'status' => Service::$status['Active']
            ));

            $conditions = array(
                'Note.user_id' => User::getCurrent('id')
            );
            $notes = $this->paginate('Note', $conditions);

        }
        $this->set(compact('title_for_layout', 'notes', 'customers'));
    }
}
?>
