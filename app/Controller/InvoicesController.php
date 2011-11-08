<?php
class InvoicesController extends AppController {
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
            $invoices = $this->Invoice->find('all', compact('conditions', 'isAjax'));
        }

        $this->set(compact('doPaginate', 'invoices', 'title_for_layout', 'wizard'));
        /*
        if (empty($this->params['url']['customer_id'])) {
            if(!empty($this->data['Invoice']['types'])) {
                $type = $this->data['Invoice']['types'];
                if (!empty($this->data['Invoice']['date'])) {
                    $month = $this->data['Invoice']['date']['month'];
                    $year = $this->data['Invoice']['date']['year'];
                    $title = __('Invoices: ',true).sprintf(' %s - %s/%s',Inflector::humanize($type),$month,$year);
                    $invoices = $this->Invoice->find('all',array(
                        'conditions' => array("MONTH(Invoice.$type)"=>$month,"YEAR(Invoice.$type)"=>$year),
                        'order' => "Invoice.$type DESC",
                        'recursive' => 1
                    ));
                } elseif (!( empty($this->data['Invoice']['start_date']) || empty($this->data['Invoice']['end_date']) )) {
                    $start_date = $this->data['Invoice']['start_date'];
                    $start_date = sprintf('%s-%s-%s 00:00:00',$start_date['year'],$start_date['month'],$start_date['day']);
                    $end_date = $this->data['Invoice']['end_date'];
                    $end_date = sprintf('%s-%s-%s 23:59:59',$end_date['year'],$end_date['month'],$end_date['day']);
                    $title = __('Invoices: '.Inflector::humanize($type),true).sprintf(' %s - %s',substr($start_date,0,10),substr($end_date,0,10));
                    $invoices = $this->Invoice->find('all',array(
                        'conditions' => array("Invoice.$type BETWEEN ? and ?" => array($start_date,$end_date)),
                        'order' => "Invoice.$type DESC",
                        'recursive' => 1
                    ));
                } elseif (!empty($this->data['Invoice']['type'])) {
                    if($this->data['Invoice']['type'] == 'overdue') {
                        $invoices = $this->Invoice->find('overdue');
                        $title = __('Invoices: All Overdue',true);
                    } elseif ($this->data['Invoice']['type'] == 'notoverdue') {
                        $invoices = $this->Invoice->find('notOverdue');
                        $title = __('Invoices: All Due',true);
                    }
                }
            }
            $this->set('invoices', $invoices);
            $this->set('title_for_layout', $title);
        } else {
            $customer_id = $this->params['url']['customer_id'];
            $paginationOptions = array('Invoice.customer_id' => $customer_id);
            $invoices = $this->paginate('Invoice',$paginationOptions);
            $this->set('invoices', $invoices);
        }
        $this->set('types',array('created'=>'Raised','date_invoice_paid'=>'Paid'));
        */
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Invoice->save($this->request->data)) {
                $message = __('Invoice created successfully');
                if ($isAjax) {
                    $invoice = $this->Invoice->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Invoice->field('customer_id')));
                }
                $this->set(compact('invoice'));
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
                if ($isAjax) {
                    $invoice = $this->Invoice->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Invoice->field('customer_id')));
                }
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

        if ($invoice = $this->Invoice->read(null, null, $isAjax)) {
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
