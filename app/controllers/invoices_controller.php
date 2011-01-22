<?php
class InvoicesController extends AppController
{
	var $name = 'Invoices';
	var $primaryModel = 'Invoice';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant','Invoice');

	function index() {
		$open_invoices = $this->Invoice->find('all',array(
			'conditions'=>array('Invoice.date_invoice_paid'=>null,'Invoice.due_date >'=>strftime('%Y-%m-%d')),
			'order'=>'Invoice.due_date ASC'
		));
		$open_invoices_count = count($open_invoices);
		$overdue_invoices = $this->Invoice->find('all',array(
			'conditions'=>array('Invoice.date_invoice_paid'=>null,'Invoice.due_date <'=>strftime('%Y-%m-%d')),
			'order'=>'Invoice.due_date ASC'
		));
		$overdue_invoices_count = count($overdue_invoices);
		$recently_paid_invoices = array();
		$recently_paid_invoices_count = 0;
		$invoices = array('open'=>$open_invoices,'overdue'=>$overdue_invoices,'recently_paid'=>$recently_paid_invoices);
		$this->set('open_invoices_count',$open_invoices_count);
		$this->set('overdue_invoices_count',$overdue_invoices_count);
		$this->set('recently_paid_invoices_count',$recently_paid_invoices_count);
		$this->set('invoices',$invoices);
	}

	function raise() {
		if(empty($this->data) || !empty($this->data['Referrer'])) {
			if(!empty($this->data['Referrer']['customer_id']))
				$this->data['Invoice']['customer_id'] = $this->data['Referrer']['customer_id'];
			$customer_id = $this->data['Invoice']['customer_id'];
			$this->data['Invoice']['reference'] = $this->Invoice->generateReference($customer_id);
			$service_list = $this->Invoice->Service->findAll(
				array('Customer.id'=>$customer_id),
				null,
				'Service.cancelled ASC'
			);
			$services = array();
			foreach($service_list as $service_item) {
				$services[$service_item['Service']['id']] = (($service_item['Service']['status']=='0')?'[Cancelled] ':'').$service_item['Website']['uri'].' '.$service_item['Service']['title'];
			}
			$this->set('services',$services);
			$this->set('vat_rates',$this->Invoice->getVatRates());
		} else {
			if($this->Invoice->save($this->data)) {
				$this->Session->setFlash('Invoice Successfully Raised');
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$service_list = $this->Invoice->Service->findAll(
					array('Customer.id'=>$this->customer_id),
					null,
					'Service.cancelled ASC'
				);
				$services = array();
				foreach($service_list as $service_item) {
					$services[$service_item['Service']['id']] = (($service_item['Service']['status']=='0')?'[Cancelled] ':'').$service_item['Website']['uri'].' '.$service_item['Service']['title'];
				}
				$this->set('services',$services);
				if(!empty($this->data['Invoice']['customer_id']))
					$this->data['Referrer']['customer_id'] = $this->data['Invoice']['customer_id'];
				if(!empty($this->data['Invoice']['website_id']))
					$this->data['Referrer']['website_id'] = $this->data['Invoice']['website_id'];
				if(!empty($this->data['Invoice']['service_id'])) {
					$this->data['Referrer']['service_id'] = $this->data['Invoice']['service_id'];
				}
			}
		}
	}

	function add() {
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			$this->data['Website']['customer_id'] =!empty($this->data['Referrer']['customer_id'])?$this->data['Referrer']['customer_id']:null;
			$this->set('website',$this->data);
			$this->set('customer', $this->Website->Customer->generateList());
		} else {
			if($this->Website->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Website->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Website->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referrer']['customer_id'] = $this->data['Website']['customer_id'];
				$this->set('customer', $this->Website->Customer->generateList());
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

	function view($id) {
		$conditions = array('Invoice.id'=>$id);
		$extra_vars = $this->params['url'];
		unset($extra_vars['url']);
		if(!count($extra_vars)) $extra_vars = null;
			if($invoice = $this->Invoice->find($conditions)) {
				if(isset($this->params['alt_content']) && $this->params['alt_content']=='Pdf') {
					$this->Invoice->cacheFDF($id,$extra_vars);
					$invoice_pdf = $this->Invoice->generatePDF($id,$extra_vars);
					header("Content-Disposition: inline; filename=\"Invoice-{$invoice['Invoice']['reference']}.pdf\"");
					$this->set('invoice',$invoice_pdf);
				} else {
					$this->set('invoice',$invoice);
				}
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
				return true;
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
	
	
	function wizard() {
		if(!empty($this->data['Invoice']['types'])) {
			$type = $this->data['Invoice']['types'];
			if(!empty($this->data['Invoice']['date'])) {
				$month = $this->data['Invoice']['date']['month'];
				$year = $this->data['Invoice']['date']['year'];
				$invoices = $this->Invoice->find('all',array(
					'conditions' => array("MONTH(Invoice.$type)"=>$month,"YEAR(Invoice.$type)"=>$year),
					'order' => "Invoice.$type DESC",
					'recursive' => 1
				));
				$this->set('invoices',$invoices);
			} elseif(!( empty($this->data['Invoice']['start_date']) || empty($this->data['Invoice']['end_date']) )) {
				$type = $this->data['Invoice']['types'];
				$start_date = $this->data['Invoice']['start_date'];
				$start_date = sprintf('%s-%s-%s 00:00:00',$start_date['year'],$start_date['month'],$start_date['day']);
				$end_date = $this->data['Invoice']['end_date'];
				$end_date = sprintf('%s-%s-%s 23:59:59',$end_date['year'],$end_date['month'],$end_date['day']);
				$invoices = $this->Invoice->find('all',array(
					'conditions' => array("Invoice.$type BETWEEN ? and ?" => array($start_date,$end_date)),
					'order' => "Invoice.$type DESC",
					'recursive' => 1
				));
				$this->set('invoices',$invoices);
			}
		}
		$this->set('types',array('created'=>'Created','date_invoice_paid'=>'Paid'));
	}
}
