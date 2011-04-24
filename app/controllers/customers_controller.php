<?php
App::import('Sanitize');
class CustomersController extends AppController {

	var $name = 'Customers';
	var $primaryModel = 'Customer';
	var $helpers = array(
		'Status','Javascript','Html','Form','Time',
		'TextAssistant','T','Customer','Service','Note',
		'Invoice','Contact','Paginator'
	);
	var $paginate = array(
		'limit' => 50,
		'order' => array('Customer.company_name' => 'ASC'),
		'recursive' => 0
	);

	function index($page=null) {
		$paginationOptions = array();
		$doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
		if ($this->RequestHandler->isAjax()) { $this->paginate['limit'] = 10; }
		if (!empty($this->params['url']['customer_id'])) {
			$customer_id = $this->params['url']['customer_id'] == 'null' ? null : $this->params['url']['customer_id'];
			$conditions = array('Customer.customer_id' => $customer_id);
		}
		if ($doPaginate) {
			$customers = $this->paginate('Customer',$conditions);
		} else {
			$this->Customer->recursive = 0;
			$customers = $this->Customer->find('all',array('conditions' => $conditions));
		}
		$this->set('doPaginate',$doPaginate);
		$this->set('customers', $customers);
		$this->set('title_for_layout',sprintf('Customer List - %s',$page));
	}

	function view($id = null) {
		$this->Customer->id = $id;
		$this->Customer->Service->unbindModel(array(
			'hasMany'=>array('Note'),
			'belongsTo'=>array('Customer','Website')
		));
		$this->Customer->Invoice->unbindModel(array(
			'hasMany'=>array('Note'),
			'belongsTo'=>array('Service','Customer')
		));
		$this->Customer->Website->unbindModel(array(
			'belongsTo'=>array('Customer')
		));
		$this->Customer->Note->unbindModel(array(
			'belongsTo'=>array('Website','Customer','Service')
		));
		$this->Customer->User->unbindModel(array(
			'hasMany'=>array('Service','Customer','Note'),
			'belongsTo'=>array('Group'),
			'hasAndBelongsToMany'=>array('TechnicalCustomer','Website')
		));
		$this->Customer->Referral->unbindModel(array(
			'hasMany'=>array('Referral','Invoice','Note','Contact'),
			'belongsTo'=>array('Reseller','User')
		));
		$this->Customer->Contact->unbindModel(array(
			'belongsTo'=>array('Customer')
		));
		if (!$this->RequestHandler->isAjax()) {
			$this->Customer->recursive = 1;
			$customer = $this->Customer->read();
		} else {
			$this->Customer->recursive = -1;
			$customer = $this->Customer->readRoot();
		}
		
		//$customer = $this->Customer->find('first',array(
		//	'conditions' => array('Customer.id'=>$id),
		//	'recursive' => 3
		//));
		if(!empty($customer)) {
			$this->set('customer', $customer);
			$this->pageTitle = sprintf('%s | Customer',$customer['Customer']['company_name']);
		} else {
			$this->cakeError('error404');
		}
	}

	function add() {
		extract($this->Dux->commonRequestInfo());
		if ($isPost) {
			if (!$isAjax) {
				if ($this->Customer->saveAll($this->data)) {
					$this->Session->setFlash(__("Customer created successfully.",true));
					$this->redirect(array('controller'=>'customers','action'=>'view',$this->Customer->id));
				} else {
					$this->Session->setFlash(__("Please correct errors below.",true));
				}
			} else {
				if ($this->Customer->save($this->data)) {
					$this->set('model', $this->Customer->readRoot());
				} else {
					$this->cakeError('ajaxError',array('message'=>'Not saved'));
				}
			}
		} else {
			if (!empty($this->passedArgs['customer_id'])) {
				$this->data['Customer']['customer_id'] = $this->passedArgs['customer_id'];
			} else {
				$this->set('customers',$this->Customer->getCustomerList());
			}
		}
	}

	function edit($id = null) {
		if(!$id) {
			$this->cakeError('missingId',array('model'=>'Customer'));
		}
		$this->Customer->id = $id;
		$this->Customer->recursive = -1;
		$this->set('customers',$this->Customer->find('listPotentialParents'));

		if (!($this->RequestHandler->isPost() || $this->RequestHandler->isPut())) {
			$this->data = $this->Customer->read(null,$id);
		} else if ($this->RequestHandler->isPost()) {
			if($this->Customer->save($this->data)) {
				$this->Session->setFlash("Customer details saved successfully.");
				$this->redirect(array('action'=>'view',$id));
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		} else if ($this->RequestHandler->isPut() && $this->RequestHandler->isAjax()) {
			$this->data = array('Customer' => $this->data['Customer']);
			if ($this->Customer->save($this->data)) {
				$this->set('model',array(
					'id'=>$id,
					'Customer'=>$this->data['Customer']
				));
			} else {
				$this->cakeError('ajaxError',array('message'=>'Not saved'));
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
