<?php
class InvoicesController extends AppController {
	var $name = 'Invoices';
	var $primaryModel = 'Invoice';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant','Invoice');
	var $uses = array("Invoice");
	var $paginate = array(
		'limit' => 5,
		'order' => array('Invoice.created' => 'DESC'),
		'recursive' => 0
	);

	function add() {
		extract($this->Dux->commonRequestInfo());
		if ($isPost) {
			if (!$isAjax) {
				if ($this->Invoice->saveAll($this->data)) {
					$this->Session->setFlash(__("Invoice created.",true));
					$this->redirect(array('controller'=>'invoices','action'=>'view',$this->Invoice->id));
				} else {
					$this->Session->setFlash(__("Please correct errors below.",true));
				}
			} else {
				if ($this->Invoice->save($this->data)) {
					$this->set('model', $this->Invoice->readRoot());
				} else {
					$this->cakeError('ajaxError',array('message'=>'Not saved'));
				}
			}
		} else {
			if (!empty($this->passedArgs['customer_id'])) {
				$this->data['Invoice']['customer_id'] = $this->passedArgs['customer_id'];
			} else {
				$this->cakeError('missingId',array('model'=>'Customer'));
			}
		}
	}

	function edit($id) {
		if( (isset($this->data['Invoice']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Invoice');
				$this->redirect($this->referer('/'));
			}
			$this->data = $this->Invoice->read(null, $id);
			$this->set('invoice',$this->data);
		} else {
			if(isset($this->data['Note'])) {
				$note['Note'] = $this->data['Note'];
				unset($this->data['Note']);
			}
			if($this->Invoice->save($this->data)) {
				if(isset($note['Note'])) {
					$this->Invoice->Note->save($note);
				}
				$this->Session->setFlash("Invoice successfully updated.");
				$this->redirect($this->referer('/'));
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('invoice',$this->data);
			}
		}
	}

	function view($id = null) {
		$this->Invoice->id = $id;
		$this->Invoice->recursive = 0;
		if (!$this->RequestHandler->isAjax()) {
			$invoice = $this->Invoice->read();
		} else {
			$invoice = $this->Invoice->readRoot();
		}
		if(!empty($invoice)) {
			$this->set('invoice', $invoice);
		} else {
			$this->cakeError('error404');
		}
	}

	function paid_in_full($id) {
		if($invoice = $this->Invoice->find(array('Invoice.id'=>$id))) {
			$this->set('invoice',$invoice);
		} else {
			$this->Session-setFlash('Invalid Invoice');
			$this->redirect($this->referer());
		}
	}

	function cancel($id=null) {
		if($id) {
			if(empty($this->data)) {
				$this->Invoice->id = $id;
				$invoice = $this->Invoice->read();
				$this->data = $invoice;
			} else {

			}
		} else {
			//Add error handling here
		}
	}

	function delete($id = null) {
		if(!empty($this->data)) {
			/*$this->Session->setFlash('Product deleted: id '.$id.'.');
			$customer_id = $this->Website->Customer->findByProduct($id);
			$this->redirect($this->referer('/customers/'));*/
		} else {
			if(!$id) {
				$this->Session->setFlash('Invalid Invoice');
				$this->redirect($this->referer('/'));
			}
			/*$this->set('id',$id);*/
		}
	}
	
	
	function index() {
		$title = __('Invoices',true);
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
	}
}
