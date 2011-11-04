<?php
class ServicesController extends AppController {
    public $primaryModel = 'Service';
    public $paginate = array(
        'conditions' => array(),
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Service.created' => 'ASC'),
        'recursive' => 0
    );

    public function index() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Service.customer_id'] = $this->request->query['customer_id'];
        }

        if ($doPaginate) {
            $this->paginate['conditions'] += $conditions;
            $services = $this->paginate('Service');
        } else {
            $this->Service->recursive = 0;
            $services = $this->Service->find('all', compact('conditions', 'isAjax'));
        }

        $this->set(compact('doPaginate', 'services'));
    }

    public function view($id = null) {
        if (!$id) {
            $message = __('No id was provided to view a service.');
            throw new BadRequestException($message);
        }

        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            $message = __('A service could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);

        if ($service = $this->Service->read(null, null, $isAjax)) {
            if (!$isAjax) {
                $title_for_layout = __('%s | Service', $service['Service']['title']);
            }
        } else {
            $message = __('There was an error retrieving this service.');
            throw new InternalErrorException($message);
        }

        $this->set(compact('service', 'title_for_layout'));
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Service->save($this->data)) {
                $message = __('Service created successfully.');
                if ($isAjax) {
                    $service = $this->Service->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Service->field('customer_id')));
                }
                $this->set(compact('service'));
            } else {
                $message = __('There was an error saving this service. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $conditions = array('Customer.id' => $this->request->query['customer_id']);
                    $websites = $this->Service->Website->find('list', compact('conditions'));
                    $users = $this->Service->User->find('list', compact('conditions'));
                    $this->set(compact('websites', 'users'));
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (array_key_exists('customer_id', $this->request->query)) {
                $conditions = array('Customer.id' => $this->request->query['customer_id']);
                $websites = $this->Service->Website->find('list', compact('conditions'));
                $users = $this->Service->User->find('list', compact('conditions'));
                $this->set(compact('websites', 'users'));
            } else {
                $message = __('No customer id was provided to add a service.');
                throw new BadRequestException($message);
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $message = __('No id was provided to edit a service.');
            throw new BadRequestException($message);
        }

        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            $message = __('A service could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Service->recursive = -1;

        if ($isPost || $isPut) {
            if ($this->Service->save($this->request->data)) {
                $message = __('Service saved successfully.');
                if ($isAjax) {
                    $service = $this->Service->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Service->field('customer_id')));
                }
                $this->set(compact('service'));
            } else {
                $message = __('There was an error saving this service. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $conditions = array('Customer.id' => $this->Service->field('customer_id'));
                    $websites = $this->Service->Website->find('list', compact('conditions'));
                    $users = $this->Service->Website->find('list', compact('conditions'));
                    $this->set(compact('websites', 'users'));
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $conditions = array('Customer.id' => $this->Service->field('customer_id'));
            $websites = $this->Service->Website->find('list', compact('conditions'));
            $users = $this->Service->Website->find('list', compact('conditions'));
            $this->set(compact('websites', 'users'));
            $this->request->data = $this->Website->read();
        }
    }

    public function delete($id = null) {
        if(!$id) {
            $message = __('No id was provided to delete a service');
            throw new BadRequestException($message);
        }

        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            $message = __('A service could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        if ($isDelete) {
            if ($this->Service->delete()) {
                $message = __('Service successfully deleted.');
                if ($isAjax) {
                    $this->set(compact('message'));
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect('/');
                }
            } else {
                $message = __('There was an error deleting this service.');
                throw new InternalErrorException($message);
            }
        } else {
            $service = $this->Service->read();
            $this->set(compact('service'));
        }
    }
}
