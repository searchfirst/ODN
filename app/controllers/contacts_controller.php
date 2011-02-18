<?php
class ContactsController extends AppController {
	var $primaryModel = 'Contact';
	var $helpers = array('T','Contact');

	function index() {
		$page = isset($this->params['page'])?strtoupper($this->params['page']):'all';
		if($page=='all') {
			$contacts= $this->Contact->find('all',array(
				'recursive'=>0
			));
			$this->set('contacts', $contacts);
		} else {
			$contacts = $this->Contact->find('all',array(
				'conditions'=>array('Contact.name LIKE'=>$page.'%'),
				'recursive'=>0
			));
			$this->set('contacts', $contacts);
			$this->set('title_for_layout',sprintf('Contacts - %s',$page));
		}
	}

	function view($id = null) {
		$this->Customer->unbindModel(array('hasMany'=>array('Note')));
		$this->Customer->bindModel(array('hasMany'=>array('Note'=>array(
			'limit'=>10,
			'order'=>'Note.created DESC'
		))));
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
		$this->log($this->data);
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
		if(!$id) {
			$this->cakeError('missingId',array('model'=>'Contact'));
		} else {
			if (empty($this->data)) {
				$this->data = $this->Contact->find('first',array('conditions'=>array('Contact.id'=>$id)));
				if (!empty($this->data)) {
					$this->set('contact', $this->data);
				} else {
					$this->cakeError('recordNotFound');
				}
			} else {
				if($this->Customer->save($this->data)) {
					$this->Session->setFlash("Customer details saved successfully.");
					$this->redirect("/customers/view/$id");
				} else {
					$this->Session->setFlash('Please correct errors below.');
					$this->set('customer', $this->Customer->read(null, $id));
				}
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
