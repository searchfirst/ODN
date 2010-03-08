<?php
class CustomersController extends AppController {

	var $name = 'Customers';
	var $primaryModel = 'Customer';
	var $helpers = array('Status','Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function beforeFilter() {
		parent::beforeFilter();
		$this->permissions = array(
			"index"=>array(
				'owner'=>null,
				'admin'=>array('group'=>array('Admin'),'conditions'=>array()),
				'group'=>array('group'=>array('User'),'conditions'=>array('Customer.customer_id'=>0,'User.id'=>$this->current_user['User']['id'])),
				'other'=>array('group'=>array(),'conditions'=>null)
			),
			"view"=>array(
				'owner'=>array('owner_conditions'=>array('Customer.id'=>(isset($this->params['pass'][0])?$this->params['pass'][0]:null),'User.id'=>$this->current_user['User']['id']),'conditions'=>array()),
				'admin'=>array('group'=>array('Admin'),'conditions'=>array()),
				'group'=>array('group'=>array(),'conditions'),
				'other'=>array('group'=>array(),'conditions')
			),
			"edit"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>array('group','conditions')
			),
			"add"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>array('group','conditions')
			),
			"delete"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>null
			)
		);
		
	}

	function index() {
		$page = isset($this->params['page'])?strtoupper($this->params['page']):'all';
		$conditions = $this->generateConditions();
		if($page=='all') {
			if($this->permissionsStatus['admin']) $customer = $this->Customer->findAll($conditions,null,null,null,null,1);
			else $customer = $this->Customer->findAllWithService($conditions);
			$this->set('customers', $customer);
			$this->pageTitle = 'Customer List';
		}
		else {
			$conditions = am($conditions,array('Customer.company_name LIKE'=>$page.'%')); //REMOVED 'Customer.customer_id'=>0,
			if($this->permissionsStatus['admin']) {
				//$customers = $this->Customer->findAll($conditions,null,null,null,null,1);
				$customers = $this->Customer->find('all',array(
					'conditions'=>$conditions,
					'recursive'=>1
				));
				
			}
			else {
				$customers = $this->Customer->findAllWithService($conditions,null,null,null,null,1);
			}
			$this->set('customers', $customers);
			$this->pageTitle = "Customer List - $page";
		}
	}

	function view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Customer.');
			$this->redirect('/customers/');
		}
		$conditions = am(array('Customer.id'=>$id),$this->generateConditions($this->Customer,null,null,null,'findWithService'));
		if($this->permissionsStatus['admin']) {
			$customer = $this->Customer->findWithService($conditions);
		} elseif($this->permissionsStatus['owner']) {
			$customer = $this->Customer->findWithService($conditions);
		} else {
			$this->viewPath = 'errors';
			$this->render('not_authorised');
			return true;
		}
		if(!empty($customer))
			$this->set('customer', $customer);
		else {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
	}

	function add() {
		if(empty($this->data)) {
			$customer_list = $this->Customer->find('all',array(
				'fields' => array('Customer.customer_id','Customer.id','Customer.company_name'),
				'conditions' => array('OR' => array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),
				'recursive'=>0,
				'order'=>'Customer.company_name ASC'
			));
			$this->set('customer_list',Set::combine($customer_list,'{n}.Customer.id','{n}.Customer.company_name'));
			if(isset($this->data['Referrer']['customer_id']))
				$this->set('customer',array('Customer'=>array('customer_id'=>$this->data['Referrer']['customer_id'])));
			else
				$this->set('customer',array('Customer'=>array('customer_id'=>null)));
		} else {
			//$this->Customer->deconstruct($this->data);
			if($this->Customer->save($this->data)) {
				$newcustomer = $this->Customer->getLastInsertId();
				if(!empty($this->data['Website']['uri'])) {
					//$this->data['Website']['title'] = empty($this->data['Website']['title'])?$this->data['Customer']['company_name']:$this->data['Website']['title'];
					$this->data['Website']['customer_id'] = $newcustomer;
					$websitedata['Website'] = $this->data['Website'];
					$this->Customer->Website->save($websitedata);
				}
				$this->Session->setFlash("Customer created successfully.");
				$this->redirect("/".Inflector::underscore($this->name)."/view/$newcustomer");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$customer_list = $this->Customer->find('all',array(
					'fields' => array('Customer.customer_id','Customer.id','Customer.company_name'),
					'conditions' => array('OR' => array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),
					'recursive'=>0,
					'order'=>'Customer.company_name ASC'
				));
				$this->set('customer_list',Set::combine($customer_list,'{n}.Customer.id','{n}.Customer.company_name'));
			}
		}
	}

	function edit($id = null) {
		$customer_list = $this->Customer->find('all',array(
			'fields' => array('Customer.customer_id','Customer.id','Customer.company_name'),
			'conditions' => array('OR' => array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),
			'recursive'=>0,
			'order'=>'Customer.company_name ASC'
		));
		$this->set('customer_list',Set::combine($customer_list,'{n}.Customer.id','{n}.Customer.company_name'));

		if( (isset($this->data['Customer']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Customer');
				$this->redirect('/customer/');
			}
			$this->data = $this->Customer->find(array('Customer.id'=>$id),null,'Customer.id ASC');
			$this->set('customer', $this->data);
			//$this->set('resellers', $this->Customer->Reseller->generateList());
		} else {
			//$this->Customer->deconstruct($this->data);
			if($this->Customer->save($this->data)) {
				$this->Session->setFlash("Customer details saved successfully.");
				$this->redirect("/customers/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('customer', $this->Customer->read(null, $id));
				//$this->set('resellers', $this->Customer->Reseller->generateList());
			}
		}
	}
	
	function resellers() {
		$this->set('resellers', $this->Customer->findResellers());
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
			$q_string = $this->params['url']['q'];
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