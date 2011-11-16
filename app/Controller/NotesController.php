<?php
class NotesController extends AppController {
    public $primaryModel = 'Note';
    public $paginate = array(
        'conditions' => array(),
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Note.created' => 'DESC'),
        'recursive' => 1
    );

    public function index() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Note.customer_id'] = $this->request->query['customer_id'];
        }

        if (array_key_exists('user_id', $this->request->query)) {
            $conditions['Note.user_id'] = $this->request->query['user_id'];
        }

        if (array_key_exists('flagged', $this->request->query)) {
            $conditions['Note.flagged'] = (boolean) $this->request->query['flagged'];
        }

        $this->paginate['conditions'] += $conditions;
        $notes = $this->paginate('Note');
        $this->set(compact('doPaginate', 'notes'));
    }

    public function you() {
        extract($this->Odn->requestInfo);
        $doPaginate = true;
        $conditions = array(
            'Note.user_id' => User::getCurrent('id')
        );

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Note.customer_id'] = $this->request->query['customer_id'];
        }

        if (array_key_exists('flagged', $this->request->query)) {
            $conditions['Note.flagged'] = (boolean) $this->request->query['flagged'];
        }

        $this->paginate['conditions'] += $conditions;
        $notes = $this->paginate('Note');
        $title_for_layout = __('Your Notes');
        $this->set(compact('doPaginate', 'notes', 'title_for_layout'));
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Note->save($this->data)) {
                $message = __('Note added successfully.');
                if ($isAjax) {
                    $note = $this->Note->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect($this->referer('/'));
                }
                $this->set(compact('note'));
            } else {
                $message = __('There was an error saving this note. Please correct any highlighted errors.');
                if ($isAjax) {
                    $this->cakeError('ajaxError', compact('message'));
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (array_key_exists('customer_id', $this->request->query)) {
                $this->request->data = array(
                    'Note' => array(
                        'customer_id' => $this->request->query
                    )
                );
            } else {
                $message = __('No customer id was provided to add a note');
                throw new BadRequestException($message);
            }
        }
    }

    function edit($id = null) {
        if (!$id) {
            $message = __('No id was provided to edit a note');
            throw new BadRequestException($message);
        }

        $this->Note->id = $id;
        if (!$this->Note->exists()) {
            $message = __('A note could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        $title_for_layout = __('Edit Note: %s', $id);

        if ($isPost || $isPut) {
            if ($this->Note->save($this->data)) {
                $message = __('Note saved successfully.');
                if ($isAjax) {
                    $note = $this->Note->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Note->field('customer_id')));
                }
            } else {
                $message = __('There was an error saving this note. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $this->request->data = $this->Note->read();
        }

        $this->set(compact('note', 'title_for_layout'));
    }
}
