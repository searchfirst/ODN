<?php
class ContactsController extends AppController {
	var $primaryModel = 'Contact';
	var $helpers = array('T','Contact');
	var $paginate = array(
		'limit' => 10,
		'order' => array('Contact.name' => 'ASC'),
		'recursive' => 1
	);

	function index() {
		$paginationOptions = array();
		$doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
		if (!empty($this->params['url']['customer_id'])) {
			$paginationOptions['Contact.customer_id'] = $this->params['url']['customer_id'];
		}
		if ($doPaginate) {
			$contacts = $this->paginate('Contact',$this->Contact->generateRelatedConditions($paginationOptions));
		} else {
			$contacts = $this->Contact->find('allRelated',array(
				'conditions' => $paginationOptions
			));
		}
		$this->set('doPaginate',$doPaginate);
		$this->set('contacts',$contacts);
	}

	function view($id = null) {
		$customer = $this->Customer->find('first',array(
			'conditions' => array('Customer.id'=>$id),
			'recursive' => 2
		));
		if(!empty($customer)) {
			$this->set('customer', $customer);
			$this->pageTitle = sprintf('%s | Customer',$customer['Customer']['company_name']);
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
	}

	function add() {
		$this->set('title_for_layout',__('Add New Contact',true));
		if(empty($this->data)) {
			$this->set('customers',$this->Contact->Customer->getCustomerList());
		} else {
			if($this->Contact->save($this->data)) {
				$this->Session->setFlash("New contact created.");
				$this->redirect("/customers/view/$newcustomer");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('customers',$this->Contact->Customer->getCustomerList());
			}
		}
	}

	function edit($id = null) {
		extract($this->Dux->commonRequestInfo());
		if(!$id) {
			$this->cakeError('missingId',array('model'=>'Contact'));
		}
		$this->Contact->id = $id;
		$this->Contact->recursive = 0;

		if (!($isPost || $isPut)) {
			$this->data = $this->Contact->read();
		} else if ($isAjax) {
			if ($this->Contact->save($this->data)) {
				$this->set('model',$this->Contact->readRoot());
			} else {
				$this->cakeError('ajaxError',array('message'=>'Not saved'));
			}
		} else {
			if($this->Contact->save($this->data)) {
				$this->Session->setFlash("Contact saved successfully.");
				$this->redirect(array('action'=>'view',$id));
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Customer');
			$this->redirect('/customers/');
		}
		if( ($this->data['Customer']['id']==$id) && ($this->Customer->del($id)) ) {
			$this->Session->setFlash('Customer successfully deleted');
			$this->redirect('/customers/');
		} else {
			$this->set('id',$id);
		}
	}
}
