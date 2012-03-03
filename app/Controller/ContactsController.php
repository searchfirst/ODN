<?php
class ContactsController extends AppController {
    public $components = array(
        'RequestHandler' => array(
            'className' => 'Rest.Rest',
            'catchredir' => true,
            'paginate' => true,
            'ratelimit' => array(
                'enable' => false
            ),
            'meta' => array(
                'enable' => false
            ),
            'actions' => array(
                'index' => array(
                    'extract' => array(
                        'contacts.{n}.Contact' => 'contacts'
                    ),
                    'embed' => false
                ),
                'view' => array(
                    'extract' => array(
                        'contact.Contact' => 'Contact'
                    ),
                    'embed' => false
                )
            )
        )
    );
    public $helpers = array('Contact');
    public $paginate = array(
        'conditions' => array(),
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Contact.name' => 'ASC'),
        'recursive' => 1,
    );

    public function index() {
        extract($this->Odn->requestInfo);

        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Contact.customer_id'] = $this->request->query['customer_id'];
        }

        if ($doPaginate) {
            $this->paginate['conditions'] += $conditions;
            $contacts = $this->paginate('Contact');
        } else {
            $contacts = $this->Contact->find('allRelated', compact('conditions'));
        }
        $this->set(compact('contacts', 'doPaginate'));
    }

    public function view($id = null) {
        if (!$id) {
            $message = __('No id was provided to view a contact.');
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Contact->id = $id;

        if ($contact = $this->Contact->read()) {
            if (!$isAjax) {
                $title_for_layout = __('%s | Contact', $contact['Contact']['name']);
            }
        } else {
            $message = __('A contact could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        $this->set(compact('contact', 'title_for_layout'));
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Contact->save($this->request->data)) {
                $message = __('Contact created successfully.');
                $this->Session->setFlash($message);
                $this->redirect(array('controller' => 'contacts', 'action' => 'view', $this->Contact->id), 201);
            } else {
                $message = __('There was an error saving this contact. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (array_key_exists('customer_id', $this->request->query)) {
                $this->request->data = array(
                    'Contact' => array(
                        'customer_id' => $this->request->query['customer_id']
                    )
                );
            } else {
                $message = __('No customer id was provided to add a contact.');
                throw new BadRequestException($message);
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $message = __('No id was provided to edit a contact.');
            throw new BadRequestException($message);
        }

        $this->Contact->id = $id;
        if (!$this->Contact->exists()) {
            $message = __('A contact could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Contact->recursive = 0;

        if ($isPost || $isPut) {
            if ($this->Contact->save($this->request->data)) {
                $message = __('Contact saved successfully.');
                $this->Session->setFlash($message);
                $this->redirect(array('controller' => 'contacts', 'action' => 'view', $id));
            } else {
                $message = __('There was an error saving this contact. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $this->request->data = $this->Contact->read();
        }

        $this->set(compact('contact'));
    }

    public function delete($id = null) {
        if(!$id) {
            $message = __('No id was provided to delete a contact.');
            throw new BadRequestException($message);
        }

        $this->Contact->id = $id;
        if (!$this->Contact->exists()) {
            $message = __('A contact could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        if ($isDelete) {
            if ($this->Contact->delete()) {
                $message = __('Contact successfully deleted.');
                if ($isAjax) {
                    $this->set(compact('message'));
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect('/');
                }
            } else {
                $message = __('There was an error deleting this contact');
                throw new InternalErrorException($message);
            }
        } else {
            $contact = $this->Contact->read();
            $this->set(compact('contact'));
        }
    }
}
