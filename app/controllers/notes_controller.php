<?php
class NotesController extends AppController {
    var $name = 'Notes';
    var $primaryModel = 'Note';
    var $helpers = array('Status','Javascript','Html','Form','Time','TextAssistant','MediaAssistant');
    var $uses = array("Note");
    var $paginate = array(
        'limit' => 10,
        'order' => array('Note.created' => 'DESC'),
        'recursive' => 0
    );

    function index() {
        if (!array_key_exists('user_id',$this->params['url'])) {
            $this->Note->unbindModel(array(
                'belongsTo' => array('Website','Service','Customer','Invoice')
            ),false);
        }
        $paginationOptions = array();
        if (!empty($this->params['url']['customer_id'])) {
            $paginationOptions['Note.customer_id'] = $this->params['url']['customer_id'];
        }
        if (!empty($this->params['url']['user_id'])) {
            $paginationOptions['Note.user_id'] = $this->params['url']['user_id'];
        }
        if (!empty($this->params['url']['flagged'])) {
            if ($this->params['url']['flagged'] == 1) {
                $paginationOptions['Note.flagged'] = true;
            } else {
                $paginationOptions['Note.flagged'] = false;
            }
        }
        $notes = $this->paginate('Note',$paginationOptions);
        $this->set('notes',$notes);
    }

    function you() {
        $paginationOptions = array(
            'Note.user_id' => User::getCurrent('id')
        );
        if (!empty($this->params['url']['customer_id'])) {
            $paginationOptions['Note.customer_id'] = $this->params['url']['customer_id'];
        }
        if (!empty($this->params['url']['flagged'])) {
            if ($this->params['url']['flagged'] == 1) {
                $paginationOptions['Note.flagged'] = true;
            } else {
                $paginationOptions['Note.flagged'] = false;
            }
        }
        $notes = $this->paginate('Note', $paginationOptions);
        $this->set('notes', $notes);
    }

    function add() {
        extract($this->Dux->commonRequestInfo());
        if ($isPost && $isAjax) {
            if ($this->Note->save($this->data)) {
                $this->set('model',$this->Note->readRoot());
            } else {
                $this->cakeError('ajaxError',array('message'=>'Error saving note'));
            }
        } else if ($isPost) {
            if($this->Note->save($this->data)) {
                $this->Session->setFlash("Note added.");
                $this->redirect($this->referer('/'));
            } else {
                $this->Session->setFlash('Please correct the errors below');
            }
        }
    }

    function all_notes($page=null) {
        if($page) {
            $all_notes['items'] = $this->Note->find('all',array('limit'=>10,'page'=>$page));
            $all_notes['count'] = $this->Note->find('count');
            $all_notes['curr_page'] = $page;
            $all_notes['pages'] = ceil($all_notes['count']/10);
            $this->set('all_notes',$all_notes);
        } else {

        }
    }

    function flagged_notes($page=null) {
        if($page) {
            $flagged_notes['items'] = $this->Note->find('all',array('conditions'=>array('Note.flagged'=>1),'limit'=>10,'page'=>$page));
            $flagged_notes['count'] = $this->Note->find('count',array('conditions'=>array('Note.flagged'=>1)));
            $flagged_notes['curr_page'] = $page;
            $flagged_notes['pages'] = ceil($flagged_notes['count']/10);
            $this->set('flagged_notes',$flagged_notes);
        } else {

        }
    }

    function your_notes($page=null) {
        if($page) {
            $your_notes = $this->Note->findForUser(User::getCurrent('id'),array('limit'=>10,'page'=>$page));
            $this->set('your_notes',$your_notes);
        } else {

        }
    }

    function your_flagged_notes($page=null) {
        if($page) {
            $your_flagged_notes = $this->Note->findForUser(User::getCurrent('id'),array('conditions'=>'Note.flagged = 1','limit'=>10,'page'=>$page));
            $this->set('your_flagged_notes',$your_flagged_notes);
        } else {

        }
    }

    function flag($id=null) {
        global $current_user;
        $cud = $current_user['User']['id'];
        if($id) {
            if(!empty($this->data) && $this->data['Note']['id']==$id) {
                if($this->Note->save($this->data)) {
                    $this->redirect($this->referer('/'));
                } else {
                    $this->Session->setFlash('There was an error flagging this note');
                    $this->redirect($this->referer('/'));
                }
            } else {
                $this->data['Note'] = array('id'=>$id,'flagged'=>1);
            }
        } else {
            $this->viewPath = 'errors';
            $this->render('not_found');
            return true;
        }
    }

    function unflag($id=null) {
        global $current_user;
        $cud = $current_user['User']['id'];
        if($id) {
            if(!empty($this->data) && $this->data['Note']['id']==$id) {
                if($this->Note->save($this->data)) {
                    $this->redirect($this->referer('/'));
                } else {
                    $this->Session->setFlash('There was an error unflagging this note');
                    $this->redirect($this->referer('/'));
                }
            } else {
                $this->data['Note'] = array('id'=>$id,'flagged'=>0);
            }
        } else {
            $this->viewPath = 'errors';
            $this->render('not_found');
            return true;
        }
    }
}
