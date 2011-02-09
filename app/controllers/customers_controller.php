<?php
App::import('Sanitize');
class CustomersController extends AppController {

	var $name = 'Customers';
	var $primaryModel = 'Customer';
	var $helpers = array('Status','Javascript','Html','Form','Time','TextAssistant','T','Customer','Service','Note','Invoice');

	function beforeRender() {
		parent::beforeRender();
		$this->set('customer_status_numbers',array(
			CUSTOMER_STATUS_CANCELLED => 'Cancelled',
			CUSTOMER_STATUS_PENDING => 'Pending',
			CUSTOMER_STATUS_ACTIVE => 'Active'
		));
	}

	function index() {
		$page = isset($this->params['page'])?strtoupper($this->params['page']):'all';
		if($page=='all') {
			$customer = $this->Customer->find('all',array(
				'recursive'=>0
			));
			$this->set('customers', $customer);
			$this->pageTitle = 'Customer List';
		} else {
			$customers = $this->Customer->find('all',array(
				'conditions'=>array('Customer.company_name LIKE'=>$page.'%'),
				'recursive'=>0
			));
			$this->set('customers', $customers);
			$this->set('title_for_layout',sprintf('Customer List - %s',$page));
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
		if(empty($this->data)) {
			$this->set('customer_list',$this->Customer->getCustomerList());
			if(isset($this->data['Referrer']['customer_id']))
				$this->set('customer',array('Customer'=>array('customer_id'=>$this->data['Referrer']['customer_id'])));
			else
				$this->set('customer',array('Customer'=>array('customer_id'=>null)));
		} else {
			if($this->Customer->save($this->data)) {
				$newcustomer = $this->Customer->getLastInsertId();
				if(!empty($this->data['Website']['uri'])) {
					$this->data['Website']['customer_id'] = $newcustomer;
					$websitedata['Website'] = $this->data['Website'];
					$this->Customer->Website->save($websitedata);
				}
				$this->Session->setFlash("Customer created successfully.");
				$this->redirect("/customers/view/$newcustomer");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('customer_list',$this->Customer->getCustomerList());
			}
		}
	}

	function edit($id = null) {
		$customer_list = $this->Customer->find('all',array(
			'fields' => array('Customer.customer_id','Customer.id','Customer.company_name'),
// 			'conditions' => array('OR' => array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),
			'conditions' => array('Customer.customer_id'=>0,array('NOT'=>array('Customer.id'=>$id))),
			'recursive' => 0,
			'order' => 'Customer.company_name ASC'
		));
		$this->set('customer_list',Set::combine($customer_list,'{n}.Customer.id','{n}.Customer.company_name'));

		if( (isset($this->data['Customer']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Customer');
				$this->redirect('/customer/');
			}
			$this->data = $this->Customer->find(array('Customer.id'=>$id),null,'Customer.id ASC');
			$this->set('customer', $this->data);
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
	
	function resellers($id=null) {
		if(!$id) {
			$this->set('resellers', $this->Customer->findResellers());
		} else {
			$reseller = $this->Customer->find('first',array(
				'conditions' => array('Customer.id'=>$id),
				'recursive' => 1
			));
			$this->set('reseller',$reseller);
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

	function search() {
		if(isset($this->params['url']['q'])) {
			$q_string = Sanitize::escape($this->params['url']['q'],'default');
		}
		$query = strtoupper($q_string);
		$customers = $this->Customer->search($query);
		$this->set('customers', $customers);
		if(count($customers)==1) {
			$this->redirect('/customers/view/'.$customers[0]['Customer']['id']);
		}		
	}
}
?>
