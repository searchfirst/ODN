<?php
class InvoicesController extends AppController {
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
                        'invoices.{n}.Invoice' => 'invoices'
                    ),
                    'embed' => false
                ),
                'view' => array(
                    'extract' => array(
                        'invoice.Invoice' => 'Invoice'
                    ),
                    'embed' => false
                )
            )
        )
    );
    public $helpers = array(
        'Invoice'
    );
    public $paginate = array(
        'conditions' => array(),
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Invoice.created' => 'DESC'),
        'recursive' => 0
    );
    public $uses = array("Invoice");

    public function index() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $wizard = true;
        $title_for_layout = __('Invoices');
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Invoice.customer_id'] = $this->request->query['customer_id'];
            $wizard = false;
        }

        if ($doPaginate) {
            $this->paginate['conditions'] += $conditions;
            $invoices = $this->paginate('Invoice');
        } else {
            $invoices = $this->Invoice->find('all', compact('conditions'));
        }

        $this->set(compact('doPaginate', 'invoices', 'title_for_layout', 'wizard'));
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Invoice->save($this->request->data)) {
                $message = __('Invoice created successfully');
				$this->Session->setFlash($message);
				$this->redirect(array('controller' => 'invoices', 'action' => 'view', $this->Invoice->id), 201);
            } else {
                $message = __('There was an error saving this invoice. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (!array_key_exists('customer_id', $this->request->query)) {
                $message = __('No customer id was provided to add an invoice.');
                throw new BadRequestException($message);
            }

            $this->request->data += array(
                'Invoice' => array(
                    'customer_id' => $this->request->query['customer_id']
                )
            );
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $message = __('No id was provided to edit an invoice');
            throw new BadRequestException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Invoice->id = $id;
        $this->Invoice->recursive = -1;

        if ($isPost || $isPut) {
            if ($this->Invoice->save($this->request->data)) {
                $message = __('Invoice saved successfully.');
				$this->Session->setFlash($message);
				$this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Invoice->field('customer_id')));
            } else {
                $message = __('There was an error saving this invoice. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $this->request->data = $this->Invoice->read();
        }
    }

    public function view($id = null) {
        if (!$id) {
            $message = __('No id was provided to view an invoice');
            throw new BadRequestException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Invoice->id = $id;

        if ($invoice = $this->Invoice->read()) {
            if (!$isAjax) {
                $title_for_layout = __('%s | Invoice', $invoice['Invoice']['reference']);
            }
        } else {
            $message = __('An invoice could not be found with id: %s', $invoice['Invoice']['id']);
            throw new NotFoundException($message);
        }

        $this->set(compact('invoice', 'title_for_layout'));
    }

    public function delete($id = null) {
        if (!$id) {
            $message = __('No id was provided to delete an invoice.');
            throw new BadRequestException($message);
        }

        $this->Invoice->id = $id;
        if (!$this->Invoice->exists()) {
            $message = __('An invoice could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        if ($isDelete) {
            if ($this->Invoice->delete()) {
                $message = __('Invoice deleted successfully.');
                if ($isAjax) {
                    $this->set(compact('message'));
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect('/');
                }
            } else {
                $message = __('There was an error deleting this invoice.');
                throw new InternalErrorException($message);
            }
        } else {
            $website = $this->Website->read();
            $title_for_layout = __('Delete %s | Invoice', $id);
        }

        $this->set(compact('invoice', 'title_for_layout'));
    }
}
