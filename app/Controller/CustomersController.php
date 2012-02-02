<?php
class CustomersController extends AppController {
    public $helpers = array(
        'Customer',
        'Service',
        'Note',
        'Invoice',
        'Contact',
        'Paginator'
    );
    public $paginate = array(
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Customer.company_name' => 'ASC'),
        'recursive' => 0
    );

    public function index() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');

        if (!empty($this->request->query['customer_id'])) {
            $customer_id = $this->request->query['customer_id'] == 'null' ? null : $this->request->query['customer_id'];
            $conditions['Customer.customer_id'] = $customer_id;
        }

        if (array_key_exists('filter',$this->request->query)) {
            $title_for_layout = __('Customers in %s', $this->request->query['filter']);
            $conditions['Customer.company_name LIKE'] = $this->request->query['filter'] . '%';
        }

        if ($isAjax) {
            $this->Customer->isAjax = true;
        }

        if ($doPaginate) {
            $customers = $this->paginate('Customer', $conditions);
        } else {
            $this->Customer->recursive = 0;
            $customers = $this->Customer->find('all', compact('conditions'));
        }

        $this->set(compact('title_for_layout', 'customers', 'doPaginate'));
    }

    public function by_service() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');
        $this->paginate['byService'] = true;

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['customer_id'] = $this->request->query['customer_id'];
        }

        if (array_key_exists('status', $this->request->query)) {
            if ($doPaginate) {
                $this->paginate['serviceStatus'] = $this->request->query['status'];
            } else {
                $status = $this->request->query['status'];
            }
        }

        if (array_key_exists('filter', $this->request->query)) {
            $title_for_layout = __('Customers in %s', $this->request->query['filter']);
            $conditions['Customer.company_name LIKE'] = $this->request->query['filter'] . '%';
        }

        if (array_key_exists('user_id', $this->request->query)) {
            $conditions['user_id'] = $this->request->query['user_id'];
        }

        if ($isAjax) {
            $this->Customer->isAjax = true;
        }

        if ($doPaginate) {
            $customers = $this->paginate('Customer',$conditions);
        } else {
            $customers = $this->Customer->find('allThroughService', compact('conditions', 'status'));
        }

        $this->set(compact('customers', 'doPaginate', 'title_for_layout'));
    }

    public function view($id = null) {
        if (!$id) {
            $message = __('No id was provided to view a customer.');
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Customer->id = $id;
        if ($isAjax) {
            $this->Customer->recursive = 0;
            $this->Customer->isAjax = true;
        } else {
            $this->Customer->recursive = 2;
        }

        if ($customer = $this->Customer->find('firstCustomerView')) {
            if (!$isAjax) {
                $title_for_layout = __('%s | Customer', $customer['Customer']['company_name']);
            }
        } else {
            $message = __('Customer not found with this id: %s', $id);
            throw new NotFoundException($message);
        }

        $this->set(compact('customer', 'title_for_layout'));
    }

    public function add() {
        extract($this->Odn->requestInfo);
        if ($isAjax) {
            $this->Customer->isAjax = true;
        }

        if ($isPost || $isPut) {
            if ($this->Customer->saveAll($this->request->data)) {
                $message = __('Customer added successfully.');
                if ($isAjax) {
                    $customer = $this->Customer->read();
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('action' => 'view', $this->Customer->id));
                }
                $this->set(compact('customer'));
            } else {
                $message = __('There was an error saving this customer. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (array_key_exists('customer_id', $this->request->query)) {
                $this->request->data = array(
                    'Customer' => array(
                        'customer_id' => $this->request->query['customer_id']
                    )
                );
            }
            $customers = $this->Customer->find('listPotentialParents');
            $this->set(compact('customers'));
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $message = __('No id was provided to edit a customer.');
            throw new BadRequestException($message);
        }

        extract($this->Odn->requestInfo);

        if ($isAjax) {
            $this->Customer->isAjax = true;
        }
        $this->Customer->id = $id;
        $this->Customer->recursive = -1;

        if ($isPost || $isPut) {
            if ($this->Customer->save($this->request->data)) {
                $message = __('Customer saved successfully.');
                if ($isAjax) {
                    $customer = $this->Customer->read();
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('action' => 'view', $id));
                }
            } else {
                $message = __('There was an error saving this customer. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $customers = $this->Customer->find('listPotentialParents');
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $customers = $this->Customer->find('listPotentialParents');
            $this->request->data = $this->Customer->read();
        }

        $this->set(compact('customer', 'customers'));
    }

    public function delete($id = null) {
        if (!$id) {
            $message = __('No id was provided to delete a customer.');
            throw new BadRequestException($message);
        }

        $this->Customer->id = $id;
        if (!$this->Customer->exists()) {
            $message = __('A customer could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        if ($isDelete) {
            if ($this->Customer->delete($id)) {
                $message = __('Customer successfully deleted.');
                if ($isAjax) {
                    $this->set(compact('message'));
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect('/');
                }
            } else {
                $message = __('There was an error deleting this customer.');
                throw new InternalErrorException($message);
            }
        } else {
            $customer = $this->Customer->read();
            $title_for_layout = __('Delete %s | Customer', $id);
        }

        $this->set(compact('customer'));
    }
}
